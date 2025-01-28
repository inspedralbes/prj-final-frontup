<template>
  <div class="todo">
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
    <h1>Bienvenido al nivel {{ levelId }}</h1>

    <!-- Contenedor de Editor y Salida -->
    <div class="editor-output-wrapper">
      <!-- Editor de HTML -->
      <div class="editor-box">
        <div class="editor-label">HTML</div>
        <div ref="htmlEditor" class="code-editor"></div>
      </div>

      <!-- Salida HTML -->
      <div class="output-container">
        <iframe class="output" :srcdoc="output"></iframe>
      </div>
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
          nombre: title.value || '',
          user_id: 1 || null,
          html_code: html.value || '',
          css_code: css.value || '',
          js_code: js.value || '',
        });
        notification.value = "Proyecto guardado con éxito.";
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
  background-color: #000000;
  font-family: 'Arial', sans-serif;
  color: #000000;
  height: 100vh;
  box-sizing: border-box;
}

h1 {
  text-align: center;
  font-size: 2rem;
  color: #4caf50;
  margin-bottom: 20px;
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

.editor-output-wrapper {
  display: flex;
  justify-content: space-between;
  gap: 10px;
  width: 100%;
  margin-top: 20px;
}

.editor-box, .output-container {
  flex: 1;
  max-width: 50%;
  border-radius: 6px;
  box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.2);
}

.editor-box {
  background-color: #2d2d2d;
  padding: 15px;
}

.editor-label {
  margin-bottom: 10px;
  font-size: 16px;
  color: #fff;
  background-color: #444;
  padding: 4px 8px;
  border-radius: 3px;
}

.code-editor {
  border: 1px solid #444;
  border-radius: 4px;
  background-color: #1e1e1e;
  color: #fff;
}

.output-container {
  background-color: #fff;
  overflow: hidden;
  box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.2);
}

.output {
  width: 100%;
  height: 100%;
  border: none;
  background-color: #ffffff;
  border-radius: 10px;
}
</style>
