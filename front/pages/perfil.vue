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
  
  // Estado para el usuario
  const user = ref(null);
  
  // Recuperamos el token desde localStorage
  const token = localStorage.getItem('token');
  
  // Verificamos si el token existe, si no, redirigimos al login
  if (!token) {
    const router = useRouter();
    router.push('/login');
  } else {
    // Realizamos la solicitud para obtener los detalles del usuario
    onMounted(async () => {
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
          user.value = data.user; // Asignamos los datos del usuario al estado
        } else {
          console.error('No se pudo obtener los detalles del usuario');
        }
      } catch (error) {
        console.error('Error al obtener los datos del usuario:', error);
      }
    });
  }
  
  const logout = () => {
    localStorage.removeItem('token'); 
    const router = useRouter();
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
  