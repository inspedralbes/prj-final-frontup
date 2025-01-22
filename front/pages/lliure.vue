<template>
  <div class="mini-codepen">
    <!-- Encabezado -->
    <header class="header">
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
        <button class="header-button">Settings</button>
        <button class="header-button">💡</button>
      </div>
    </header>

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
import { ref, onMounted } from "vue";
import CodeMirror from "codemirror";

// Importar los modos y temas
import "codemirror/lib/codemirror.css";
import "codemirror/theme/eclipse.css";
import "codemirror/mode/htmlmixed/htmlmixed";
import "codemirror/mode/css/css";
import "codemirror/mode/javascript/javascript";

export default {
  setup() {
    const html = ref("");
    const css = ref("");
    const js = ref("");
    const title = ref("Untitled");  

    const htmlEditor = ref(null);
    const cssEditor = ref(null);
    const jsEditor = ref(null);
    const isEditing = ref(false);  

    let htmlEditorInstance = null;
    let cssEditorInstance = null;
    let jsEditorInstance = null;

    onMounted(() => {
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

    return { html, css, js, htmlEditor, cssEditor, jsEditor, title, isEditing };
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

.mini-codepen {
  display: flex;
  flex-direction: column;
  background-color: #6e6b6b;
  
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px 20px;
  background-color: #000;
  color: #fff;
}

.header-title {
  font-size: 14px;
  color: #fff;
  background-color: #202020;
  border: none;
  width: 200px; 
  padding: 5px;
  border-radius: 4px;
  text-align: center;
  margin-left: 50px;
}

.header-actions {
  display: flex;
  gap: 10px;
}

.header-button {
  background-color: #444;
  border: none;
  color: #fff;
  padding: 5px 10px;
  border-radius: 4px;
  cursor: pointer;
  font-size: 12px;
}

.header-button:hover {
  background-color: #555;
}

.editor-container {
  display: flex;
}

.editor-box {
  flex: 1;
  position: relative;
  display: flex;
  flex-direction: column;
  padding: 10px;
  border-right: 1px solid #333;
}

.editor-label {
  position: absolute;
  top: 8px;
  left: 10px;
  font-size: 20px;
  color: #fff;
  background-color: #202020;
  padding: 2px 6px;
}

.code-editor {
  margin-top: 40px;
  height: 100%;
  border: 1px solid #444;
  border-radius: 4px;
 
}

.output {
  height: 33%;
}
</style>
