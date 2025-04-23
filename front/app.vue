<template>
  <div id="app" v-if="!lliureStore.lliure">
    <div v-if="show" class="leftsection-alert">
      {{ message }}
    </div>

    <div class="leftsection-card">
      <ul class="leftsection-list">
        <li class="leftsection-element" @click="navigateToHome">
          <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="none"
            stroke="#7e8590" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
            <polyline points="9 22 9 12 15 12 15 22"></polyline>
          </svg>
          <p class="label">FrontUp</p>
        </li>
      </ul>

      <div class="leftsection-separator"></div>

      <ul class="leftsection-list">
        <li class="leftsection-element leftsection-element--crear" @click="navigateToLliure">
          <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="none"
            stroke="#7e8590" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
          </svg>
          <p class="label">Crear Projecte</p>
        </li>
        <li v-if="isLoged" class="leftsection-element" @click="navigateToMeusProjectes">
          <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="none"
            stroke="#7e8590" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
            <circle cx="12" cy="7" r="4"></circle>
          </svg>
          <p class="label">Els Meus Projectes</p>
        </li>
        <li class="leftsection-element" @click="navigateToNiveles">
          <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="none"
            stroke="#7e8590" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 3h18v18H3zM12 8v8m-4-4h8" />
          </svg>
          <p class="label">Nivells</p>
        </li>
        <li class="leftsection-element" @click="navigateToTotsProjectes">
          <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="none"
            stroke="#7e8590" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="3" width="18" height="18" rx="2" />
            <path d="M8 7v10M16 7v10M12 7v10" />
          </svg>
          <p class="label">Tots els Projectes</p>
        </li>
        <li v-if="isLoged" class="leftsection-element" @click="navigateToLikes">
          <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="none"
            stroke="#7e8590" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path
              d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
            </path>
          </svg>
          <p class="label">Likes</p>
        </li>
      </ul>
      <div class="collaboration-section">
        <div class="join-collaboration">
          <input type="text" v-model="collaborationCode" placeholder="Codi de col·laboració" class="collaboration-input"
            maxlength="6" />
          <button class="join-button" @click="joinCollaboration">Unir-se</button>
        </div>
      </div>
      <div class="leftsection-separator"></div>

      <ul class="leftsection-list">
        <li class="leftsection-element" @click="navigateToProfile" v-if="appStore.isLoggedIn">
          <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="none"
            stroke="#7e8590" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
            <circle cx="12" cy="7" r="4" />
          </svg>
          <p class="label">Perfil</p>
        </li>
        <li class="leftsection-element" @click="navigateToLogin" v-else>
          <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="none"
            stroke="#7e8590" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M13.8 12H3" />
          </svg>
          <p class="label">{{ loginText }}</p>
        </li>
      </ul>
    </div>
  </div>
  <NuxtPage />
  <footer>
    <p>© 2025 FrontUp</p>
  </footer>
</template>

<script setup>
import { useLliureStore } from '~/stores/app'
import { useAppStore } from '@/stores/app'
import { useIdProyectoActualStore } from '@/stores/app'
import useCommunicationManager from '@/stores/comunicationManager'
import { ref, watch, onMounted, reactive, computed } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useBuscadorStore } from '@/stores/app'

const idProyectoActualStore = useIdProyectoActualStore();
const theme = ref('')
const loginText = ref('Login')
const isLoged = computed(() => {
  return localStorage.getItem('loginInfo') !== null
})
const appStore = useAppStore()
const lliureStore = useLliureStore()
const buscadorStore = useBuscadorStore()
const comunicationManager = useCommunicationManager();
const roomId = computed(() => route.params.id || 'default-room');

onMounted(() => {
  comunicationManager.connect();

});

const router = useRouter()
const route = useRoute()
const collaborationCode = ref('');
const show = ref(false)
const message = ref('')
const projecte = reactive({ result: {} });

const showAlert = (alertMessage) => {
  message.value = alertMessage
  show.value = true
  setTimeout(() => {
    show.value = false
  }, 3000)
}

const joinCollaboration = () => {
  const code = collaborationCode.value.trim();
  if (!code || code.length !== 6) {
    showAlert('El codi de col·laboració ha de tenir 6 caràcters');
    return;
  }

  const sock = comunicationManager.socket.value;
  if (!sock || typeof sock.emit !== 'function') {
    showAlert('Error de connexió amb el servidor');
    return;
  }

  console.log('Botón presionado, código ingresado:', code);
  sock.emit('check-room', { roomId: code }, ({ exists, projectId }) => {
    if (!exists) {
      showAlert('El codi no existeix o ha caducat');
      return;
    }
    router.push(`/lliure/${projectId}?code=${code}`);
  });
};




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
const navigateToLikes = () => {
  router.push('/likes');
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

watch(() => appStore.isLoggedIn, (newValue) => {
})

watch(route, () => {
  if (route.query.code) {
    const projectId = route.query.projectId;
    if (projectId) {
      router.push(`/lliure/${projectId}?code=${route.query.code}`);
    }
  }
});

</script>


<style>
body {
  background-color: rgb(29, 32, 39);
  margin-left: 200px;
}

h2 {
  padding: 10px;
}

.leftsection-alert {
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

.leftsection-card {
  width: 220px;
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

.leftsection-separator {
  border-top: 1.5px solid #42434a;
  margin: 10px 0;
}

.leftsection-list {
  list-style-type: none;
  padding: 0 15px;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.leftsection-element {
  display: flex;
  align-items: center;
  gap: 15px;
  padding: 10px;
  border-radius: 8px;
  color: #7e8590;
  cursor: pointer;
  transition: all 0.3s ease;
}

.leftsection-element:hover {
  background-color: #5353ff;
  color: white;
  transform: translateX(5px);
}

.leftsection-element:hover svg {
  stroke: white;
}

.leftsection-element--crear:hover {
  background-color: #4CAF50;
}

.leftsection-element .label {
  font-weight: 600;
  font-size: 0.95rem;
  margin: 0;
}

footer {
  background-image: linear-gradient(139deg,
      rgba(34, 38, 47, 1) 0%,
      rgba(32, 36, 42, 1) 50%,
      rgba(28, 33, 42, 1) 100%);
  text-align: center;
  padding: 10px 20px;
  color: white;
}
</style>
