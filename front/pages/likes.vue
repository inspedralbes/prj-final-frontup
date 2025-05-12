<template>
    <div class="container">
        <h1>Projectes públics</h1>
        <div class="header">
            <div class="search-bar">
                <input type="text" v-model="searchQuery" placeholder="🔍 Filtrar projectes..." class="filter-input" />
            </div>
            <div class="sort-container">
                <label for="sort" class="sort-label">Ordenar per:</label>
                <select id="sort" v-model="sortCriteria" class="sort-select">
                    <option value="default">Destacats</option>
                    <option value="date_asc">Data: Més antics primer</option>
                    <option value="date_desc">Data: Més recents primer</option>
                </select>
            </div>
        </div>

        <div v-if="loading" class="loading">Carregant...</div>
        <div v-else-if="error" class="error">{{ error }}</div>
        <div v-else>
            <div v-if="paginatedProjects.length === 0" class="no-projects">
                No hi ha projectes disponibles.
            </div>
            <div class="projects-list">
                <Item v-for="project in paginatedProjects" :key="project.project.id" :project="project.project" />
            </div>

            <div class="pagination">
                <button @click="prevPage" :disabled="currentPage === 1" class="page-btn">Anterior</button>
                <span>Pàgina {{ currentPage }} de {{ totalPages }}</span>
                <button @click="nextPage" :disabled="currentPage === totalPages" class="page-btn">Següent</button>
            </div>
        </div>
    </div>
</template>

<script>
import Item from "~/components/item.vue";
import useCommunicationManager from "../stores/comunicationManager"
export default {
    name: "TotsProjectes",
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
            projectsPerPage: 9,
        };
    },
    computed: {
        filteredProjects() {
            let filtered = this.projects.filter((project) =>
                project.project.nombre.toLowerCase().includes(this.searchQuery.toLowerCase())
            );

            if (this.sortCriteria === "date_desc") {
                return filtered.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
            } else if (this.sortCriteria === "date_asc") {
                return filtered.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
            } else {
                return filtered;
            }
        },
        totalPages() {
            return Math.ceil(this.filteredProjects.length / this.projectsPerPage);
        },
        paginatedProjects() {
            const start = (this.currentPage - 1) * this.projectsPerPage;
            const end = start + this.projectsPerPage;
            return this.filteredProjects.slice(start, end);
        },
    },
    async mounted() {
        await this.fetchProjects();
    },
    methods: {
        async fetchProjects() {
            try {
                const data = await useCommunicationManager().getUserAllLikes();
                console.log(data);
                
                this.projects = data;
                this.loading = false;
            } catch (error) {
                this.error = error.message;
                this.loading = false;
            }
        },
        nextPage() {
            if (this.currentPage < this.totalPages) this.currentPage++;
        },
        prevPage() {
            if (this.currentPage > 1) this.currentPage--;
        },
    },
};
</script>

<style scoped>
.container {
  max-width: 100%;
  margin: auto;
  padding: 30px;
  text-align: center;
  border-radius: 10px;
  box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
  height: 100vh;
}

h1 {
  color: #f3f4f6;
  text-align: left;
  margin-bottom: 20px;
  font-size: 2rem;
  font-weight: bold;
}

.header {
  display: flex;
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
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 20px;
}

.pagination {
  margin-top: 20px;
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 15px;
  color: #e5e7eb;
  padding-top: 20px;
}

.page-btn {
  padding: 10px 15px;
  background-color: #444;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  transition: background-color 0.2s;
}

.page-btn:disabled {
  background-color: #666;
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
  margin-left: -90%;
  padding: 30px;
  padding-top: 80px;
  text-align: center;
  background-color: #252323;
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
    background-color: #3a3a3a;
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