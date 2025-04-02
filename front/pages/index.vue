<!-- index.vue -->
<template>
  <div class="todo">
    <div class="main-container">
      <!-- Contenido de la Home -->
      <div class="body-content">
        <h2>El lloc ideal per crear, provar i explorar codi web.</h2>
        <br><br>
        <p>
          Aquesta plataforma és un entorn interactiu per a desenvolupadors i dissenyadors front-end.
          Crea i experimenta amb projectes, comparteix les teves creacions, prova les teves idees i troba inspiració per
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
      <br><br><br>
      <!-- Card Container -->
      <div class="card-container">
        <div class="card">
          <h3>Crea i Experimenta</h3>
          <p>
            Dóna vida a les teves idees construint projectes complets o provant funcions i animacions específiques.
          </p>
          <button class="btn">Prova l'Editor</button>
        </div>
        <div class="card">
          <h3>Practica els teus coneixements</h3>
          <p>
            Vols provar-te fent uns exercicis per veure quin nivell tens?
          </p>
          <button class="btn">Participa els reptes que et proposem</button>
        </div>
        <div class="card">
          <h3>Comparteix els Teus Projectes</h3>
          <p>
            Uneix-te a la comunitat global de desenvolupadors front-end compartint les teves creacions i inspirant
            altres.
          </p>
          <button class="btn">Descobreix el Més Popular</button>
        </div>
      </div>
      <br><br>
    </div>
  </div>
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
}

.card-container {
  display: flex;
  justify-content: space-around;
  gap: 10px;
  align-items: stretch;
}

.card {
  background-image: linear-gradient(139deg,
      rgba(36, 40, 50, 1) 0%,
      rgba(36, 40, 50, 1) 0%,
      rgba(37, 28, 40, 1) 100%);
  padding: 10px;
  border-radius: 8px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
  width: 300px;
  text-align: center;
  display: flex;
  flex-direction: column;
  height: 250px;
}

.card h3 {
  margin-bottom: 15px;
  font-size: 25px;
  background: linear-gradient(45deg, #ffffff, #bd89ff);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.card p {
  color: #d4cece;
  margin-bottom: 20px;
  flex-grow: 1;
  font-size: 1.1em;
  color: #b0b0b0;
}

.btn {
  border: none;
  border-radius: 4px;
  background-color: #28243b;
  color: #fff;
  cursor: pointer;
  width: 100%;
  padding: 10px;
  margin-top: auto;
  font-size: 1em;
  transition: background-color 0.3s ease;
}

.btn:hover {
  background-color: #474261;
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
  top: 5%;
  
  background: linear-gradient(139deg, rgba(36, 40, 50, 0.9) 0%, rgba(37, 28, 40, 0.9) 100%);
  border-radius: 12px;
  padding: 10px;
  border: 1px solid rgba(255, 255, 255, 0.1);
  
  
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
</style>