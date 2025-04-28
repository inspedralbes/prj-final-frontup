<template>
  <div class="profile-container">
    <div class="profile-card">
      <div class="profile-card-bg"></div>
      <div class="profile-header">
        <h1>👤 Perfil d'Usuari</h1>
        <button class="logout-btn" @click="logout">
          <span class="icon">🚪</span>Tancar sessió
        </button>
      </div>

      <div v-if="loading" class="loading-container">
        <div class="spinner"></div>
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
                <div class="progress-bar html" :style="{ width: (user.nivel * 10) + '%' }"></div>
                <span>HTML {{ user.nivel * 10 }}%</span>
              </div>
            </div>
            <div class="skill">
              <span class="skill-icon">🎨</span>
              <div class="skill-progress">
                <div class="progress-bar css" :style="{ width: ((user.nivel_css - 11) / (20 - 11) * 90 + 10) + '%' }">
                </div>
                <span>CSS {{ ((user.nivel_css - 11) / (20 - 11) * 90 + 10).toFixed(0) }}%</span>
              </div>
            </div>

            <div class="skill">
              <span class="skill-icon">⚙️</span>
              <div class="skill-progress">
                <div class="progress-bar js" :style="{ width: ((user.nivel_js - 21) / 9 * 90 + 10) + '%' }"></div>
                <span>JS {{ ((user.nivel_js - 21) / 9 * 90 + 10).toFixed(0) }}%</span>
              </div>
            </div>
          </div>
        </div>

        <form @submit.prevent="updateAvatar" class="avatar-form">
          <input v-model="newAvatar" type="text" placeholder="Introdueix text per al nou avatar" class="avatar-input" />
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
      const response = await fetch('http://127.0.0.1:8000/api/user', {
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

    const response = await fetch('http://127.0.0.1:8000/api/user/avatar', {
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
  height: 100%;
  color: #e0e0e0;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 2rem;
  background-color: #07182E;
}

.profile-card {
  width: 90%;
  max-width: 900px;
  background: #07182E;
  border-radius: 15px;
  position: relative;
  overflow: hidden;
  padding: 2.5rem;
}

/* Animación del lado derecho */
.profile-card::before {
  content: '';
  position: absolute;
  width: 150px;
  background-image: linear-gradient(180deg, rgb(0, 183, 255), rgb(255, 48, 255));
  height: 170%;
  animation: rotBGimg 8s linear infinite;
  transition: all 0.2s linear;
  top: -20%;
  right: -50px;
}

/* Nueva animación del lado izquierdo */
.profile-card::after {
  content: '';
  position: absolute;
  width: 150px;
  background-image: linear-gradient(180deg, rgb(255, 48, 255), rgb(0, 183, 255));
  height: 170%;
  animation: rotBGimgReverse 8s linear infinite;
  transition: all 0.2s linear;
  bottom: -20%;
  left: -50px;
  z-index: 0;
}

/* Capa interior para el contenido */
.profile-card > * {
  position: relative;
  z-index: 2;
}

/* Fondo interno para crear el efecto de borde */
.profile-card-bg {
  content: '';
  position: absolute;
  background: #07182E;
  inset: 5px;
  border-radius: 12px;
  z-index: 1;
}

.profile-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2.5rem;
  position: relative;
  z-index: 2;
}

.profile-header h1 {
  font-size: 2.2rem;
  margin: 0;
  background: linear-gradient(45deg, #ffffff, #bd89ff);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.profile-content {
  position: relative;
  z-index: 2;
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.avatar-section {
  display: flex;
  justify-content: center;
  margin-bottom: 1.5rem;
}

.avatar {
  width: 150px;
  height: 150px;
  border-radius: 50%;
  object-fit: cover;
  border: 3px solid rgba(189, 137, 255, 0.6);
  box-shadow: 0 0 20px rgba(189, 137, 255, 0.4);
  transition: all 0.3s ease;
}

.avatar:hover {
  transform: scale(1.05);
  box-shadow: 0 0 30px rgba(189, 137, 255, 0.7);
}

.user-info {
  background: rgba(7, 24, 46, 0.8);
  border-radius: 10px;
  padding: 1.5rem;
  border: 1px solid rgba(189, 137, 255, 0.2);
}

.info-item {
  display: flex;
  margin-bottom: 1rem;
  align-items: center;
}

.info-item label {
  font-weight: bold;
  width: 100px;
  color: #bd89ff;
}

.info-value {
  flex: 1;
  padding: 0.5rem;
  background: rgba(255, 255, 255, 0.05);
  border-radius: 5px;
  border-left: 3px solid #bd89ff;
  color: #fff;
}

.skill-levels {
  margin-top: 1.5rem;
}

.skill {
  display: flex;
  align-items: center;
  margin-bottom: 1rem;
}

.skill-icon {
  font-size: 1.5rem;
  margin-right: 1rem;
  width: 30px;
  text-align: center;
}

.skill-progress {
  flex: 1;
  background: rgba(255, 255, 255, 0.1);
  height: 25px;
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
  transition: width 1s ease-out;
}

.progress-bar.html {
  background: linear-gradient(90deg, #ff8a00, #e52e71);
}

.progress-bar.css {
  background: linear-gradient(90deg, #3498db, #8e44ad);
}

.progress-bar.js {
  background: linear-gradient(90deg, #f1c40f, #e67e22);
}

.skill-progress span {
  position: absolute;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  padding-left: 15px;
  color: #fff;
  font-weight: bold;
  text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7);
}

.avatar-form {
  display: flex;
  gap: 1rem;
  position: relative;
  z-index: 2;
}

.avatar-input {
  padding: 0.8rem 6rem;
  border-radius: 10px;
  border: 1px solid rgba(189, 137, 255, 0.3);
  background-color: rgba(255, 255, 255, 0.05);
  color: #fff;
  font-size: 1rem;
}

.avatar-input:focus {
  outline: none;
  border-color: rgba(189, 137, 255, 0.8);
  box-shadow: 0 0 10px rgba(189, 137, 255, 0.3);
}

.avatar-input::placeholder {
  color: rgba(255, 255, 255, 0.6);
}

.submit-btn,
.logout-btn {
  border: none;
  border-radius: 10px;
  background-color: rgba(189, 137, 255, 0.2);
  color: #fff;
  cursor: pointer;
  padding: 12px 20px;
  font-size: 1em;
  transition: all 0.3s ease;
  position: relative;
  z-index: 2;
  border: 1px solid rgba(189, 137, 255, 0.3);
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
}

.submit-btn:hover,
.logout-btn:hover {
  background-color: rgba(189, 137, 255, 0.4);
  box-shadow: 0 0 15px rgba(189, 137, 255, 0.5);
  transform: translateY(-2px);
}

.logout-btn {
  background-color: rgba(229, 46, 113, 0.2);
  border: 1px solid rgba(229, 46, 113, 0.3);
}

.logout-btn:hover {
  background-color: rgba(229, 46, 113, 0.4);
  box-shadow: 0 0 15px rgba(229, 46, 113, 0.5);
}

.loading-container {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(7, 24, 46, 0.9);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 10;
  border-radius: 15px;
}

.spinner {
  width: 50px;
  height: 50px;
  border: 4px solid rgba(255, 255, 255, 0.1);
  border-top: 4px solid #bd89ff;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

@keyframes rotBGimg {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

@keyframes rotBGimgReverse {
  from {
    transform: rotate(360deg);
  }
  to {
    transform: rotate(0deg);
  }
}

/* Responsive styles */
@media (min-width: 768px) {
  .profile-content {
    flex-direction: row;
    flex-wrap: wrap;
  }
  
  .avatar-section {
    width: 30%;
  }
  
  .user-info {
    width: 65%;
  }
}

@media (max-width: 768px) {
  .profile-card {
    padding: 1.5rem;
  }

  .profile-header {
    flex-direction: column;
    gap: 1rem;
  }

  .avatar-form {
    flex-direction: column;
  }
}
@media (max-width: 450px) {
  .profile-container {
  height: 100%;
  color: #e0e0e0;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 2rem;
  background-color: #07182E;
  margin-left: -90%;
}


}
</style>
