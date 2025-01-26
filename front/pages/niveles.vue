<template>
  <div class="selector">
    <h1>Niveles</h1>
    <div class="botones">
      <button class="boton HTML" @click="html_ejercicios"></button>
      <button class="boton boton_CSS" @click="css_ejercicios"></button>
      <button class="boton JAVA_SCRIP" @click="js_ejercicios">JS</button>
    </div>
  </div>
</template>

<script>
export default {
  name: "nivels",
  methods: {
    html_ejercicios() {
      this.$router.push("/nivel_html");
    },
    css_ejercicios() {
      this.$router.push("/nivel_css");
    },
    js_ejercicios() {
      this.$router.push("/nivel_js");
    },
  },
};
</script>

<style scoped>
/* General container style */
.selector {
  height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  background-size: cover;
  background-position: center;
  background-color: #202020; /* Fallback color */
}

/* Title styling */
h1 {
  color: white;
  background-color: gray;
  text-align: center;
  margin-bottom: 20px;
  padding: 10px 20px;
  border-radius: 12px;
  font-size: 2rem;
  font-weight: bold;
}

/* Buttons container */
.botones {
  display: flex;
  justify-content: space-between; /* Space between buttons */
  gap: 20px; /* Space between each button */
  width: 100%; /* Full width */
  max-width: 1200px; /* Limit the width of the container */
  padding: 0 20px;
}

/* Button general styling */
button {
  flex: 1; /* Evenly distributes width for each button */
  height: 400px; /* Taller than it is wide */
  max-width: 300px; /* Set max width for each button */
  font-size: 1.5rem;
  font-weight: bold;
  border: none;
  border-radius: 12px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: transform 0.4s cubic-bezier(0.22, 1, 0.36, 1), box-shadow 0.4s ease, background-size 0.4s ease;
  background-size: cover;
  background-position: center;
  overflow: hidden;
  position: relative;
}

/* Button hover effect */
button:hover {
  transform: scale(1.1) rotate(1deg); /* Zoom with slight rotation */
  box-shadow: 0 10px 25px rgba(255, 255, 255, 0.4); /* Glow effect */
}

/* Glow animation on hover */
button::before {
  content: "";
  position: absolute;
  top: -50%;
  left: -50%;
  width: 200%;
  height: 200%;
  background: radial-gradient(circle, rgba(255, 255, 255, 0.3), transparent 70%);
  opacity: 0;
  transition: opacity 0.4s ease, transform 0.4s ease;
  transform: scale(0);
  z-index: 0;
}

button:hover::before {
  opacity: 1;
  transform: scale(1);
}

/* Text stays on top */
button span {
  position: relative;
  z-index: 1;
}

/* Specific styles for each button */
.HTML {
  background-image: url('/html.png'); /* Replace with your image */
  color: black;
}

.boton_CSS {
  background-image: url('css3.png'); /* Replace with your image */
  color: black;
}

.JAVA_SCRIP {
  background-image: url('lion.png');
  color: black;
}

/* Responsive design */
@media (max-width: 768px) {
  .botones {
    flex-direction: column; /* Stack buttons vertically on small screens */
    gap: 15px;
  }

  button {
    height: 300px; /* Adjust height for smaller screens */
    max-width: 100%; /* Allow buttons to take full width */
  }
}
</style>
