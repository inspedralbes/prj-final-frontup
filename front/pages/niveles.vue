<template>
  <div class="selector">
    <h1>Vamos a practicar</h1>
    <div class="botones">
      <div class="boton-container" style="flex: 1;" @mouseenter="hovered = 'HTML'" @mouseleave="hovered = ''">
        <button class="boton HTML" @click="html_ejercicios"></button>
        <div v-if="hovered === 'HTML'" class="hover-text">¡Bienvenido al mundo de HTML! Aprende a estructurar páginas web de manera sencilla y eficiente.</div>
      </div>
      <div class="boton-container" style="flex: 1;" @mouseenter="hovered = 'CSS'" @mouseleave="hovered = ''">
        <button class="boton boton_CSS" @click="css_ejercicios"></button>
        <div v-if="hovered === 'CSS'" class="hover-text">¡Bienvenido a CSS! Descubre cómo darle estilo y personalidad a tus páginas web.</div>
      </div>
      <div class="boton-container" style="flex: 1;" @mouseenter="hovered = 'JS'" @mouseleave="hovered = ''">
        <button class="boton JAVA_SCRIP" @click="js_ejercicios"></button>
        <div v-if="hovered === 'JS'" class="hover-text">¡Bienvenido a JavaScript! Da vida a tus páginas con interactividad y dinamismo.</div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "nivels",
  data() {
    return {
      hovered: ""
    };
  },
  methods: {
    html_ejercicios() {
      this.$router.push("/nivel");
    },
    css_ejercicios() {
      this.$router.push("/nivel_css");
    },
    js_ejercicios() {
      this.$router.push("/nivel_js");
    }
  }
};
</script>

<style scoped>
.selector {
  height: 100vh;
  width: 90%;
  margin-left: auto;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  background-color: #202020;
}

h1 {
  color: white;
  text-align: center;
  margin-top: -50px;  /* Baja un poco el título */
  margin-bottom: 30px; /* Separa el título de los botones */
  font-size: 30px;
  font-weight: bold;
}

.botones {
  display: flex;
  gap: 10%;
  max-width: 1200px;
  padding: 0 20px;
  width: 80%;
  justify-content: space-between;
}

.boton-container {
  border-radius: 12px;
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 200px;
  height: 250px;
  flex-direction: column;
}

button {
  width: 100%;
  height: 100%;
  font-size: 1.5rem;
  border: none;
  border-radius: 12px;
  cursor: pointer;
  transition: opacity 0.5s, transform 0.3s;
  background-size: cover;
  background-position: center;
  position: absolute;
}

button:hover {
  transform: scale(1.05); /* Aumenta el tamaño ligeramente */
}

.hover-text {
  width: 100%;
  height: 100px;
  border-radius: 12px;
  background: linear-gradient(145deg, gray, white); 
  border: solid 4px black; /* Borde begro más delgado */
  color: #333; /* Color de texto oscuro */
  font-size: 20px; /* Aumenté el tamaño de la fuente */
  font-weight: bold;
  text-align: center;
  display: flex;
  justify-content: center;
  align-items: center;
  position: absolute;
  margin-top: 400px;
  opacity: 0; /* Inicialmente invisible */
  transform: translateY(-100%); /* Empuja el texto hacia arriba */
  transition: opacity 0.5s ease, transform 0.5s ease, box-shadow 0.3s ease; /* Añadí transición para sombra */
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2); /* Sombra sutil */
  padding: 10px;
  font-family: 'Arial', sans-serif; /* Tipografía más moderna */
}

.boton-container:hover .hover-text {
  opacity: 1; /* Se hace visible */
  transform: translateY(0); /* El texto se mueve hacia abajo */
  box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3); /* Sombra más prominente al hacer hover */
}


.HTML { background-image: url('/html.png'); }
.boton_CSS { background-image: url('/css.png'); }
.JAVA_SCRIP { background-image: url('/lion.png'); }

</style>
