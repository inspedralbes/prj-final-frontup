<template>
  <!-- Aviso para móviles en landscape -->
  <div class="block-mobile-landscape">Please rotate your device</div>

 
  <!-- Contenedor del slider: su clase "slide" se activa cuando showRegister es true -->
  <div class="slider" :class="{ slide: showRegister }">
    <!-- Container flexible con dos paneles (login y register) -->
    <div class="container">
      <!-- Panel izquierdo: Login -->
      <div class="left">
        <div class="content">
          <div class="title">Log ing</div>
          <form @submit.prevent="login" novalidate>
  <div class="info">
    <label for="email" class="label">Correo electrónico</label>
    <input
      type="email"
      id="email"
      v-model="formData.email"
      @blur="validateEmail"
      @keyup.enter="onEnterPressed('email')"
      required
      class="input"
    />
  </div>
  <div class="info">
    <label for="password" class="label">Contraseña</label>
    <input
      type="password"
      id="password"
      v-model="formData.password"
      @blur="validatePassword"
      @keyup.enter="onEnterPressed('password')"
      required
      class="input"
    />
  </div>
  <button type="submit" class="button" :disabled="!isLoginFormValid()">Sign In</button>
</form>

          <div class="slide-button-container">
            ¿No tienes cuenta?
            <span class="slide-button" @click="toggleRegister">Sign Up</span>
          </div>
          <p v-if="errorMessage" class="error-message">{{ errorMessage }}</p>
        </div>
      </div>
      <!-- Panel derecho: Register -->
      <div class="right">
        <div class="content">
          <div class="title">Register</div>
          <form @submit.prevent="register" novalidate>
            <div class="info">
              <input
                type="text"
                placeholder="Nombre"
                v-model="formData.name"
                @blur="validatename"
                required
                class="input"
              />
            </div>
            <div class="info">
              <input
                type="email"
                placeholder="Correo electrónico"
                v-model="formData.email"
                @blur="validateEmail"
                required
                class="input"
              />
            </div>
            <div class="info">
              <input
                type="password"
                placeholder="Contraseña"
                v-model="formData.password"
                @blur="validatePassword"
                required
                class="input"
              />
            </div>
            <div class="info">
              <input
                type="password"
                placeholder="Repetir contraseña"
                v-model="formData.passwordRepeat"
                @blur="validatePasswordRepeat"
                required
                class="input"
              />
            </div>
            <button type="submit" class="button" :disabled="!isRegisterFormValid()">
              Register
            </button>
          </form>
          <div class="slide-button-container">
            ¿Ya tienes cuenta?
            <span class="slide-button" @click="toggleRegister">Login</span>
          </div>
          <p v-if="errorMessage" class="error-message">{{ errorMessage }}</p>
        </div>
      </div>
    </div>
  </div>
</template>


<script setup>


import { reactive, ref, onUnmounted, onMounted } from 'vue';
import { useRouter } from 'nuxt/app';
import { useAppStore } from '@/stores/app';
import { useLliureStore } from '~/stores/app';


const router = useRouter();
const appStore = useAppStore();
const lliureStore = useLliureStore();


onMounted(() => {
  lliureStore.toggleLliure();
});
onUnmounted(() => {
  lliureStore.toggleLliure();

});
// Datos reactivos para formularios y errores
const formData = reactive({
  email: '',
  password: '',
  name: '',
  passwordRepeat: ''
});



const errors = reactive({
  email: '',
  password: '',
  name: '',
  passwordRepeat: ''
});


const errorMessage = ref('');
const showRegister = ref(false);


// Alterna entre Login y Register
const toggleRegister = () => {
  showRegister.value = !showRegister.value;
};


// Validaciones básicas (ajusta las expresiones regulares o mensajes si lo deseas)
const validateEmail = () => {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  errors.email = emailRegex.test(formData.email) ? '' : 'Correo inválido';
};


const validatename = () => {
  errors.name = formData.name ? '' : 'El nombre de usuario es obligatorio';
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
    formData.name &&
    formData.passwordRepeat &&
    !errors.email &&
    !errors.password &&
    !errors.name &&
    !errors.passwordRepeat
  );
};


// Función de login (conserva tu lógica de conexión)
const login = async () => {
  validateEmail();
  validatePassword();
  if (isLoginFormValid()) {
    try {
      const response = await fetch('http://127.0.0.1:8000/api/login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email: formData.email, password: formData.password })
      });
      const data = await response.json();
      if (response.ok) {
        appStore.setLoginInfo({
          loggedIn: true,
          id: data.user.id,
          token: data.token,
          name: data.user.name,
          email: data.user.email,
          nivel_html: data.user.nivel_html,
          nivel_css: data.user.nivel_css,
          nivel_js: data.user.nivel_js,
          avatar: data.user.avatar
        });
        router.push('/');
      } else {
        errorMessage.value = data.message || 'Credenciales inválidas';
      }
    } catch (err) {
      errorMessage.value = 'Error de red. No se pudo conectar al servidor.';
    }
  }
};


// Función de registro (conserva tu lógica de conexión)
const register = async () => {
  validateEmail();
  validatename();
  validatePassword();
  validatePasswordRepeat();
  if (isRegisterFormValid()) {
    const avatarUrl = `https://api.multiavatar.com/${formData.name}.png`;
    try {
      const response = await fetch('http://127.0.0.1:8000/api/register', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ ...formData, avatar: avatarUrl })
      });
      const data = await response.json();
      if (response.ok) {
        const loginResponse = await fetch('http://127.0.0.1:8000/api/login', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ email: formData.email, password: formData.password })
        });
        const loginData = await loginResponse.json();
        if (loginResponse.ok) {
          appStore.setLoginInfo({
            loggedIn: true,
            id: loginData.user.id,
            token: loginData.token,
            name: loginData.user.name,
            email: loginData.user.email,
            nivel_html: loginData.user.nivel_html,
            nivel_css: loginData.user.nivel_css,
            nivel_js: loginData.user.nivel_js,
            avatar: avatarUrl
          });
          router.push('/');
        } else {
          errorMessage.value = loginData.message || 'Error al iniciar sesión automáticamente';
        }
      } else {
        errorMessage.value = data.message || 'Error al crear la cuenta';
      }
    } catch (err) {
      errorMessage.value = 'Error de red. No se pudo conectar al servidor.';
    }
  }
};

</script>


<style lang="scss">
/* ==================== ESTILOS GLOBALES ==================== */
/* Coloca este bloque sin "scoped" para que afecte al body y demás */


:root {
  --background-image: url(https://i.imgur.com/NWC1ak5_d.webp?maxwidth=1520&fidelity=grand);
  --primary-color: #f2eeeb;
  --secondary-color: #a57c6e;
  --body-color: #08080d;
  --body-color-gradient: #08080dec;
  --title-color: #a5a4a8;
  --text-color: #848488;
  --valid-color: #00ff00;
  --invalid-color: #ff0000;
  --transparent: #00000000;
  --font-family: "Poppins", sans-serif;
  --large-font-size: calc(3rem + 1.625vh);
  --medium-font-size: min(calc(1rem + 0.725vh), 1.25rem);
  --small-font-size: min(calc(0.675rem + 0.5vh), 1rem);
}


/* Body */
body {
  width: 100%;
  height: 100%;
  overflow: hidden;
  font-family: var(--font-family);
  margin: 0;
  padding: 0;
  color: var(--text-color);
  background: var(--background-image) no-repeat center center fixed;
  background-size: cover;
}


/* Bloque para móviles en landscape */
.block-mobile-landscape {
  display: none;
  position: absolute;
  z-index: 100;
  width: 100%;
  height: 100%;
  background: var(--body-color);
  font-size: 2.5rem;
  text-transform: capitalize;
  background: linear-gradient(
      to top,
      var(--body-color-gradient),
      var(--primary-color) 150%
    ),
    var(--background-image) no-repeat center center fixed;
  background-size: cover;
}


/* Slider: ocupa el 30% de ancho y se posiciona a la izquierda por defecto */
.slider {
  width: 40%;
  height: 100%; 
  overflow: hidden;
  position: absolute;
  transition: margin-left 0.9s ease;
  margin-left: 0;
}
.slider.slide {
  margin-left: 60%;
}
/* Container interno con display: flex */
.container {
  
  display: flex;
  width: 200%;
  height: 100%;
  transition: transform .9s ease;
  transform: translateX(0);
}
.slider.slide .container {
  transform: translateX(-50%);
}


/* Paneles */
.left, .right {
  width: 50%;
  height: 100%;
  
}
.left {
  
  background: transparent;
}
.right {
  background: transparent;
}


/* Contenido de cada formulario */
.content {
  align-items: center;
  background: rgba(0, 0, 0, 0.6); /* Fondo semitransparente */
  padding: 51px;
  text-align: center;
  color: white;
  width: 84%;
  margin: 0 auto;
  position: relative;  /* Cambié a absolute para posicionar dentro del contenedor */
  top:51%;
  left: 50%;           /* Asegura el centrado horizontal */
  transform: translate(-50%, -50%); /* Centrado tanto vertical como horizontal */
  height: 100%;        /* Esto hará que ocupe toda la altura de la pantalla */
  min-height: 100vh;   /* Asegura que tenga como mínimo el alto completo de la pantalla */
}



/* Estilos de inputs */
.info {
  margin: 1rem 0;
}
.input {
  width: 100%;
  border: 2px solid var(--primary-color);
  background-color: rgba(0, 0, 0, 0.4);
  border-radius: 50px;
  padding: 0.8rem 1.2rem;
  font-size: var(--medium-font-size);
  color: var(--title-color);
  outline: none;
  margin-top: 0.5rem;
}


/* Botón */
.button {
  width: 100%;
  background-color: var(--primary-color);
  font-size: var(--medium-font-size);
  border: none;
  border-radius: 5px;
  padding: 1rem;
  text-transform: capitalize;
  font-weight: 600;
  color: var(--title-color);
  margin: 0.5rem 0;
  transition: all 0.25s;
  cursor: pointer;
  &:hover {
    background-color: var(--secondary-color);
  }
}


/* Contenedor del botón de cambio de formulario */
.slide-button-container {
  margin-top: 1rem;
  font-size: var(--small-font-size);
}
.slide-button {
  color: var(--secondary-color);
  cursor: pointer;
  font-weight: bold;
}


/* Mensaje de error */
.error-message {
  color: red;
  font-size: var(--medium-font-size);
  margin-top: 10px;
  text-align: center;
}
.title{
  font-weight: bold;
}


</style>



