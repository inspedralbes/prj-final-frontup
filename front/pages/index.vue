<template>
    <div class="home-page">
      <h1>Bienvenido a mi Proyecto</h1>
      <p>Explora la funcionalidad de este proyecto estilo CodePen.</p>
      <button @click="navigateToLliure">Ir a Lliure</button>
    </div>
  </template>
  
  <script>
  export default {
    name: 'HomePage',
    methods: {
      navigateToLliure() {
        this.$router.push('/lliure'); 
      },
    },
  };
  </script>
  
  <style scoped>
  .home-page {
    text-align: center;
    padding: 2rem;
  }
  button {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    cursor: pointer;
    border-radius: 5px;
    font-size: 1rem;
  }
  button:hover {
    background-color: #0056b3;
  }
  </style>
  