<template>
  <div class="profile-page">
    <h1>Perfil del Usuario</h1>

    <div v-if="user">
      <p><strong>Nombre:</strong> {{ user.username }}</p>
      <p><strong>Correo Electrónico:</strong> {{ user.email }}</p>
      <p><strong>Nivel:</strong> {{ user.role }}</p>
      <button @click="logout">Cerrar sesión</button>
    </div>
    <div v-else>
      <p>No estás autenticado.</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAppStore } from '../stores/app';

const user = ref(null);
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
      user.value = data.user; 
    } else {
      console.error('No se pudo obtener los detalles del usuario');
    }
  } catch (error) {
    console.error('Error al obtener los datos del usuario:', error);
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
</style>
