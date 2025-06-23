<template>
  <div style="max-width: 400px; margin: 100px auto;">
    <el-card>
      <h2>Login</h2>
      <el-form :model="form" label-width="80px" @submit.prevent="handleLogin">
        <el-form-item label="E-mail">
          <el-input v-model="form.email" placeholder="Digite seu e-mail" />
        </el-form-item>
        <el-form-item label="Senha">
          <el-input v-model="form.password" type="password" placeholder="Digite sua senha" />
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="handleLogin" :loading="loading">Entrar</el-button>
        </el-form-item>
        <div v-if="error" style="color: #f56c6c; text-align: center;">{{ error }}</div>
      </el-form>
    </el-card>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const router = useRouter()
const authStore = useAuthStore()
const form = reactive({ email: '', password: '' })
const loading = ref(false)
const error = ref('')

async function handleLogin() {
  error.value = ''
  loading.value = true
  const result = await authStore.login(form)
  loading.value = false
  if (result.success) {
    router.push('/')
  } else {
    error.value = result.error
  }
}
</script>
