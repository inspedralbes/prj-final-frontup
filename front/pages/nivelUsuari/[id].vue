<template>
    <div class="level-viewer-container" v-if="level">
      <h2 class="level-title">{{ level.title }}</h2>
      <p class="level-description">{{ level.description }}</p>
  
      <div class="code-sections">
        <div>
          <h3>HTML Inicial</h3>
          <textarea v-model="editableHtml" class="code-input html-input"></textarea>
        </div>
        <div>
          <h3>CSS Inicial</h3>
          <textarea v-model="editableCss" class="code-input css-input"></textarea>
        </div>
        <div>
          <h3>JS Inicial</h3>
          <textarea v-model="editableJs" class="code-input js-input"></textarea>
        </div>
      </div>
  
      <h3>Vista Previa</h3>
      <iframe class="preview-frame" :srcdoc="previewContent" sandbox="allow-scripts allow-same-origin"></iframe>
  
      <div class="verify-container">
        <button @click="checkLevel" class="verify-button">Verificar Nivel</button>
      </div>
      
    </div>
  
    <div v-else class="loading">Cargando nivel...</div>
  </template>
  
  <script setup>
  import { ref, onMounted, computed } from 'vue'
  import { useRoute, useRouter } from 'vue-router'
  import Swal from 'sweetalert2' 
  
  const route = useRoute()
  const router = useRouter()
  const level = ref(null)
  
  const editableHtml = ref('')
  const editableCss = ref('')
  const editableJs = ref('')
  
  const fetchLevel = async () => {
    try {
      const response = await $fetch(`https://back.frontapp.cat/api/nivells_usuaris/${route.params.id}`, {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('token')}`,
          'Accept': 'application/json'
        }
      })
      level.value = response
  
      editableHtml.value = response.initial_html
      editableCss.value = response.initial_css
      editableJs.value = response.initial_js
    } catch (error) {
      console.error('Error cargando el nivel:', error)
    }
  }
  
  onMounted(fetchLevel)
  
  const previewContent = computed(() => {
    return `
      <!DOCTYPE html>
      <html>
        <head>
          <style>${editableCss.value}</style>
        </head>
        <body>
          ${editableHtml.value}
          <script>${editableJs.value}<\/script>
        </body>
      </html>
    `
  })
  
  const checkLevel = () => {
    const isCorrect =
      editableHtml.value === level.value.expected_html &&
      editableCss.value === level.value.expected_css &&
      editableJs.value === level.value.expected_js
  
    if (isCorrect) {
      
      Swal.fire({
        title: '¡Nivel completado correctamente!',
        text: '¡Felicidades! 🎉',
        icon: 'success',
        confirmButtonText: 'OK'
      }).then((result) => {
    if (result.isConfirmed) {
      router.push('/nivelesUsuarios')
    }
  })
    } else {
      
      Swal.fire({
        title: 'El nivel no es correcto',
        text: 'Por favor, revisa tu código y vuelve a intentarlo.',
        icon: 'error',
        confirmButtonText: 'Revisar'
      })
    }
  }
  </script>
  
  <style scoped>
  .level-viewer-container {
    max-width: 1000px;
    margin: 2rem auto;
    padding: 2rem;
    background: rgba(36, 40, 50, 0.8);
    border-radius: 8px;
    color: #e0e0e0;
  }
  
  .level-title {
    font-size: 2rem;
    margin-bottom: 0.5rem;
  }
  
  .level-description {
    color: #ccc;
    margin-bottom: 2rem;
  }
  
  .code-sections {
    display: grid;
    gap: 1.5rem;
    grid-template-columns: 1fr;
    margin-bottom: 2rem;
  }
  
  .code-sections > div {
    display: flex;
    flex-direction: column;
  }
  
  h3 {
    margin-bottom: 0.5rem;
    font-size: 1.4rem;
    color: #b0b0b0;
  }
  
  .code-input {
    width: 100%;
    height: 200px;
    padding: 10px;
    margin-bottom: 1rem;
    background-color: #2d323a;
    color: #f8f8f2;
    border: 1px solid #444;
    border-radius: 6px;
    font-family: "Courier New", Courier, monospace;
    font-size: 1rem;
    resize: vertical;
  }
  
  .preview-frame {
    width: 100%;
    height: 400px;
    border: 1px solid #42434a;
    border-radius: 4px;
    background-color: #1d2027;
    margin-top: 2rem;
  }
  
  .loading {
    text-align: center;
    margin-top: 4rem;
    font-size: 1.2rem;
    color: #999;
  }
  
  .verify-container {
  margin-top: 2rem;
  display: flex;
  justify-content: center;
  gap: 1rem; 
  }
  
  .verify-button {
    background-color: #4caf50;
    color: white;
    padding: 12px 24px;
    border: none;
    border-radius: 6px;
    font-size: 1rem;
    cursor: pointer;
  }
  
  .verify-button:hover {
    background-color: #45a049;
  }
  </style>
  