<template>
  <div class="todo">
    <div class="main-container">
      <div class="body-content">
        <h2>El lloc ideal per crear, provar i explorar codi web.</h2>
        <br /><br />
        <p>
          Aquesta plataforma és un entorn interactiu per a desenvolupadors i
          dissenyadors front-end. Crea i experimenta amb projectes, comparteix
          les teves creacions, prova les teves idees i troba inspiració per
          continuar aprenent.
        </p>
      </div>
      <div class="looping-words">
        <div class="looping-words__containers">
          <ul class="looping-words__list">
            <li class="looping-words__list-item">HTML</li>
            <li class="looping-words__list-item">CSS</li>
            <li class="looping-words__list-item">JAVASCRIPT</li>
            <li class="looping-words__list-item">HTML</li>
            <li class="looping-words__list-item">CSS</li>
            <li class="looping-words__list-item">JAVASCRIPT</li>
          </ul>
        </div>
      </div>
      <br /><br /><br />
      <div class="card-container">
        <div class="card">
          <div class="card-content">
            <h3>Crea i Experimenta</h3>
            <p>
              Dóna vida a les teves idees construint projectes complets o
              provant funcions i animacions específiques.
            </p>
            <div class="btn-container">
              <button class="btn" @click="goToEditar">Prova l'Editor</button>
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-content">
            <h3>Practica els teus coneixements</h3>
            <p>Vols provar-te fent uns exercicis per veure quin nivell tens?</p>
            <div class="btn-container">
              <button class="btn" @click="goToNiveles">
                Participa els reptes que et proposem
              </button>
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-content">
            <h3>Comparteix els Teus Projectes</h3>
            <p>
              Uneix-te a la comunitat global de desenvolupadors front-end
              compartint les teves creacions i inspirant altres.
            </p>
            <div class="btn-container">
              <button class="btn" @click="goToTotsProjectes">
                Descobreix el Més Popular
              </button>
            </div>
          </div>
        </div>
      </div>
      <br /><br />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import { useAppStore } from "@/stores/app";
import { useIdProyectoActualStore } from "@/stores/app";
import useCommunicationManager from "@/stores/comunicationManager";

const router = useRouter();
const appStore = useAppStore();
const idProyectoActualStore = useIdProyectoActualStore();
const comunicationManager = useCommunicationManager();

// Si quieres mostrar un mensaje de alerta en el template,
// crea aquí la ref y úsala después:
const alertaVisible = ref(false);

const goToEditar = async () => {
  if (localStorage.getItem("loginInfo") !== null) {
    try {
      // Llamada a la API
      const respuesta = await comunicationManager.crearProyectoDB({
        nombre: "untitled",
        user_id: appStore.loginInfo.id,
        html_code: "",
        css_code: "",
        js_code: "",
      });

      // La API devuelve { success: "...", id: 42 }
      const nuevoId = respuesta.id;
      if (!nuevoId) {
        console.error("ID de proyecto no devuelto:", respuesta);
        return;
      }

      // Guarda el ID en el store y en localStorage
      idProyectoActualStore.actalizarId(nuevoId);
      localStorage.setItem("idProyectoActual", nuevoId);

      // Redirige al editor
      router.push(`/lliure/${nuevoId}`);
    } catch (error) {
      console.error("Error al crear el proyecto:", error);
    }
  } else {
    // activa tu alerta en el template
    alertaVisible.value = true;
  }
};

const goToNiveles = () => {
  router.push("/niveles");
};

const goToTotsProjectes = () => {
  router.push("/totsProjectes");
};

onMounted(() => {
  setTimeout(() => {
    const wordList = document.querySelector(".looping-words__list");
    const words = Array.from(wordList.children);
    const wordHeight = 100 / words.length;

    const moveWords = () => {
      wordList.style.transition = "transform 1s ease-out";
      wordList.style.transform = `translateY(-${wordHeight}%)`;

      setTimeout(() => {
        wordList.appendChild(words[0]);
        wordList.style.transition = "none";
        wordList.style.transform = "translateY(0)";
        words.push(words.shift());
      }, 1000);
    };

    setInterval(moveWords, 2000);
  }, 500);
});
</script>

<style scoped>
.todo {
  height: 100%;
  color: #e0e0e0;
}

.main-container {
  flex-direction: flex;
  justify-content: space-between;
  margin-left: 2%;
}

.body-content {
  text-align: left;
}

.body-content h2 {
  width: 50%;
  font-size: 3.5em;
  margin-bottom: 30px;
  background: linear-gradient(45deg, #ffffff, #bd89ff);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  line-height: 1.2;
}

.body-content p {
  font-size: 1.1em;
  color: #b0b0b0;
  line-height: 1.6;
  max-width: 80%;
  margin-left: 10px;
  margin-top: 0;
}

.card-container {
  display: flex;
  justify-content: space-around;
  gap: 30px;
  align-items: stretch;
}

.card {
  width: 300px;
  height: 350px;
  background: #07182e;
  position: relative;
  display: flex;
  place-content: center;
  place-items: center;
  overflow: hidden;
  border-radius: 10px;
}

.card-content {
  z-index: 1;
  display: flex;
  flex-direction: column;
  text-align: center;
  padding: 20px;
  height: 100%;
  width: 100%;
}

.card h3 {
  margin-bottom: 15px;
  font-size: 25px;
  background: linear-gradient(45deg, #ffffff, #bd89ff);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  z-index: 1;
}

.card p {
  color: #d4cece;
  margin-bottom: 20px;
  flex-grow: 1;
  font-size: 1.1em;
  color: #b0b0b0;
  z-index: 1;
}

.card::before {
  content: "";
  position: absolute;
  width: 100px;
  background-image: linear-gradient(
    180deg,
    rgb(0, 183, 255),
    rgb(255, 48, 255)
  );
  height: 130%;
  animation: rotBGimg 6s linear infinite;
  transition: all 0.2s linear;
}

@keyframes rotBGimg {
  from {
    transform: rotate(0deg);
  }

  to {
    transform: rotate(360deg);
  }
}

.card::after {
  content: "";
  position: absolute;
  background: #07182e;
  inset: 5px;
  border-radius: 15px;
}

.btn-container {
  width: 100%;
  position: relative;
  margin-bottom: 60px;
}

.btn {
  border: none;
  border-radius: 10px;
  background-color: rgba(189, 137, 255, 0.2);
  color: #fff;
  cursor: pointer;
  width: 100%;
  padding: 12px 0;
  font-size: 1em;
  transition: all 0.3s ease;
  position: relative;
  z-index: 1;
  border: 1px solid rgba(189, 137, 255, 0.3);
}

.btn:hover {
  background-color: rgba(189, 137, 255, 0.4);
  box-shadow: 0 0 15px rgba(189, 137, 255, 0.5);
}

.looping-words {
  position: absolute;
  width: 35%;
  height: 3.4em;
  font-size: 5vw;
  padding-left: 0.1em;
  padding-right: 0.1em;
  overflow: hidden;
  right: 2%;
  top: 5%;
  border-radius: 10px;
  padding: 10px;
}

.looping-words__list {
  display: flex;
  flex-direction: column;
  padding: 0;
  margin: 0;
  list-style: none;
  transition: transform 1s ease-out;
}

.looping-words__list-item {
  text-align: center;
  text-transform: uppercase;
  font-family: PP Neue Corp, sans-serif;
  font-weight: 700;
  min-height: 0em;
  color: #bd89ff;
  text-shadow: 0 0 10px rgba(189, 137, 255, 0.3);
  opacity: 0.9;
  background: linear-gradient(45deg, #bd89ff, #e77777);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

@media (max-width: 450px) {
  .body-content h2 {
    font-size: 2.5em;
    margin-bottom: 1rem;
    display: block;
    width: 100%;
    max-width: none;
  }

  .body-content p {
    font-size: 1em;
    color: #b0b0b0;
    line-height: 1.6;
    max-width: 80%;
  }

  .body-content {
    position: relative;
    padding-top: 0.2rem;
    margin: 50px;
  }

  .body-content p {
    margin-top: 0;
  }

  .looping-words {
    display: none;
  }

  .card-container {
    display: flex;
    flex-direction: column;
    gap: 30px;
    align-items: center;
  }

  .card {
    width: 300px;
    height: 350px;
    background: #07182e;
    position: relative;
    display: flex;
    place-content: center;
    place-items: center;
    overflow: hidden;
    border-radius: 10px;
  }

  .card-content {
    z-index: 1;
    display: flex;
    flex-direction: column;
    text-align: center;
    padding: 20px;
    height: 100%;
    width: 100%;
  }

  .card h3 {
    margin-bottom: 15px;
    font-size: 25px;
    background: linear-gradient(45deg, #ffffff, #bd89ff);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    z-index: 1;
  }

  .card p {
    color: #d4cece;
    margin-bottom: 20px;
    flex-grow: 1;
    font-size: 1.1em;
    color: #b0b0b0;
    z-index: 1;
  }

  .card::before {
    content: "";
    position: absolute;
    width: 100px;
    background-image: linear-gradient(
      180deg,
      rgb(0, 183, 255),
      rgb(255, 48, 255)
    );
    height: 130%;
    animation: rotBGimg 6s linear infinite;
    transition: all 0.2s linear;
  }
}
</style>