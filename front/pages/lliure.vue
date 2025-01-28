<template>
  <div class="todo">
    <!-- Encabezado -->
    <header class="header">
      <button class="header-button" @click="goBack">Atrás</button>
      <input type="text" v-model="title" class="header-title" @focus="isEditing = true" @blur="isEditing = false"
        :readonly="!isEditing" />
      <div class="header-actions">
        <button class="header-button" @click="guardarProyecto">Guardar</button>
        <button class="header-button" @click="openSettingsModal">Configuración</button>
        <button class="header-button">💡</button>
      </div>
    </header>

    <!-- Notificación -->
    <div v-if="notification" class="notification">
      <div class="notification-icon">❗</div>
      <span>{{ notification }}</span>
      <button class="notification-close" @click="clearNotification">X</button>
    </div>

    <!-- Modal de Configuración -->
    <div v-if="showSettingsModal" class="modal-overlay" @click="closeSettingsModal">
      <div class="modal-content" @click.stop>
        <h2>Configuración del Proyecto</h2>
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
          <div class="modal-actions">
            <button type="submit" class="modal-button">Guardar</button>
            <button type="button" class="modal-button cancel" @click="closeSettingsModal">Cancelar</button>
          </div>
        </form>
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

    <!-- Salida del código -->
    <div class="output-container" :class="{ expanded: isExpanded }" ref="outputContainer">
      <button class="expand-button" @click="toggleExpand">
        <img v-if="!isExpanded" src="/assets/img/pantalla-grande.svg" alt="Pantalla Grande" width="30" />
        <img v-if="isExpanded" src="/assets/img/pantalla-pequeña.svg" alt="Pantalla Pequeña" width="30" />
      </button>
      <div class="resize-bar" @mousedown="startResize"></div>
      <iframe class="output" :srcdoc="output"></iframe>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, computed, onUnmounted } from "vue";
import { useRouter } from "vue-router";
import CodeMirror from "codemirror";
import { useLliureStore } from "~/stores/app";
import "codemirror/lib/codemirror.css";
import "codemirror/theme/dracula.css";
import "codemirror/mode/htmlmixed/htmlmixed";
import "codemirror/mode/css/css";
import "codemirror/mode/javascript/javascript";
import useCommunicationManager from "@/stores/comunicationManager";

export default {
  setup() {
    const router = useRouter();
    const { guardarProyectoDB } = useCommunicationManager();
    const lliureStore = useLliureStore();

    // Reactive state
    const title = ref("Untitled");
    const html = ref("");
    const css = ref("");
    const js = ref("");
    const isEditing = ref(false);
    const showSettingsModal = ref(false);
    const notification = ref("");
    const modalTitle = ref("");
    const modalDescription = ref("");
    const isExpanded = ref(false); 

    const htmlEditor = ref(null);
    const cssEditor = ref(null);
    const jsEditor = ref(null);

    let htmlEditorInstance, cssEditorInstance, jsEditorInstance;

    // Lifecycle hooks
    onMounted(() => {
      lliureStore.toggleLliure();

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
      });

      cssEditorInstance.on("change", (instance) => {
        css.value = instance.getValue();
      });

      jsEditorInstance.on("change", (instance) => {
        js.value = instance.getValue();
      });
    });

    onUnmounted(() => {
      lliureStore.toggleLliure();
    });

    // Functions
    const toggleExpand = () => {
      isExpanded.value = !isExpanded.value;
    };

    const goBack = () => router.push("/");

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
      try {
        await guardarProyectoDB({
          nombre: title.value || "",
          user_id: 1 || null,
          html_code: html.value || "",
          css_code: css.value || "",
          js_code: js.value || "",
        });
        alert.value = "Proyecto guardado con éxito.";
      } catch (error) {
        notification.value = "Error al guardar el proyecto.";
        console.error(error);
      } finally {
        setTimeout(() => (notification.value = ""), 3000);
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
      notification,
      modalTitle,
      modalDescription,
      goBack,
      openSettingsModal,
      closeSettingsModal,
      saveSettings,
      guardarProyecto,
      isExpanded, 
      toggleExpand, 
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
  background-color: #121212;
  font-family: 'Arial', sans-serif;
  color: #ffffff;
  
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px 20px;
  background-color: #1e1e1e;
  color: #fff;
}

.header-title {
  font-size: 15px;
  color: #fff;
  background-color: #333;
  border: none;
  padding: 8px;
  border-radius: 4px;
  text-align: center;
  margin-right: 750px;
}

.header-actions {
  display: flex;
  gap: 15px;
}

.header-button {
  background-color: #444;
  border: none;
  color: #fff;
  padding: 10px 15px;
  border-radius: 5px;
  cursor: pointer;
  font-size: 14px;
  transition: background-color 0.3s;
}

.header-button:hover {
  background-color: #666;
}

.editor-container {
  display: flex;
  gap: 30px;
  margin-top: 20px;
}

.editor-box {
  flex: 1;
  position: relative;
  display: flex;
  flex-direction: column;
  padding: 5px;
  border-radius: 8px;
  box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
  background-color: #1e1e1e;
}

.editor-label {
  position: absolute;
  top: 17px;
  left: 20px;
  font-size: 16px;
  color: #fff;
  background-color: #333;
  padding: 4px 10px;
  border-radius: 5px;
}

.code-editor {
  margin-top: 50px;
  height: 322px; 
  width: 31vw;
  border: 1px solid #333;
  border-radius: 4px;
  background-color: #121212;
  color: #fff; 
  padding: 10px;
  box-sizing: border-box; 
}

.output-container {
  position: relative;
  transition: all 0.3s ease-in-out;
  background-color: white;
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
</style>
