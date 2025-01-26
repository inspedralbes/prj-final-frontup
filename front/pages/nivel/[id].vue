<template>
    <div>
      <h1>Nivel {{ levelId }}</h1>
      <p>Bienvenido al nivel {{ levelId }}</p>
    </div>
  </template>
  
  <script>
  export default {
    async asyncData({ params }) {
      // 'params.id' captura el valor del parámetro dinámico de la URL
      return {
        levelId: params.id, // Guardamos el valor dinámico para usar en el template
      };
    },
  };
  </script>
  
  <style scoped>
  /* Estilos personalizados para cada nivel */
  h1 {
    font-size: 2rem;
    color: #4caf50;
  }
  </style>
  