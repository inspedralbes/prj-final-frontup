<template>
  <div class="todo">
    <div class="main-container">
      <!-- Body Content -->
      <div class="body-content">
        <h2>El lloc ideal per crear, <br> provar i explorar codi web.</h2>
        <p>Aquesta plataforma és un entorn interactiu per a desenvolupadors i dissenyadors front-end.
          <br> Crea i experimenta amb projectes, comparteix les teves creacions,
          <br> prova les teves idees i troba inspiració per continuar aprenent.
        </p>
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
            Uneix-te a la comunitat global de desenvolupadors front-end compartint les teves creacions i inspirant
            altres.
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
};
</script>

<style scoped>
.todo {
  background-color: #121212;
  height: 100vh;
  color: #e0e0e0;
}

.main-container {
  margin-top: 80px;
  margin-left: 200px;
  padding: 20px;
  flex-direction: column;
  justify-content: space-between;
}

.body-content {
  text-align: left;
  margin-bottom: 40px;
}

.body-content h2 {
  font-size: 2em;
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
  gap: 20px;
  margin-right: 30px;
}

.card {
  background-color: #1e1e1e;
  padding: 20px;
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

.card button {
  padding: 10px 20px;
  border: none;
  border-radius: 4px;
  background-color: #333;
  color: #fff;
  cursor: pointer;
}

.card button:hover {
  background-color: #555;
}

footer {
  background-color: #222;
  text-align: center;
  padding: 10px 20px;
  color: white;
}
</style>

