<template>
    <div class="container">
      <h1 class="title">Mis Proyectos</h1>
      
      <div v-if="loading" class="loading">Cargando proyectos...</div>
  
      <div v-else-if="projects.length === 0" class="no-projects">
        No tienes proyectos aún.
      </div>
  
      <div v-else class="projects-list">
        <div v-for="project in projects" :key="project.id" class="project-card">
          <h2>{{ project.nombre }}</h2> 
          <p><strong>ID:</strong> {{ project.id }}</p>
          <p><strong>Creado en:</strong> {{ new Date(project.created_at).toLocaleDateString() }}</p>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        projects: [],
        loading: true,
        error: null,
      };
    },
    async mounted() {
      await this.fetchProjects();
    },
    methods: {
      async fetchProjects() {
        try {
          const token = localStorage.getItem("token");
          if (!token) throw new Error("No hay token guardado");
  
          const response = await fetch("http://localhost:8000/api/projects", {
            headers: {
              Authorization: `Bearer ${token}`,
              "Content-Type": "application/json",
            },
          });
  
          if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || "Error al obtener los proyectos");
          }
  
          const data = await response.json();
          this.projects = data.projects;
          this.loading = false;
        } catch (error) {
          this.error = error.message; 
          this.loading = false; 
        }
      },
    },
  };
  </script>
  
  <style scoped>
  .container {
    max-width: 1200px;
    margin: auto;
    padding: 20px;
    text-align: center;
  }
  
  .title {
    font-size: 28px;
    margin-bottom: 30px;
    font-weight: bold;
    color: #333;
  }
  
  .loading {
    font-size: 18px;
    color: #555;
  }
  
  .no-projects {
    font-size: 18px;
    color: red;
  }
  
  .projects-list {
    margin-left: vh;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 20px;
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
  
  .project-card h2 {
    font-size: 22px;
    color: #333;
    margin-bottom: 10px;
  }
  
  .project-card p {
    font-size: 16px;
    color: #555;
    margin: 5px 0;
  }
  
  .project-card strong {
    color: #333;
  }
  </style>
  