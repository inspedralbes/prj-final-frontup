<template>
  <router-view />
  <link rel="stylesheet" href="/front/static/normalize.css">
  <div id="app" v-if="!lliureStore.lliure">
    <!-- Barra lateral y barra superior -->
    <div class="left-section">
      <h2>FrontUp</h2>
      <button class="btn">Els meus projectes</button>
      <button class="btn" @click="navigateToLliure">Lliure</button>
      <button class="btn" @click="navigateToNiveles">Nivells</button>
      <button class="btn">Projectes favorits</button>
    </div>

    <header>
      <div class="header-left">
        <input class="search-box" type="text" placeholder="Buscar...">
      </div>
      <div class="header-right">
        <button @click="toggleTheme" class="btn">{{ themeIcon }}</button>
        <button class="btn" @click="navigateToProfile" v-if="appStore.isLoggedIn">Mi Perfil</button>
        <button class="btn" @click="navigateToLogin" v-else>{{ loginText }}</button>

      </div>
    </header>

    <!-- Contenido principal -->
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useLliureStore } from '~/stores/app' // Asegúrate de importar el store correctamente
import { useRouter } from 'vue-router' // Asegúrate de usar el router correctamente
import { useAppStore } from '@/stores/app';


// Variables reactivas
const theme = ref('')
const themeIcon = ref('☀️')
const loginText = ref('Login')

// Usar el store
const appStore = useAppStore();
const lliureStore = useLliureStore()

// Usar el router
const router = useRouter()

// Métodos
const toggleTheme = () => {
  if (theme.value === '') {
    theme.value = 'light-mode'
    themeIcon.value = '🌙'
  } else {
    theme.value = ''
    themeIcon.value = '☀️'
  }
  document.body.className = theme.value
}

const navigateToLliure = () => {
  router.push('/lliure')
}

const navigateToNiveles = () => {
  router.push('/niveles')
}

const navigateToLogin = () => {
  router.push('/login')
}

const navigateToProfile = () => {
  router.push('/perfil')
}
</script>

<style scoped>
body {
  margin: 0;
  font-family: Arial, sans-serif;
}

.left-section {
  position: fixed;
  top: 0;
  left: 0;
  width: 180px;
  height: 100%;
  background-color: #404040;
  padding: 15px;
  box-sizing: border-box;
}

.left-section .btn {
  padding: 10px 15px;
  background-color: #333;
  color: #fff;
  cursor: pointer;
  text-transform: uppercase;
  border-radius: 4px;
}

.left-section button {
  margin-bottom: 15px;
}

.left-section h2 {
  color: white;
}

header {
  position: absolute;
  top: 0;
  left: 180px;
  right: 0;
  height: 80px;
  background-color: black;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 20px;
}

.header-left {
  display: flex;
  align-items: center;
}

.header-right {
  display: flex;
  gap: 20px;
}

.search-box {
  padding: 8px;
  width: 300px;
  border: 1px solid #ddd;
  border-radius: 4px;
}

.header-right .btn {
  padding: 10px 15px;
  background-color: #333;
  color: #fff;
  cursor: pointer;
  text-transform: uppercase;
  border-radius: 4px;
}

.header-right .btn:hover {
  background-color: #0056b3;
}

.light-mode {
  background-color: #f7f7f7;
  color: #333;
}

.light-mode .todo {
  background-color: #f5ebeb;
}

.light-mode .left-section {
  background-color: #eee0e0;
}

.light-mode .btn {
  background-color: #eee;
  color: #333;
  border: 1px solid #ccc;
}

.light-mode .btn:hover {
  background-color: #ddd;
}

.light-mode header {
  background-color: #cfc8c8;
}

.light-mode h2 {
  color: black;
}

.light-mode .search-box {
  background-color: #fff;
  border: 1px solid #ddd;
  color: #333;
}
</style>
