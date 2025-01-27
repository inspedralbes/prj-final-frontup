<template>
  <div class="selector">
    <h1>Selecciona tu lenguaje</h1>
    <div class="botones">
      <div class="boton-container" style="flex: 1;" @mouseenter="hovered = 'HTML'" @mouseleave="hovered = ''">
        <button class="boton HTML" @click="html_ejercicios"></button>

        <span  class="hover-text">Introducción a html: lore<br>
                                  html es blablablabla</span>

      </div>
      <div class="boton-container" style="flex: 1;" @mouseenter="hovered = 'CSS'" @mouseleave="hovered = ''">
        <button class="boton boton_CSS" @click="css_ejercicios"></button>
        <span v-if="hovered === 'CSS'" class="hover-text">CSS</span>
      </div>
      <div class="boton-container" style="flex: 1;" @mouseenter="hovered = 'JS'" @mouseleave="hovered = ''">
        <button class="boton JAVA_SCRIP" @click="js_ejercicios">JS</button>
        <span v-if="hovered === 'JS'" class="hover-text">JavaScript</span>
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
      this.$router.push("/nivel_html");
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
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  background-color: #202020;
}

h1 {
  color: white;
  text-align: center;
  margin-bottom: 20px;
  font-size: 2rem;
  font-weight: bold;
}

.botones {
  display: flex;
  gap: 20px;
  max-width: 1200px;
  padding: 0 20px;
  width: 100%;
  justify-content: space-between;
}

.boton-container {
  background-color: gray;
  border-radius: 12px;
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 200px;
  height: 250px;
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
  opacity: 0;
}

.hover-text {
  color: black;
  font-size: 20px;
  font-weight: bold;
  transition: opacity 0.5s;
  text-align: left;
}

.HTML { background-image: url('/html.png'); }
.boton_CSS { background-image: url('/css.png'); }
.JAVA_SCRIP { background-image: url('/lion.png'); }
</style>
