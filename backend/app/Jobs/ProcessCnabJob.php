<?php

namespace App\Jobs;

use App\Models\CnabProcessing;
use App\Models\Fund;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ProcessCnabJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $processingId;

    public function __construct($processingId)
    {
        $this->processingId = $processingId;
    }

    public function handle()
    {
        $processing = CnabProcessing::findOrFail($this->processingId);
        
        try {
            $processing->update(['status' => 'processing']);

            // Buscar dados do fundo
            $fund = Fund::where('name', $processing->fund)->first();
            if (!$fund) {
                throw new \Exception('Fundo não encontrado');
            }

            // Ler arquivo Excel
            $excelData = Excel::toArray([], storage_path('app/public/' . $processing->file_path));
            $rows = $excelData[0];

            // Remover cabeçalho se existir
            array_shift($rows);

            // Gerar CNAB
            $cnabContent = $this->generateCnab($fund, $processing->sequence, $rows);

            // Salvar arquivo CNAB
            $cnabFileName = 'cnab_' . time() . '_' . $processing->sequence . '.txt';
            $cnabPath = 'cnab/' . $cnabFileName;
            Storage::disk('public')->put($cnabPath, $cnabContent);

            // Atualizar processamento
            $processing->update([
                'status' => 'completed',
                'cnab_path' => $cnabPath
            ]);

        } catch (\Exception $e) {
            $processing->update([
                'status' => 'error',
                'error_message' => $e->getMessage()
            ]);
        }
    }

    private function generateCnab($fund, $sequence, $rows)
    {
        $cnab = '';

        // Cabeçalho (40 posições)
        $header = str_pad(substr($fund->name, 0, 10), 10, ' ', STR_PAD_RIGHT); // 0-9
        $header .= str_pad($fund->cnpj, 13, '0', STR_PAD_LEFT); // 10-22
        $header .= str_pad(substr($fund->logradouro, 0, 9), 9, ' ', STR_PAD_RIGHT); // 23-31
        $header .= str_pad($fund->numero, 3, '0', STR_PAD_LEFT); // 32-34
        $header .= str_pad($sequence, 3, '0', STR_PAD_LEFT); // 35-37
        $header .= str_repeat(' ', 2); // 38-39 (espaços para completar 40)
        $cnab .= $header . "\n";

        // Corpo
        $totalValue = 0;
        foreach ($rows as $row) {
            if (empty($row[0])) continue; // Pular linhas vazias

            $contract = str_pad($row[0], 6, '0', STR_PAD_LEFT); // 0-5
            $client = str_pad(substr($row[1], 0, 22), 22, ' ', STR_PAD_RIGHT); // 6-27
            $value = str_pad(str_replace(['.', ','], '', $row[2]), 6, '0', STR_PAD_LEFT); // 28-33
            $date = date('Ymd', strtotime($row[3])); // 34-40
            $spaces = str_repeat(' ', 6); // 41-46 (espaços para completar 40)

            $line = $contract . $client . $value . $date . $spaces;
            $cnab .= $line . "\n";
            $totalValue += (float) str_replace(['.', ','], '', $row[2]);
        }

        // Rodapé (40 posições)
        $footer = str_pad($totalValue, 11, '0', STR_PAD_LEFT); // 0-10
        $footer .= str_pad('341', 3, '0', STR_PAD_LEFT); // 11-13 (Código do banco)
        $footer .= str_pad('12345', 5, '0', STR_PAD_LEFT); // 14-18 (Agência)
        $footer .= str_pad('987651', 6, '0', STR_PAD_LEFT); // 19-24 (Conta)
        $footer .= str_repeat(' ', 15); // 25-39 (espaços para completar 40)
        $cnab .= $footer;

        return $cnab;
    }
} 