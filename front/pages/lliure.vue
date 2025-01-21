<template>
  <div class="mini-codepen">
    <div class="editor">
      <!-- Editor de HTML -->
      <div ref="htmlEditor" class="code-editor"></div>
      <div class="editor-label">HTML</div>

      <!-- Editor de CSS -->
      <div ref="cssEditor" class="code-editor"></div>
      <div class="editor-label">CSS</div>

      <!-- Editor de JS -->
      <div ref="jsEditor" class="code-editor"></div>
      <div class="editor-label">JS</div>
    </div>

    <!-- Mostrar el resultado en un iframe -->
    <iframe class="output" :srcdoc="output"></iframe>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import CodeMirror from 'codemirror';

// Importar los modos y temas de manera correcta
import 'codemirror/lib/codemirror.css'; // Debería funcionar con la versión 5.x
import 'codemirror/theme/eclipse.css'; // Tema claro
import 'codemirror/mode/htmlmixed/htmlmixed'; // Para HTML
import 'codemirror/mode/css/css'; // Para CSS
import 'codemirror/mode/javascript/javascript'; // Para JS

export default {
  setup() {
    const html = ref('');
    const css = ref('');
    const js = ref('');

    const htmlEditor = ref(null);
    const cssEditor = ref(null);
    const jsEditor = ref(null);

    let htmlEditorInstance = null;
    let cssEditorInstance = null;
    let jsEditorInstance = null;

    onMounted(() => {
      htmlEditorInstance = CodeMirror(htmlEditor.value, {
        mode: 'htmlmixed',
        theme: 'eclipse', // Tema claro
        lineNumbers: true,
        value: html.value,
        lineWrapping: true,
        indentWithTabs: false, // Desactiva el uso de tabuladores
        smartIndent: false, // Desactiva la indentación inteligente
      });

      cssEditorInstance = CodeMirror(cssEditor.value, {
        mode: 'css',
        theme: 'eclipse',
        lineNumbers: true,
        value: css.value,
        lineWrapping: true,
        indentWithTabs: false, // Desactiva el uso de tabuladores
        smartIndent: false, // Desactiva la indentación inteligente
      });

      jsEditorInstance = CodeMirror(jsEditor.value, {
        mode: 'javascript',
        theme: 'eclipse',
        lineNumbers: true,
        value: js.value,
        lineWrapping: true,
        indentWithTabs: false, // Desactiva el uso de tabuladores
        smartIndent: false, // Desactiva la indentación inteligente
      });

      // Escuchar cambios y actualizar las variables de Vue
      htmlEditorInstance.on('change', (instance) => {
        html.value = instance.getValue();
      });

      cssEditorInstance.on('change', (instance) => {
        css.value = instance.getValue();
      });

      jsEditorInstance.on('change', (instance) => {
        js.value = instance.getValue();
      });

      // Forzar la altura de los editores
      setEditorHeight(htmlEditorInstance);
      setEditorHeight(cssEditorInstance);
      setEditorHeight(jsEditorInstance);
    });

    // Función para ajustar la altura del editor
    const setEditorHeight = (editorInstance) => {
      const container = editorInstance.getWrapperElement();
      container.style.height = '100%'; // Forzar altura del editor a ocupar todo el espacio disponible
    };

    return { html, css, js, htmlEditor, cssEditor, jsEditor };
  },
  computed: {
    // Generar el HTML completo para el iframe
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
    }
  }
};
</script>

<style scoped>
.mini-codepen {
  display: flex;
  flex-direction: column;
  height: 100vh;
}

.editor {
  display: flex;
  gap: 15px;
  padding: 20px;
  flex: 1;
  height: 60%; /* Limitar el alto de los editores */
}

.editor-label {
  margin-top: 10px;
  font-size: 12px;
  color: #333;
}

.output {
  flex: 1;
  border: none;
  width: 100%;
  height: 200px;
}

.code-editor {
  flex: 1;
  height: 100%;
  border: 1px solid #ddd;
  border-radius: 4px;
  min-height: 150px; /* Ajuste para limitar la altura de los editores */
}
</style>
