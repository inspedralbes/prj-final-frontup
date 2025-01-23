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
    <p>
      ¿No tienes cuenta?
    <div class="creglog" :class="{ active: showRegister }">
      <button @click="toggleRegister">Regístrate aquí</button>
    </div>
    </p>
  </div>

  <div v-if="!showRegister" class="register">
    <div class="atom">
      <div class="center"></div>
      <div class="orbit orbit1">
        <div class="electron elector1"></div>
      </div>
      <div class="orbit orbit2">
        <div class="electron electron2"></div>
      </div>
      <div class="orbit orbit3">
        <div class="electron electron3"></div>
      </div>
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
    <p>
      ¿Ya tienes cuenta?
    <div class="creglog" :class="{ active: showRegister }">
      <button @click="toggleRegister">Inicia session aquí</button>
    </div>
    </p>
  </div>
</template>

<script setup>
import { reactive, computed } from 'vue';
import { useRouter } from 'nuxt/app';

const router = useRouter();

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
        localStorage.setItem('token', data.token);
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
  width: 35%;
  padding: 100px;
  height: 500px;
  background-color: #404040;
  border-radius: 8px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  position: absolute;
  margin-top: 0;
  right: 0;
}

.error {
  color: red;
  font-size: 0.9rem;
}

.atom {
	position: relative;
	width: 200px;
	height: 200px;
}

.center {
	width: 30px;
	height: 30px;
	background: orange;
	border-radius: 50%;
	position: absolute;
	top: 50%;
	left: 50%;
	transform: translate(-50%, -50%);
	box-shadow: 0 0 20px orange, 0 0 50px orange;
}

.orbit {
	position: absolute; /* Change to absolute */
	top: 20%; /* Center the orbit */
	left: 0%; /* Center the orbit */
	translate: -50%, -50%; /* Center it perfectly */
	border-radius: 50%; /* Keep the elliptical shape */
	border: 1px dashed rgba(255, 255, 255, 0.3);
	transform-style: preserve-3d;
	animation: rotate 3s linear infinite;
}

.orbit1 {
	width: 200px; /* Major axis */
	height: 120px; /* Minor axis */
	transform: translate(-50%, -50%) rotateX(45deg); /* Combine centering and rotation */
}

.orbit2 {
	width: 200px;
	height: 120px;
	transform: translate(-50%, -50%) rotateX(-45deg);
	animation-duration: 5s;
}

.orbit3 {
	width: 200px;
	height: 120px;
	transform: translate(-50%, -50%) rotateY(90deg);
	animation-duration: 10s;
}

.electron {
	width: 15px;
	height: 15px;

	border-radius: 50%;
	position: absolute;
	top: 0;
	left: 50%;
	transform: translate(-50%, -50%);
	box-shadow: 0 0 10px cyan, 0 0 30px cyan;
}
.electron1 {
	background: rgba(0, 150, 255);
}
.electron2 {
	background: rgba(0, 0, 255);
}
.electron3 {
	background: rgba(255, 0, 255);
}

@keyframes rotate {
	0% {
		transform: rotate(0deg);
	}
	100% {
		transform: rotate(360deg);
	}
}

</style>