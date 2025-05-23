<template>
  <div class="container">

    <h1 class="title">Els meus projectes</h1>

    <div class="header">
      <div class="search-bar">
        <input type="text" v-model="searchQuery" placeholder="🔍 Filtrar projectes..." class="filter-input" />
      </div>
      <div class="sort-container">
        <label for="sort" class="sort-label">Ordenar per:</label>
        <select id="sort" v-model="sortCriteria" class="sort-select">
          <option value="default">Destacats</option>
          <option value="popular">Més Populars</option>
          <option value="date_asc">Data: Més antics primer</option>
          <option value="date_desc">Data: Més recents primer</option>
        </select>
      </div>
    </div>

    <div v-if="loading" class="loading">Carregant projectes...</div>
    <div v-else-if="error" class="error">{{ error }}</div>
    <div v-else>
      <div v-if="projects.length === 0" class="no-projects">
        No hi ha projectes disponibles.
      </div>
      <div class="projects-list">
        <div v-for="project in projects" :key="project.id" class="project-card">
          <Item :project="project" />
          <button @click="deleteProject(project.id)" class="delete-btn">🗑️ Eliminar</button>
        </div>
      </div>

      <div class="pagination">
        <button @click="prevPage" :disabled="currentPage === 1" class="page-btn">
          Anterior
        </button>
        <span>Pàgina {{ currentPage }} de {{ totalPages }}</span>
        <button @click="nextPage" :disabled="currentPage === totalPages" class="page-btn">
          Següent
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import Item from '~/components/item.vue';
import useCommunicationManager from '~/stores/comunicationManager';

export default {
  name: "Projectes",
  components: {
    Item,
  },
  data() {
    return {
      projects: [],
      searchQuery: "",
      sortCriteria: "default",
      loading: true,
      error: null,
      currentPage: 1,
      totalPages: 1,
    };
  },
  watch: {
    searchQuery: 'resetAndFetch',
    sortCriteria: 'resetAndFetch'
  },
  async mounted() {
    this.communicationManager = useCommunicationManager();
    await this.fetchProjects();
  },
  methods: {
    resetAndFetch() {
      this.currentPage = 1;
      this.fetchProjects();
    },
    async fetchProjects(page = 1) {
      this.loading = true;

      const result = await this.communicationManager.fetchProjects({
        page,
        searchQuery: this.searchQuery,
        sortCriteria: this.sortCriteria,
      });

      if (result.success) {
        const data = result.data;
        this.projects = data.data;
        this.currentPage = data.current_page;
        this.totalPages = data.last_page;
        this.error = null;
      } else {
        this.error = result.message;
      }

      this.loading = false;
    },
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
        this.fetchProjects(this.currentPage);
      }
    },
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
        this.fetchProjects(this.currentPage);
      }
    },
    async deleteProject(id) {
      if (!confirm("¿Seguro que quieres eliminar este proyecto?")) return;

      try {
        await this.communicationManager.borrarProyectoDB(id);
        this.fetchProjects(this.currentPage);
      } catch (err) {
        alert("Error eliminando el proyecto: " + err.message);
      }
    },
  },
};
</script>

<style scoped>
.container {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  max-width: 100%;
  margin: auto;
  padding: 30px;
  text-align: center;

  box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
}

.title {
  color: #f3f4f6;
  text-align: left;
  margin-bottom: 20px;
  font-size: 2rem;
  font-weight: bold;
}

.header {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
  gap: 20px;
}

.search-bar {
  flex-grow: 1;
  max-width: 400px;
}

.filter-input {
  width: 100%;
  padding: 10px;
  font-size: 16px;
  border-radius: 8px;
  border: 1px solid #444;
  outline: none;
  background-color: #434952;
  color: #e5e7eb;
}

.sort-container {
  display: flex;
  align-items: center;
  gap: 10px;
}

.sort-label {
  font-size: 16px;
  color: #e5e7eb;
}

.sort-select {
  padding: 10px;
  font-size: 16px;
  border-radius: 8px;
  border: 1px solid #555;
  outline: none;
  background-color: #434952;
  color: #f3f4f6;
  cursor: pointer;
}

.loading,
.error {
  font-size: 18px;
  color: #f87171;
}

.no-projects {
  font-size: 18px;
  color: #facc15;
}

.projects-list {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 30px;
  padding: 10px 0;
}

.delete-btn {
  margin-top: 10px;
  padding: 8px 14px;
  background-color: #ef4444;
  color: white;
  border: none;
  border-radius: 6px;
  font-weight: bold;
  cursor: pointer;
  transition: background-color 0.3s;
}

.delete-btn:hover {
  background-color: #dc2626;
}

.pagination {
  margin-top: auto;
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 15px;
  color: #e5e7eb;
  padding-top: 20px;
}

.page-btn {
  padding: 10px 15px;
  background-color: #3b3b3b;
  color: #e2e8f0;
  border: 1px solid #555;
  border-radius: 6px;
  cursor: pointer;
  transition: background-color 0.2s;
}

.page-btn:disabled {
  background-color: #555;
  color: #999;
  cursor: not-allowed;
}

.page-btn:hover:not(:disabled) {
  background-color: #525252;
}

@media (max-width: 450px) {
  .container {
    display: flex;
    flex-direction: column;
    min-height: 80vh;
    max-width: 170%;
    padding: 30px;
    padding-top: 80px;
    text-align: center;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
  }

  .header {
    display: flex;
    flex-direction: column;
    gap: 15px;
    padding: 10px;
    align-items: stretch;
  }

  .search-bar,
  .sort-container {
    width: 100%;
  }

  .filter-input,
  .sort-select {
    width: 100%;
    max-width: 100%;
    padding: 10px;
    font-size: 16px;
    border-radius: 5px;
    border: none;
    background-color: #434952;
    color: #ddd;
  }

  .sort-label {
    display: block;
    margin-bottom: 5px;
    color: #ccc;
    text-align: left;
  }

  .projects-list {
    display: grid;
    grid-template-columns: repeat(1, 1fr);
    gap: 30px;
    padding: 10px;
  }
}
</style>