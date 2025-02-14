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
  background-color: #121212;
  height: 100%;
  color: #e0e0e0;
}

.main-container {
  margin-top: 80px;
  margin-left: 180px;
  padding: 10px;
  flex-direction: flex;
  justify-content: space-between;
  background-color: #121212;
}

.body-content {
  text-align: left;
  width: 50%;
}

.body-content h2 {
  font-size: 3em;
  margin-bottom: 20px;
  color: #fff;
}

.body-content p {
  font-size: 1em;
  color: #b0b0b0;
}

.card-container {
  display: flex;
  justify-content: space-around;
  gap: 5vw;
}

.card {
  margin-top: 10vh;
  background-color: #1e1e1e;
  padding: 10px;
  border-radius: 8px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
  width: 300px;
  text-align: center;
}

.card h3 {
  margin-bottom: 15px;
  font-size: 25px;
  color: #fff;
}

.card p {
  color: #d4cece;
  margin-bottom: 20px;
}

.btn {
    border: none;
  border-radius: 4px;
  background-color: #333;
  color: #fff;
  cursor: pointer;
  width: 20vw;
  height: 10vh;
}

.btn:hover{
  background-color: #555;
}


footer {
  background-color: #000000;
  text-align: center;
  padding: 10px 20px;
  color: white;
}

.looping-words {
  position: absolute;
  width: 35%; 
  height: 3.5em;
  font-size: 5vw; 
  padding-left: 0.1em;
  padding-right: 0.1em;
  overflow: hidden;
  right: 2%;
  top: 20%;
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
}
</style>