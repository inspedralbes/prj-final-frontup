<template>
    <div class="level-list-container">
      <div class="level-list-header">
        <h2 class="level-list-title">Niveles Publicados</h2>
        <p class="level-list-subtitle">Explora los niveles creados por la comunidad FrontUp</p>
      </div>
  
      <div v-if="isLoading" class="loading">Cargando niveles...</div>
      <div v-else-if="levels.length === 0" class="empty-state">No hay niveles publicados todavía.</div>
      <div v-else class="levels-grid">
        <div v-for="level in levels" :key="level.id" class="level-card">
          <h3 class="level-card-title">{{ level.title }}</h3>
          <p class="level-card-description">{{ level.description }}</p>
          <div class="level-card-meta">
            <span>Autor ID: {{ level.user_id }}</span>
          </div>
          <router-link :to="`/nivelUsuari/${level.id}`" class="btn btn-primary">Ver Nivel</router-link>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue'
  
  const levels = ref([])
  const isLoading = ref(true)
  
  onMounted(async () => {
    try {
      const response = await $fetch('http://localhost:8000/api/nivells_usuaris', {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('token')}`,
          'Accept': 'application/json'
        }
      })
      levels.value = response
    } catch (error) {
      console.error('Error al cargar niveles:', error)
    } finally {
      isLoading.value = false
    }
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
  </style>
  