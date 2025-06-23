<?php

namespace App\Http\Controllers;

use App\Models\CnabProcessing;
use App\Models\Fund;
use App\Jobs\ProcessCnabJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class CnabController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        $query = CnabProcessing::with('user');

        // Filtros
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->has('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Apenas admin pode ver todos, usuário vê apenas os seus
        if (auth()->user()->profile !== 'admin') {
            $query->where('user_id', auth()->id());
        }

        return response()->json($query->orderBy('created_at', 'desc')->paginate(10));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx,xls',
            'fund' => 'required|string',
            'sequence' => 'required|string|max:3',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Verificar se o fundo existe
        $fund = Fund::where('name', $request->fund)->first();
        if (!$fund) {
            return response()->json(['error' => 'Fundo não encontrado'], 404);
        }

        // Salvar arquivo
        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('uploads', $fileName, 'public');

        // Criar processamento
        $processing = CnabProcessing::create([
            'user_id' => auth()->id(),
            'file_name' => $fileName,
            'file_path' => $filePath,
            'fund' => $request->fund,
            'sequence' => $request->sequence,
            'status' => 'pending'
        ]);

        // Disparar job para processamento
        ProcessCnabJob::dispatch($processing->id);

        return response()->json([
            'message' => 'Arquivo enviado para processamento',
            'processing' => $processing
        ], 201);
    }

    public function show($id)
    {
        $processing = CnabProcessing::with('user')->findOrFail($id);

        // Verificar permissão
        if (auth()->user()->profile !== 'admin' && $processing->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($processing);
    }

    public function downloadExcel($id)
    {
        $processing = CnabProcessing::findOrFail($id);

        // Verificar permissão
        if (auth()->user()->profile !== 'admin' && $processing->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if (!Storage::disk('public')->exists($processing->file_path)) {
            return response()->json(['error' => 'Arquivo não encontrado'], 404);
        }

        return Storage::disk('public')->download($processing->file_path);
    }

    public function downloadCnab($id)
    {
        $processing = CnabProcessing::findOrFail($id);

        // Verificar permissão
        if (auth()->user()->profile !== 'admin' && $processing->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($processing->status !== 'completed' || !$processing->cnab_path) {
            return response()->json(['error' => 'CNAB não disponível'], 404);
        }

        return Storage::disk('public')->download($processing->cnab_path);
    }

    public function getFunds()
    {
        return response()->json(Fund::all());
    }
} 