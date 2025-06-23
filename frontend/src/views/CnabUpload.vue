<template>
  <div>
    <h2>Importar Arquivo Excel</h2>
    <el-form label-width="120px">
      <el-form-item label="Arquivo Excel">
        <el-upload
          action=""
          :auto-upload="false"
          :on-change="handleFileChange"
          accept=".xlsx,.xls"
        >
          <el-button>Selecionar Arquivo</el-button>
        </el-upload>
      </el-form-item>
      <el-form-item label="Fundo">
        <el-input v-model="fund" placeholder="Nome do fundo" />
      </el-form-item>
      <el-form-item label="Sequência do Arquivo">
        <el-input v-model="sequence" placeholder="Ex: 001" maxlength="3" />
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="handleSubmit">Enviar</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import api from '../services/api'

const file = ref(null)
const fund = ref('')
const sequence = ref('')

function handleFileChange(uploadFile) {
  file.value = uploadFile.raw
}

async function handleSubmit() {
  if (!file.value || !fund.value || !sequence.value) {
    alert('Preencha todos os campos!')
    return
  }
  const formData = new FormData()
  formData.append('file', file.value)
  formData.append('fund', fund.value)
  formData.append('sequence', sequence.value)
  await api.post('/cnab', formData)
  alert('Arquivo enviado!')
}
</script>
