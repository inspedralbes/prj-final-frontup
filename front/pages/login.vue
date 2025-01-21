<template>
    <div class="auth-page">
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
        ¿No tienes cuenta? <nuxt-link to="/register">Regístrate aquí</nuxt-link>
      </p>
    </div>
  </template>
  
  <script setup>
  import { reactive, computed } from 'vue';
  import { useRouter } from 'nuxt/app';
  import useAuth from '~/composables/useAuth';
  
  const router = useRouter();
  const { loginUser } = useAuth();
  
  const formData = reactive({ email: '', password: '' });
  const errors = reactive({ email: '', password: '' });
  
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
      const success = await loginUser(formData);
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
  