<template>
  <div class="todo">
    <header class="header">
      <button class="header-button" @click="goBack">Home</button>
      <input type="text" v-model="title" class="header-title" @focus="isEditing = true" @blur="isEditing = false"
        :readonly="!isEditing" />
      <div class="header-actions">
        <button class="header-button" @click="toggleChat">Xat IA</button>
        <button class="header-button" @click="guardarProyecto">Guardar</button>
        <button class="header-button" @click="openSettingsModal">Configuració</button>
        <button class="header-button">💡</button>
      </div>
    </header>

    <div v-if="showSettingsModal" class="modal-overlay" @click="closeSettingsModal">
      <div class="modal-content" @click.stop>
        <h2>Configuració del Projecte</h2>
        <form @submit.prevent="saveSettings">
          <div class="input-group">
            <label for="project-title">Títol del Projecte</label>
            <input type="text" id="project-title" v-model="modalTitle" class="modal-input"
              placeholder="Escriu el títol" />
          </div>
          <div class="input-group">
            <label for="project-description">Descripció</label>
            <textarea id="project-description" v-model="modalDescription" class="modal-textarea"
              placeholder="Escriu la descripció del projecte"></textarea>
          </div>
          <div class="modal-actions">
            <button type="submit" class="modal-button">Guardar</button>
            <button type="button" class="modal-button cancel" @click="closeSettingsModal">Cancel·lar</button>
          </div>
        </form>
      </div>
    </div>
    <div v-if="guardarParaSalir" class="modal-overlay" @click="closeGuardarParaSalir">
      <div class="modal-content" @click.stop>
        <h2>Vols guardar aquest projecte?</h2>
        <form @submit.prevent="guardarProyecto2">
          <div class="modal-actions">
            <button type="submit" class="modal-button">Guardar</button>
            <button type="button" class="modal-button cancel" @click="volverHome">Cancel·lar</button>
          </div>
        </form>
      </div>
    </div>
    <!-- Chat IA flotante -->
    <div v-if="isChatVisible" class="chat-container"
      :style="{ transform: `translate(${chatPosition.x}px, ${chatPosition.y}px)` }" @mousedown="startDrag">
      <button class="close-chat-button" @click="toggleChat">✖</button>
      <h2 class="chat-title">IA FrontUp</h2>
      <div class="messages-container" ref="messagesContainer">
        <div v-for="(msg, index) in messages" :key="index" class="message" :class="{
          'user': msg.type === 'user',
          'ai': msg.type === 'ai',
          'loading': msg.type === 'loading'
        }">
          <div v-if="msg.type === 'loading'" class="loading-indicator">
            <div class="dot-flashing"></div>
          </div>
          <p v-else>{{ msg.content }}</p>
        </div>
      </div>
      <div class="input-container">
        <input type="text" v-model="newMessage" placeholder="Escriu el teu missatge ..." class="chat-input"
          @keyup.enter="sendMessage" :disabled="state.loading" />
        <button class="send-button" @click="sendMessage" :disabled="state.loading">
          {{ state.loading ? 'Enviant...' : 'Enviar' }}
        </button>
      </div>
    </div>

    <!-- Contenedor principal -->
    <div class="editor-container">
      <div class="editor-box">
        <div class="editor-label">HTML</div>
        <div ref="htmlEditor" class="code-editor"></div>
      </div>
      <div class="editor-box">
        <div class="editor-label">CSS</div>
        <div ref="cssEditor" class="code-editor"></div>
      </div>
      <div class="editor-box">
        <div class="editor-label">JS</div>
        <div ref="jsEditor" class="code-editor"></div>
      </div>
    </div>

    <div class="output-container" :class="{ expanded: isExpanded }" ref="outputContainer">
      <button class="expand-button" @click="toggleExpand">
        <img v-if="!isExpanded" src="/assets/img/pantalla-grande.svg" alt="Maximitzar" width="30" />
        <img v-if="isExpanded" src="/assets/img/pantalla-pequeña.svg" alt="Minimitzar" width="30" />
      </button>
      <div class="resize-bar" @mousedown="startResize"></div>
      <iframe class="output" :srcdoc="output"></iframe>
    </div>
  </div>
</template>
<script>
import { ref, onMounted, computed, onUnmounted, nextTick } from "vue";
import { useRouter } from "vue-router";
import CodeMirror from "codemirror";
import { useLliureStore } from "~/stores/app";
import "codemirror/lib/codemirror.css";
import "codemirror/theme/dracula.css";
import "codemirror/mode/htmlmixed/htmlmixed";
import "codemirror/mode/css/css";
import "codemirror/mode/javascript/javascript";
import useCommunicationManager from "@/stores/comunicationManager";
import { useAppStore } from '@/stores/app';
import { useIdProyectoActualStore } from '@/stores/app'

export default {
  setup() {
    const appStore = useAppStore();
    const idProyectoActualStore = useIdProyectoActualStore();
    const router = useRouter();
    const { guardarProyectoDB, chatIA, state, borrarProyectoDB } = useCommunicationManager();
    const lliureStore = useLliureStore();
    const isDragging = ref(false);
    const chatPosition = ref({ x: 20, y: 20 });
    const dragStartPosition = ref({ x: 0, y: 0 });
    const title = ref("Untitled");
    const html = ref("");
    const css = ref("");
    const js = ref("");
    const isEditing = ref(false);
    const showSettingsModal = ref(false);
    const modalTitle = ref("");
    const modalDescription = ref("");
    const isExpanded = ref(false);
    const isChatVisible = ref(false);
    const newMessage = ref("");
    const cambiadoSinGuardar = ref(false);
    const messages = ref([
      { type: 'ai', content: "¡Hola! ¿En qué puedo ayudarte hoy?" }
    ]);
    const messagesContainer = ref(null);
    const guardarParaSalir = ref(false);

    const htmlEditor = ref(null);
    const cssEditor = ref(null);
    const jsEditor = ref(null);

    let htmlEditorInstance, cssEditorInstance, jsEditorInstance;
    const startDrag = (event) => {
      isDragging.value = true;
      dragStartPosition.value = {
        x: event.clientX - chatPosition.value.x,
        y: event.clientY - chatPosition.value.y,
      };
      document.addEventListener("mousemove", onDrag);
      document.addEventListener("mouseup", stopDrag);
    };
    const CambiosSinGuardarToTrue = () => {
      console.log('entrado en cambio a true', cambiadoSinGuardar.value);

      cambiadoSinGuardar.value = true
    }
    const CambiosSinGuardarToFalse = () => {

      cambiadoSinGuardar.value = false
      console.log('entrado a cambio a false', cambiadoSinGuardar.value);
    }
    const onDrag = (event) => {
      if (isDragging.value) {
        chatPosition.value = {
          x: event.clientX - dragStartPosition.value.x,
          y: event.clientY - dragStartPosition.value.y,
        };
      }
    };
    const volverHome = async () => {
      console.log('css.value', css.value);
      router.push("/");
    }
    const stopDrag = () => {
      isDragging.value = false;
      document.removeEventListener("mousemove", onDrag);
      document.removeEventListener("mouseup", stopDrag);
    };
    const closeGuardarParaSalir = () => {
      guardarParaSalir.value = false;
    }
    onMounted(() => {
      lliureStore.toggleLliure();

      // Configurar editores
      htmlEditorInstance = CodeMirror(htmlEditor.value, {
        mode: "htmlmixed",
        theme: "dracula",
        lineNumbers: true,
      });
      cssEditorInstance = CodeMirror(cssEditor.value, {
        mode: "css",
        theme: "dracula",
        lineNumbers: true,
      });

      jsEditorInstance = CodeMirror(jsEditor.value, {
        mode: "javascript",
        theme: "dracula",
        lineNumbers: true,
      });

      htmlEditorInstance.on("change", (instance) => {
        html.value = instance.getValue();
        CambiosSinGuardarToTrue();
      });

      cssEditorInstance.on("change", (instance) => {
        css.value = instance.getValue();
        CambiosSinGuardarToTrue();
      });

      jsEditorInstance.on("change", (instance) => {
        js.value = instance.getValue();
        CambiosSinGuardarToTrue();
      });
    });

    onUnmounted(() => {
      lliureStore.toggleLliure();
      idProyectoActualStore.vaciarId();
    });

    const guardarProyecto2 = () => {
      guardarProyecto();
      guardarParaSalir.value = false;
      router.push('/');
    }
    const toggleChat = () => {
      isChatVisible.value = !isChatVisible.value;
    };

    const sendMessage = async () => {
      if (!newMessage.value.trim() || state.loading) return;

      const userMessage = newMessage.value;
      newMessage.value = "";

      messages.value.push({
        type: 'user',
        content: userMessage
      });

      messages.value.push({
        type: 'loading',
        content: ''
      });

      try {
        const response = await chatIA(userMessage, html.value, css.value, js.value);
        messages.value.pop();
        messages.value.push({
          type: 'ai',
          content: response
        });
      } catch (error) {
        messages.value.pop();
        messages.value.push({
          type: 'ai',
          content: "❌ Error al obtener respuesta. Intenta nuevamente."
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
            let id = idProyectoActualStore?.id || Number(localStorage.getItem("idProyectoActual"));

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
        console.log('¿Se muestra el guardar para salir?', guardarParaSalir.value);
      }
    };

    const openSettingsModal = () => {
      showSettingsModal.value = true;
      modalTitle.value = title.value;
    };

    const closeSettingsModal = () => {
      showSettingsModal.value = false;
    };

    const saveSettings = () => {
      title.value = modalTitle.value;
      closeSettingsModal();
    };

    const guardarProyecto = async () => {
      CambiosSinGuardarToFalse();
      try {
        await guardarProyectoDB({
          nombre: title.value || "",
          user_id: appStore.loginInfo.id || null,
          html_code: html.value || "",
          css_code: css.value || "",
          js_code: js.value || "",
        }, idProyectoActualStore.id);
      } catch (error) {
        console.error(error);
      }
    };

    return {
      title,
      html,
      css,
      js,
      htmlEditor,
      cssEditor,
      jsEditor,
      isEditing,
      showSettingsModal,
      modalTitle,
      modalDescription,
      isExpanded,
      isChatVisible,
      newMessage,
      messages,
      messagesContainer,
      state,
      guardarParaSalir,
      guardarProyecto2,
      volverHome,
      toggleChat,
      sendMessage,
      toggleExpand,
      goBack,
      openSettingsModal,
      closeSettingsModal,
      closeGuardarParaSalir,
      saveSettings,
      guardarProyecto,
      isDragging,
      chatPosition,
      startDrag,
      onDrag,
      stopDrag,
      CambiosSinGuardarToTrue,
      output: computed(() => {
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
      }),
    };
  },
};
</script>
<style scoped>
.todo {
  display: flex;
  flex-direction: column;
  background-color: #1e1e1e;
  font-family: 'Arial', sans-serif;
  color: #ffffff;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px 20px;
  background-color: #2d2d2d;
  color: #fff;
}

.header-title {
  font-size: 15px;
  color: #fff;
  background-color: #444;
  border: none;
  padding: 8px;
  border-radius: 4px;
  text-align: center;
}

.header-actions {
  display: flex;
  gap: 10px;
}

.header-button {
  background-color: #555;
  border: none;
  color: #fff;
  padding: 8px 12px;
  border-radius: 4px;
  cursor: pointer;
  font-size: 14px;
}

.header-button:hover {
  background-color: #777;
}

.editor-container {
  display: flex;
  padding: 20px;
  gap: 20px;
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
  width: 29vw;
  height: 300px;
  border: 1px solid #444;
  border-radius: 4px;
  background-color: #1e1e1e;
  color: #fff;
}


.output-container {
  position: relative;
  transition: all 0.3s ease-in-out;
  background-color: white;
  height: 100vh;
  overflow: hidden;
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
  justify-content: flex-end;
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
  animation-delay: .5s;
}

.dot-flashing::before,
.dot-flashing::after {
  content: '';
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
</style>