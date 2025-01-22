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

    <div class="register-page">
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
  const { loginUser } = comunicationManager();
  
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
  
  .error {
    color: red;
    font-size: 0.9rem;
  }
</style>
  