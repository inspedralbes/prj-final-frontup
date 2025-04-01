<template>
  <NuxtPage />
  <div id="app" v-if="!lliureStore.lliure">
    <div v-if="show" class="alert">
      {{ message }}
    </div>

    <div class="card">
      <ul class="list">
        <li class="element" @click="navigateToHome">
          <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="none"
            stroke="#7e8590" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
            <polyline points="9 22 9 12 15 12 15 22"></polyline>
          </svg>
          <p class="label">Home</p>
        </li>
      </ul>

      <div class="separator"></div>

      <ul class="list">
        <li class="element crear" @click="navigateToLliure">
          <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="none"
            stroke="#7e8590" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
          </svg>
          <p class="label">Crear Projecte</p>
        </li>
        <li class="element" @click="navigateToMeusProjectes">
          <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="none"
            stroke="#7e8590" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
            <circle cx="12" cy="7" r="4"></circle>
          </svg>
          <p class="label">Els Meus Projectes</p>
        </li>
        <li class="element" @click="navigateToNiveles">
          <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="none"
            stroke="#7e8590" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 3h18v18H3zM12 8v8m-4-4h8" />
          </svg>
          <p class="label">Nivells</p>
        </li>
        <li class="element" @click="navigateToTotsProjectes">
          <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="none"
            stroke="#7e8590" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="3" width="18" height="18" rx="2" />
            <path d="M8 7v10M16 7v10M12 7v10" />
          </svg>
          <p class="label">Tots els Projectes</p>
        </li>
      </ul>

      <div class="separator"></div>

      <ul class="list">
        <li class="element" @click="toggleTheme">
          <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="none"
            stroke="#7e8590" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="5" />
            <path
              d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42" />
          </svg>
          <p class="label">{{ themeIcon }}</p>
        </li>
        <li class="element" @click="navigateToProfile" v-if="appStore.isLoggedIn">
          <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="none"
            stroke="#7e8590" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
            <circle cx="12" cy="7" r="4" />
          </svg>
          <p class="label">Perfil</p>
        </li>
        <li class="element" @click="navigateToLogin" v-else>
          <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="none"
            stroke="#7e8590" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M13.8 12H3" />
          </svg>
          <p class="label">{{ loginText }}</p>
        </li>
      </ul>
    </div>

    <header>
      <div class="header-left">
        <template v-if="buscadorStore.mostrarBuscador">
          <input class="search-box" type="text" placewholder="Cercar...">
        </template>
        <template v-else>
          <button class="btn volver-btn" @click="navigateToHome">Tornar al Home</button>
        </template>
      </div>
      <div class="header-right">
        <button @click="toggleTheme" class="btn">{{ themeIcon }}</button>
        <button class="btn" @click="navigateToProfile" v-if="appStore.isLoggedIn">Perfil</button>
        <button class="btn" @click="navigateToLogin" v-else>{{ loginText }}</button>
      </div>
    </header>
  </div>
</template>


<script setup>

import { useLliureStore } from '~/stores/app'
import { useAppStore } from '@/stores/app';
import { useIdProyectoActualStore } from '@/stores/app'
import useCommunicationManager from '@/stores/comunicationManager'
import { ref, watch, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useBuscadorStore } from '@/stores/app'

const idProyectoActualStore = useIdProyectoActualStore();
const theme = ref('')
const themeIcon = ref('☀️')
const loginText = ref('Login')

const appStore = useAppStore()
const lliureStore = useLliureStore()
const buscadorStore = useBuscadorStore()

const comunicationManager = useCommunicationManager();
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
const show = ref(false)
const message = ref('')

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
const showAlert = (alertMessage) => {
  message.value = alertMessage
  show.value = true
  setTimeout(() => {
    show.value = false
  }, 3000)
}
const projecte = reactive({ result: {} });
const navigateToLliure = async () => {
  if (appStore.loginInfo.id != null) {
    try {
      projecte.result = await comunicationManager.crearProyectoDB({
        nombre: "untitled",
        user_id: appStore.loginInfo.id,
        html_code: "",
        css_code: "",
        js_code: "",
      });
      console.log("lo que devuelve")
      console.log(projecte.result.id)
    } catch (error) {
      console.error(error);
    }
    let id = projecte.result.id;
    idProyectoActualStore.actalizarId(id);
    localStorage.setItem("idProyectoActual", id);
    router.push(`/lliure/${id}`);
  } else {
    showAlert(`Registra't per crear un projecte`)
  }
}

const navigateToNiveles = () => {
  router.push('/niveles');
}

const navigateToLogin = () => {
  router.push('/login');
}

const navigateToProfile = () => {
  router.push('/perfil');
}

const navigateToMeusProjectes = () => {
  router.push('/projectes');
}

const navigateToHome = () => {
  router.push('/')
}
const navigateToTotsProjectes = () => {
  router.push("/totsProjectes");
}
</script>


<style scoped>
body {
  margin: 0;
  font-family: Arial, sans-serif;
  background-color: #121212;
  color: #e0e0e0;
}

h2 {
  padding: 10px;
}

.alert {
  position: fixed;
  font-size: larger;
  top: 20px;
  right: 20px;
  padding: 4vh 4vw;
  background: #ff4444;
  color: white;
  border-radius: 4px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  animation: movimiento 0.3s ease-out, opacidad 2s ease-in-out forwards;
  z-index: 1000;
}

@keyframes movimiento {
  from {
    transform: translateX(100%);
    opacity: 0;
  }

  to {
    transform: translateX(0);
    opacity: 0.9;
  }
}

@keyframes opacidad {
  0% {
    opacity: 0.9;
  }

  60% {
    opacity: 0.9;
  }

  100% {
    opacity: 0;
  }
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

.card {
  width: 240px;
  height: 100vh;
  background-color: rgba(36, 40, 50, 1);
  background-image: linear-gradient(139deg,
      rgba(36, 40, 50, 1) 0%,
      rgba(36, 40, 50, 1) 0%,
      rgba(37, 28, 40, 1) 100%);
  border-radius: 0 10px 10px 0;
  padding: 20px 0;
  display: flex;
  flex-direction: column;
  gap: 10px;
  position: fixed;
  left: 0;
  top: 0;
  z-index: 1000;
}

.separator {
  border-top: 1.5px solid #42434a;
  margin: 10px 0;
}

.list {
  list-style-type: none;
  padding: 0 15px;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.element {
  display: flex;
  align-items: center;
  gap: 15px;
  padding: 10px;
  border-radius: 8px;
  color: #7e8590;
  cursor: pointer;
  transition: all 0.3s ease;
}

.element:hover {
  background-color: #5353ff;
  color: white;
  transform: translateX(5px);
}

.element:hover svg {
  stroke: white;
}

.element.crear:hover {
  background-color: #4CAF50;
}

.element .label {
  font-weight: 600;
  font-size: 0.95rem;
  margin: 0;
}
</style>

<style>
body {
  background-color: #2d2d2d;
}
</style>