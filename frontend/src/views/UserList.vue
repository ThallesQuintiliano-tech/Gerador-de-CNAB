<template>
  <div>
    <h2>Gestão de Usuários</h2>
    <el-table :data="users" style="width: 100%">
      <el-table-column prop="name" label="Nome" />
      <el-table-column prop="email" label="E-mail" />
      <el-table-column prop="profile" label="Perfil" />
    </el-table>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../services/api'

const users = ref([])

onMounted(async () => {
  const { data } = await api.get('/users')
  users.value = data
})
</script>
