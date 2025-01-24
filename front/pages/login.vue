<template>
  <div v-if="!showRegister" class="auth-page">
    <h1>Iniciar Sesión</h1>
    <form @submit.prevent="login">
      <div>
        <label for="email">Correo Electrónico:</label>
        <input type="email" id="email" v-model="formData.email" @blur="validateEmail" />
        <p class="error" v-if="errors.email">{{ errors.email }}</p>
      </div>
      <div>
        <label for="password">Contraseña:</label>
        <input type="password" id="password" v-model="formData.password" />
        <p class="error" v-if="errors.password">{{ errors.password }}</p>
      </div>
      <button type="submit" :disabled="!isFormValid">Ingresar</button>
    </form>
    <p>¿No tienes cuenta?</p>
    <div class="creglog" :class="{ active: showRegister }">
      <button @click="toggleRegister">Regístrate aquí</button>
    </div>
  </div>

  <div v-if="showRegister" class="register-page">
    <h1>Crear Cuenta</h1>
    <form @submit.prevent="register">
      <div>
        <label for="username">Nombre de Usuario:</label>
        <input type="text" id="username" v-model="formData.username" @blur="validateUsername" />
        <p class="error" v-if="errors.username">{{ errors.username }}</p>
      </div>
      <div>
        <label for="email">Correo Electrónico:</label>
        <input type="email" id="email" v-model="formData.email" @blur="validateEmail" />
        <p class="error" v-if="errors.email">{{ errors.email }}</p>
      </div>
      <div>
        <label for="password">Contraseña:</label>
        <input type="password" id="password" v-model="formData.password" />
        <p class="error" v-if="errors.password">{{ errors.password }}</p>
      </div>
      <div>
        <label for="passwordRepeat">Repetir Contraseña:</label>
        <input type="password" id="passwordRepeat" v-model="formData.passwordRepeat" @blur="validatePasswordRepeat" />
        <p class="error" v-if="errors.passwordRepeat">{{ errors.passwordRepeat }}</p>
      </div>
      <button type="submit" :disabled="!isFormValid">Registrar</button>
    </form>
    <p>¿Ya tienes cuenta?</p>
    <div class="creglog" :class="{ active: showRegister }">
      <button @click="toggleRegister">Inicia session aquí</button>
    </div>
  </div>

  <div v-if="!showRegister" class="register"><img src="../public/desk-4222025_1920.jpg" alt=""></div>
  <div v-if="showRegister" class="login"><img src="../public/desk-4222025_1920.jpg" alt=""></div>
</template>

<script setup>
import { reactive, computed } from 'vue';
import { useRouter } from 'nuxt/app';
import { useAppStore } from '@/stores/app'; // Ruta del store

const router = useRouter();
const appStore = useAppStore();

const formData = reactive({ email: '', password: '' });
const errors = reactive({ email: '', password: '' });

const showRegister = ref(false);
const toggleRegister = () => {
  showRegister.value = !showRegister.value;
};

const validateEmail = () => {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  errors.email = emailRegex.test(formData.email) ? '' : 'Correo inválido';
};

const validatePassword = () => {
  errors.password = formData.password ? '' : 'La contraseña es obligatoria';
};

const isFormValid = computed(() => !errors.email && !errors.password);

const login = async () => {
  validateEmail();
  validatePassword();
  if (isFormValid.value) {
    try {
      const response = await fetch('http://127.0.0.1:8000/api/login', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(formData),
      });

      const data = await response.json();

      if (response.ok) {
        appStore.setLoginInfo({
          loggedIn: true,
          token: data.token,
          username: data.username,
          email: data.email,
          role: data.role,
          image: data.image,
          imageId: data.imageId,
        });
        router.push('/'); 
      } else {
        alert(data.message || 'Error al iniciar sesión');
      }
    } catch (error) {
      console.error('Error durante el login:', error);
      alert('Error de red. No se pudo conectar al servidor.');
    }
  }
};
</script>


<style scoped>
.auth-page {
  width: 30%;
  padding: 30px;
  background-color: #404040;
  border-radius: 8px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  position: absolute;
  margin-top: 15%;
  left: 8%;
}

.register-page {
  width: 30%;
  padding: 30px;
  background-color: #404040;
  border-radius: 8px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  position: absolute;
  margin-top: 13%;
  right: 8%;
}

.register {
  width: 50%;
  height: 100%;
  background-color: #404040;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  position: absolute;
  margin-top: 0;
  right: 0;
}

.login {
  width: 50%;
  height: 100%;
  background-color: #404040;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  position: absolute;
  margin-top: 0;
  left: 0;
}

img{
  height: 100%;
  width: 100%;
}

.error {
  color: red;
  font-size: 0.9rem;
}
</style>