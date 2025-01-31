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

    <div class="exercise-instructions">
      <p><strong>Instrucciones:</strong></p>
      <p v-if="loading">Cargando pregunta...</p>
      <p v-else-if="error">Error al cargar la pregunta</p>
      <p v-else>{{ question }}</p>
    </div>

    <div class="editor-output-wrapper">
      <div class="editor-box">
        <div class="editor-label">HTML</div>
        <div ref="htmlEditor" class="code-editor"></div>
      </div>

      <div class="submit-container">
        <button class="submit-button" @click="validateExercise">Enviar</button>
      </div>

      <div class="output-container">
        <iframe class="output" :srcdoc="output"></iframe>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, computed } from "vue";
import { useRoute, useRouter } from "vue-router";
import CodeMirror from "codemirror";
import "codemirror/lib/codemirror.css";
import "codemirror/theme/dracula.css";
import "codemirror/mode/htmlmixed/htmlmixed";
import "codemirror/mode/css/css";
import "codemirror/mode/javascript/javascript";
import { useLliureStore } from "~/stores/app";

export default {
  setup() {
    const router = useRouter();
    const route = useRoute();  
    const lliureStore = useLliureStore();

    const title = ref("Ejercicio");
    const html = ref("");
    const css = ref("");
    const js = ref("");
    const isEditing = ref(false);
    const question = ref("");
    const loading = ref(true);
    const error = ref(false);
    const nivelId = ref(route.params.id);  

    const htmlEditor = ref(null);
    let htmlEditorInstance;

    const fetchQuestion = async () => {
      try {
        loading.value = true;

        const response = await fetch(`http://localhost:8000/api/preguntas/${nivelId.value}`);

        if (!response.ok) throw new Error("Error al obtener la pregunta");

        const data = await response.json();

        question.value = data.question;
      } catch (err) {
        console.error("Error en fetchQuestion:", err);
        error.value = true;
      } finally {
        loading.value = false;
      }
    };
    onUnmounted(()=>{
      lliureStore.toggleLliure();

    })
    onMounted(() => {
      lliureStore.toggleLliure();
      fetchQuestion(); 

      htmlEditorInstance = CodeMirror(htmlEditor.value, {
        mode: "htmlmixed",
        theme: "dracula",
        lineNumbers: true,
      });

      htmlEditorInstance.on("change", (instance) => {
        html.value = instance.getValue();
      });
    });

    const goBack = () => router.push("/");

    return {
      title,
      html,
      css,
      js,
      htmlEditor,
      isEditing,
      goBack,
      question,
      loading,
      error,
      output: computed(() => `
        <html>
          <head><style>${css.value}</style></head>
          <body>${html.value}</body>
        </html>
      `),
    };
  },
};
</script>

<style scoped>
.todo {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: flex-start;
  background-color: #000000;
  font-family: 'Arial', sans-serif;
  color: #ffffff;
  height: 100vh;
  box-sizing: border-box;
}

.header {
  position: fixed;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px 15px; 
  background-color: #2d2d2d;
  color: #fff;
  width: 100%;
  height: 10%;
  box-sizing: border-box;
}

.header-title {
  font-size: 16px; 
  color: #fff;
  background-color: #444;
  border: none;
  padding: 6px;
  border-radius: 4px;
  text-align: center;
}

.header-actions {
  display: flex;
  gap: 8px; 
}

.header-button {
  background-color: #555;
  border: none;
  color: #fff;
  padding: 6px 10px;
  border-radius: 4px;
  cursor: pointer;
  font-size: 13px;
}

.header-button:hover {
  background-color: #777;
}

.exercise-instructions {
  margin-top: 100px; 
  background-color: #1e1e1e;
  color: #fff;
  padding: 8px 15px; 
  border-radius: 6px;
  box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.3);
  max-width: 750px; 
}

.editor-output-wrapper {
  display: flex;
  justify-content: center;
  align-items: flex-start;
  gap: 30px; 
  width: 100%;
  max-width: 1000px;
  margin-top: 30px; 
  box-sizing: border-box;
 
}

.editor-box,
.output-container {
  flex: 1;
  max-width: 100%; 
  border-radius: 6px;
  box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.2);
}

.editor-box {
  background-color: #2d2d2d;
  padding: 10px; 
  display: flex;
  flex-direction: column;
}

.editor-label {
  margin-bottom: 8px;
  font-size: 15px; 
  color: #fff;
  background-color: #444;
  padding: 4px 6px;
  border-radius: 3px;
  text-align: center;
}

.code-editor {
  flex: 1;
  border: 1px solid #444;
  border-radius: 4px;
  background-color: #1e1e1e;
  color: #fff;
  width: 31vw;
}

.output-container {
  background-color: #fff;
  overflow: hidden;
  box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.2);
  height: 100%;
}

.output {
  width: 100%;
  height: 100%;
  border: none;
  background-color: #ffffff;
  border-radius: 10px;
}

.submit-button {
  background-color: #4CAF50;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 14px;
  margin-top: 300px;
}

.submit-button:hover {
  background-color: #45a049;
}
</style>
