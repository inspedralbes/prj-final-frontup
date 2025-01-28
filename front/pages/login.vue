<template>
  <div class="todo">
    <div v-if="!showRegister" class="auth-page">
      <form class="form" @submit.prevent="login">
        <p id="heading">Login</p>
        <div class="field">
          <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
          <path d="M13.106 7.222c0-2.967-2.249-5.032-5.482-5.032-3.35 0-5.646 2.318-5.646 5.702 0 3.493 2.235 5.708 5.762 5.708.862 0 1.689-.123 2.304-.335v-.862c-.43.199-1.354.328-2.29.328-2.926 0-4.813-1.88-4.813-4.798 0-2.844 1.921-4.881 4.594-4.881 2.735 0 4.608 1.688 4.608 4.156 0 1.682-.554 2.769-1.416 2.769-.492 0-.772-.28-.772-.76V5.206H8.923v.834h-.11c-.266-.595-.881-.964-1.6-.964-1.4 0-2.378 1.162-2.378 2.823 0 1.737.957 2.906 2.379 2.906.8 0 1.415-.39 1.709-1.087h.11c.081.67.703 1.148 1.503 1.148 1.572 0 2.57-1.415 2.57-3.643zm-7.177.704c0-1.197.54-1.907 1.456-1.907.93 0 1.524.738 1.524 1.907S8.308 9.84 7.371 9.84c-.895 0-1.442-.725-1.442-1.914z"></path>
          </svg>
          <input type="email" class="input-field" id="email" placeholder="Correu electrònic" v-model="formData.email"
            @blur="validateEmail" />
          <p class="error" v-if="errors.email">{{ errors.email }}</p>
        </div>
        <div class="field">
          <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
         <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"></path>
          </svg>
          <input type="password" class="input-field" id="password" placeholder="Contrasenya" v-model="formData.password" />
          <p class="error" v-if="errors.password">{{ errors.password }}</p>
        </div>
        <button type="submit" :disabled="!isLoginFormValid()">Ingresar</button>
        <p v-if="errorMessage" class="error-message">{{ errorMessage }}</p>
        <p>¿No tens compte?</p>
        <div :class="{ active: showRegister }">
          <button class="button1" @click="toggleRegister">Registra't aquí</button>
        </div>
      </form>
    </div>
 
 
    <div v-if="showRegister" class="register-page">
      <form class="form" @submit.prevent="register">
        <p>Crear nou compte</p>
        <div class="field">
          <svg viewBox="0 0 19 19" fill="none" width="16" height="16" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M5 21C5 17.134 8.13401 14 12 14C15.866 14 19 17.134 19 21M16 7C16 9.20914 14.2091 11 12 11C9.79086 11 8 9.20914 8 7C8 4.79086 9.79086 3 12 3C14.2091 3 16 4.79086 16 7Z" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
          <input type="text" class="input-field" id="username" placeholder="Nom d'usuari" v-model="formData.username"
            @blur="validateUsername" />
          <p class="error" v-if="errors.username">{{ errors.username }}</p>
        </div>
        <div class="field">
          <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
          <path d="M13.106 7.222c0-2.967-2.249-5.032-5.482-5.032-3.35 0-5.646 2.318-5.646 5.702 0 3.493 2.235 5.708 5.762 5.708.862 0 1.689-.123 2.304-.335v-.862c-.43.199-1.354.328-2.29.328-2.926 0-4.813-1.88-4.813-4.798 0-2.844 1.921-4.881 4.594-4.881 2.735 0 4.608 1.688 4.608 4.156 0 1.682-.554 2.769-1.416 2.769-.492 0-.772-.28-.772-.76V5.206H8.923v.834h-.11c-.266-.595-.881-.964-1.6-.964-1.4 0-2.378 1.162-2.378 2.823 0 1.737.957 2.906 2.379 2.906.8 0 1.415-.39 1.709-1.087h.11c.081.67.703 1.148 1.503 1.148 1.572 0 2.57-1.415 2.57-3.643zm-7.177.704c0-1.197.54-1.907 1.456-1.907.93 0 1.524.738 1.524 1.907S8.308 9.84 7.371 9.84c-.895 0-1.442-.725-1.442-1.914z"></path>
          </svg>
          <input type="email" class="input-field" id="email" placeholder="Correu electrònic" v-model="formData.email"
            @blur="validateEmail" />
          <p class="error" v-if="errors.email">{{ errors.email }}</p>
        </div>
        <div class="field">
          <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"></path>
          </svg>
          <input type="password" class="input-field" id="password" placeholder="Contrasenya" v-model="formData.password" @blur="validatePassword" />
          <p class="error" v-if="errors.password">{{ errors.password }}</p>
        </div>
        <div class="field">
          <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"></path>
          </svg>
          <input type="password" class="input-field" id="passwordRepeat" placeholder="Repetir contrasenya" v-model="formData.passwordRepeat"
            @blur="validatePasswordRepeat" />
          <p class="error" v-if="errors.passwordRepeat">{{ errors.passwordRepeat }}</p>
        </div>
        <button type="submit" class="button1" @click="!isRegisterFormValid()">Registrar</button>
        <p>¿Ja tens compte?</p>
        <div :class="{ active: showRegister }">
          <button class="button1" @click="toggleRegister">Inicia sesión aquí</button>
        </div>
      </form>
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
          nivel: data.nivel,
          avatar: data.avatar,
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
    const avatarUrl = `https://api.multiavatar.com/${formData.username}.png`;

    try {
      const response = await fetch('http://127.0.0.1:8000/api/register', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          ...formData,
          avatar: avatarUrl,
        }),
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
            nivel: loginData.nivel,
            avatar: avatarUrl,
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
 border-radius: 16px;
 position: absolute;
 color: white;
 left: 8%;
 text-align: center;
}

.register-page {
 width: 100%;
 max-width: 400px;
 padding: 30px;
 position: absolute;
 color: white;
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
}

.form {
 display: flex;
 flex-direction: column;
 gap: 10px;
 padding-left: 2em;
 padding-right: 2em;
 padding-bottom: 0.4em;
 background-color: #171717;
 border-radius: 25px;
 transition: .4s ease-in-out;
}

.form:hover {
 transform: scale(1.05);
 border: 1px solid black;
}

#heading {
 text-align: center;
 margin: 2em;
 color: rgb(255, 255, 255);
 font-size: 1.2em;
}

.field {
 display: flex;
 align-items: center;
 justify-content: center;
 gap: 0.5em;
 border-radius: 25px;
 padding: 0.6em;
 border: none;
 outline: none;
 color: white;
 background-color: #171717;
 box-shadow: inset 2px 5px 10px rgb(5, 5, 5);
}

.input-field {
 background: none;
 border: none;
 outline: none;
 width: 100%;
 color: #d3d3d3;
}

.button1 {
 padding: 0.5em;
 padding-left: 1.1em;
 padding-right: 1.1em;
 border-radius: 5px;
 margin-right: 0.5em;
 border: none;
 outline: none;
 transition: .4s ease-in-out;
 background-color: #252525;
 color: white;
}

.input-icon {
 height: 1.3em;
 width: 1.3em;
 fill: white;
}

button:disabled {
 background: #555;
 cursor: not-allowed;
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