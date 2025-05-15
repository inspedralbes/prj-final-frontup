<template>
  <div class="level-creator-container">
    <div class="level-creator-header">
      <h2 class="level-creator-title">Crear Nou Nivell</h2>
      <p class="level-creator-subtitle">Comparteix el teu coneixement amb la comunitat FrontUp</p>
    </div>

    <form @submit.prevent="handleSubmit" class="level-creator-form">
      <div class="form-section">
        <div class="form-group">
          <label for="level-title" class="form-label">Títol del Nivell</label>
          <input
            id="level-title"
            v-model="level.title"
            type="text"
            required
            class="form-input"
            placeholder="Ex: Crear un disseny responsive amb CSS Grid"
          />
        </div>

        <div class="form-group">
          <label for="level-description" class="form-label">Descripció</label>
          <textarea
            id="level-description"
            v-model="level.description"
            required
            rows="3"
            class="form-textarea"
            placeholder="Descriu en detall l'objectiu del nivell i els conceptes que es practicaran"
          ></textarea>
        </div>
      </div>

      <div class="form-section">
        <h3 class="section-title">Codi Inicial</h3>
        <p class="section-description">Defineix el codi inicial que veuran els usuaris en començar el nivell</p>
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
        <h3 class="section-title">Solució Esperada</h3>
        <p class="section-description">Opcional: Defineix el codi de la solució per a la validació automàtica</p>
        <div class="editor-section">
          <label class="form-label">HTML Esperat</label>
          <textarea v-model="level.expectedHTML" class="code-textarea" spellcheck="false"></textarea>
        </div>
        <div class="editor-section">
          <label class="form-label">CSS Esperat</label>
          <textarea v-model="level.expectedCSS" class="code-textarea" spellcheck="false"></textarea>
        </div>
        <div class="editor-section">
          <label class="form-label">JS Esperat</label>
          <textarea v-model="level.expectedJS" class="code-textarea" spellcheck="false"></textarea>
        </div>
      </div>

      <div class="form-section">
        <h3 class="section-title">Previsualització</h3>
        <iframe
          class="preview-frame"
          sandbox="allow-scripts allow-same-origin"
          :srcdoc="previewContent"
        ></iframe>
      </div>

      <div class="form-actions">
        <button type="button" class="btn btn-secondary" @click="handleCancel">Cancel·lar</button>
        <button type="submit" class="btn btn-primary" :disabled="isSubmitting">
          <span v-if="isSubmitting">Publicant...</span>
          <span v-else>Publicar Nivell</span>
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
      title: 'Èxit',
      text: message,
    })
  }
}

const handleSubmit = async () => {
  if (!appStore.isLoggedIn) {
    showNotification('Has d’iniciar sessió per crear nivells', 'error')
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
    
    showNotification('Nivell creat amb èxit!', 'success')
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
    title: 'Segur que vols cancel·lar?',
    text: 'Els canvis no es guardaran.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, cancel·la',
    cancelButtonText: 'No, tornar enrere'
  }).then((result) => {
    if (result.isConfirmed) {
      router.push('/')
    }
  })
}

onMounted(() => {
  level.value.initialHTML = `<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>
        
    </body>
    </html>`
    level.value.expectedHTML = level.value.initialHTML

  level.value.initialCSS = `/* Afegeix estils aqui */

`

  level.value.initialJS = `// Afegeix aqui el codi JavaScript
  
`
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

@media (max-width: 450px) {
  .level-creator-container {
    padding: 2rem;
    margin-top: 0.5rem;
    border-radius: 6px;
  }

  .level-creator-title {
    font-size: 1.4rem;
    text-align: center;
  }

  .level-creator-subtitle {
    font-size: 0.9rem;
    text-align: center;
  }

  .form-section {
    padding: 1rem;
    margin-bottom: 1.5rem;
  }

  .section-title {
    font-size: 1.1rem;
    text-align: center;
  }

  .section-description {
    font-size: 0.85rem;
    text-align: center;
  }

  .form-group {
    margin-bottom: 1rem;
  }

  .form-label {
    font-size: 0.95rem;
  }

  .form-input,
  .form-textarea,
  .code-textarea {
    font-size: 0.9rem;
    padding: 0.6rem;
  }

  .code-editors-grid {
    grid-template-columns: 1fr;
    gap: 1rem;
  }

  .code-textarea {
    min-height: 150px;
  }

  .preview-frame {
    height: 300px;
  }

  .form-actions {
    flex-direction: column;
    align-items: stretch;
    gap: 0.75rem;
    margin-top: 1.5rem;
  }

  .btn {
    width: 100%;
    padding: 0.75rem;
    font-size: 0.95rem;
  }
}

</style>