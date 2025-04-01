<!-- index.vue -->
<template>
  <div class="todo">
    <div class="main-container">
      <!-- Contenido de la Home -->
      <div class="body-content">
        <h2>El lloc ideal per crear, provar i explorar codi web.</h2>
        <p>
          Aquesta plataforma és un entorn interactiu per a desenvolupadors i dissenyadors front-end.
          Crea i experimenta amb projectes, comparteix les teves creacions, prova les teves idees i troba inspiració per continuar aprenent.
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
      <br><br><br>
      <!-- Card Container -->
      <div class="card-container">
        <div class="card">
          <h3>Crea i Experimenta</h3>
          <p>
            Dóna vida a les teves idees construint projectes complets o provant funcions i animacions específiques.
          </p>
          <button>Prova l'Editor</button>
        </div>
        <div class="card">
          <h3>Practica els teus coneixements</h3>
          <p>
            Vols provar-te fent uns exercicis per veure quin nivell tens?
          </p>
          <button>Participa els reptes que et proposem</button>
        </div>
        <div class="card">
          <h3>Comparteix els Teus Projectes</h3>
          <p>
            Uneix-te a la comunitat global de desenvolupadors front-end compartint les teves creacions i inspirant altres.
          </p>
          <button>Descobreix el Més Popular</button>
        </div>
      </div>
      <br><br>
      <!-- Footer -->
    </div>
  </div>
  <footer>
    <p>© 2025 FrontUp</p>
  </footer>
</template>

<script>

export default {
  name: 'Index',
  mounted() {

    setTimeout(() => {
      const wordList = document.querySelector('.looping-words__list');
      const words = Array.from(wordList.children);
      const wordHeight = 100 / words.length;

      const moveWords = () => {
        wordList.style.transition = 'transform 1s ease-out';
        wordList.style.transform = `translateY(-${wordHeight}%)`;

        setTimeout(() => {
          wordList.appendChild(words[0]); // Mueve la primera palabra al final
          wordList.style.transition = 'none';
          wordList.style.transform = 'translateY(0)';

          words.push(words.shift()); // Reorganiza el array en memoria
        }, 1000); // Tiempo suficiente para completar la animación
      };

      setInterval(moveWords, 2000);
    }, 500);
  },
};
</script>


<style scoped>
.todo {
  background-color: #0f1014;
  min-height: 100vh;
  color: #e0e0e0;
}

.main-container {
  margin-top: 80px;
  margin-left: 280px; /* Ajuste para la barra lateral */
  padding: 40px;
  background: linear-gradient(139deg, #0f1014 0%, #0d0e12 100%);
}

.body-content {
  text-align: left;
  width: 60%;
  margin-bottom: 80px;
}

.body-content h2 {
  font-size: 3.5em;
  margin-bottom: 30px;
  background: linear-gradient(45deg, #ffffff, #bd89ff);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  line-height: 1.2; 
}

.body-content p {
  font-size: 1.1em;
  color: #a0a0a0;
  line-height: 1.6;
  max-width: 80%;
}

.card-container {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
  gap: 30px;
  margin-top: 60px;
}

.card {
  background: linear-gradient(139deg, rgba(36, 40, 50, 0.9) 0%, rgba(37, 28, 40, 0.9) 100%);
  padding: 30px;
  border-radius: 16px;
  border: 1px solid rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  min-height: 320px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.card:hover {
  transform: translateY(-5px);
  box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
}

.card h3 {
  font-size: 1.8em;
  color: #ffffff;
  margin-bottom: 20px;
  background: linear-gradient(45deg, #ffffff, #bd89ff);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.card p {
  color: #b0b0b0;
  font-size: 1em;
  line-height: 1.6;
  margin-bottom: 25px;
}

.btn {
  background: linear-gradient(45deg, #5353ff, #8a2be2);
  color: white !important;
  border: none;
  padding: 14px 28px;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  align-self: flex-start;
  margin-top: auto;
}

.btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(83, 83, 255, 0.3);
}

.looping-words {
  position: absolute;
  width: 35%;
  height: 4em;
  right: 5%;
  top: 25%;
  background: linear-gradient(139deg, rgba(36, 40, 50, 0.9) 0%, rgba(37, 28, 40, 0.9) 100%);
  border-radius: 12px;
  padding: 20px;
  border: 1px solid rgba(255, 255, 255, 0.1);
}

.looping-words__list-item {
  color: #bd89ff;
  font-size: 2.5em;
  text-shadow: 0 0 10px rgba(189, 137, 255, 0.3);
  opacity: 0.9;
}

footer {
  background: linear-gradient(139deg, rgba(36, 40, 50, 0.9) 0%, rgba(37, 28, 40, 0.9) 100%);
  border-top: 1px solid rgba(255, 255, 255, 0.1);
  padding: 30px;
  margin-top: 100px;
  text-align: center;
  color: #a0a0a0;
}

@media (max-width: 1200px) {
  .main-container {
    margin-left: 240px;
    padding: 30px;
  }
  
  .body-content h2 {
    font-size: 2.5em;
  }
  
  .card-container {
    grid-template-columns: 1fr;
  }
}
</style>