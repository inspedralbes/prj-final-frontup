<template>
    <div class="profile-page">
      <h1>Perfil del Usuario</h1>
  
      <div v-if="user">
        <p><strong>Nombre:</strong> {{ user.name }}</p>
        <p><strong>Correo Electrónico:</strong> {{ user.email }}</p>
        <p><strong>Nivel:</strong> {{ user.nivel }}</p>
        <button @click="logout">Cerrar sesión</button>
      </div>
      <div v-else>
        <p>No estás autenticado.</p>
      </div>
    </div>
  </template>
  
  <script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'nuxt/app';

const user = ref(null);
const token = ref(null);
const router = useRouter();

onMounted(() => {
  if (typeof window !== 'undefined') {
    token.value = localStorage.getItem('token'); // Asignamos el token desde localStorage
  }

  if (!token.value) {
    router.push('/login'); // Si no hay token, redirigimos al login
  } else {
    fetchUserData(token.value);
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
      user.value = data.user; // Asignamos los datos del usuario
    } else {
      console.error('No se pudo obtener los detalles del usuario');
    }
  } catch (error) {
    console.error('Error al obtener los datos del usuario:', error);
  }
};

const logout = () => {
  localStorage.removeItem('token'); // Eliminar el token de localStorage
  router.push('/login'); // Redirigir al login
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
  