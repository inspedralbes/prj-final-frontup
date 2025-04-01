<template>
  <div class="selector">
    <h1>Anem a practicar</h1>
    <div class="botones">
      <div class="boton-container" style="flex: 1;" @mouseenter="hovered = 'HTML'" @mouseleave="hovered = ''">
        <button class="boton HTML" @click="html_ejercicios"></button>
        <div v-if="hovered === 'HTML'" class="hover-text">Benvingut al món de l'HTML! Aprèn a estructurar pàgines web de manera senzilla i eficient.</div>
      </div>
      <div class="boton-container" style="flex: 1;" @mouseenter="hovered = 'CSS'" @mouseleave="hovered = ''">
        <button class="boton boton_CSS" @click="css_ejercicios"></button>
        <div v-if="hovered === 'CSS'" class="hover-text">Benvingut al CSS! Descobreix com donar estil i personalitat a les teves pàgines web.</div>
      </div>
      <div class="boton-container" style="flex: 1;" @mouseenter="hovered = 'JS'" @mouseleave="hovered = ''">
        <button class="boton JAVA_SCRIP" @click="js_ejercicios"></button>
        <div v-if="hovered === 'JS'" class="hover-text">Benvingut a JavaScript! Dona vida a les teves pàgines amb interactivitat i dinamisme.</div>
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
  margin-top: -50px; 
  margin-bottom: 30px; 
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
  background-color: #202020;
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
  transform: scale(1.10); 
}

.hover-text {
  width: 100%;
  height: 100px;
  border-radius: 12px;
  background: linear-gradient(145deg, gray, white); 
  border: solid 4px black; 
  color: #333; 
  font-size: 20px; 
  font-weight: bold;
  text-align: center;
  display: flex;
  justify-content: center;
  align-items: center;
  position: absolute;
  margin-top: 400px;
  opacity: 0;
  transform: translateY(-100%); 
  transition: opacity 0.5s ease, transform 0.5s ease, box-shadow 0.3s ease; 
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
  padding: 10px;
  font-family: 'Arial', sans-serif; 
}

.boton-container:hover .hover-text {
  opacity: 1;
  transform: translateY(0); 
  box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3); 
}


.HTML { background-image: url('/html.svg'); }       
.boton_CSS { background-image: url('/css.svg'); }
.JAVA_SCRIP { background-image: url('/js.svg'); }

</style>
