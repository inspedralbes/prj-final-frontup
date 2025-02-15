<template>
  <div class="profile-container">
    <div class="profile-card">
      <div class="profile-header">
        <h1>👤 Perfil d'Usuari</h1>
        <button class="logout-btn" @click="logout">
          <span class="icon">🚪</span>Tancar sessió
        </button>
      </div>

      <div v-if="loading" class="loading-container">
        <div class="loading-content">
          <img src="assets/img/loading2.gif" alt="Cargando..." />
        </div>
      </div>

      <div v-if="user && !loading" class="profile-content">
        <div class="avatar-section">
          <img :src="user.avatar" alt="Avatar" class="avatar" />
        </div>

        <div class="user-info">
          <div class="info-item">
            <label>Nom:</label>
            <div class="info-value">{{ user.name }}</div>
          </div>
          <div class="info-item">
            <label>Correu:</label>
            <div class="info-value">{{ user.email }}</div>
          </div>

          <div class="skill-levels">
            <div class="skill">
              <span class="skill-icon">🛠️</span>
              <div class="skill-progress">
                <div class="progress-bar html" :style="{width: (user.nivel * 10) + '%'}"></div>
                <span>HTML {{ user.nivel * 10 }}%</span>
              </div>
            </div>
            <div class="skill">
              <span class="skill-icon">🎨</span>
              <div class="skill-progress">
                <div class="progress-bar css" :style="{ width: ((user.nivel_css - 11) / (20 - 11) * 90 + 10) + '%' }"></div>
                <span>CSS {{ ((user.nivel_css - 11) / (20 - 11) * 90 + 10).toFixed(0) }}%</span>
              </div>
            </div>

            <div class="skill">
              <span class="skill-icon">⚙️</span>
              <div class="skill-progress">
                <div class="progress-bar js" :style="{width: ((user.nivel_js - 21) / 9 * 90 + 10) + '%'}"></div>
                <span>JS {{ ((user.nivel_js - 21) / 9 * 90 + 10).toFixed(0) }}%</span>
              </div>
            </div>
          </div>
        </div>

        <form @submit.prevent="updateAvatar" class="avatar-form">
          <input 
            v-model="newAvatar" 
            type="text" 
            placeholder="Introdueix text per al nou avatar" 
            class="avatar-input"
          />
          <button type="submit" class="submit-btn">
            <span class="icon">🔄</span>Actualitzar Avatar
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAppStore } from '../stores/app';

const user = ref(null);
const newAvatar = ref('');
const loading = ref(true);  // Esta variable controla el estado de carga
const router = useRouter();
const appStore = useAppStore();

onMounted(() => {
  if (!appStore.isLoggedIn) {
    router.push('/login');
  } else {
    fetchUserData(appStore.getLoginInfo.token);
  }
});

const fetchUserData = async (token) => {
  try {
    // Simula una carga de 2 segundos
    setTimeout(async () => {
      const response = await fetch('http://161.22.40.52/api/user', {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${token}`,
        },
      });

      if (response.ok) {
        const data = await response.json();
        if (data.user) {
          user.value = data.user;
        } else {
          console.error('No es va trobar informació d\'usuari');
        }
      } else {
        console.error('No se pudo obtener los detalles del usuario');
      }
      loading.value = false;  // Detiene el gif después de obtener los datos
    }, 2000); // 2 segundos de espera

  } catch (error) {
    console.error('Error al obtener los datos del usuario:', error);
    loading.value = false;  // Detiene el gif si ocurre un error
  }
};

const updateAvatar = async () => {
  try {
    let avatarUrl = newAvatar.value;

    if (!avatarUrl.startsWith('http')) {
      avatarUrl = `https://api.dicebear.com/9.x/personas/svg?seed=${encodeURIComponent(avatarUrl)}`;
    }

    const response = await fetch('http://161.22.40.52/api/user/avatar', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${appStore.getLoginInfo.token}`,
      },
      body: JSON.stringify({ avatar: avatarUrl }),
    });

    if (response.ok) {
      const data = await response.json();
      user.value = data.user;
      newAvatar.value = ''; 
    } else {
      console.error('Error al actualizar el avatar');
    }
  } catch (error) {
    console.error('Error al enviar la solicitud:', error);
  }
};

const logout = () => {
  appStore.logout();
  router.push('/login');
};
</script>

<style scoped>
.profile-container {
  min-height: 100vh;
  background: linear-gradient(135deg, #1a1a1a, #2d2d2d);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem;
  margin-left: 180px;
}

.profile-card {
  background: rgba(255, 255, 255, 0.05);
  backdrop-filter: blur(10px);
  border-radius: 20px;
  padding: 2.5rem;
  width: 100%;
  max-width: 800px;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
  border: 1px solid rgba(255, 255, 255, 0.1);
  margin-top: 6rem;
}

.profile-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2.5rem;
}

h1 {
  color: #fff;
  font-size: 2.2rem;
  margin: 0;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.avatar-section {
  position: relative;
  width: 150px;
  margin: 0 auto 2rem;
}

.avatar {
  width: 150px;
  height: 150px;
  border-radius: 50%;
  object-fit: cover;
  border: 3px solid #4CAF50;
  box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
}

.avatar-overlay {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  background: rgba(0, 0, 0, 0.7);
  color: #fff;
  padding: 0.5rem;
  text-align: center;
  border-radius: 0 0 20px 20px;
  cursor: pointer;
  opacity: 0;
  transition: all 0.3s ease;
}

.avatar-section:hover .avatar-overlay {
  opacity: 1;
}

.user-info {
  background: rgba(255, 255, 255, 0.05);
  border-radius: 15px;
  padding: 1.5rem;
  margin-bottom: 2rem;
}

.info-item {
  display: grid;
  grid-template-columns: 100px 1fr;
  align-items: center;
  margin-bottom: 1rem;
}

label {
  color: #4CAF50;
  font-weight: 500;
}

.info-value {
  color: #fff;
  padding: 0.5rem;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 8px;
}

.skill-levels {
  margin-top: 2rem;
}

.skill {
  display: flex;
  align-items: center;
  gap: 1rem;
  margin-bottom: 1.2rem;
}

.skill-progress {
  flex-grow: 1;
  background: rgba(255, 255, 255, 0.1);
  height: 30px;
  border-radius: 15px;
  position: relative;
  overflow: hidden;
}

.progress-bar {
  height: 100%;
  border-radius: 15px;
  position: absolute;
  left: 0;
  top: 0;
  transition: width 0.5s ease;
}

.html { background: #E44D26; }
.css { background: #264DE4; }
.js { background: #cab31c; }

.skill-progress span {
  position: relative;
  z-index: 1;
  color: #fff;
  padding-left: 1rem;
  line-height: 30px;
}

.avatar-form {
  display: flex;
  gap: 1rem;
  flex-wrap: wrap;
}

.avatar-input {
  flex-grow: 1;
  background: rgba(255, 255, 255, 0.1);
  border: none;
  padding: 0.8rem 1.2rem;
  border-radius: 8px;
  color: #fff;
  font-size: 1rem;
}

.avatar-input::placeholder {
  color: rgba(255, 255, 255, 0.6);
}

.submit-btn, .logout-btn {
  background: #4CAF50;
  color: #fff;
  border: none;
  padding: 0.8rem 1.5rem;
  border-radius: 8px;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  transition: all 0.3s ease;
}

.submit-btn:hover, .logout-btn:hover {
  background: #45a049;
  transform: translateY(-2px);
  box-shadow: 0 4px 15px rgba(76, 175, 80, 0.4);
}

.logout-btn {
  background: #f44336;
}

.logout-btn:hover {
  background: #d32f2f;
  box-shadow: 0 4px 15px rgba(244, 67, 54, 0.4);
}

.auth-message {
  text-align: center;
  color: #fff;
  font-size: 1.2rem;
}

.login-link {
  color: #4CAF50;
  text-decoration: none;
  margin-top: 1rem;
  display: inline-block;
}

@media (max-width: 768px) {
  .profile-card {
    padding: 1.5rem;
  }
  
  .profile-header {
    flex-direction: column;
    gap: 1rem;
  }
  
  .info-item {
    grid-template-columns: 1fr;
    gap: 0.5rem;
  }
  
  .avatar-form {
    flex-direction: column;
  }
  
  .avatar-input {
    width: 100%;
  }
}

.loading-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100%;
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  z-index: 10;
}

.loading-content {
  text-align: center;
}

.loading-container img {
  width: 10vw;
  height: 20vh;
}
</style>
