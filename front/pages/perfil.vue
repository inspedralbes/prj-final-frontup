<template>
  <div class="todo"> 
    <div class="profile-page">
      <h1>Perfil d'Usuari</h1>
    
      <div v-if="user">
        <img :src="user.avatar" alt="Avatar d'Usuari" class="avatar-image" />
        <p><strong>Nombre: </strong> &nbsp;{{ user.name }}</p>
        <p><strong>Correo Electrónico: </strong> &nbsp;{{ user.email }}</p>
        <p><strong>Nivel:</strong> &nbsp;{{ user.nivel }}</p>
        
        <form @submit.prevent="updateAvatar">
          <input v-model="newAvatar" type="text" placeholder="Nom del nou avatar" class="input-field" />
        <button type="submit">Actualitzar Avatar</button>
      </form>

      <button class="logout" @click="logout">Cerrar sesión</button>
    </div>
    <div v-else>
      <p>No estàs autenticat.</p>
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
.todo{
  width: 100%;
  height: 115vh;
  background-color: black;
}
.profile-page {
  max-width: 600px;
  margin: 2rem auto;
  padding: 2rem;
  background-color: #1E1E1E;
  border-radius: 8px;
  box-shadow: 0px 4px 12px rgba(255, 255, 255, 0.05);
  color: rgba(255, 255, 255, 0.87);
}

h1 {
  color: #90CAF9;
  font-size: 2.125rem;
  font-weight: 500;
  margin-bottom: 2rem;
  text-align: center;
  transition: color 0.3s ease;
}

.avatar-image {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  object-fit: cover;
  margin: 1rem auto;
  box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
  border: 3px solid #424242;
  transition: transform 0.3s ease;
  display: block;
  margin: 1rem auto;
}

.avatar-image:hover {
  transform: scale(1.05);
}

p {
  font-size: 1rem;
  margin: 1rem 0;
  color: rgba(255, 255, 255, 0.87);
  display: flex;
  transition: color 0.3s ease;
}

strong {
  font-weight: 500;
  color: #90CAF9;
  margin-right: 0.5rem;
}

form {
  margin: 2rem 0;
  padding: 1.5rem;
  background-color: #2D2D2D;
  border-radius: 8px;
  transition: background-color 0.3s ease;
}

form:hover {
  background-color: #424242;
}

.input-field {
  width: 100%;
  padding: 12px 16px;
  margin: 8px 0;
  border: none;
  border-bottom: 2px solid #616161;
  background-color: transparent;
  color: rgba(255, 255, 255, 0.87);
  font-size: 1rem;
  transition: border-color 0.3s ease;
}

.input-field:focus {
  border-color: #90CAF9;
  outline: none;
}

.input-field::placeholder {
  color: rgba(255, 255, 255, 0.6);
}

button {
  padding: 12px 24px;
  margin: 8px;
  border: none;
  border-radius: 4px;
  font-size: 0.875rem;
  font-weight: 500;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

button[type="submit"] {
  background-color: #1976D2;
  color: white;
  box-shadow: 0px 2px 4px rgba(25, 118, 210, 0.3);
}

button[type="submit"]:hover {
  background-color: #1565C0;
  box-shadow: 0px 4px 8px rgba(25, 118, 210, 0.3);
  transform: scale(1.05);
}

.logout {
  background-color: #D32F2F;
  color: white;
  box-shadow: 0px 2px 4px rgba(211, 47, 47, 0.3);
}

.logout:hover {
  background-color: #C62828;
  box-shadow: 0px 4px 8px rgba(211, 47, 47, 0.3);
  transform: scale(1.05);}

@media (max-width: 600px) {
  .profile-page {
    width: 95%;
    padding: 1rem;
  }
  
  h1 {
    font-size: 1.75rem;
  }
  
  p {
    flex-direction: column;
    text-align: center;
  }
  
  strong {
    margin-bottom: 0.5rem;
  }
}
</style>
