<template>
  <div class="todo">
    <header class="header" v-show="!isExpanded">
      <button class="header-button" @click="goBack">Atrás</button>
      <input type="text" v-model="title" class="header-title" @focus="isEditing = true" @blur="isEditing = false"
        :readonly="!isEditing" />
      <div class="header-actions">
        <button class="header-button" @click="toggleChat">Xat IA</button>
        <button class="header-button" @click="guardarProyecto">Guardar</button>
        <button class="header-button" @click="openSettingsModal">Configuració</button>
        <button class="header-button" @click="generateShareCode">Compartir</button>
        <button class="header-button">💡</button>
      </div>
    </header>

    <!-- Indicador de colaboración activa -->
    <div v-if="isCollaborating" class="collaboration-active">
      <span class="collaboration-indicator"></span>
      <span>Collaboración activa: {{ activeUsers }} usuarios</span>
    </div>

    <!-- Modal de configuración -->
    <div v-if="showSettingsModal" class="modal-overlay" @click="closeSettingsModal">
      <div class="modal-content" @click.stop>
        <h2>Configuració del Projecte</h2>
        <form @submit.prevent="saveSettings">
          <div class="input-group">
            <label for="project-title">Título del Proyecto</label>
            <input type="text" id="project-title" v-model="modalTitle" class="modal-input"
              placeholder="Escribe el título" />
          </div>
          <div class="input-group">
            <label for="project-description">Descripción</label>
            <textarea id="project-description" v-model="modalDescription" class="modal-textarea"
              placeholder="Escribe la descripción del proyecto"></textarea>
          </div>
          <!-- Selector de privacidad -->
          <div class="input-group">
            <label for="project-privacy">Privacidad</label>
            <select v-model="isPrivate" id="project-privacy" class="modal-input">
              <option value="0">Público</option>
              <option value="1">Privado</option>
            </select>
          </div>
          <div class="modal-actions">
            <button type="submit" class="modal-button">Guardar</button>
            <button type="button" class="modal-button cancel" @click="closeSettingsModal">Cancel·lar</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Modal para compartir código de colaboración -->
    <div v-if="showShareModal" class="modal-overlay" @click="closeShareModal">
      <div class="modal-content" @click.stop>
        <h2>Codi de col·laboració</h2>
        <div class="share-code-container">
          <p class="share-code">{{ shareCode }}</p>
          <button class="modal-button copy-button" @click="copyShareCode">Copiar</button>
        </div>
        <p class="share-instructions">Comparteix aquest codi amb altres usuaris perquè puguin unir-se i editar aquest projecte en temps real.</p>
        <div class="modal-actions">
          <button type="button" class="modal-button" @click="closeShareModal">Tancar</button>
        </div>
      </div>
    </div>

    <!-- Modal para guardar antes de salir -->
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
          user: msg.type === 'user',
          ai: msg.type === 'ai',
          loading: msg.type === 'loading'
        }">
          <div v-if="msg.type === 'loading'" class="loading-indicator">
            <div class="dot-flashing"></div>
          </div>
          <p v-else>{{ msg.content }}</p>
        </div>
      </div>
      <div class="input-container">
        <input type="text" v-model="newMessage" placeholder="Escribe tu mensaje..." class="chat-input"
          @keyup.enter="sendMessage" :disabled="state.loading" />
        <button class="send-button" @click="sendMessage" :disabled="state.loading">
          {{ state.loading ? 'Enviant...' : 'Enviar' }}
        </button>
      </div>
    </div>

    <!-- Contenedor principal de editores -->
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
import { ref, onMounted, computed, onUnmounted, nextTick, watch } from "vue";
import { useRouter, useRoute } from "vue-router";
import CodeMirror from "codemirror";
import { useLliureStore } from "~/stores/app";
import "codemirror/lib/codemirror.css";
import "codemirror/theme/dracula.css";
import { io } from "socket.io-client";

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

import useCommunicationManager from "@/stores/comunicationManager";
import { useAppStore, useIdProyectoActualStore } from "@/stores/app";

export default {
  setup() {
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
    const messages = ref([{ type: "ai", content: "¡Hola! ¿En qué puedo ayudarte hoy?" }]);
    const messagesContainer = ref(null);
    const guardarParaSalir = ref(false);
    const isPrivate = ref(0);
    const description = ref("");

    // Nuevas variables para la colaboración en tiempo real
    const showShareModal = ref(false);
    const shareCode = ref("");
    const socket = ref(null);
    const isCollaborating = ref(false);
    const activeUsers = ref(1);

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
      console.log("entrado en cambio a true", cambiadoSinGuardar.value);
      cambiadoSinGuardar.value = true;
    };

    const CambiosSinGuardarToFalse = () => {
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

    onMounted(async () => {
      lliureStore.toggleLliure();
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
        }
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
          description.value = proyecto.descripcion || "";
          htmlEditorInstance.setValue(html.value);
          cssEditorInstance.setValue(css.value);
          jsEditorInstance.setValue(js.value);
          isPrivate.value = proyecto.statuts || 0;
        }
      } else {
        console.error("No se encontró un ID de proyecto válido en la ruta.");
      }

      // Inicializar conexión de socket
      initSocketConnection();

      // Verificar si hay un código de colaboración en la URL
      const collabCode = route.query.code;
      if (collabCode) {
        joinCollaborationSession(collabCode);
      }

      // Modificar los event listeners para incluir la emisión de cambios por socket
      htmlEditorInstance.on("change", (instance) => {
        const newValue = instance.getValue();
        html.value = newValue;
        CambiosSinGuardarToTrue();
        
        if (isCollaborating.value) {
          socket.value.emit("html-change", {
            code: newValue,
            roomId: shareCode.value
          });
        }
      });
      
      cssEditorInstance.on("change", (instance) => {
        const newValue = instance.getValue();
        css.value = newValue;
        CambiosSinGuardarToTrue();
        
        if (isCollaborating.value) {
          socket.value.emit("css-change", {
            code: newValue,
            roomId: shareCode.value
          });
        }
      });
      
      jsEditorInstance.on("change", (instance) => {
        const newValue = instance.getValue();
        js.value = newValue;
        CambiosSinGuardarToTrue();
        
        if (isCollaborating.value) {
          socket.value.emit("js-change", {
            code: newValue,
            roomId: shareCode.value
          });
        }
      });
    });

    const initSocketConnection = () => {
      socket.value = io("http://localhost:5000");
      
      socket.value.on("connect", () => {
        console.log("Conectado al servidor de socket");
      });
      
      socket.value.on("html-change", (newValue) => {
        if (newValue !== html.value) {
          html.value = newValue;
          htmlEditorInstance.setValue(newValue);
        }
      });
      
      socket.value.on("css-change", (newValue) => {
        if (newValue !== css.value) {
          css.value = newValue;
          cssEditorInstance.setValue(newValue);
        }
      });
      
      socket.value.on("js-change", (newValue) => {
        if (newValue !== js.value) {
          js.value = newValue;
          jsEditorInstance.setValue(newValue);
        }
      });
      
      socket.value.on("initial-state", ({ html: htmlCode, css: cssCode, js: jsCode }) => {
        html.value = htmlCode;
        css.value = cssCode;
        js.value = jsCode;
        
        htmlEditorInstance.setValue(htmlCode);
        cssEditorInstance.setValue(cssCode);
        jsEditorInstance.setValue(jsCode);
      });
      
      socket.value.on("user-joined", () => {
        activeUsers.value++;
      });
      
      socket.value.on("user-left", () => {
        activeUsers.value = Math.max(1, activeUsers.value - 1);
      });
      
      socket.value.on("room-users", ({ count }) => {
        activeUsers.value = count;
      });
    };

    onUnmounted(() => {
      lliureStore.toggleLliure();
      idProyectoActualStore.vaciarId();
      
      if (socket.value) {
        socket.value.disconnect();
      }
    });

    const generateShareCode = () => {
      // Generar un código aleatorio de 6 caracteres alfanuméricos
      const randomCode = Math.random().toString(36).substring(2, 8).toUpperCase();
      shareCode.value = randomCode;
      
      // Crear la room en el servidor
      socket.value.emit("create-room", {
        roomId: randomCode,
        projectId: idProyectoActualStore.id,
        initialData: {
          html: html.value,
          css: css.value,
          js: js.value
        }
      });
      
      isCollaborating.value = true;
      showShareModal.value = true;
    };

    const closeShareModal = () => {
      showShareModal.value = false;
    };

    const copyShareCode = () => {
      navigator.clipboard.writeText(shareCode.value);
      alert("Codi copiat al portapapers");
    };

    const joinCollaborationSession = (code) => {
      shareCode.value = code;
      
      socket.value.emit("join-room", {
        roomId: code,
        projectId: idProyectoActualStore.id
      });
      
      isCollaborating.value = true;
    };

    const guardarProyecto2 = () => {
      guardarProyecto();
      guardarParaSalir.value = false;
      router.push("/");
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

    const openSettingsModal = () => {
      showSettingsModal.value = true;
      modalTitle.value = title.value;
      modalDescription.value = description.value;
    };

    const closeSettingsModal = () => {
      showSettingsModal.value = false;
    };

    const saveSettings = async () => {
      title.value = modalTitle.value;
      description.value = modalDescription.value;
      CambiosSinGuardarToTrue();
      await guardarProyecto();
      closeSettingsModal();
    };

    const guardarProyecto = async () => {
      CambiosSinGuardarToFalse();

      if (!idProyectoActualStore.id) {
        console.error("ID del proyecto es null o no se encuentra.");
        return;
      }

      try {
        const response = await guardarProyectoDB(
          {
            nombre: title.value || "",
            descripcion: description.value || "",
            user_id: appStore.loginInfo.id || null,
            html_code: html.value || "",
            css_code: css.value || "",
            js_code: js.value || "",
            statuts: isPrivate.value,
          },
          idProyectoActualStore.id
        );
        if(response.success == false) {
          console.log(response.message);
        }
      } catch (error) {
        console.error("Error al guardar el proyecto:", error);
      }
    };

    // Agregar escuchadores para eventos de resize (para el editor y output)
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
      isPrivate,
      description,
      showShareModal,
      shareCode,
      generateShareCode,
      closeShareModal,
      copyShareCode,
      joinCollaborationSession,
      isCollaborating,
      activeUsers,
      outputContainer,
      startResize,
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

.header-title {
  font-size: 18px;
  color: #ffffff;
  background-color: transparent;
  border: 2px solid transparent;
  padding: 6px 12px;
  border-radius: 6px;
  transition: all 0.3s ease;
  text-align: center;
  min-width: 180px;
}

.header-title:focus {
  border-color: #4CAF50;
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
}

.header-button:hover {
  background-color: #3a3a3a;
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
</style>