<template>
  <div class="profile-page">
    <h1>Perfil d'Usuari</h1>

    <div v-if="user">
      <img :src="user.avatar" alt="Avatar d'Usuari" class="avatar-image" />
      <p><strong>Nombre:</strong> {{ user.name }}</p>
      <p><strong>Correo Electrónico:</strong> {{ user.email }}</p>
      <p><strong>Nivel:</strong> {{ user.nivel }}</p>

      <form @submit.prevent="updateAvatar">
        <input v-model="newAvatar" type="text" placeholder="Nom del nou avatar" class="input-field" />
        <button type="submit">Actualitzar Avatar</button>
      </form>

      <button @click="logout">Cerrar sesión</button>
    </div>
    <div v-else>
      <p>No estàs autenticat.</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAppStore } from '../stores/app';

const user = ref(null);
const newAvatar = ref('');
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
  } catch (error) {
    console.error('Error al obtener los datos del usuario:', error);
  }
};

const updateAvatar = async () => {
  try {
    let avatarUrl = newAvatar.value;

    if (!avatarUrl.startsWith('http')) {
      avatarUrl = `https://api.multiavatar.com/${encodeURIComponent(avatarUrl)}.png`;
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
.profile-page {
  width: 60%;
  padding: 30px;
  background-color: #404040;
  border-radius: 8px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  margin: 0 auto;
  text-align: center;
}

button {
  padding: 10px;
  background-color: #ff4d4d;
  color: white;
  border: none;
  cursor: pointer;
  border-radius: 4px;
}

button:hover {
  background-color: #e03e3e;
}

.avatar-image {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  margin: 10px auto;
  object-fit: cover;
  border: 2px solid white;
}

.input-field {
  width: calc(100% - 20px);
  padding: 10px;
  margin: 10px 0;
  border: 1px solid #ccc;
  border-radius: 4px;
}
</style>