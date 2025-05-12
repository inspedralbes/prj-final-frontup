<template>
  <div class="container">
    <h1 class="title">Projectes públics</h1>
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
    <div class="content">
      <div v-if="loading" class="loading">Carregant...</div>
      <div v-else-if="error" class="error">{{ error }}</div>
      <div v-else>
        <div v-if="projects.length === 0" class="no-projects">
          No hi ha projectes disponibles.
        </div>
        <div class="projects-list">
          <Item v-for="project in projects" :key="project.id" :project="project" />
        </div>
      </div>
    </div>
    <div class="pagination">
      <button @click="prevPage" :disabled="currentPage === 1" class="page-btn">Anterior</button>
      <span>Pàgina {{ currentPage }} de {{ totalPages }}</span>
      <button @click="nextPage" :disabled="currentPage === totalPages" class="page-btn">Següent</button>
    </div>
  </div>
</template>

<script>
import Item from "~/components/item.vue";
export default {
  name: "TotsProjectes",
  components: { Item },
  data() {
    return {
      projects: [],
      searchQuery: "",
      sortCriteria: "default",
      loading: true,
      error: null,
      currentPage: 1,
      totalPages: 1
    };
  },
  async mounted() {
    await this.fetchProjects(this.currentPage);
  },
  watch: {
    searchQuery() {
      this.currentPage = 1;
      this.fetchProjects(this.currentPage);
    },
    sortCriteria() {
      this.currentPage = 1;
      this.fetchProjects(this.currentPage);
    }
  },
  methods: {
    async fetchProjects(page = 1) {
      this.loading = true;
      const params = new URLSearchParams();
      params.append("page", page);
      if (this.searchQuery) params.append("search", this.searchQuery);
      if (this.sortCriteria && this.sortCriteria !== "default") params.append("sort", this.sortCriteria);
      try {
        const response = await fetch(`http://localhost:8000/api/projects/all?${params.toString()}`, { headers: { "Content-Type": "application/json" } });
        if (!response.ok) {
          const errorData = await response.json();
          throw new Error(errorData.message || "Error carregant projectes");
        }
        const data = await response.json();
        this.projects = data.data;
        this.currentPage = data.current_page;
        this.totalPages = data.last_page;
        this.loading = false;
      } catch (error) {
        this.error = error.message;
        this.loading = false;
      }
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
    }
  }
};
</script>

<style scoped>
.container {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
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

</style>
