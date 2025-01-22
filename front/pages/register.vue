<template>
    <div class="auth-page">
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
        ¿Ya tienes cuenta? <nuxt-link to="/login">Inicia sesión aquí</nuxt-link>
      </p>
    </div>
  </template>
  
  <script setup>
  import { reactive, computed } from 'vue';
  import { useRouter } from 'nuxt/app';
  import { comunicationManager } from "@/stores/comunicationManager";
  
  const router = useRouter();
  const { registerUser } = comunicationManager();
  
  const formData = reactive({ username: '', email: '', password: '', passwordRepeat: '' });
  const errors = reactive({ username: '', email: '', password: '', passwordRepeat: '' });
  
  const validateUsername = () => {
    errors.username = formData.username.length >= 3 ? '' : 'El nombre debe tener al menos 3 caracteres';
  };
  
  const validateEmail = () => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    errors.email = emailRegex.test(formData.email) ? '' : 'Correo inválido';
  };
  
  const validatePassword = () => {
    errors.password = formData.password.length >= 4 ? '' : 'La contraseña debe tener al menos 4 caracteres';
  };
  
  const validatePasswordRepeat = () => {
    errors.passwordRepeat = formData.password === formData.passwordRepeat ? '' : 'Las contraseñas no coinciden';
  };
  
  const isFormValid = computed(() => !errors.username && !errors.email && !errors.password && !errors.passwordRepeat);
  
  const register = async () => {
    validateUsername();
    validateEmail();
    validatePassword();
    validatePasswordRepeat();
    if (isFormValid.value) {
      const success = await registerUser(formData);
      if (success) router.push('/home');
    }
  };
  </script>
  
  <style scoped>
  .error {
    color: red;
    font-size: 0.9rem;
  }
  </style>
  