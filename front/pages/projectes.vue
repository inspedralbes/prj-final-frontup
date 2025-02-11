<template>
  <div class="container">
    <h1 class="title">Mis Proyectos</h1>
    
    <!-- Selector de ordenación con nuevo estilo -->
    <div class="sort-container">
      <label for="sort" class="sort-label">Ordenar per:</label>
      <select id="sort" v-model="sortCriteria" class="sort-select">
        <option value="default">Destacats</option>
        <option value="date_asc">Data: Més antics primer</option>
        <option value="date_desc">Data: Méss recents primer</option>
      </select>
    </div>

    <div v-if="loading" class="loading">Cargant projectes...</div>

    <div v-else-if="sortedProjects.length === 0" class="no-projects">
      No tens projectes encara.
      <br /><br />
      <button class="btn" @click="navigateToLibre">Crear el teu primer projecte</button>
    </div>

    <div v-else class="projects-list">
      <Item v-for="project in sortedProjects" :key="project.id" :project="project" />
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
      sortCriteria: "default",
    };
  },
  computed: {
    sortedProjects() {
      let sorted = [...this.projects];

      if (this.sortCriteria === "date_asc") {
        return sorted.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
      } 
      if (this.sortCriteria === "date_desc") {
        return sorted.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
      }
      return sorted;
    }
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
      this.$router.push("/lliure");
    }
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

.sort-container {
  display: flex;
  justify-content: flex-end; 
  align-items: center;
  margin-bottom: 15px;
  padding-right: 20px;
}

.sort-label {
  font-size: 16px;
  font-weight: bold;
  color: #ddd;
  margin-right: 10px;
}

.sort-select {
  background-color: #292929;
  color: #ffffff;
  border: 2px solid #444;
  padding: 10px 15px;
  font-size: 14px;
  font-weight: bold; 
  border-radius: 6px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.sort-select:hover {
  background-color: #3d3d3d;
  transform: scale(1.05);
}

</style>
