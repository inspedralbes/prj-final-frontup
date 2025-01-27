<template>
  <div class="todo">
    <div v-if="!showRegister" class="auth-page">
      <h1>Inici de sessió</h1>
      <form @submit.prevent="login">
        <div>
          <label for="email">Correu Electrònic:</label>
          <input type="email" id="email" placeholder="Correu electrònic" v-model="formData.email"
            @blur="validateEmail" />
          <p class="error" v-if="errors.email">{{ errors.email }}</p>
        </div>
        <div>
          <label for="password">Contrasenya:</label>
          <input type="password" id="password" placeholder="Contrasenya" v-model="formData.password" />
          <p class="error" v-if="errors.password">{{ errors.password }}</p>
        </div>
        <button type="submit" :disabled="!isLoginFormValid()">Ingresar</button>
      </form>
      <p v-if="errorMessage" class="error-message">{{ errorMessage }}</p>
      <p>¿No tienes cuenta?</p>
      <div class="creglog" :class="{ active: showRegister }">
        <button @click="toggleRegister">Registra't aquí</button>
      </div>
    </div>

    <div v-if="showRegister" class="register-page">
      <h1>Crear nou compte</h1>
      <form @submit.prevent="register">
        <div>
          <label for="username">Nom d'usuari:</label>
          <input type="text" id="username" placeholder="Nom d'usuari" v-model="formData.username"
            @blur="validateUsername" />
          <p class="error" v-if="errors.username">{{ errors.username }}</p>
        </div>
        <div>
          <label for="email">Correu Electrònic:</label>
          <input type="email" id="email" placeholder="Correu electrònic" v-model="formData.email"
            @blur="validateEmail" />
          <p class="error" v-if="errors.email">{{ errors.email }}</p>
        </div>
        <div>
          <label for="password">Contraseña:</label>
          <input type="password" id="password" v-model="formData.password" @blur="validatePassword" />
          <p class="error" v-if="errors.password">{{ errors.password }}</p>
        </div>
        <div>
          <label for="passwordRepeat">Repetir Contrasenya:</label>
          <input type="password" id="passwordRepeat" placeholder="Repetir contrasenya" v-model="formData.passwordRepeat"
            @blur="validatePasswordRepeat" />
          <p class="error" v-if="errors.passwordRepeat">{{ errors.passwordRepeat }}</p>
        </div>
        <button type="submit" :disabled="!isRegisterFormValid()">Registrar</button>
      </form>
      <p>¿Ja tens compte?</p>
      <div class="creglog" :class="{ active: showRegister }">
        <button @click="toggleRegister">Inicia sesión aquí</button>
      </div>
    </div>

    <transition name="slide-to-right">
      <div v-if="!showRegister" class="register">
        <img src="../public/desk-4222025_1920.jpg" alt="Registre" @click="toggleRegister">
      </div>
    </transition>
    <transition name="slide-to-left">
      <div v-if="showRegister" class="login">
        <img src="../public/desk-4222025_1920.jpg" alt="Login" @click="toggleRegister">
      </div>
    </transition>
  </div>
</template>

<script setup>
import { reactive, ref, onUnmounted } from 'vue';
import { useRouter } from 'nuxt/app';
import { useAppStore } from '@/stores/app';
import { useLliureStore } from '~/stores/app'

const lliureStore = useLliureStore();
const router = useRouter();
const appStore = useAppStore();

onUnmounted(() => {
  lliureStore.toggleLliure()
});
onMounted(() => {
  lliureStore.toggleLliure()
});
const formData = reactive({
  email: '',
  password: '',
  username: '',
  passwordRepeat: ''
});

const errors = reactive({
  email: '',
  password: '',
  username: '',
  passwordRepeat: ''
});

const errorMessage = ref('');
const showRegister = ref(false);
const toggleRegister = () => {
  showRegister.value = !showRegister.value;
};

const validateEmail = () => {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  errors.email = emailRegex.test(formData.email) ? '' : 'Correo inválido';
};

const validateUsername = () => {
  errors.username = formData.username ? '' : 'El nombre de usuario es obligatorio';
};

const validatePassword = () => {
  errors.password = formData.password ? '' : 'La contraseña es obligatoria';
};

const validatePasswordRepeat = () => {
  errors.passwordRepeat =
    formData.passwordRepeat === formData.password ? '' : 'Las contraseñas no coinciden';
};

const isLoginFormValid = () => {
  return formData.email && formData.password && !errors.email && !errors.password;
};

const isRegisterFormValid = () => {
  return (
    formData.email &&
    formData.password &&
    formData.username &&
    formData.passwordRepeat &&
    !errors.email &&
    !errors.password &&
    !errors.username &&
    !errors.passwordRepeat
  );
};

const login = async () => {
  validateEmail();
  validatePassword();
  if (isLoginFormValid()) {
    try {
      const response = await fetch('http://127.0.0.1:8000/api/login', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          email: formData.email,
          password: formData.password
        }),
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
        errorMessage.value = data.message || 'Credenciales inválidas';
      }
    } catch (error) {
      console.error('Error durante el login:', error);
      errorMessage.value = 'Error de red. No se pudo conectar al servidor.';
    }
  }
};

const register = async () => {
  validateEmail();
  validateUsername();
  validatePassword();
  validatePasswordRepeat();
  if (isRegisterFormValid()) {
    try {
      const response = await fetch('http://127.0.0.1:8000/api/register', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(formData),
      });

      const data = await response.json();

      if (response.ok) {
        const loginResponse = await fetch('http://127.0.0.1:8000/api/login', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            email: formData.email,
            password: formData.password,
          }),
        });

        const loginData = await loginResponse.json();

        if (loginResponse.ok) {
          appStore.setLoginInfo({
            loggedIn: true,
            token: loginData.token,
            username: loginData.username,
            email: loginData.email,
            role: loginData.role,
            image: loginData.image,
            imageId: loginData.imageId,
          });

          router.push('/');
        } else {
          errorMessage.value = loginData.message || 'Error al iniciar sesión automáticamente';
        }
      } else {
        errorMessage.value = data.message || 'Error al crear la cuenta';
      }
    } catch (error) {
      console.error('Error durante el registro:', error);
      errorMessage.value = 'Error de red. No se pudo conectar al servidor.';
    }
  }
};
</script>

<style scoped>

.todo {
  height: 100vh;
  width: 100%;
  background-color: #1c1c1c;
  display: flex;
  justify-content: center;
  align-items: center;
}

.slide-to-right-enter-active {
  transition: transform 1s ease;
}

.slide-to-right-enter-from {
  transform: translateX(-100%);
}

.slide-to-left-enter-active {
  transition: transform 1s ease;
}

.slide-to-left-enter-from {
  transform: translateX(100%);
}

.auth-page {
  width: 100%;
  max-width: 400px; 
  padding: 30px;
  background: linear-gradient(135deg, #1c1c1c, #3a3a3a);
  border-radius: 16px;
  box-shadow: 0 10px 20px rgba(255, 255, 255, 0.2);
  position: absolute;
  color: white;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  left: 8%;
  text-align: center;
}

.register-page {
  width: 100%;
  max-width: 400px; 
  padding: 30px;
  background: linear-gradient(135deg, #1c1c1c, #3a3a3a);
  border-radius: 16px;
  box-shadow: 0 10px 20px rgba(255, 255, 255, 0.2);
  position: absolute;
  color: white;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  right: 8%;
  text-align: center;
}

.register {
  width: 50%;
  height: 100%;
  background-color: #303030;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  position: absolute;
  margin-top: 0;
  right: 0;
}

.login {
  width: 50%;
  height: 100%;
  background-color: #303030;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  position: absolute;
  margin-top: 0;
  left: 0;
  background: linear-gradient(135deg, #3a3a3a, #1c1c1c);
}

img {
  height: 100%;
  width: 100%;
  object-fit: cover;
}


form {
  display: flex;
  flex-direction: column;
  gap: 15px;
  width: 70%;
  align-items: center ;
  margin: 0 auto;
  align-items: center;
}

form div {
  width: 100%;
}

label {
  display: block;
  margin-bottom: 5px;
}

input {
  width: 70%;
  padding: 10px;
  border: none;
  border-radius: 5px;
  background: #2a2a2a;
  color: white;
}

input:focus {
  outline: 2px solid #555;
}

button {
  padding: 10px;
  background: #007bff;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

button:disabled {
  background: #555;
  cursor: not-allowed;
}

.creglog button {
  background: transparent;
  color: #007bff;
  border: none;
  cursor: pointer;
}

.creglog button:hover {
  text-decoration: underline;
}

.error {
  color: red;
  font-size: 0.9rem;
}

.error-message {
  color: red;
  font-size: 1rem;
  margin-top: 10px;
}
</style>
