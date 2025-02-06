<template>
  <div class="container">
    <h1 class="title">Mis Proyectos</h1>
    
    <div v-if="loading" class="loading">Cargando proyectos...</div>

    <div v-else-if="projects.length === 0" class="no-projects">
      No tienes proyectos aún.
      <br /><br />
      <button class="btn" @click="navigateToLibre">Crear el teu primer projecte</button>
    </div>

    <div v-else class="projects-list">
      <!-- Se utiliza el componente Item para cada proyecto -->
      <Item v-for="project in projects" :key="project.id" :project="project" />
    </div>
  </div>
</template>

<script>
import Item from '@/pages/item.vue';

export default {
  name: "Projectes",
  components: {
    Item,
  },
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
    navigateToLibre() {
      // Ajusta la ruta de creación si es necesario
      this.$router.push("/libre");
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
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 20px;
  margin-top: 20px;
  margin-left: 190px;
}
.btn {
  background-color: #292929;
  color: #ffffff;
  border: 2px solid #444;
  padding: 12px 18px;
  width: 40%;
  cursor: pointer;
  text-transform: uppercase;
  border-radius: 6px;
  font-weight: bold;
  transition: background 0.3s ease, transform 0.2s ease;
}
.btn:hover {
  background-color: #3d3d3d;
  transform: scale(1.05);
}
</style>
