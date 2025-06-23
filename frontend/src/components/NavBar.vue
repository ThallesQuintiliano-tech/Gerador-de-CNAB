<template>
  <el-menu
    :default-active="activeIndex"
    class="nav-menu"
    mode="horizontal"
    background-color="#409EFF"
    text-color="#fff"
    active-text-color="#ffd04b"
    router
  >
    <el-menu-item index="/">
      <el-icon><House /></el-icon>
      Dashboard
    </el-menu-item>
    
    <el-menu-item index="/cnab">
      <el-icon><Document /></el-icon>
      CNAB
    </el-menu-item>
    
    <el-menu-item v-if="user?.profile === 'admin'" index="/users">
      <el-icon><User /></el-icon>
      Usuários
    </el-menu-item>
    
    <div class="flex-grow" />
    
    <el-sub-menu index="user">
      <template #title>
        <el-icon><User /></el-icon>
        {{ user?.name }}
      </template>
      <el-menu-item @click="handleLogout">
        <el-icon><SwitchButton /></el-icon>
        Sair
      </el-menu-item>
    </el-sub-menu>
  </el-menu>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import { ElMessage } from 'element-plus'

const route = useRoute()
const router = useRouter()
const authStore = useAuthStore()

const user = computed(() => authStore.user)
const activeIndex = computed(() => route.path)

const handleLogout = () => {
  authStore.logout()
  ElMessage.success('Logout realizado com sucesso!')
  router.push('/login')
}
</script>

<style scoped>
.nav-menu {
  display: flex;
  align-items: center;
}

.flex-grow {
  flex-grow: 1;
}
</style> 