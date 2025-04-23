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
        <Item v-for="project in projects" :key="project.id" :project="project" />
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
    searchQuery() {
      this.currentPage = 1;
      this.fetchProjects();
    },
    sortCriteria() {
      this.currentPage = 1;
      this.fetchProjects();
    },
  },
  async mounted() {
    await this.fetchProjects();
  },
  methods: {
    async fetchProjects(page = 1) {
      try {
        this.loading = true;
        const url = new URL("http://localhost:8000/api/projects");
        url.searchParams.append("page", page);
        if (this.searchQuery) {
          url.searchParams.append("search", this.searchQuery);
        }
        if (this.sortCriteria && this.sortCriteria !== "default") {
          url.searchParams.append("sort", this.sortCriteria);
        }

        const token = localStorage.getItem("token");
        if (!token) throw new Error("No hay token guardado");

        const response = await fetch(url, {
          headers: {
            Authorization: `Bearer ${token}`,
            "Content-Type": "application/json",
          },
        });

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
  padding-top: 80px;
  text-align: center;
  background-color: #252323;
  border-radius: 10px;
  box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
}

.title {
  color: white;
  text-align: left;
  margin-bottom: 20px;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
  padding: 10px;
}

.search-bar {
  width: 200vh;
  align-self: flex-start;
}

.filter-input {
  width: 100%;
  max-width: 400px;
  padding: 10px;
  font-size: 16px;
  border-radius: 5px;
  border: none;
  outline: none;
  background-color: #3a3a3a;
  color: #ddd;
}

.sort-container {
  display: flex;
  align-items: center;
  gap: 10px;
}

.sort-label {
  font-size: 16px;
  color: #fff;
}

.sort-select {
  padding: 10px;
  font-size: 16px;
  border-radius: 5px;
  border: none;
  outline: none;
  background-color: #2c2c2c;
  color: #fff;
  cursor: pointer;
}

.loading,
.error {
  font-size: 18px;
  color: #ff4d4d;
}

.no-projects {
  font-size: 18px;
  color: #ffb74d;
}

.projects-list {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 30px;
  padding: 10px;
}

.pagination {
  margin-top: auto;
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 15px;
  color: white;
  padding-top: 10px;
}

.page-btn {
  padding: 10px 15px;
  background-color: #444;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: 0.2s;
}

.page-btn:disabled {
  background-color: #666;
  cursor: not-allowed;
}

.page-btn:hover:not(:disabled) {
  background-color: #777;
}
</style>