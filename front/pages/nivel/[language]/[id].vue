<template>
  <div class="todo">
    <header class="header">
      <button class="header-button" @click="goBack">Atrás</button>
      <h1 class="header-title">{{ title }}</h1>
      <div class="header-actions">
        <button class="header-button" @click="toggleChat">Xat IA</button>
        <button class="header-button" @click="guardarProyecto">Guardar</button>
        <button class="header-button" @click="openSettingsModal">Configuració</button>
        <button class="header-button">💡</button>
      </div>
    </header>

    <div class="exercise-instructions">
      <p><strong>Instruccions:</strong></p>
      <p v-if="loading">Carregant pregunta...</p>
      <p v-else-if="error">Error en carregar la pregunta</p>
      <p v-else>{{ question }}</p>
    </div>

    <div class="editor-output-wrapper">
      <div class="editor-box">
        <div class="editor-label">{{ languageLabel }}</div>
        <div ref="htmlEditor" class="code-editor"></div>
      </div>

      <div class="submit-container">
        <button class="submit-button" @click="validateExercise">Enviar</button>
      </div>

      <div class="output-container">
        <iframe class="output" :srcdoc="output" sandbox="allow-scripts"></iframe>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, computed, onUnmounted, watch } from "vue";
import { useRoute, useRouter } from "vue-router";
import CodeMirror from "codemirror";
import "codemirror/lib/codemirror.css";
import "codemirror/theme/dracula.css";
import "codemirror/mode/htmlmixed/htmlmixed";
import "codemirror/mode/css/css";
import "codemirror/mode/javascript/javascript";
import { useLliureStore } from "~/stores/app";
import Swal from 'sweetalert2';

export default {
  setup() {
    const router = useRouter();
    const route = useRoute();
    const lliureStore = useLliureStore();

    const title = computed(() => `Exercici ${id.value}`);

    const languageLabel = computed(() => {
      if (language.value === 'html') return 'Llenguatge: HTML';
      if (language.value === 'css') return 'Llenguatge: CSS';
      if (language.value === 'js') return 'Llenguatge: JavaScript';
      return 'Llenguatge desconegut';
    });

    const html = ref("");
    const css = ref("");
    const js = ref("");
    const isEditing = ref(false);
    const question = ref("");
    const loading = ref(true);
    const error = ref(false);
    const language = ref(route.params.language);
    const id = ref(route.params.id);

    const htmlEditor = ref(null);
    let htmlEditorInstance;

    watch(() => route.params.language, (newLanguage) => {
      if (newLanguage === 'html') {
        id.value = Math.max(1, Math.min(10, parseInt(route.params.id)));
      } else if (newLanguage === 'css') {
        id.value = Math.max(11, Math.min(20, parseInt(route.params.id)));
      } else if (newLanguage === 'js') {
        id.value = Math.max(21, Math.min(30, parseInt(route.params.id)));
      }
    }, { immediate: true });

    const fetchQuestion = async () => {
      try {
        loading.value = true;
        error.value = false;

        const response = await fetch(`http://localhost:8000/api/preguntas/${language.value}/${id.value}`);
        if (!response.ok) throw new Error("Error a l'obtenir la pregunta");

        const data = await response.json();
        question.value = data.question;
      } catch (err) {
        console.error("Error en fetchQuestion:", err);
        error.value = true;
      } finally {
        loading.value = false;
      }
    };

    const validateExercise = async () => {
      if (!question.value) {
        alert("No s'ha carregat la pregunta.");
        return;
      }

      try {
        const response = await fetch(`http://localhost:8000/api/preguntas/${language.value}/${id.value}`);
        if (!response.ok) throw new Error("Error a l'obtenir la resposta correcta");

        const data = await response.json();

        const cleanString = (str) => {
          return str.replace(/[\s\u200B\u200C\u200D\uFEFF]+/g, '').toUpperCase();
        };

        const respuestaCorrecta = cleanString(data.resp_correcta);
        console.log("respuesta correcta", respuestaCorrecta);
        const respuestaUsuario = cleanString(html.value);
        console.log("respuesta usuari", respuestaUsuario);


        if (respuestaUsuario === respuestaCorrecta) {
          await Swal.fire({
            icon: 'success',
            title: '¡Felicitats!',
            text: "Has completat l'exercici.",
          });

          await actualizarNivel();

          let nextId = parseInt(id.value) + 1;

          if (
            (language.value === "html" && nextId > 10) ||
            (language.value === "css" && nextId > 20) ||
            (language.value === "js" && nextId > 30)
          ) {
            await Swal.fire({
              icon: 'info',
              title: '🎉 Enhorabona!',
              text: "Has completat totes les preguntes d'aquest llenguatge.",
            });
            return;
          }

          router.push(`/nivel/${language.value}/${nextId}`);
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Incorrecte',
            text: "La teva resposta no es correcta. Torna-ho a intentar.",
          });
        }


      } catch (error) {
        console.error("Error al validar l'exercici:", error);
      }
    };


    const actualizarNivel = async () => {
      try {
        const userId = 1;
        let campoNivel = "";

        if (language.value === "html") campoNivel = "nivel";
        else if (language.value === "css") campoNivel = "nivel_css";
        else if (language.value === "js") campoNivel = "nivel_js";

        const response = await fetch(`http://localhost:8000/api/actualizar-nivel`, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            userId: userId,
            campo: campoNivel,
            nivel: id.value,
          }),
        });

        if (!response.ok) throw new Error("Error a l'actualizar el nivell");

      } catch (error) {
        console.error("Error en actualizarNivel:", error);
      }
    };

    watch(() => route.params.id, (newId) => {
      id.value = newId;
      fetchQuestion();
      clearEditors();
    });

    const clearEditors = () => {
      html.value = '';
      css.value = '';
      js.value = '';

      if (htmlEditorInstance) {
        htmlEditorInstance.setValue('');
      }
    };

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

      if (language.value === "css") {
        const basicHTML = `<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles.css">
</head>
<style>

</style>
<body>
    <h1>Hola, mon</h1>
    <p>Modifica el CSS per cambiar l'estil.</p>
</body>
</html>`;
        htmlEditorInstance.setValue(basicHTML);
        html.value = basicHTML;
      }
    });

    const goBack = () => {
      let rutaBase = "/nivel_";

      if (language.value === "html") {
        rutaBase += "html";
      } else if (language.value === "css") {
        rutaBase += "css";
      } else if (language.value === "js") {
        rutaBase += "js";
      }

      router.push(rutaBase);
    };



    onUnmounted(() => {
      lliureStore.toggleLliure();
    });

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
      validateExercise,
      languageLabel,
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
  margin-left: -220px;
}

.header {
  position: fixed;
  top: 0;
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
  text-align: center;
  min-width: 180px;
  margin: 0;
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
