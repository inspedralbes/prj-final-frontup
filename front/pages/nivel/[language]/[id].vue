<template>
  <div class="todo">
    <header class="header">
  <div class="header-row">
    <button class="header-button" @click="goBack">Atrás</button>
    <h1 class="header-title">{{ title }}</h1>
    <button class="header-button" @click="toggleChat">Xat IA</button>
  </div>

  <div class="header-row">
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
    const language = ref(route.params.language);
    const id = ref(parseInt(route.params.id));

    const languageLabel = computed(() => {
      return {
        'html': 'Llenguatge: HTML',
        'css': 'Llenguatge: CSS',
        'js': 'Llenguatge: JavaScript'
      }[language.value] || 'Llenguatge desconegut';
    });

    const html = ref("");
    const css = ref("");
    const js = ref("");
    const question = ref("");
    const loading = ref(true);
    const error = ref(false);
    const htmlEditor = ref(null);
    let htmlEditorInstance;

    const levelLimits = {
      html: { min: 1, max: 10 },
      css: { min: 1, max: 10 },
      js: { min: 1, max: 10 }
    };

    watch(() => route.params.language, (newLanguage) => {
      language.value = newLanguage;
      id.value = Math.max(
        levelLimits[newLanguage].min,

        Math.min(levelLimits[newLanguage].max, id.value)
      );
    }, { immediate: true });

    watch(() => route.fullPath, () => {
      id.value = parseInt(route.params.id);
      fetchQuestion();
      clearEditors();
    });

    const fetchQuestion = async () => {
      try {
        loading.value = true;
        error.value = false;

        const response = await fetch(
          `https://back.frontapp.cat/api/niveles/${language.value}/pregunta/${id.value}`,
          {
            headers: {
              'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
          }
        );

        if (!response.ok) throw new Error("Error a l'obtenir la pregunta");

        const data = await response.json();
        question.value = data.question;
      } catch (err) {
        console.error("Error en fetchQuestion:", err);
        error.value = true;
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: "No s'ha pogut carregar la pregunta",
        });
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
        const response = await fetch(
          `https://back.frontapp.cat/api/niveles/${language.value}/verificar/${id.value}`,
          {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            body: JSON.stringify({
              respuesta_usuario: {
                html: html.value,
                css: css.value,
                js: js.value
              }[language.value]
            })
          }
        );

        if (!response.ok) throw new Error("Error en la verificació");

        const { correct, message } = await response.json();

        if (correct) {
          await Swal.fire({
            icon: 'success',
            title: '¡Felicitats!',
            text: message,
          });

          await updateUserLevel();

          const nextId = id.value + 1;
          if (nextId > levelLimits[language.value].max) {
            await Swal.fire({
              icon: 'info',
              title: '🎉 Enhorabona!',
              text: "Has completat totes les preguntes d'aquest llenguatge.",
            });
            router.push('/niveles');
          } else {
            router.push(`/nivel/${language.value}/${nextId}`);
          }
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Incorrecte',
            text: message,
          });
        }
      } catch (error) {
        console.error("Error al validar l'exercici:", error);
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: "Hi ha hagut un problema en verificar la resposta",
        });
      }
    };

    const updateUserLevel = async () => {
      try {
        const response = await fetch(
          `https://back.frontapp.cat/api/niveles/${language.value}/actualizar`,
          {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            body: JSON.stringify({
              nivel: id.value,
              language: language.value
            })
          }
        );

        if (!response.ok) throw new Error("Error a l'actualizar el nivell");

        const data = await response.json();
        console.log("Nivel actualizado:", data);
      } catch (error) {
        console.error("Error en actualizarNivel:", error);
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: "No s'ha pogut actualitzar el nivell",
        });
      }
    };

    const clearEditors = () => {
      html.value = '';
      css.value = '';
      js.value = '';

      if (htmlEditorInstance) {
        htmlEditorInstance.setValue('');
      }
    };

    const goBack = () => {
      router.push(`/nivel_${language.value}`);
    };

    onMounted(() => {
      lliureStore.toggleLliure();
      fetchQuestion();

      let mode = "htmlmixed";
      if (language.value === "css") mode = "css";
      else if (language.value === "js") mode = "javascript";

      htmlEditorInstance = CodeMirror(htmlEditor.value, {
        mode,
        theme: "dracula",
        lineNumbers: true,
      });

      const initialContent = {
        html: "<!-- Escriu el teu codi HTML aquí -->",
        css: "/* Escriu el teu codi CSS aquí */",
        js: "// Escriu el teu codi JavaScript aquí"
      }[language.value];

      htmlEditorInstance.setValue(initialContent);
      if (language.value === "html") html.value = initialContent;
      else if (language.value === "css") css.value = initialContent;
      else if (language.value === "js") js.value = initialContent;

      htmlEditorInstance.on("change", (instance) => {
        const value = instance.getValue();
        if (language.value === "html") html.value = value;
        else if (language.value === "css") css.value = value;
        else if (language.value === "js") js.value = value;
      });
    });

    onUnmounted(() => {
      lliureStore.toggleLliure();
    });

    return {
      title,
      html,
      css,
      js,
      htmlEditor,
      question,
      loading,
      error,
      output: computed(() => `
  <html>
    <head>
      <style>${css.value}</style>
    </head>
    <body>
      ${html.value}
      <script>${js.value}<\/script>
    </body>
  </html>
`),
      validateExercise,
      languageLabel,
      goBack
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

.header-row {
  display: flex;
  align-items: center;
  gap: 12px;
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

@media (max-width: 450px) {

  .todo {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: flex-start;
  background-color: #000000;
  font-family: 'Arial', sans-serif;
  color: #ffffff;
  height: 93vh;
  box-sizing: border-box;
  margin: auto;
}


  .header {
    display: flex;
    flex-direction: column;
    gap: 10px;
    height: 100px;
  }

  .header-row {
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .header-title {
    font-size: 18px;
    color: #ffffff;
    background-color: transparent;
    border: 2px solid transparent;
    padding: 10px 12px;
    border-radius: 6px;
    transition: all 0.3s ease;
    text-align: center;
    min-width: 180px;
  }

  .editor-output-wrapper {
    flex-direction: column;
  align-items: center;
  max-width: 400px;
  margin: 0 auto;
  width: 100%;
  padding: 0 12px;
  box-sizing: border-box;
  }

  .submit-container {
    order: 0;
    display: flex;
    justify-content: center;
    margin-top: -280px;
    padding: 0 170px;
  }
  

  .editor-box {
  order: 1;
  max-width: 400px; /* o el ancho que desees */
  width: 100%;
  margin: 0 auto; /* centra horizontalmente */
  box-sizing: border-box;
  background-color: #2d2d2d; /* asegúrate que este sea el color que quieres */
  border-radius: 8px; /* opcional, mejora el aspecto en móvil */
}


  .output-container {
    order: 2;
  }

  .submit-button {
    width: 100%;
  }

  .code-editor {
    width: 100%;
    height: 200px;
  }

  .editor-box,
  .output-container {
    width: 100%;
  }

  .exercise-instructions {
  margin-top: 100px;
  background-color: #1e1e1e;
  color: #fff;
  padding: 8px 40px;
  border-radius: 6px;
  box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.3);
  max-width: 750px;
} 


}
</style>
