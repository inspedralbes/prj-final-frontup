<template>
    <div class="contenedor">
      <h1>Nivel {{ levelId }}</h1>
      <p>Bienvenido al nivel {{ levelId }}</p>
    </div>

     <!-- Encabezado -->
     <header class="header">
      <button class="header-button" @click="goBack">Atrás</button>
      <input type="text" v-model="title" class="header-title" @focus="isEditing = true" @blur="isEditing = false"
        :readonly="!isEditing" />
      <div class="header-actions">
        <button class="header-button" @click="guardarProyecto">Guardar</button>
        <button class="header-button" @click="openSettingsModal">Configuración</button>
        <button class="header-button">💡</button>
      </div>
    </header>
    
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
  .contenedor{
    position: relative;
  height: 99vh;
  width: 90%;
  margin-left: auto;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  background-image: url(/fondo.png); 
  border: 1px solid #ddd;
  border-radius: 10px;
  background-size: cover;
  background-position: center;
  }
  /* Estilos personalizados para cada nivel */
  h1 {
    font-size: 2rem;
    color: #4caf50;
  }
  </style>
  