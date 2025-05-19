<template>
  <div id="app" v-if="!lliureStore.lliure">
    <div v-if="show" class="leftsection-alert">
      {{ message }}
    </div>
    <header class="top-navbar">
      <button class="toggle-navbar-btn" @click="navbarVisible = !navbarVisible">
        {{ navbarVisible ? "✕" : "☰" }}
      </button>
    </header>

    <div :class="['navbar', { show: navbarVisible, hide: !navbarVisible }]">
      <div :class="'leftsection-card'">
        <ul class="leftsection-list">
          <li></li>
          <br class="space" />
          <li class="leftsection-element" @click="navigateToHome(); navbarVisible = false">
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
          <li class="leftsection-element leftsection-element--crear" @click="navigateToLliure(); navbarVisible = false">
            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="none"
              stroke="#7e8590" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="12" y1="5" x2="12" y2="19"></line>
              <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            <p class="label">Crear Projecte</p>
          </li>
          <li v-if="isLoged" class="leftsection-element" @click="navigateToMeusProjectes(); navbarVisible = false">
            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="none"
              stroke="#7e8590" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
              <circle cx="12" cy="7" r="4"></circle>
            </svg>
            <p class="label">Els Meus Projectes</p>
          </li>
          <li class="leftsection-element" @click="navigateToNiveles(); navbarVisible = false">
            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="none"
              stroke="#7e8590" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M3 3h18v18H3zM12 8v8m-4-4h8" />
            </svg>
            <p class="label">Nivells</p>
          </li>
          <li class="leftsection-element" @click="navigateToTotsProjectes(); navbarVisible = false">
            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="none"
              stroke="#7e8590" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="3" width="18" height="18" rx="2" />
              <path d="M8 7v10M16 7v10M12 7v10" />
            </svg>
            <p class="label">Tots els Projectes</p>
          </li>
          <li v-if="isLoged" class="leftsection-element" @click="navigateToLikes(); navbarVisible = false">
            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="none"
              stroke="#7e8590" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path
                d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
              </path>
            </svg>
            <p class="label">Likes</p>
          </li>
          <li class="leftsection-element" @click="navigateToCreadorNiveles(); navbarVisible = false">
            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="none"
              stroke="#7e8590" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M3 3h18v18H3zM12 8v8m-4-4h8" />
            </svg>
            <p class="label">Creador nivells</p>
          </li>
          <li class="leftsection-element" @click="navigateToNivelesUsuarios(); navbarVisible = false">
            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="none"
              stroke="#7e8590" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M3 3h18v18H3zM12 8v8m-4-4h8" />
            </svg>
            <p class="label">Nivells d'usuaris</p>
          </li>
          <li class="leftsection-element" @click="showCollaborationModal = true; navbarVisible = false">
            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="none"
              stroke="#7e8590" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M10 20v-6h4v6m-7-8h10V4H7z" />
            </svg>
            <p class="label">Unir-se a Projecte</p>
          </li>
          <div class="leftsection-separator"></div>
        </ul>

        <ul class="leftsection-list">
          <li class="leftsection-element" @click="navigateToProfile(); navbarVisible = false"
            v-if="appStore.isLoggedIn">
            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="none"
              stroke="#7e8590" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
              <circle cx="12" cy="7" r="4" />
            </svg>
            <p class="label">Perfil</p>
          </li>
          <li class="leftsection-element" @click="navigateToLogin(); navbarVisible = false" v-else>
            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="none"
              stroke="#7e8590" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M13.8 12H3" />
            </svg>
            <p class="label">{{ loginText }}</p>
          </li>
        </ul>
      </div>
    </div>

    <div v-if="showCollaborationModal" class="modal-overlay" @click.self="showCollaborationModal = false">
      <div class="modal">
        <h3>Introdueix el codi</h3>
        <input type="text" v-model="collaborationCode" maxlength="6" placeholder="Codi de col·laboració" />
        <div class="modal-actions">
          <button @click="joinCollaboration">Unir-se</button>
          <button class="cancel" @click="showCollaborationModal = false">
            Cancel·lar
          </button>
        </div>
      </div>
    </div>

    <AlertComponent v-if="alertVisible" :success="false" :text="'Has d\'iniciar sessió per crear un projecte nou.'"
      :duration="3000" @close="alertVisible = false" />
  </div>
  <NuxtPage />
  <footer>
    <p>© 2025 FrontUp</p>
  </footer>
</template>

<script setup>
import { useLliureStore } from "~/stores/app";
import { useAppStore } from "@/stores/app";
import { useIdProyectoActualStore } from "@/stores/app";
import useCommunicationManager from "@/stores/comunicationManager";
import { ref, watch, onMounted, reactive, computed } from "vue";
import { useRouter, useRoute } from "vue-router";
import { useBuscadorStore } from "@/stores/app";
import AlertComponent from "./components/AlertComponent.vue";

const showCollaborationModal = ref(false);
const idProyectoActualStore = useIdProyectoActualStore();
const theme = ref("");
const loginText = ref("Login");
const isLoged = computed(() => {
  return localStorage.getItem("loginInfo") !== null;
});
const appStore = useAppStore();
const lliureStore = useLliureStore();
const buscadorStore = useBuscadorStore();
const comunicationManager = useCommunicationManager();
const roomId = computed(() => route.params.id || "default-room");

const alertVisible = ref(false);

onMounted(() => {
  comunicationManager.connect();
});

const router = useRouter();
const route = useRoute();
const collaborationCode = ref("");
const show = ref(false);
const message = ref("");
const projecte = reactive({ result: {} });
const navbarVisible = ref(false);

const showAlert = (alertMessage) => {
  message.value = alertMessage;
  show.value = true;
  setTimeout(() => {
    show.value = false;
  }, 3000);
};

const joinCollaboration = () => {
  const code = collaborationCode.value.trim();
  if (!code || code.length !== 6) {
    showAlert("El codi de col·laboració ha de tenir 6 caràcters");
    return;
  }

  const sock = comunicationManager.socket.value;
  if (!sock || typeof sock.emit !== "function") {
    showAlert("Error de connexió amb el servidor");
    return;
  }

  console.log("Botón presionado, código ingresado:", code);
  sock.emit("check-room", { roomId: code }, ({ exists, projectId }) => {
    if (!exists) {
      showAlert("El codi no existeix o ha caducat");
      return;
    }
    router.push(`/lliure/${projectId}?code=${code}`);
  });
};

const navigateToLliure = async () => {
  // Verificar si existe loginInfo en localStorage
  if (localStorage.getItem("loginInfo") !== null) {
    try {
      projecte.result = await comunicationManager.crearProyectoDB({
        nombre: "untitled",
        user_id: appStore.loginInfo.id,
        html_code: "",
        css_code: "",
        js_code: "",
      });
      console.log("lo que devuelve");
      console.log(projecte.result.id);

      let id = projecte.result.id;
      idProyectoActualStore.actalizarId(id);
      localStorage.setItem("idProyectoActual", id);
      router.push(`/lliure/${id}`);
    } catch (error) {
      console.error(error);
    }
  } else {
    alertVisible.value = true;
  }
};

const navigateToNiveles = () => {
  router.push("/niveles");
};

const navigateToCreadorNiveles = () => {
  router.push("/creadorNiveles");
};

const navigateToNivelesUsuarios = () => {
  router.push("/nivelesUsuarios");
};

const navigateToLikes = () => {
  router.push("/likes");
};
const navigateToLogin = () => {
  router.push("/login");
};

const navigateToProfile = () => {
  router.push("/perfil");
};

const navigateToMeusProjectes = () => {
  router.push("/projectes");
};

const navigateToHome = () => {
  router.push("/");
};
const navigateToTotsProjectes = () => {
  router.push("/totsProjectes");
};

watch(
  () => appStore.isLoggedIn,
  (newValue) => { }
);

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
  margin-left: 205px;
}

.leftsection-alert {
  position: fixed;
  font-size: larger;
  top: 20px;
  right: 20px;
  padding: 4vh 4vw;
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

.leftsection-card {
  width: 220px;
  height: 100vh;
  background-image: linear-gradient(139deg,
      rgba(36, 40, 50, 1) 0%,
      rgba(36, 40, 50, 1) 0%,
      rgba(37, 28, 40, 1) 100%);
  border-radius: 0 10px 10px 0;
  padding: 0px 0;
  display: flex;
  flex-direction: column;
  gap: 10px;
  position: fixed;
  left: 0;
  top: 0px;
  z-index: 1000;
}

.leftsection-card,
.navbar {
  transition: transform 0.3s ease;
  max-height: 100vh;
  overflow-y: auto;
  scrollbar-width: thin;
  scrollbar-color: #555 transparent;
}

.navbar::-webkit-scrollbar {
  width: 6px;
}

.navbar::-webkit-scrollbar-thumb {
  background-color: #555;
  border-radius: 3px;
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
  background-color: #4caf50;
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
  padding: 10px 0px;
  color: white;
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(10, 10, 10, 0.8);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1100;
  animation: fadeInOverlay 0.25s ease-out;
}

@keyframes fadeInOverlay {
  from {
    opacity: 0;
  }

  to {
    opacity: 1;
  }
}

.modal {
  background: linear-gradient(135deg, #2a2d3a, #232539);
  padding: 2rem;
  border-radius: 12px;
  max-width: 400px;
  width: 90%;
  text-align: center;
  box-shadow: 0 12px 32px rgba(0, 0, 0, 0.4);
  color: #eceff4;
  animation: popIn 0.3s cubic-bezier(0.2, 0.8, 0.2, 1);
}

@keyframes popIn {
  from {
    transform: scale(0.95);
    opacity: 0;
  }

  to {
    transform: scale(1);
    opacity: 1;
  }
}

.modal h3 {
  margin-bottom: 1rem;
  font-size: 1.25rem;
  letter-spacing: 0.03em;
  color: #81a1c1;
  text-transform: uppercase;
}

.modal input {
  width: 80%;
  padding: 0.75rem 1rem;
  margin: 1rem 0;
  font-size: 1rem;
  border: 1px solid #4c566a;
  border-radius: 6px;
  background-color: #2e3440;
  color: #eceff4;
  box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.2);
  transition: border-color 0.3s, box-shadow 0.3s;
}

.modal input:focus {
  outline: none;
  border-color: #81a1c1;
  box-shadow: 0 0 0 3px rgba(129, 161, 193, 0.3);
}

.modal-actions {
  display: flex;
  gap: 1rem;
  margin-top: 1.5rem;
}

.modal-actions button {
  flex: 1;
  padding: 0.75rem;
  font-weight: 600;
  font-size: 0.95rem;
  text-transform: uppercase;
  letter-spacing: 0.03em;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.3);
  transition: transform 0.2s, filter 0.2s;
}

.modal-actions button:not(.cancel) {
  background: linear-gradient(90deg, #5e81ac, #81a1c1);
  color: #fff;
}

.modal-actions button:not(.cancel):hover {
  filter: brightness(1.1);
  transform: translateY(-2px);
}

.modal-actions .cancel {
  background: linear-gradient(90deg, #bf616a, #d08770);
  color: #eceff4;
}

.modal-actions .cancel:hover {
  filter: brightness(1.1);
  transform: translateY(-2px);
}

.toggle-navbar-btn {
  display: none;
}

.space {
  display: none;
}

@media (max-width: 450px) {
  .space {
    display: block;
  }

  body {
    margin: 0;
    padding: 0;
    overflow-x: hidden;
  }

  .toggle-navbar-btn {
    display: block;
    background-color: transparent;
    color: #7e8590;
    font-size: 24px;
    border: none;
    cursor: pointer;
    position: fixed;
    top: 10px;
    left: 15px;
    z-index: 1001;
  }

  .top-navbar {
    width: 100%;
    height: 55px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 16px;
    box-sizing: border-box;
    position: fixed;
    top: 0px;
    left: 0;
    z-index: 1002;
    background-color: rgba(36, 40, 50, 1);
  }

  .leftsection-card {
    top: -5%;
  }

  .navbar {
    scrollbar-width: none;
    position: fixed;
    top: 50px;
    left: 0;
    width: 100%;
    height: calc(100vh - 50px);
    background-color: #222;
    box-shadow: -2px 0 5px rgba(0, 0, 0, 0.1);
    transition: transform 0.4s ease-in-out;
    transform: translateX(-100%);
    z-index: 1000;
  }

  .navbar.show {
    transform: translateX(0);
    background-color: transparent;
    width: 60%;
    scrollbar-width: none;
  }

  .navbar.hide {
    transform: translateX(-100%);
    background-color: transparent;
  }

  footer {
    background-image: linear-gradient(139deg,
        rgba(34, 38, 47, 1) 0%,
        rgba(32, 36, 42, 1) 50%,
        rgba(28, 33, 42, 1) 100%);
    width: 100%;
    text-align: center;
    padding: 20px 0;
    color: white;
    position: relative;
    margin: 0 auto;
  }
}
</style>
