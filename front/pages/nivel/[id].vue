<template>
  <div class="contenedor">
    <h1>Nivel {{ nivelId }} - {{ nivelLenguage }}</h1>
    <div v-if="preguntas.length > 0">
      <div v-for="pregunta in preguntas" :key="pregunta.id" class="pregunta">
        <p>{{ pregunta.pregunta }}</p>
        <p>Respuesta correcta: {{ pregunta.resp_correcta }}</p>
      </div>
    </div>
    <div v-else>
      <p>No hay preguntas para este nivel.</p>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      preguntas: [], // Inicializar para evitar el error
    };
  },
  async asyncData({ params }) {
    try {
      const nivelLenguage = params.lenguage || "";
      const nivelId = params.id || "";

      // URL correcta con interpolación de variables
      const response = await fetch(
        `http://localhost:8000/api/preguntas/${nivelLenguage}/${nivelId}`
      );

      if (!response.ok) {
        throw new Error(`Error HTTP: ${response.status}`);
      }

      const data = await response.json();

      return {
        nivelLenguage,
        nivelId,
        preguntas: Array.isArray(data) ? data : [], // Asegurar que siempre sea un array
      };
    } catch (error) {
      console.error("Error al obtener las preguntas:", error);
      return {
        nivelLenguage: params.lenguage || "",
        nivelId: params.id || "",
        preguntas: [], // Evitar que sea undefined
      };
    }
  },
};
</script>



<style scoped>
.contenedor {
  padding: 20px;
  font-family: Arial, sans-serif;
}

.pregunta {
  margin-bottom: 20px;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 5px;
  background-color: #f9f9f9;
}

h1 {
  color: #333;
}
</style>
