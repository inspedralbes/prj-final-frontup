<template>
  <div class="level-creator-container">
    <div class="level-creator-header">
      <h2 class="level-creator-title">Crear Nuevo Nivel</h2>
      <p class="level-creator-subtitle">Comparte tu conocimiento con la comunidad FrontUp</p>
    </div>

    <form @submit.prevent="handleSubmit" class="level-creator-form">
      <div class="form-section">
        <div class="form-group">
          <label for="level-title" class="form-label">Título del Nivel</label>
          <input
            id="level-title"
            v-model="level.title"
            type="text"
            required
            class="form-input"
            placeholder="Ej: Crear un layout responsive con CSS Grid"
          />
        </div>

        <div class="form-group">
          <label for="level-description" class="form-label">Descripción</label>
          <textarea
            id="level-description"
            v-model="level.description"
            required
            rows="3"
            class="form-textarea"
            placeholder="Describe en detalle el objetivo del nivel y los conceptos que se practicarán"
          ></textarea>
        </div>
      </div>

      <div class="form-section">
        <h3 class="section-title">Código Inicial</h3>
        <p class="section-description">Define el código inicial que verán los usuarios al comenzar el nivel</p>
        <div class="code-editors-grid">
          <div class="editor-section">
            <label class="form-label">HTML Inicial</label>
            <textarea v-model="level.initialHTML" class="code-textarea" spellcheck="false"></textarea>
          </div>
          <div class="editor-section">
            <label class="form-label">CSS Inicial</label>
            <textarea v-model="level.initialCSS" class="code-textarea" spellcheck="false"></textarea>
          </div>
          <div class="editor-section">
            <label class="form-label">JS Inicial</label>
            <textarea v-model="level.initialJS" class="code-textarea" spellcheck="false"></textarea>
          </div>
        </div>
      </div>

      <div class="form-section">
        <h3 class="section-title">Solución Esperada</h3>
        <p class="section-description">Opcional: Define el código solución para validación automática</p>
        <div class="editor-section">
          <label class="form-label">HTML Esperado</label>
          <textarea v-model="level.expectedHTML" class="code-textarea" spellcheck="false"></textarea>
        </div>
        <div class="editor-section">
          <label class="form-label">CSS Esperado</label>
          <textarea v-model="level.expectedCSS" class="code-textarea" spellcheck="false"></textarea>
        </div>
        <div class="editor-section">
          <label class="form-label">JS Esperado</label>
          <textarea v-model="level.expectedJS" class="code-textarea" spellcheck="false"></textarea>
        </div>
      </div>

      <div class="form-section">
        <h3 class="section-title">Vista Previa</h3>
        <iframe
          class="preview-frame"
          sandbox="allow-scripts allow-same-origin"
          :srcdoc="previewContent"
        ></iframe>
      </div>

      <div class="form-actions">
        <button type="button" class="btn btn-secondary" @click="handleCancel">Cancelar</button>
        <button type="submit" class="btn btn-primary" :disabled="isSubmitting">
          <span v-if="isSubmitting">Publicando...</span>
          <span v-else>Publicar Nivel</span>
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAppStore } from '@/stores/app'
import Swal from 'sweetalert2'

const router = useRouter()
const appStore = useAppStore()

const isSubmitting = ref(false)

const level = ref({
  title: '',
  description: '',
  difficulty: 'intermediate',
  tags: [],
  initialHTML: '',
  initialCSS: '',
  initialJS: '',
  expectedHTML: '',
  expectedCSS: '',
  expectedJS: '',
  authorId: appStore.user?.id || null
})

const previewContent = computed(() => {
  return `
    <!DOCTYPE html>
    <html>
      <head>
        <style>${level.value.initialCSS}</style>
      </head>
      <body>
        ${level.value.initialHTML}
        <script>${level.value.initialJS}<\/script>
      </body>
    </html>
  `
})

const showNotification = (message, type = 'success') => {
  if (type === 'error') {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: message,
    })
  } else {
    Swal.fire({
      icon: 'success',
      title: 'Éxito',
      text: message,
    })
  }
}

const handleSubmit = async () => {
  if (!appStore.isLoggedIn) {
    showNotification('Debes iniciar sesión para crear niveles', 'error')
    return router.push('/login')
  }

  isSubmitting.value = true

  try {
    const response = await $fetch('http://localhost:8000/api/nivells_usuaris', {
      method: 'POST',
      body: JSON.stringify({
        title: level.value.title,
        description: level.value.description,
        difficulty: level.value.difficulty,
        initial_html: level.value.initialHTML,
        initial_css: level.value.initialCSS,
        initial_js: level.value.initialJS,
        expected_html: level.value.expectedHTML,
        expected_css: level.value.expectedCSS,
        expected_js: level.value.expectedJS
      }),
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`,
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    
    showNotification('Nivel creado exitosamente!', 'success')
    router.push('/niveles')
  } catch (error) {
    console.error('Error creating level:', error)
    let errorMsg = 'Error al crear el nivel'
    
    if (error.data) {
      errorMsg = error.data.message || JSON.stringify(error.data)
    } else if (error.response?.data) {
      errorMsg = error.response.data.message || JSON.stringify(error.response.data)
    }
    
    showNotification(errorMsg, 'error')
  } finally {
    isSubmitting.value = false
  }
}

const handleCancel = () => {
  Swal.fire({
    title: '¿Seguro que quieres cancelar?',
    text: 'Los cambios no se guardarán.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, cancelar',
    cancelButtonText: 'No, regresar'
  }).then((result) => {
    if (result.isConfirmed) {
      router.push('/')
    }
  })
}

onMounted(() => {
  level.value.initialHTML = `<!-- Escribe tu HTML aquí -->
<div class="card">
  <h1>Hola Mundo</h1>
  <p>Este es un nivel básico</p>
</div>`

  level.value.initialCSS = `/* Estilos básicos */
.card {
  padding: 1rem;
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}`

  level.value.initialJS = `// Código JavaScript opcional
console.log('Nivel iniciado')`
})
</script>

<style scoped>
.level-creator-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem;
  color: #e0e0e0;
  background-color: rgba(36, 40, 50, 0.8);
  border-radius: 8px;
  margin-top: 1rem;
}

.level-creator-header {
  margin-bottom: 2rem;
  border-bottom: 1px solid #42434a;
  padding-bottom: 1rem;
}

.level-creator-title {
  font-size: 1.8rem;
  color: #ffffff;
  margin-bottom: 0.5rem;
}

.level-creator-subtitle {
  color: #7e8590;
  font-size: 1rem;
}

.form-section {
  margin-bottom: 2rem;
  padding: 1.5rem;
  background-color: rgba(42, 46, 58, 0.5);
  border-radius: 6px;
  border: 1px solid #42434a;
}

.section-title {
  font-size: 1.3rem;
  color: #ffffff;
  margin-bottom: 0.5rem;
}

.section-description {
  color: #7e8590;
  font-size: 0.9rem;
  margin-bottom: 1.5rem;
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-label {
  display: block;
  margin-bottom: 0.5rem;
  color: #a0a8c0;
  font-weight: 500;
}

.form-input, .form-textarea, .code-textarea {
  width: 100%;
  padding: 0.75rem;
  background-color: rgba(29, 32, 39, 0.8);
  border: 1px solid #42434a;
  border-radius: 4px;
  color: #e0e0e0;
  font-family: 'Consolas', 'Monaco', monospace;
  transition: border-color 0.2s;
}

.form-input:focus, .form-textarea:focus, .code-textarea:focus {
  outline: none;
  border-color: #5353ff;
}

.form-textarea {
  min-height: 100px;
  resize: vertical;
}

.code-textarea {
  min-height: 200px;
  resize: vertical;
  white-space: pre;
}

.code-editors-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 1.5rem;
  margin-bottom: 1.5rem;
}

.editor-section {
  background-color: rgba(29, 32, 39, 0.9);
  border-radius: 4px;
  overflow: hidden;
}

.preview-frame {
  width: 100%;
  height: 400px;
  border: 1px solid #42434a;
  border-radius: 4px;
  background-color: #1d2027;
}

.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
  margin-top: 2rem;
}

.btn {
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 4px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

.btn-primary {
  background-color: #5353ff;
  color: white;
}

.btn-primary:hover {
  background-color: #4343df;
}

.btn-primary:disabled {
  background-color: #5353ff80;
  cursor: not-allowed;
}

.btn-secondary {
  background-color: #42434a;
  color: #e0e0e0;
}

.btn-secondary:hover {
  background-color: #53535a;
}
</style>