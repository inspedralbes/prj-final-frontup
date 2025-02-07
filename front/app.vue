<template>
  <NuxtPage />
  <div id="app" v-if="!lliureStore.lliure">
    <div class="left-section">
      <button class="btn-home" @click="navigateToHome"><h2>FrontUp</h2></button>
      <button class="btn btn-crear" @click="navigateToLliure">Crear projecte</button>
      <button class="btn" @click="navigateToMeusProjectes">Els meus projectes</button>
      <button class="btn" @click="navigateToNiveles">Nivells</button>
      <button class="btn">Projectes favorits</button>
    </div>

    <header>
      <div class="header-left">
        <template v-if="buscadorStore.mostrarBuscador">
          <input class="search-box" type="text" placeholder="Buscar...">
        </template>
        <template v-else>
          <button class="btn volver-btn" @click="navigateToHome">Tornar al Home</button>
        </template>
      </div>
      <div class="header-right">
        <button @click="toggleTheme" class="btn">{{ themeIcon }}</button>
        <button class="btn" @click="navigateToProfile" v-if="appStore.isLoggedIn">Mi Perfil</button>
        <button class="btn" @click="navigateToLogin" v-else>{{ loginText }}</button>
      </div>
    </header>
  </div>
</template>


<script setup>
import { ref, watch, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useLliureStore } from '~/stores/app'
import { useAppStore } from '@/stores/app'
import { useBuscadorStore } from '@/stores/app'

const theme = ref('')
const themeIcon = ref('☀️')
const loginText = ref('Login')

const appStore = useAppStore()
const lliureStore = useLliureStore()
const buscadorStore = useBuscadorStore()

const router = useRouter()
const route = useRoute()

const updateBuscadorState = () => {
  if (route.path === '/' || route.name === 'index') {
    buscadorStore.activarBuscador();
  } else {
    buscadorStore.desactivarBuscador();
  }
}

onMounted(() => {
  updateBuscadorState()
})

// Observar cambios en la ruta para actualizar el estado automáticamente
watch(() => route.path, () => {
  updateBuscadorState()
})

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

const navigateToMeusProjectes = () => {
  router.push('/projectes')
}

const navigateToHome = () => {
  router.push('/')
}
</script>


<style scoped>
body {
  margin: 0;
  font-family: Arial, sans-serif;
  background-color: #121212;
  color: #e0e0e0;
}

h2{
  padding: 10px;
}

.left-section {
  position: fixed;
  top: 0;
  left: 0;
  width: 180px;
  height: 100%;
  background-color: #1e1e1e;
  box-sizing: border-box;
  text-align: center;
}

.left-section .btn {
  background-color: #1e1e1e;
  color: #fff;
  border: none;
  padding: 10px 15px;
  width: 100%;
  cursor: pointer;
  text-transform: uppercase;
  border-radius: 4px;
}
  
.left-section .btn:hover {
  background-color: #141414;
}

.left-section .btn-crear {
  background-color: #000; 
  font-weight: bold;
  border: 2px solid;
  border-radius: 6px;
  width: 80%;
}

.left-section .btn-crear:hover {
  background-color: #000;
}

.left-section .btn-home {
  background-color: #1e1e1e; 
  height: 80px;
  border: none;
  padding: 10px 15px;
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
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
  background-color: #000000;
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
  border: 1px solid #555;
  border-radius: 4px;
  background-color: #333;
  color: #fff;
}

.header-right .btn {
  padding: 10px 15px;
  background-color: #444;
  color: #fff;
  cursor: pointer;
  text-transform: uppercase;
  border-radius: 4px;
  border: none;
}

.header-right .btn:hover {
  background-color: #555;
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

.volver-btn {
  padding: 10px 15px;
  background-color: #444;
  color: #fff;
  cursor: pointer;
  text-transform: uppercase;
  border-radius: 4px;
  border: none;
  transition: background-color 0.3s ease;
}

.volver-btn:hover {
  background-color: #555;
}

</style>

<style>
body{
  background-color: #2d2d2d;
}
</style>