<template>
  <div class="level-list-container">
    <div class="level-list-header">
      <h2 class="level-list-title">Nivells Publicats</h2>
      <p class="level-list-subtitle">Explora els nivells creats per la comunitat FrontApp</p>
    </div>
    
    <div class="search-filter-container">
      <div class="search-bar">
        <input 
          type="text" 
          v-model="searchQuery" 
          placeholder="🔍 Filtrar nivells..." 
          class="filter-input" 
        />
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

    <div v-if="isLoading" class="loading">Carregant nivells...</div>
    <div v-else-if="levels.length === 0" class="empty-state">Encara no hi ha cap nivell publicat.</div>
    <div v-else class="levels-grid">
      <div v-for="level in levels" :key="level.id" class="level-card">
        <h3 class="level-card-title">{{ level.title }}</h3>
        <p class="level-card-description">{{ level.description }}</p>
        <div class="level-card-meta">
          <span>Autor: {{ level.user.name }}</span>
        </div>
        <router-link :to="`/nivelUsuari/${level.id}`" class="btn btn-primary">Veure Nivell</router-link>
      </div>
    </div>
    <div class="pagination">
      <button @click="prevPage" :disabled="currentPage === 1" class="page-btn">Anterior</button>
      <span>Pàgina {{ currentPage }} de {{ totalPages }}</span>
      <button @click="nextPage" :disabled="currentPage === totalPages" class="page-btn">Següent</button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'

const levels = ref([])
const isLoading = ref(true)
const currentPage = ref(1)
const totalPages = ref(1)
const searchQuery = ref('')
const sortCriteria = ref('default')

const fetchLevels = async (page = 1) => {
  try {
    isLoading.value = true
    const params = new URLSearchParams()
    params.append('page', page)
    if (searchQuery.value) params.append('search', searchQuery.value)
    if (sortCriteria.value !== 'default') params.append('sort', sortCriteria.value)
    
    const response = await $fetch(`http://161.22.40.52/api/nivells_usuaris?${params.toString()}`, {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`,
        'Accept': 'application/json'
      }
    })
    
    levels.value = response.data || response
    if (response.meta) {
      currentPage.value = response.meta.current_page
      totalPages.value = response.meta.last_page
    } else {
      totalPages.value = 1
    }
  } catch (error) {
    console.error('Error al cargar niveles:', error)
  } finally {
    isLoading.value = false
  }
}

watch([searchQuery, sortCriteria], () => {
  currentPage.value = 1
  fetchLevels(currentPage.value)
})

const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++
    fetchLevels(currentPage.value)
  }
}

const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--
    fetchLevels(currentPage.value)
  }
}

onMounted(() => {
  fetchLevels()
})
</script>

<style scoped>
.level-list-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem;
  color: #e0e0e0;
  background-color: rgba(36, 40, 50, 0.8);
  border-radius: 8px;
  margin-top: 1rem;
}

.level-list-header {
  margin-bottom: 2rem;
  border-bottom: 1px solid #42434a;
  padding-bottom: 1rem;
}

.level-list-title {
  font-size: 1.8rem;
  color: #ffffff;
  margin-bottom: 0.5rem;
}

.level-list-subtitle {
  color: #7e8590;
  font-size: 1rem;
}

.search-filter-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
  gap: 1rem;
}

.search-bar {
  flex: 1;
  max-width: 500px;
  margin: 0 auto;
}

.filter-input {
  width: 80%;
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
.empty-state {
  text-align: center;
  font-size: 1.1rem;
  margin-top: 2rem;
  color: #aaa;
}

.levels-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 1.5rem;
}

.level-card {
  background-color: rgba(42, 46, 58, 0.7);
  border: 1px solid #42434a;
  border-radius: 6px;
  padding: 1.5rem;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.level-card-title {
  font-size: 1.2rem;
  color: #ffffff;
  margin-bottom: 0.5rem;
}

.level-card-description {
  color: #c0c0c0;
  font-size: 0.95rem;
  margin-bottom: 1rem;
}

.level-card-meta {
  font-size: 0.8rem;
  color: #888;
  margin-bottom: 1rem;
}

.btn {
  align-self: flex-start;
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 4px;
  font-weight: 500;
  text-decoration: none;
  color: white;
  background-color: #5353ff;
  transition: background-color 0.2s;
}

.btn:hover {
  background-color: #4343df;
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

@media (max-width: 450px) {
  .level-list-container {
    padding: 2rem;
    margin-top: 0.5rem;
    border-radius: 6px;
  }

  .level-list-title {
    font-size: 1.4rem;
    text-align: center;
  }

  .level-list-subtitle {
    font-size: 0.9rem;
    text-align: center;
  }

  .search-filter-container {
    flex-direction: column;
    align-items: stretch;
    gap: 0.75rem;
  }

  .search-bar {
    max-width: 100%;
    width: 100%;
  }

  .filter-input {
    width: 100%;
    font-size: 0.95rem;
    padding: 0.6rem;
  }

  .sort-container {
    flex-direction: column;
    align-items: stretch;
  }

  .sort-label {
    font-size: 0.95rem;
    margin-bottom: 4px;
  }

  .sort-select {
    width: 100%;
    padding: 0.6rem;
    font-size: 0.95rem;
  }

  .levels-grid {
    grid-template-columns: 1fr;
    gap: 1rem;
  }

  .level-card {
    padding: 1rem;
  }

  .level-card-title {
    font-size: 1.1rem;
  }

  .level-card-description {
    font-size: 0.9rem;
  }

  .level-card-meta {
    font-size: 0.75rem;
  }

  .btn {
    width: 100%;
    padding: 0.6rem;
    text-align: center;
    font-size: 0.95rem;
  }

  .pagination {
    flex-wrap: wrap;
    gap: 10px;
    padding-top: 1rem;
  }

  .page-btn {
    font-size: 0.9rem;
    padding: 8px 12px;
  }
}

</style>