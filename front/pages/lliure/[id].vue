<template>
  <AlertComponent v-if="alertVisible" :success="alertSuccess" :text="alertText" :duration="1500"
    @close="alertVisible = false" />
  <div class="todo">
    <header class="header" v-show="!isExpanded">
      <button class="header-button" @click="leaveCollaborationSession(); goBack()">Enrere</button>
      <input type="text" v-model="title" class="header-title" @focus="isEditing = true" @blur="isEditing = false"
        :readonly="!isEditing" />
      <div class="header-actions">
        <button class="header-button" @click="handleShare" :class="{ 'sharing-active': isCollaborating }">
          {{ isCollaborating ? 'Compartint' : 'Compartir' }}
        </button>
        <button class="header-button" @click="toggleChat">Xat IA</button>
        <button class="header-button" @click="guardarProyecto">Guardar</button>
        <select v-model="isPrivate" @change="savePrivacy" class="header-select">
          <option :value="0">Público</option>
          <option :value="1">Privado</option>
        </select>
      </div>
    </header>
    <div class="top-bar">
      <div v-if="activeUsersList.length > 0" class="users-container">
        <div v-for="(user, index) in activeUsersList" :key="index" class="user-card">
          <img :src="user.avatar" alt="avatar" class="avatar-img" />
          <div class="user-info">
            <div class="user-name">{{ user.name }}</div>
          </div>
        </div>
      </div>

      <div class="layout-buttons">
        <button class="button-position" @click="setLayout('left')" aria-label="Sidebar izquierda">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
            <rect x="3" y="3" width="6" height="18" />
            <rect x="11" y="3" width="10" height="18" opacity="0.3" />
          </svg>
        </button>

        <button class="button-position" @click="setLayout('right')" aria-label="Sidebar derecha">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
            <rect x="15" y="3" width="6" height="18" />
            <rect x="3" y="3" width="10" height="18" opacity="0.3" />
          </svg>
        </button>

        <button class="button-position" @click="setLayout('normal')" aria-label="Layout normal">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
            <rect x="3" y="3" width="18" height="6" />
            <rect x="3" y="11" width="18" height="10" opacity="0.3" />
          </svg>
        </button>
      </div>
    </div>

    <!-- Modal para compartir código de colaboración -->
    <div v-if="showShareModal" class="modal-overlay" @click="closeShareModal">
      <div class="modal-content" @click.stop>
        <button class="close-modal-button" @click="closeShareModal">✖</button>
        <h2>Codi de col·laboració</h2>
        <div class="share-code-container">
          <p class="share-code">{{ shareCode }}</p>
          <button class="modal-button copy-button" @click="copyShareCode">
            Copiar
          </button>
        </div>
        <p class="share-instructions">Comparteix aquest codi amb altres usuaris perquè puguin unir-se i editar aquest
          projecte en temps real.</p>
        <div class="modal-actions-single">
          <button type="button" class="modal-button cancel-room" @click="closeRoom">Tancar Room</button>
        </div>
      </div>
    </div>

    <!-- Modal para guardar antes de salir -->
    <div
      v-if="guardarParaSalir"
      class="modal-overlay"
      @click="closeGuardarParaSalir"
    >
      <div class="modal-content" @click.stop>
        <h2>Vols guardar aquest projecte?</h2>
        <form @submit.prevent="guardarProyecto2">
          <div class="modal-actions">
            <button type="submit" class="modal-button">Guardar</button>
            <button
              type="button"
              class="modal-button cancel"
              @click="volverHome"
            >
              Cancel·lar
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Chat IA flotante -->
    <div
      v-if="isChatVisible"
      class="chat-container"
      :style="{
        transform: `translate(${chatPosition.x}px, ${chatPosition.y}px)`,
      }"
      @mousedown="startDrag"
    >
      <button class="close-chat-button" @click="toggleChat">✖</button>
      <h2 class="chat-title">IA FrontUp</h2>
      <div class="messages-container" ref="messagesContainer">
        <div
          v-for="(msg, index) in messages"
          :key="index"
          class="message"
          :class="{
            user: msg.type === 'user',
            ai: msg.type === 'ai',
            loading: msg.type === 'loading',
          }"
        >
          <div v-if="msg.type === 'loading'" class="loading-indicator">
            <div class="dot-flashing"></div>
          </div>
          <p v-else-if="msg.type === 'user'">{{ msg.content }}</p>
          <div v-else-if="msg.type === 'ai'">
            <template v-if="msg.content.includes('```')">
              <div class="code-block">
                <pre><code>{{ extractCode(msg.content) }}</code></pre>
                <button class="btn-copy" @click="copyCode(extractCode(msg.content))">
                  Copiar
                </button>
              </div>
            </template>
            <p v-else>{{ msg.content }}</p>
          </div>
        </div>
      </div>
      <div class="input-container">
        <input
          type="text"
          v-model="newMessage"
          placeholder="Escribe tu mensaje..."
          class="chat-input"
          @keyup.enter="sendMessage"
          :disabled="state.loading"
        />
        <button
          class="send-button"
          @click="sendMessage"
          :disabled="state.loading"
        >
          {{ state.loading ? "Enviant..." : "Enviar" }}
        </button>
      </div>
    </div>


    <div :class="['layout', 'layout-' + layoutType]">
      <!-- botones solo para móvil -->
      <div class="editor-tabs" v-if="isMobile">
        <button
          :class="{ active: activeTab === 'html' }"
          @click="activeTab = 'html'"
        >
          html
        </button>
        <button
          :class="{ active: activeTab === 'css' }"
          @click="activeTab = 'css'"
        >
          css
        </button>
        <button
          :class="{ active: activeTab === 'js' }"
          @click="activeTab = 'js'"
        >
          js
        </button>
      </div>
      <!-- Contenedor principal de editores -->
      <div class="editor-container">
        <div class="editor-box" v-show="!isMobile || activeTab === 'html'">
          <div class="editor-label">HTML</div>
          <div ref="htmlEditor" class="code-editor"></div>
        </div>
        <div class="editor-box" v-show="!isMobile || activeTab === 'css'">
          <div class="editor-label">CSS</div>
          <div ref="cssEditor" class="code-editor"></div>
        </div>
        <div class="editor-box" v-show="!isMobile || activeTab === 'js'">
          <div class="editor-label">JS</div>
          <div ref="jsEditor" class="code-editor"></div>
        </div>
      </div>

      <div class="output-container" :class="{ expanded: isExpanded }" ref="outputContainer">
        <button class="expand-button" @click="toggleExpand">
          <img
            v-if="!isExpanded"
            src="/assets/img/pantalla-grande.svg"
            alt="Maximitzar"
            width="30"
          />
          <img
            v-else
            src="/assets/img/pantalla-pequeña.svg"
            alt="Minimitzar"
            width="30"
          />
        </button>
        <div class="resize-bar" @mousedown="startResize"></div>
        <iframe class="output" :srcdoc="output"></iframe>
      </div>
    </div>
  </div>
</template>
<script setup>
import { ref, onMounted, computed, onUnmounted, nextTick, watch } from "vue";
import { useRouter, useRoute } from "vue-router";
import CodeMirror from "codemirror";
import { useLliureStore } from "~/stores/app";
import AlertComponent from '@/components/AlertComponent.vue';
import "codemirror/lib/codemirror.css";
import "codemirror/theme/dracula.css";

import "codemirror/mode/htmlmixed/htmlmixed";
import "codemirror/mode/css/css";
import "codemirror/mode/javascript/javascript";

import "codemirror/addon/hint/show-hint";
import "codemirror/addon/hint/show-hint.css";
import "codemirror/addon/hint/html-hint";
import "codemirror/addon/hint/anyword-hint";
import "codemirror/addon/edit/closetag";
import "codemirror/addon/edit/matchtags";
import "codemirror/addon/hint/javascript-hint";
import "codemirror/addon/edit/closebrackets";
import "codemirror/addon/display/placeholder";
import useCommunicationManager from "@/stores/comunicationManager";
import { useAppStore, useIdProyectoActualStore } from "@/stores/app";

const manager = useCommunicationManager();
const appStore = useAppStore();
const idProyectoActualStore = useIdProyectoActualStore();
const router = useRouter();
const route = useRoute();
const {
  guardarProyectoDB,
  chatIA,
  state,
  borrarProyectoDB,
  useProyectoStore,
} = useCommunicationManager();
const lliureStore = useLliureStore();

const alertVisible = ref(false);
const alertSuccess = ref(false);
const alertText = ref('');

const isDragging = ref(false);
const chatPosition = ref({ x: 20, y: 20 });
const dragStartPosition = ref({ x: 0, y: 0 });
const title = ref("Untitled");
const html = ref("");
const css = ref("");
const js = ref("");
const isEditing = ref(false);
const isExpanded = ref(false);
const isChatVisible = ref(false);
const newMessage = ref("");
const cambiadoSinGuardar = ref(false);
const messages = ref([{ type: "ai", content: "¡Hola! ¿En qué puedo ayudarte hoy?" }]);
const messagesContainer = ref(null);
const guardarParaSalir = ref(false);
const isPrivate = ref(0);
const layoutType = ref('normal');

const showShareModal = ref(false);
const shareCode = ref("");
const socket = ref(null);
const isCollaborating = ref(false);
const activeUsers = ref(1);
const activeUsersList = ref([]);

const isApplyingExternalChanges = ref({
  html: false,
  css: false,
  js: false
});

const htmlEditor = ref(null);
const cssEditor = ref(null);
const jsEditor = ref(null);

let htmlEditorInstance, cssEditorInstance, jsEditorInstance;

const showAlert = (message, isSuccess = false) => {
  alertText.value = message;
  alertSuccess.value = isSuccess;
  alertVisible.value = true;
};

const redirectToHome = (message) => {
  if (cambiadoSinGuardar.value) {
    try {
      guardarProyecto();
    } catch (error) {
      console.error("Error al guardar proyecto antes de redirigir:", error);
    }
  }

  isCollaborating.value = false;
  shareCode.value = "";
  activeUsersList.value = [];
  
  showAlert(message, false);
  
  setTimeout(() => {
    router.push("/");
  }, 2000);
};

const startDrag = (event) => {
  isDragging.value = true;
  dragStartPosition.value = {
    x: event.clientX - chatPosition.value.x,
    y: event.clientY - chatPosition.value.y,
  };
  document.addEventListener("mousemove", onDrag);
  document.addEventListener("mouseup", stopDrag);
};

const markDirty = () => {
  console.log("entrado en cambio a true", cambiadoSinGuardar.value);
  cambiadoSinGuardar.value = true;
};

const markClean = () => {
  cambiadoSinGuardar.value = false;
  console.log("entrado a cambio a false", cambiadoSinGuardar.value);
};

const onDrag = (event) => {
  if (isDragging.value) {
    chatPosition.value = {
      x: event.clientX - dragStartPosition.value.x,
      y: event.clientY - dragStartPosition.value.y,
    };
  }
};

const volverHome = async () => {
  console.log("css.value", css.value);
  router.push("/");
};

const stopDrag = () => {
  isDragging.value = false;
  document.removeEventListener("mousemove", onDrag);
  document.removeEventListener("mouseup", stopDrag);
};

const closeGuardarParaSalir = () => {
  guardarParaSalir.value = false;
};

const setLayout = (dir) => {
  layoutType.value = dir
}

const extractCode = (text) => {
  const match = text.match(/```(?:[\w-]+\n)?([\s\S]*?)```/);
  return match ? match[1].trim() : text;
};

const copyCode = async (code) => {
  try {
    await navigator.clipboard.writeText(code);
    showAlert("Código copiado al portapapeles", true);
  } catch {
    showAlert("Error al copiar el código", false);
  }
};

// Soporte para móviles
const isMobile = ref(false);
const activeTab = ref('html');

const checkMobile = () => {
  isMobile.value = window.innerWidth <= 450;
}
const handleShare = () => {
  if (isCollaborating.value) {
    showShareModal.value = true;
  } else {
    generateShareCode();
  }
};

const setupSocketListeners = () => {
  if (!socket.value) return;
  
  socket.value.on("room-deleted", ({ roomId }) => {
    if (roomId === shareCode.value) {
      if (isCollaborating.value) {
        const isHost = activeUsersList.value.some(user => 
          user.name === appStore.loginInfo.name && user.isHost
        );
        
        if (!isHost) {
          redirectToHome("El host ha tancat la sessió de col·laboració");
        } else {
          isCollaborating.value = false;
          shareCode.value = "";
          activeUsersList.value = [];
          closeShareModal();
        }
      }
    }
  });
  
  socket.value.on("host-status", ({ isHost }) => {
    if (isHost) {
      showAlert("Ara ets el host d'aquesta sessió", true);
    }
  });
};

const closeRoom = async () => {
  if (!isCollaborating.value || !shareCode.value) {
    showAlert("No estàs en una sessió de col·laboració", false);
    return;
  }
  
  try {
    const isHost = activeUsersList.value.some(user => 
      user.name === appStore.loginInfo.name && user.isHost
    );
    
    if (!isHost) {
      showAlert("Només el host pot tancar la room", false);
      return;
    }
    
    socket.value.emit("delete-room", { roomId: shareCode.value });
    
    socket.value.once("room-deleted", ({ roomId }) => {
      if (roomId === shareCode.value) {
        showAlert("Room tancada correctament", true);
        isCollaborating.value = false;
        shareCode.value = "";
        activeUsersList.value = [];
        closeShareModal();
      }
    });
  } catch (error) {
    console.error("Error al tancar la room:", error);
    showAlert("Error al tancar la room", false);
  }
};

onMounted(async () => {
  lliureStore.toggleLliure();
  
  // Verificar si es dispositivo móvil
  checkMobile();
  window.addEventListener('resize', checkMobile);
  
  htmlEditorInstance = CodeMirror(htmlEditor.value, {
    mode: "htmlmixed",
    theme: "dracula",
    lineNumbers: true,
    autoCloseTags: true,
    autoCloseBrackets: true,
    matchTags: { bothTags: true },
    extraKeys: {
      "Ctrl-Space": "autocomplete"
    },
  });
  htmlEditorInstance.on("inputRead", (editor, change) => {
    if (change.text[0] === "<") {
      editor.showHint({
        completeSingle: false
      });
    }
  });

  cssEditorInstance = CodeMirror(cssEditor.value, {
    mode: "css",
    theme: "dracula",
    lineNumbers: true,
    autoCloseBrackets: true,
    extraKeys: {
      "Ctrl-Space": "autocomplete",
    },
  });
  cssEditorInstance.on("inputRead", (editor, change) => {
    if (change.text[0].match(/[a-zA-Z\-]/)) {
      editor.showHint({ completeSingle: false });
    }
  });

  jsEditorInstance = CodeMirror(jsEditor.value, {
    mode: "javascript",
    theme: "dracula",
    lineNumbers: true,
    autoCloseBrackets: true,
    extraKeys: {
      "Ctrl-Space": "autocomplete"
    },
    placeholder: "// ctrl + espai per suggeriments d'etiquetes"
  });

  const projectId = route.params.id;
  if (projectId) {
    idProyectoActualStore.id = projectId;

    const proyectoStore = useProyectoStore();
    const proyecto = await proyectoStore.obtenerProyecto(projectId);
    if (proyecto) {
      html.value = proyecto.html_code || "";
      css.value = proyecto.css_code || "";
      js.value = proyecto.js_code || "";
      title.value = proyecto.nombre || "Untitled";
      htmlEditorInstance.setValue(html.value);
      cssEditorInstance.setValue(css.value);
      jsEditorInstance.setValue(js.value);
      isPrivate.value = proyecto.statuts || 0;
    }
  } else {
    console.error("No se encontró un ID de proyecto válido en la ruta.");
  }

  manager.initSocketConnection({
    socket,
    activeUsersList,
    html,
    css,
    js,
    isApplyingExternalChanges,
    htmlEditorInstance,
    cssEditorInstance,
    jsEditorInstance,
  });

  if (socket.value) {
    setupSocketListeners();
  }

  const collabCode = route.query.code;
  if (collabCode) {
    joinCollaborationSession(collabCode);
  }

  htmlEditorInstance.on("change", (instance) => {
    if (isApplyingExternalChanges.value.html) return;

    const newValue = instance.getValue();
    html.value = newValue;
    markDirty();

    if (isCollaborating.value) {
      socket.value.emit("html-change", {
        code: newValue,
        roomId: shareCode.value
      });
    }
  });

  cssEditorInstance.on("change", (instance) => {
    if (isApplyingExternalChanges.value.css) return;

    const newValue = instance.getValue();
    css.value = newValue;
    markDirty();

    if (isCollaborating.value) {
      socket.value.emit("css-change", {
        code: newValue,
        roomId: shareCode.value
      });
    }
  });

  jsEditorInstance.on("change", (instance) => {
    if (isApplyingExternalChanges.value.js) return;

    const newValue = instance.getValue();
    js.value = newValue;
    markDirty();

    if (isCollaborating.value) {
      socket.value.emit("js-change", {
        code: newValue,
        roomId: shareCode.value
      });
    }
  });
});

manager.initSocketConnection({
  socket,
  activeUsersList,
  html,
  css,
  js,
  isApplyingExternalChanges,
  htmlEditorInstance,
  cssEditorInstance,
  jsEditorInstance,
});

onUnmounted(() => {
  lliureStore.toggleLliure();
  idProyectoActualStore.vaciarId();

  // Eliminar el listener de resize para soporte móvil
  window.removeEventListener('resize', checkMobile);

  if (socket.value) {
    socket.value.disconnect();
  }
});

const generateShareCode = async () => {
  try {
    const response = await fetch('http://localhost:5000/generate-share-code', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        projectId: idProyectoActualStore.id,
        html: html.value,
        css: css.value,
        js: js.value,
        userName: appStore.loginInfo.name,
        avatar: appStore.loginInfo.avatar
      })
    });

    const data = await response.json();

    if (data.success) {
      shareCode.value = data.roomId;

      if (data.isNewRoom) {
        socket.value.emit("join-room", {
          roomId: data.roomId,
          projectId: idProyectoActualStore.id,
          userName: appStore.loginInfo.name,
          avatar: appStore.loginInfo.avatar
        });
        showAlert("Has començat a compartir el projecte", true);
      } else {
        socket.value.emit("join-room", {
          roomId: data.roomId,
          projectId: idProyectoActualStore.id,
          userName: appStore.loginInfo.name,
          avatar: appStore.loginInfo.avatar
        });
      }

      isCollaborating.value = true;
      showShareModal.value = true;
    } else {
      console.error('Error al generar el codi de compartir:', data.error);
      showAlert("Error al generar el codi de compartir", false);
    }
  } catch (error) {
    console.error('Error al generar el codi de compartir:', error);
    showAlert("Error al generar el codi de compartir", false);
  }
};

const closeShareModal = () => {
  showShareModal.value = false;
};

const copyShareCode = () => {
  navigator.clipboard.writeText(shareCode.value);
  showAlert("Codi copiat correctament al portapapers", true);
  closeShareModal();
};

const joinCollaborationSession = (code) => {
  if (!code) return;

  console.log("Intentant unir-se a la room:", code);
  shareCode.value = code;

  socket.value.emit("join-room", {
    roomId: code,
    projectId: idProyectoActualStore.id,
    userName: appStore.loginInfo.name,
    avatar: appStore.loginInfo.avatar
  });

  isCollaborating.value = true;
};

const guardarProyecto2 = () => {
  const prevGuardarParaSalir = guardarParaSalir.value;

  guardarProyecto();

  setTimeout(() => {
    if (prevGuardarParaSalir) {
      guardarParaSalir.value = false;
      if (!alertVisible.value || alertSuccess.value) {
        router.push("/");
      }
    }
  }, 500);
};

const toggleChat = () => {
  isChatVisible.value = !isChatVisible.value;
};

const sendMessage = async () => {
  if (!newMessage.value.trim() || state.loading) return;

  const userMessage = newMessage.value;
  newMessage.value = "";

  messages.value.push({ type: "user", content: userMessage });
  messages.value.push({ type: "loading", content: "" });

  try {
    const response = await chatIA(userMessage, html.value, css.value, js.value);
    messages.value.pop();
    messages.value.push({ type: "ai", content: response });
  } catch (error) {
    messages.value.pop();
    messages.value.push({
      type: "ai",
      content: "❌ Error al obtener respuesta. Intenta nuevamente.",
    });
  } finally {
    await nextTick();
    if (messagesContainer.value) {
      messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight;
    }
  }
};

const toggleExpand = () => {
  isExpanded.value = !isExpanded.value;
};

const goBack = async () => {
  if (!cambiadoSinGuardar.value) {
    if (html.value === "" && css.value === "" && js.value === "") {
      try {
        let id =
          idProyectoActualStore?.id ||
          Number(localStorage.getItem("idProyectoActual"));
        if (id) {
          await borrarProyectoDB(id);
        }
      } catch (error) {
        console.error("Error al borrar el proyecto:", error);
      }
    }
    router.push("/");
  } else {
    guardarParaSalir.value = true;
    console.log("¿Se muestra el guardar para salir?", guardarParaSalir.value);
  }
};

const savePrivacy = async () => {
  markDirty();

  try {
    await guardarProyecto();
    if (!alertVisible.value) {
    }
  } catch (error) {
    showAlert(`Error al canviar la privacitat del projecte`, false);
  }
};

const guardarProyecto = async () => {
  markClean();

  if (!idProyectoActualStore.id) {
    console.error("ID del proyecto es null o no se encuentra.");
    showAlert("Error: No s'ha trobat l'ID del projecte", false);
    return;
  }

  if (!localStorage.getItem('loginInfo')) {
    showAlert("Has d'iniciar sessió per guardar aquest projecte", false);
    return;
  }

  try {
    const response = await guardarProyectoDB(
      {
        nombre: title.value || "",
        user_id: appStore.loginInfo.id || null,
        html_code: html.value || "",
        css_code: css.value || "",
        js_code: js.value || "",
        statuts: isPrivate.value,
      },
      idProyectoActualStore.id
    );

    if (response.success === false) {
      showAlert("Cal ser el propietari del projecte per poder-lo guardar", false);
      console.log(response.message);
    } else {
      showAlert("Projecte guardat correctament", true);
    }
  } catch (error) {
    console.error("Error al guardar el proyecto:", error);
    showAlert("Error al guardar el projecte", false);
  }
};

let isResizing = false;
const outputContainer = ref(null);
const startResize = (event) => {
  isResizing = true;
  document.addEventListener('mousemove', handleResize);
  document.addEventListener('mouseup', stopResize);
};

const handleResize = (event) => {
  if (!isResizing || !outputContainer.value) return;
  const newHeight = window.innerHeight - event.clientY;
  outputContainer.value.style.height = `${newHeight}px`;
};

const stopResize = () => {
  isResizing = false;
  document.removeEventListener('mousemove', handleResize);
  document.removeEventListener('mouseup', stopResize);
};

const leaveCollaborationSession = () => {
  if (!isCollaborating.value) return;
  
  const isHost = activeUsersList.value.some(user => 
    user.name === appStore.loginInfo.name && user.isHost
  );
  
  if (isHost) {
    socket.value.emit("delete-room", { roomId: shareCode.value });
    showAlert("Has tancat la sessió de col·laboració", true);
  }
  
  if (socket.value) {
    socket.value.disconnect();
    socket.value = null;
  }

  isCollaborating.value = false;
  shareCode.value = "";
  activeUsers.value = 0;
  activeUsersList.value = [];

  manager.initSocketConnection({
    socket,
    activeUsersList,
    html,
    css,
    js,
    isApplyingExternalChanges,
    htmlEditorInstance,
    cssEditorInstance,
    jsEditorInstance,
  });
  
  if (socket.value) {
    setupSocketListeners();
  }
};

const output = computed(() => {
  let jsContent = js.value;
  let scriptContent = `
        try {
          ${jsContent}
        } catch (e) {
          console.error('Error in JavaScript:', e);
        }
      `;
  return `
        <html>
          <head><style>${css.value}</style></head>
          <body>${html.value}
            <script>${scriptContent}<\/script>
          </body>
        </html>`;
});
</script>
<style scoped>
.layout-buttons {
  display: flex;
  gap: 12px;
  margin-left: auto;
  margin-top: 20px;
  margin-right: 20px;
}


.top-bar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.5rem 1rem;
  gap: 1rem;
  flex-wrap: wrap;
}

.users-container {
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
  gap: 1rem;
}


.user-card {
  display: flex;
  flex-direction: column;
  align-items: center;
  background: #222;
  padding: 0.5rem;
  border-radius: 8px;
  color: white;
  width: 80px;
}

.avatar-img {
  width: 50px;
  height: 50px;
  object-fit: cover;
  border-radius: 9999px;
}

.user-info {
  margin-top: 0.5rem;
  text-align: center;
}


.todo {
  display: flex;
  flex-direction: column;
  background-color: #1e1e1e;
  font-family: "Arial", sans-serif;
  color: #ffffff;
  margin-left: -210px;
}

.header {
  left: 0;
  right: 0;
  height: 70px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 30px;
  background-color: #1f1f1f;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
  z-index: 100;
}

.header-row {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 16px;
}

.header-row:last-child {
  margin-bottom: 0;
}


.header-title {
  font-size: 18px;
  color: #ffffff;
  background-color: transparent;
  border: 2px solid transparent;
  padding: 6px 12px;
  border-radius: 6px;
  transition: all 0.3s ease;
  text-align: center;
  min-width: 400px;
}

.header-title:focus {
  border-color: #4caf50;
  background-color: #292929;
  outline: none;
}

.header-actions {
  display: flex;
  gap: 12px;
}

.header-button {
  background-color: #2e2e2e;
  border: 1px solid #444;
  color: #fff;
  padding: 8px 14px;
  border-radius: 6px;
  cursor: pointer;
  font-size: 14px;
  transition: background-color 0.3s ease;
  margin-right: 10px;
}

.header-button.sharing-active {
  background-color: #00796b;
  border-color: #00796b;
}

.button-position {
  background-color: #2e2e2e;
  border: 1px solid #444;
  color: #fff;
  padding: 10px 14px;
  border-radius: 6px;
  cursor: pointer;
  font-size: 14px;
  transition: background-color 0.3s ease;
}

.button-position svg {
  width: 20px;
  height: 20px;
}

.layout-buttons {
  display: flex;
  gap: 12px;
  align-self: flex-end;
  margin: 20px 20px 0 0; 
}

.header-button:hover {
  background-color: #3a3a3a;
}

.header-button.sharing-active:hover {
  background-color: #00695c;
}

.editor-container {
  display: flex;
  padding: 20px;
  gap: 20px;
}

.alert {
  z-index: 1002;
  position: fixed;
  font-size: larger;
  top: 20px;
  right: 20px;
  padding: 4vh 4vw;
  background: rgb(50, 226, 40);
  color: white;
  border-radius: 4px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  animation: movimiento 0.3s ease-out, opacidad 2s ease-in-out forwards;
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

.editor-box {
  flex: 1;
  position: relative;
  display: flex;
  flex-direction: column;
  padding: 15px;
  border-radius: 6px;
  box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.2);
  background-color: #2d2d2d;
  overflow: hidden;
}

.editor-label {
  position: absolute;
  top: 8px;
  left: 10px;
  font-size: 16px;
  color: #fff;
  background-color: #444;
  padding: 4px 8px;
  border-radius: 3px;
}

.code-editor {
  margin-top: 40px;
  width: 100%;
  height: 300px;
  border: 1px solid #444;
  border-radius: 4px;
  background-color: #1e1e1e;
  color: #fff;
  overflow-y: auto;
}

.output-container {
  position: relative;
  transition: all 0.3s ease-in-out;
  background-color: white;
  height: 100vh;
  overflow: hidden;
  margin-left: 10px;
}

.output-container.expanded {
  position: fixed;
  width: 100vw;
  height: 100vh;
}

.output-container.expanded .output {
  border-radius: 0;
}

.expand-button {
  position: absolute;
  top: 10px;
  right: 10px;
  border: none;
  padding: 8px 12px;
  border-radius: 4px;
  cursor: pointer;
  opacity: 0.5;
  background-color: white;
}

.expand-button:hover {
  opacity: 1;
  background-color: white;
}

.output {
  width: 100%;
  height: 100%;
  border: none;
  background-color: #ffffff;
}

.modal-overlay {
  position: fixed;
  width: 100vw;
  height: 100vh;
  background: rgba(0, 0, 0, 0.7);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-content {
  background-color: #2d2d2d;
  padding: 30px;
  border-radius: 10px;
  width: 500px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
  color: #fff;
  position: relative;
}

.modal-input,
.modal-textarea {
  width: 100%;
  padding: 12px;
  background-color: #444;
  color: #fff;
  border-radius: 5px;
  border: none;
  margin-top: 10px;
  box-sizing: border-box;
}

.modal-actions {
  display: flex;
  justify-content: space-between;
  margin-top: 20px;
}

.modal-actions-single {
  display: flex;
  justify-content: center;
  margin-top: 20px;
}

.modal-button {
  background-color: #555;
  border: none;
  color: #fff;
  padding: 10px 15px;
  border-radius: 5px;
  cursor: pointer;
}

.modal-button:hover {
  background-color: #777;
}

.cancel {
  background-color: #888;
  margin-left: 10px;
}

.cancel:hover {
  background-color: #aaa;
}

.close-modal-button {
  position: absolute;
  top: 10px;
  right: 10px;
  background: none;
  border: none;
  color: #fff;
  font-size: 16px;
  cursor: pointer;
}

.close-modal-button:hover {
  color: #ff4444;
}

.share-code-container {
  display: flex;
  align-items: center;
  background-color: #1e1e1e;
  padding: 12px;
  border-radius: 6px;
  margin: 15px 0;
}

.share-code {
  font-size: 1.5rem;
  font-weight: bold;
  margin: 0;
  flex: 1;
  text-align: center;
}

.copy-button {
  background-color: #00796b;
  color: white;
}

.copy-button:hover {
  background-color: #004d40;
}

.cancel-room {
  background-color: #d32f2f;
  padding: 12px 24px;
  font-size: 16px;
  min-width: 200px;
}

.cancel-room:hover {
  background-color: #b71c1c;
}

.share-instructions {
  margin: 15px 0;
  line-height: 1.5;
  color: #bbb;
}

.chat-container {
  position: fixed;
  bottom: 20px;
  right: 20px;
  width: 300px;
  height: 400px;
  background-color: #2d2d2d;
  border-radius: 10px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
  display: flex;
  flex-direction: column;
  padding: 15px;
  z-index: 1001;
  color: #fff;
  cursor: grab;
}

.chat-container:active {
  cursor: grabbing;
}

.chat-title {
  font-size: 1.2rem;
  margin-bottom: 15px;
  text-align: center;
}

.messages-container {
  flex-grow: 1;
  overflow-y: auto;
  margin-bottom: 15px;
  padding: 10px;
  background-color: #1e1e1e;
  border-radius: 8px;
}

.message {
  padding: 8px 12px;
  margin: 8px 0;
  border-radius: 12px;
  max-width: 80%;
}

.message.ai {
  background-color: #444;
  align-self: flex-start;
}

.message.user {
  background-color: #00796b;
  align-self: flex-end;
}

.input-container {
  display: flex;
  gap: 10px;
}

.chat-input {
  flex: 1;
  padding: 10px;
  border-radius: 8px;
  border: 1px solid #444;
  background-color: #1e1e1e;
  color: #fff;
}

.send-button {
  padding: 10px 20px;
  background-color: #00796b;
  border: none;
  border-radius: 8px;
  color: #fff;
  cursor: pointer;
}

.send-button:hover {
  background-color: #004d40;
}

.close-chat-button {
  position: absolute;
  top: 10px;
  right: 10px;
  background: none;
  border: none;
  color: #fff;
  cursor: pointer;
  padding: 5px;
}

.close-chat-button:hover {
  color: #ff4444;
}

.dot-flashing {
  position: relative;
  width: 10px;
  height: 10px;
  border-radius: 5px;
  background-color: #9880ff;
  color: #9880ff;
  animation: dotFlashing 1s infinite linear alternate;
  animation-delay: 0.5s;
}

.dot-flashing::before,
.dot-flashing::after {
  content: "";
  display: inline-block;
  position: absolute;
  top: 0;
}

.dot-flashing::before {
  left: -15px;
  width: 10px;
  height: 10px;
  border-radius: 5px;
  background-color: #9880ff;
  color: #9880ff;
  animation: dotFlashing 1s infinite alternate;
  animation-delay: 0s;
}

.dot-flashing::after {
  left: 15px;
  width: 10px;
  height: 10px;
  border-radius: 5px;
  background-color: #9880ff;
  color: #9880ff;
  animation: dotFlashing 1s infinite alternate;
  animation-delay: 1s;
}

@keyframes dotFlashing {
  0% {
    background-color: #9880ff;
  }

  50%,
  100% {
    background-color: #ebe6ff;
  }
}

.loading-indicator {
  padding: 10px;
  color: #666;
}

.message.loading {
  background-color: transparent;
  animation: pulse 1.5s infinite;
}

@keyframes pulse {
  0% {
    opacity: 0.6;
  }

  50% {
    opacity: 1;
  }

  100% {
    opacity: 0.6;
  }
}

::-webkit-scrollbar {
  width: 6px;
  height: 6px;
}

::-webkit-scrollbar-track {
  background: transparent;
}

::-webkit-scrollbar-thumb {
  background-color: rgba(255, 255, 255, 0.1);
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background-color: rgba(255, 255, 255, 0.2);
}

* {
  scrollbar-width: thin;
  scrollbar-color: rgba(255, 255, 255, 0.1) transparent;
}

:deep(.CodeMirror-scrollbar-filler),
:deep(.CodeMirror-gutter-filler) {
  background-color: transparent;
}

.header-select {
  background-color: #2e2e2e;
  color: #fff;
  border: 1px solid #444;
  padding: 8px;
  border-radius: 6px;
  font-size: 14px;
}

.layout {
  display: flex;
  width: 100%;
  height: 100%;
  transition: all 0.3s ease;
}

.layout-normal {
  flex-direction: column;
}

.layout-left {
  flex-direction: row;
}

.layout-right {
  flex-direction: row-reverse;
}

.layout-left .editor-container,
.layout-right .editor-container {
  display: flex;
  flex-direction: column;
  width: 45vw;
  max-width: 50vw;
  max-height: 90vh;
  overflow-y: auto;
}

.layout-left .output-container,
.layout-right .output-container {
  flex: 1;
}

.code-block {
  position: relative;
  background: #2d2d2d;
  padding: 1rem;
  border-radius: 4px;
  margin-bottom: 0.5rem;
}

.code-block pre {
  margin: 0;
  overflow-x: auto;
}

.btn-copy {
  position: absolute;
  top: 0.5rem;
  right: 0.5rem;
  background: #4caf50;
  color: white;
  border: none;
  padding: 0.25rem 0.5rem;
  border-radius: 3px;
  cursor: pointer;
  font-size: 0.75rem;
}

.btn-copy:hover {
  background: #45a049;
}

@media (max-width: 450px) {
  .todo {
    display: flex;
    flex-direction: column;
    background-color: #1e1e1e;
    font-family: "Arial", sans-serif;
    color: #ffffff;
    margin: 0;
    padding: 0 10px;
    box-sizing: border-box;
    width: 100vw;
    overflow-x: hidden;
  }

  .layout {
    flex-direction: column;
    width: 100%;
    overflow-x: hidden;
  }

  .editor-container {
    display: flex;
    flex-direction: column;
    width: 100%;
    padding: 10px 0;
    gap: 10px;
  }

  .editor-box {
  width: 100%;
  padding: 10px;
  box-sizing: border-box;
  margin-top: 0; /* asegúrate que no tenga espacio arriba */
}


  .code-editor {
    height: 200px;
    font-size: 14px;
  }

  .output-container {
    width: 100%;
    height: 300px;
    margin: 0;
    border-radius: 6px;
    border: 1px solid #333;
    overflow: auto;
  }

  .expand-button {
    top: 6px;
    right: 6px;
    padding: 6px 10px;
  }

  .layout-buttons,
  .button-position {
    display: none;
  }

  .header {
  display: flex;
  flex-direction: column;
  padding: 10px;
  gap: 10px;
}

.header-row {
  display: flex;
  justify-content: space-between;
  gap: 8px;
  flex-wrap: nowrap;
  width: 100%;
  overflow-x: hidden;
margin-bottom: 6px; 
}

.header-button {
  padding: 6px 8px;
  font-size: 12px;
  flex: 1 1 auto;
  min-width: 0;
  white-space: nowrap;
}

.header-title {
  flex: 2 1 auto;
  font-size: 12px;
  padding: 6px 8px;
  min-width: 0;
}
.header {
  display: flex;
  flex-direction: column;
  padding: 10px;
  gap: 10px;
}

.header-row {
  display: flex;
  justify-content: space-between;
  gap: 8px;
  flex-wrap: nowrap;
  width: 100%;
  overflow-x: hidden;
}

.header-button {
  padding: 6px 8px;
  font-size: 12px;
  flex: 1 1 auto;
  min-width: 0;
  white-space: nowrap;
}

.header-title {
  flex: 2 1 auto;
  font-size: 12px;
  padding: 6px 8px;
  min-width: 0;
}
  .editor-tabs {
  flex-wrap: wrap;
  justify-content: center;
  gap: 6px;
  margin-bottom: 0; /* antes era 10px */
}


   .editor-tabs button {
  padding: 6px 12px;
  font-size: 12px;
  border: none;
  background-color: #444;
  color: #fff;
  cursor: pointer;
  border-radius: 5px;
  transition: background-color 0.3s;
}


  iframe.output {
    width: 100%;
    height: 100%;
  }
}



</style>

