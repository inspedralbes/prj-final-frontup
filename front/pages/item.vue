<template>
    <!-- Envolvemos la tarjeta en un router-link para hacerla clicable -->
    <router-link :to="`/proyecto/${project.id}`" class="project-card-link">
      <div class="project-card">
        <h2>{{ project.nombre }}</h2>
        <p><strong>ID:</strong> {{ project.id }}</p>
        <p><strong>Creado en:</strong> {{ formattedDate }}</p>
      </div>
    </router-link>
  </template>
  
  <script>
  export default {
    name: "Item",
    props: {
      project: {
        type: Object,
        required: true,
      },
    },
    computed: {
      formattedDate() {
        return new Date(this.project.created_at).toLocaleDateString();
      },
    },
  };
  </script>
  
  <style scoped>
  .project-card-link {
    text-decoration: none;
    color: inherit;
  }
  .project-card {
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background: #f9f9f9;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }
  .project-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
  }
  </style>
  