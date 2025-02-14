<template>
  <div class="block-mobile-landscape">Si us plau, gira el teu dispositiu</div>

  <div class="slider" :class="{ slide: showRegister }">
    <div class="container">
      <div class="left">
        <div class="content">
          <div class="title">Iniciar sessió</div>
          <form @submit.prevent="login" novalidate>
            <div class="info">
              <label for="email" class="label">Correu electrònic</label>
              <input
                type="email"
                id="email"
                v-model="formData.email"
                @blur="validateEmail"
                @keyup.enter="onEnterPressed('email')"
                required
                :class="{'input-error': errors.email}"
                class="input"
              />
            </div>
            <div class="info">
              <label for="password" class="label">Contrasenya</label>
              <div class="password-input-container">
                <input
                  :type="passwordVisible ? 'text' : 'password'"
                  id="password"
                  v-model="formData.password"
                  @blur="validatePassword"
                  @keyup.enter="onEnterPressed('password')"
                  required
                  :class="{'input-error': errors.password}"
                  class="input"
                />
                <button type="button" @click="togglePasswordVisibility" class="show-password-btn">
                  <span v-if="passwordVisible">👁️</span>
                  <span v-else>👁️</span>
                </button>
              </div>
            </div>
            <button type="submit" class="button" :disabled="!isLoginFormValid()">Iniciar sessió</button>
          </form>

          <div class="slide-button-container">
            No tens compte?
            <span class="slide-button" @click="toggleRegister">Registra't</span>
          </div>
          <p v-if="errorMessage" class="error-message">{{ errorMessage }}</p>
        </div>
      </div>

      <!-- Panel derecho: Registro -->
      <div class="right">
        <div class="content">
          <div class="title">Registrar-se</div>
          <form @submit.prevent="register" novalidate>
            <div class="info">
              <input
                type="text"
                placeholder="Nom"
                v-model="formData.name"
                @blur="validateName"
                required
                :class="{'input-error': errors.name}"
                class="input"
              />
            </div>
            <div class="info">
              <input
                type="email"
                placeholder="Correu electrònic"
                v-model="formData.email"
                @blur="validateEmail"
                required
                :class="{'input-error': errors.email}"
                class="input"
              />
            </div>
           
            <div class="info">
  <input
    type="password"
    placeholder="Contrasenya"
    v-model="formData.password"
    @blur="validatePassword"
    required
    :class="{
      'input-error': errors.password,
      'input-valid': formData.password && !errors.password
    }"
    class="input"
  />
  <button type="button" @click="togglePasswordVisibility" class="show-password-btn">
    <span v-if="passwordVisible">👁️</span>
    <span v-else>👁️</span>
  </button>
</div>

<div class="info">
  <input
    type="password"
    placeholder="Repetir contrasenya"
    v-model="formData.passwordRepeat"
    @blur="validatePasswordRepeat"
    required
    :class="{
      'input-error': errors.passwordRepeat,
      'input-valid': formData.passwordRepeat && !errors.passwordRepeat
    }"
    class="input"
  />
</div>

            <button type="submit" class="button" :disabled="!isRegisterFormValid()">Registrar-se</button>
          </form>
          <div class="slide-button-container">
            Ja tens compte?
            <span class="slide-button" @click="toggleRegister">Iniciar sessió</span>
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
const passwordVisible = ref(false); // Estado reactivo para manejar la visibilidad de la contraseña

const togglePasswordVisibility = () => {
  passwordVisible.value = !passwordVisible.value;
};

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

const validatePasswordRepeat = () => {
  if (!formData.passwordRepeat) {
    errors.passwordRepeat = '';  // Si está vacío, no hay error
  } else if (formData.passwordRepeat !== formData.password) {
    errors.passwordRepeat = 'Las contraseñas no coinciden';
  } else {
    errors.passwordRepeat = '';  // No hay error si coinciden
  }
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

const login = async () => {
  validateEmail();
  validatePassword();
  if (!isLoginFormValid()) {
    return;
  }
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
};
</script>




<style lang="scss">

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

.left, .right {
  width: 50%;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
}

.left {
  background: transparent;
}

.right {
  background: transparent;
}

.content {
  background: rgba(0, 0, 0, 0.6);
  padding: 70px;
  text-align: center;
  color: white;
  width: 73%;
  margin: 0 auto;
  position: relative;
  top: 51%;
  left: 50%;
  transform: translate(-50%, -50%);
  height: 100%;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.info {
  margin: 1rem 0;
  text-align: center;
}

.input-valid {
  border-color: var(--valid-color) !important;  // Verde cuando es válido
}

.input-error {
  border-color: var(--invalid-color) !important;  // Rojo cuando hay error
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

.slide-button-container {
  margin-top: 1rem;
  font-size: var(--small-font-size);
  text-align: center;
}

.slide-button {
  color: var(--secondary-color);
  cursor: pointer;
  font-weight: bold;
}

.error-message {
  color: red;
  font-size: var(--medium-font-size);
  margin-top: 10px;
  text-align: center;
}

.password-input-container {
  position: relative;
}

.show-password-btn {
  position: absolute;
  top: 75%;
  right: -10%;
  transform: translateY(-100%);
  background: none;
  border: none;
  cursor: pointer;
  font-size: 18px;
}

.input {
  width: 100%;
  padding-right: 40px; /* Deja espacio para el icono */
}


.title {
  font-weight: bold;
  text-align: center;
}

</style>
