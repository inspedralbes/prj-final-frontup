<template>
  <router-view />
  <div id="app" v-if="!lliureStore.lliure">
    <div v-if="show" class="alert">
      {{ message }}
    </div>
    <div class="left-section">
      <h2>FrontUp</h2>
      <button class="btn btn-crear" @click="navigateToLliure">Crear projecte</button>
      <button class="btn" @click="navigateToMeusProjectes">Els meus projectes</button>
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

  </div>
</template>

<script setup>

import { useLliureStore } from '~/stores/app'
import { useRouter } from 'vue-router'
import { useAppStore } from '@/stores/app';
import useCommunicationManager from '@/stores/comunicationManager'
import { useIdProyectoActualStore } from '@/stores/app'

const idProyectoActualStore = useIdProyectoActualStore();
const theme = ref('')
const themeIcon = ref('☀️')
const loginText = ref('Login')
const comunicationManager = useCommunicationManager();
const appStore = useAppStore();
const lliureStore = useLliureStore();
const router = useRouter()
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
const projecte = reactive({result:{}});
const navigateToLliure = async () => {
  if (appStore.loginInfo.id != null) {
    try {
        projecte.result= await comunicationManager.crearProyectoDB({
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
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
  60%{
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
</style>
<style>
body {
  background-color: #2d2d2d;
}
</style>