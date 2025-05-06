<template>
  <div class="login-wrapper">
    <div class="slider" :class="{ slide: showRegister }">
      <div>
    <button v-if="showRegister" class="atras" @click="gohome">
      FrontUp
    </button>
    <button v-else class="atras" @click="gohome">
      FrontUp
    </button>
  </div>
      <div class="Formulario">
        <!-- Formulario de login -->
        <div class="left">
          <div class="content">
            <div class="title">Iniciar sessió</div>
            <form @submit.prevent="login" novalidate>
              <div class="info">
                <label for="email" class="label">Correu electrònic</label>
                <input
                  type="email"
                  id="email"
                  placeholder="Correu electrónic"
                  v-model="formData.email"
                  @blur="validateEmail"
                  @keyup.enter="onEnterPressed('email')"
                  required
                  :class="{ 'input-error': errors.email }"
                  class="input"
                />
                <div v-if="errors.email" class="input-error-message">
                  {{ errors.email }}
                </div>
              </div>
              <div class="info">
                <label for="password" class="label">Contrasenya</label>
                <div class="password-input-Formulario">
                  <input
                    :type="passwordVisibleLogin ? 'text' : 'password'"
                    id="password"
                    placeholder="Contrasenya"
                    v-model="formData.password"
                    @blur="validatePassword"
                    @keyup.enter="onEnterPressed('password')"
                    required
                    :class="{ 'input-error': errors.password }"
                    class="input"
                  />
                  <button
                    type="button"
                    @click="togglePasswordVisibilityLogin"
                    class="show-password-btn"
                  >
                    <span v-if="passwordVisibleLogin">
                      <img src="/public/close-eye.png" alt="Icono" class="ojo" />
                    </span>
                    <span v-else>
                      <img src="/public/open-eye.png" alt="Icono" class="ojo" />
                    </span>
                  </button>
                </div>
                <div v-if="errors.password" class="input-error-message">
                  {{ errors.password }}
                </div>
              </div>
              <button
                type="submit"
                class="button login"
                :disabled="!isLoginFormValid()"
              >
                Iniciar sessió
              </button>
            </form>

            <div class="slide-button-Formulario">
              No tens compte?
              <span class="slide-button" @click="togglebutton"
                >Registra't</span
              >
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
                  :class="{ 'input-error': errors.name }"
                  class="input"
                />
                <div v-if="errors.name" class="input-error-message">
                  {{ errors.name }}
                </div>
              </div>
              <div class="info">
                <input
                  type="email"
                  placeholder="Correu electrònic"
                  v-model="formData.email"
                  @blur="validateEmail"
                  required
                  :class="{ 'input-error': errors.email }"
                  class="input"
                />
                <div v-if="errors.email" class="input-error-message">
                  {{ errors.email }}
                </div>
              </div>
              <!-- Input de contraseña -->
              <div class="info">
                <div class="password-input-Formulario">
                  <input
                    :type="passwordVisibleRegister ? 'text' : 'password'"
                    id="password"
                    placeholder="Contrasenya"
                    v-model="formData.password"
                    @blur="validatePassword"
                    :class="{
                      'input-error': errors.password,
                      'input-valid': formData.password && !errors.password,
                    }"
                    class="input"
                  />

                  <button
                    type="button"
                    @click="togglePasswordVisibilityRegister"
                    class="show-password-btn"
                  >
                    <span v-if="passwordVisibleRegister">
                      <img src="/public/close-eye.png" alt="Icono" class="ojo" />
                    </span>
                    <span v-else>
                      <img src="/public/open-eye.png" alt="Icono" class="ojo" />
                    </span>
                  </button>
                </div>
                <div v-if="errors.password" class="input-error-message">
                  {{ errors.password }}
                </div>
              </div>

              <!-- Input de repetir contraseña -->
              <div class="info">
                <div class="password-input-Formulario">
                  <input
                    :type="passwordVisibleRepeat ? 'text' : 'password'"
                    id="passwordRepeat"
                    placeholder="Repetir contrasenya"
                    v-model="formData.passwordRepeat"
                    @blur="validatePasswordRepeat"
                    :class="{
                      'input-error': errors.passwordRepeat,
                      'input-valid':
                        formData.passwordRepeat && !errors.passwordRepeat,
                    }"
                    class="input"
                  />

                  <button
                    type="button"
                    @click="togglePasswordVisibilityRepeat"
                    class="show-password-btn"
                  >
                    <span v-if="passwordVisibleRepeat">
                      <img src="/public/close-eye.png" alt="Icono" class="ojo" />
                    </span>
                    <span v-else>
                      <img src="/public/open-eye.png" alt="Icono" class="ojo" />
                    </span>
                  </button>
                </div>
                <div v-if="errors.passwordRepeat" class="input-error-message">
                  {{ errors.passwordRepeat }}
                </div>
              </div>

              <button
                type="submit"
                class="button password_register"
                :disabled="!isRegisterFormValid()"
              >
                Registrar-se
              </button>
            </form>
            <div class="slide-button-Formulario">
              Ja tens compte?
              <span class="slide-button" @click="togglebutton"
                >Iniciar sessió</span
              >
            </div>
            <p v-if="errorMessage" class="error-message">{{ errorMessage }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref, onUnmounted, onMounted, watch } from "vue";
import { useRouter } from '#app';
import { useAppStore } from "@/stores/app";
import { useLliureStore } from "~/stores/app";
import { onBeforeUnmount } from "vue";


onMounted(() => {
  document.body.classList.add('login-page');  // Añadir clase para la página de login
});

onBeforeUnmount(() => {
  document.body.classList.remove('login-page');  // Eliminar clase cuando se navega a otra página
});

const router = useRouter();
const appStore = useAppStore();
const lliureStore = useLliureStore();

const passwordVisibleLogin = ref(false); // Para el login
const passwordVisibleRegister = ref(false); // Para el registro
const passwordVisibleRepeat = ref(false); // Para el repetir contraseña

const errorMessage = ref("");
const showRegister = ref(false); // Valor inicial, asegúrate de que sea false


const gohome = () => {
  showRegister.value = !showRegister.value;
  router.push('/'); // Esto ahora funcionará correctamente
};


const togglePasswordVisibilityLogin = () => {
  passwordVisibleLogin.value = !passwordVisibleLogin.value;
};

const togglePasswordVisibilityRegister = () => {
  passwordVisibleRegister.value = !passwordVisibleRegister.value;
};

const togglePasswordVisibilityRepeat = () => {
  passwordVisibleRepeat.value = !passwordVisibleRepeat.value;
};

onMounted(() => {
  lliureStore.toggleLliure();
});
onUnmounted(() => {
  lliureStore.toggleLliure();
});

const formData = reactive({
  email: "",
  password: "",
  name: "",
  passwordRepeat: "",
});

const errors = reactive({
  email: "",
  password: "",
  name: "",
  passwordRepeat: "",
});



const togglebutton = () => {
  showRegister.value = !showRegister.value;
  
};

const validateEmail = () => {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!formData.email) {
    errors.email = ""; // Si está vacío, no hay error
  } else {
    errors.email = emailRegex.test(formData.email) ? "" : "Correo inválido";
  }
};

const validatename = () => {
  errors.name = formData.name ? "" : "El nombre de usuario es obligatorio";
};

const validatePassword = () => {
  if (!formData.password) {
    errors.password = ""; // Si está vacío, no hay error
  } else if (formData.password !== formData.password) {
    errors.password = formData.password ? "" : "La contraseña es obligatoria";
  } else {
    errors.password = null; // No hay error si coinciden
  }
};

const validatePasswordRepeat = () => {
  // Si alguno de los dos campos está vacío, se limpian los errores.
  if (!formData.password || !formData.passwordRepeat) {
    errors.password = "";
    errors.passwordRepeat = "";
    return;
  }

  // Si no coinciden, se marca error en ambos.
  if (formData.password !== formData.passwordRepeat) {
    errors.password = "Las contraseñas no coinciden";
    errors.passwordRepeat = "Las contraseñas no coinciden";
  } else {
    // Si coinciden, se eliminan los errores.
    errors.password = null;
    errors.passwordRepeat = null;
  }
};

// Observar cambios en ambos campos
watch(() => formData.password, validatePasswordRepeat);
watch(() => formData.passwordRepeat, validatePasswordRepeat);

const isLoginFormValid = () => {
  return (
    formData.email && formData.password && !errors.email && !errors.password
  );
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
    const response = await fetch("http://127.0.0.1:8000/api/login", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        email: formData.email,
        password: formData.password,
      }),
    });
    const data = await response.json();
    if (response.ok) {
      const loginInfo = {
        loggedIn: true,
        id: data.user.id,
        token: data.token,
        name: data.user.name,
        email: data.user.email,
        nivel: data.user.nivel_html,
        nivel_css: data.user.nivel_css,
        nivel_js: data.user.nivel_js,
        avatar: data.user.avatar,
      };
      appStore.setLoginInfo(loginInfo);
      console.log("Login info:", loginInfo);
      router.push("/");
    } else {
      errorMessage.value = data.message || "Credenciales inválidas";
    }
  } catch (err) {
    errorMessage.value = "Error de red. No se pudo conectar al servidor.";
  }
};

const register = async () => {
  validateEmail();
  validatename();
  validatePassword();
  validatePasswordRepeat();

  if (isRegisterFormValid()) {
    // Genera la URL del avatar basado en el nombre de usuario
    const avatarUrl = `https://api.dicebear.com/9.x/personas/svg?seed=${formData.name}`;

    try {
      // Llamada al endpoint de registro
      console.log("entrado en fetch register", {
        ...formData,
        avatar: avatarUrl,
      });

      const response = await fetch("http://localhost:8000/api/register", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
        },
        body: JSON.stringify({
          ...formData,
          avatar: avatarUrl,
        }),
      });

      const data = await response.json();

      if (response.ok) {
        // Luego de registrarse, se inicia sesión automáticamente
        const loginResponse = await fetch("http://127.0.0.1:8000/api/login", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            email: formData.email,
            password: formData.password,
          }),
        });

        const loginData = await loginResponse.json();
        if (loginResponse.ok) {
          // Guarda en Pinia y en localStorage la misma información que en el login
          appStore.setLoginInfo({
            loggedIn: true,
            id: loginData.user.id,
            token: loginData.token,
            name: loginData.user.name,
            email: loginData.user.email,
            nivel: data.user.nivel_html,
            nivel_css: loginData.user.nivel_css,
            nivel_js: loginData.user.nivel_js,
            avatar: avatarUrl,
          });

          router.push("/");
        } else {
          errorMessage.value =
            loginData.message || "Error al iniciar sesión automáticamente";
        }
      } else {
        errorMessage.value = data.message || "Error al crear la cuenta";
      }
    } catch (error) {
      console.error("Error durante el registro:", error);
      errorMessage.value = "Error de red. No se pudo conectar al servidor.";
    }
  }
};
</script>
<style scoped, lang="scss">
:root {
  --background-image: url(/deserd.png);
  --primary-color: #f2eeeb;
  --secondary-color: #5353ff;
  --title-color: black;
  --text-color: white;
  --valid-color: #00ff00;
  --invalid-color: #ff0000;
  --font-family: "Poppins", sans-serif;
  --large-font-size: calc(3rem + 1.625vh);
  --medium-font-size: min(calc(1rem + 0.725vh), 1.25rem);
  --small-font-size: min(calc(0.675rem + 0.5vh), 1rem);
} 

body.login-page {
  min-height: 100%;
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
.login-wrapper {
  min-height: calc(100vh - 60px);  /* Mantiene la altura al 100% */
  padding-bottom: 60px; /* Deja espacio suficiente para que el contenido no tape el footer */
  display: flex;
  flex-direction: column;
}


.slider {
  width: 50%;
  height: 100%;
  overflow: hidden;
  position: absolute;
  transition: margin-left 0.9s ease;
  margin-left: 0;
}
.slider.slide {
  margin-left: 50%;
}

.atras {
  position: relative;
  z-index: 10; 
  width: 20%;
  background-color: var(--primary-color);
  font-size: var(--medium-font-size);
  border: none;
  border-radius: 25px;
  padding: 1%;
  text-transform: capitalize;
  font-weight: 600;
  color: var(--title-color);
  margin: auto;
  transition: all 0.25s;
  cursor: pointer;
  left: 10%;  /* Ajusta este valor para mover más a la derecha */
  margin-top: 20px;  &:hover {
    background-color: var(--secondary-color);
    color: var(--primary-color);
  }
}

.Formulario {
  display: flex;
  width: 200%;
  height: 100%;
  transition: transform 0.9s ease;
  transform: translateX(0);
}

.slider.slide .Formulario {
  transform: translateX(-50%);
}

.left {
  width: 50%;
  height: 100%;
  display: flex;
  align-items: center;
}

.right {
  width: 50%;
  height: 100%;
  display: flex;
  align-items: center;
}

.content {
  background: rgba(0, 0, 0, 0.6);
  padding: 20%;
  text-align: center;
  color: white;
  width: 100%;
  margin: 0 auto;
  position: relative;
  top: 60%;
  left: 50%;
  transform: translate(-50%, -50%);
  height: 100%;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.info {
  position: relative;
  margin: 1rem 0;
  text-align: center;
}

.input {
  width: 90%;
  border: 1px solid var(--primary-color);
  background-color: rgba(0, 0, 0, 0.4);
  border-radius: 50px;
  padding: 0.8rem 0.7rem;
  font-size: var(--medium-font-size);
  color: var(--text-color);
  outline: none;
  margin-top: 0.5rem;
}

.button {
  width: 50%;
  background-color: var(--primary-color);
  font-size: var(--medium-font-size);
  border: none;
  border-radius: 25px;
  padding: 1rem;
  text-transform: capitalize;
  font-weight: 600;
  color: var(--title-color);
  margin: 0.9rem 0;
  transition: all 0.25s;
  cursor: pointer;

  &:hover {
    background-color: var(--secondary-color);
    color: var(--primary-color);
  }
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

.password-input-Formulario {
  position: relative;
}

.show-password-btn {
  position: absolute;
  top: 70%;
  left: 87%;
  transform: translateY(-70%);
  background: none;
  border: none;
  cursor: pointer;
  font-size: 18px;
}

.ojo {
  width: 20px;
  height: 20px;
}

.title {
  font-weight: bold;
  font-size: 30px;
}

.input-error-message {
  font-size: 0.85rem;
  color: var(--invalid-color);
  text-align: left;
  margin-top: 5px;
  top: 50%;
}

.input-valid {
  border-color: var(--valid-color);
}

.input-error {
  border-color: var(--invalid-color);
}

@media (max-width: 1080px) {

@media (max-width: 1080px) {
  .content {
    background: rgba(0, 0, 0, 0.6);
    padding: 10%;
    text-align: center;
    color: white;
    width: 100%;
    margin: 0 auto;
    position: relative;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    height: 100%;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  .slider {
    width: 100%;
    height: 100%;
    overflow: hidden;
    position: absolute;
    transition: margin-left 0.9s ease;
    margin-left: 0;
  }

  .slider.slide {
    margin-left: 0%;
  }

  .left {
    width: 50%;
    height: 100%;
    display: flex;
    align-items: center;
  }

  .right {
    width: 50%;
    height: 100%;
    display: flex;
    align-items: center;
  }

  .button {
    width: 40%;
    background-color: var(--primary-color);
    font-size: var(--medium-font-size);
    border: none;
    border-radius: 25px;
    padding: 3%;
    text-transform: capitalize;
    font-weight: 600;
    color: var(--title-color);
    margin: 0.5rem 20%;
    transition: all 0.25s;
    cursor: pointer;

    &:hover {
      background-color: var(--secondary-color);
      color: var(--primary-color);
    }
  }

  .input {
    width: 80%;
    border: 5px solid var(--primary-color);
    background-color: rgba(0, 0, 0, 0.4);
    border-radius: 50px;
    padding: 2% 2%;
    font-size: var(--medium-font-size);
    color: var(--text-color);
    outline: none;
  }

  .show-password-btn {
    position: absolute;
    top: 70%;
    left: 85%;
    transform: translateY(-70%);
    background: none;
    border: none;
    cursor: pointer;
    font-size: 18px;
  }

  .input-valid {
    border-color: var(--valid-color);
  }

  .input-error {
    border-color: var(--invalid-color);
  }

  .input-error-message {
    font-size: 25px;
    color: var(--invalid-color);
    text-align: left;
    margin-top: 5px;
  }
  
}
  @media (max-width: 400) {
    .content {
      background: rgba(0, 0, 0, 0.6);
      padding: 20%;
      text-align: center;
      color: white;
      width: 100%;
      margin: 0 auto;
      position: relative;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      height: 100%;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }
    .slider {
      width: 100%;
      height: 100%;
      overflow: hidden;
      position: absolute;
      transition: margin-left 0.9s ease;
      margin-left: 0;
    }
    .slider.slide {
      margin-left: 0%;
    }

    .left {
      width: 50%;
      height: 100%;
      display: flex;
      align-items: center;

    .right {
      width: 50%;
      height: 100%;
      display: flex;
      align-items: center;
    }
    .button {
      width: 40%;
      background-color: var(--primary-color);
      font-size: var(--medium-font-size);
      border: none;
      border-radius: 25px;
      padding: 3%;
      text-transform: capitalize;
      font-weight: 600;
      color: var(--title-color);
      margin: 0.5rem 20%;
      transition: all 0.25s;
      cursor: pointer;
      &:hover {
        background-color: var(--secondary-color);
        color: var(--primary-color);
      }
    }
    .input {
      width: 80%;
      border: 5px solid var(--primary-color);
      background-color: rgba(0, 0, 0, 0.4);
      border-radius: 50px;
      padding: 2% 2%;
      font-size: var(--medium-font-size);
      color: var(--text-color);
      outline: none;
    }

    .show-password-btn {
      position: absolute;
      top: 70%;
      left: 85%;
      transform: translateY(-70%);
      background: none;
      border: none;
      cursor: pointer;
      font-size: 18px;
    }
    .input-valid {
      border-color: var(--valid-color);
    }

    .input-error {
      border-color: var(--invalid-color);
    }
    .input-error-message {
      font-size: 25px;
      color: var(--invalid-color); 
      text-align: left;
      margin-top: 5px;
    }
  } 
  
}
}
</style>
