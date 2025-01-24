<template>
  <div class="todo">
    <!-- Encabezado -->
    <header class="header">
      <button class="header-button" @click="goBack">Atras</button>
      <input
        type="text"
        v-model="title"
        class="header-title"
        @focus="isEditing = true"
        @blur="isEditing = false"
        :readonly="!isEditing"
      />
      <div class="header-actions">  
        <button class="header-button">Save</button>
        <button class="header-button" @click="openSettingsModal">Settings</button>
        <button class="header-button">💡</button>
      </div>
    </header>

    <!-- Modal de Configuración -->
    <div v-if="showSettingsModal" class="modal-overlay" @click="closeSettingsModal">
      <div class="modal-content" @click.stop>
        <h2>Configuración del Proyecto</h2>
        <form @submit.prevent="saveSettings">
          <div class="input-group">
            <label for="project-title">Título del Proyecto</label>
            <input
              type="text"
              id="project-title"
              v-model="modalTitle"
              class="modal-input"
              placeholder="Escribe el título"
            />
          </div>

          <div class="input-group">
            <label for="project-description">Descripción</label>
            <textarea
              id="project-description"
              v-model="modalDescription"
              class="modal-textarea"
              placeholder="Escribe la descripción del proyecto"
            ></textarea>
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
      <!-- Editor de HTML -->
      <div class="editor-box">
        <div class="editor-label">HTML</div>
        <div ref="htmlEditor" class="code-editor"></div>
      </div>

      <!-- Editor de CSS -->
      <div class="editor-box">
        <div class="editor-label">CSS</div>
        <div ref="cssEditor" class="code-editor"></div>
      </div>

      <!-- Editor de JS -->
      <div class="editor-box">
        <div class="editor-label">JS</div>
        <div ref="jsEditor" class="code-editor"></div>
      </div>
    </div>

    <!-- Salida del código -->
    <iframe class="output" :srcdoc="output"></iframe>
  </div>
</template>

<script>
import { ref, onMounted, watch, onUnmounted } from "vue";
import { useRouter } from "vue-router"; 
import CodeMirror from "codemirror";
import { useLliureStore } from '~/stores/app'
import "codemirror/lib/codemirror.css";
import "codemirror/theme/eclipse.css";
import "codemirror/mode/htmlmixed/htmlmixed";
import "codemirror/mode/css/css";
import "codemirror/mode/javascript/javascript";

export default {
  setup() {
    const lliureStore = useLliureStore();
    const router = useRouter();  
    const html = ref("");
    const css = ref("");
    const js = ref("");
    const title = ref("Untitled");  

    const htmlEditor = ref(null);
    const cssEditor = ref(null);
    const jsEditor = ref(null);
    const isEditing = ref(false);  
    const showSettingsModal = ref(false); 

    const modalTitle = ref(title.value);  
    const modalDescription = ref("");     

    let htmlEditorInstance = null;
    let cssEditorInstance = null;
    let jsEditorInstance = null;
    onUnmounted(() => {
      lliureStore.toggleLliure()
    });
    onMounted(() => {
      console.log('has entrado en lliure');
      htmlEditorInstance = CodeMirror(htmlEditor.value, {
        mode: "htmlmixed",
        theme: "eclipse",
        lineNumbers: true,
        value: html.value,
      });

      cssEditorInstance = CodeMirror(cssEditor.value, {
        mode: "css",
        theme: "eclipse",
        lineNumbers: true,
        value: css.value,
      });

      jsEditorInstance = CodeMirror(jsEditor.value, {
        mode: "javascript",
        theme: "eclipse",
        lineNumbers: true,
        value: js.value,
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

    watch(modalTitle, (newTitle) => {
      title.value = newTitle;
    });

    const goBack = () => {
      router.push("/");  
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

    return { 
      html, css, js, htmlEditor, cssEditor, jsEditor, title, isEditing, goBack, 
      openSettingsModal, closeSettingsModal, showSettingsModal, 
      modalTitle, modalDescription, saveSettings
    };
  },
  computed: {
    output() {
      return `
        <html>
          <head>
            <style>${this.css}</style>
          </head>
          <body>
            ${this.html}
            <script>${this.js}<\/script>
          </body>
        </html>
      `;
    },
  },
};
</script>

<style scoped>
.todo {
  display: flex;
  flex-direction: column;
  background-color: #f4f4f4;
  font-family: 'Arial', sans-serif;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px 20px;
  background-color: black;
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
  margin-right: 750px;
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
  background-color: #fff;
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
  height: 300px;
  border: 1px solid #444;
  border-radius: 4px;
}

.output {
  height: 50%;
  border: none;
  border-radius: 8px;
  border: 1px solid black;
  margin-left: 20px;
  margin-right: 20px;
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.6);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 999;
}

.modal-content {
  background-color: #fff;
  padding: 30px;
  border-radius: 8px;
  width: 450px;
  text-align: center;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
}

.modal-content h2 {
  margin-bottom: 20px;
  font-size: 18px;
  color: #333;
}

.input-group {
  margin-bottom: 20px;
}

.modal-input,
.modal-textarea {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 4px;
  margin-top: 5px;
}

.modal-actions {
  display: flex;
  justify-content: space-between;
  gap: 10px;
}

.modal-button {
  background-color: #444;
  color: #fff;
  border: none;
  padding: 10px;
  cursor: pointer;
  border-radius: 4px;
  transition: background-color 0.3s;
}

.modal-button:hover {
  background-color: #555;
}

.cancel {
  background-color: #ccc;
  color: #333;
}

.cancel:hover {
  background-color: #bbb;
}
</style>
