import { defineStore } from 'pinia';

export const useAppStore = defineStore('app', {
  state: () => ({
    loginInfo: JSON.parse(localStorage.getItem('loginInfo')) || {
      loggedIn: false, // Valor predeterminado
      token: '',
      username: '',
      email: '',
      role: '',
      image: localStorage.getItem('avatarUrl') || '',
      imageId: '',
    },
    registrationInfo: JSON.parse(localStorage.getItem('registrationInfo')) || {
      loggedIn: false,
      token: '',
      username: '',
      email: '',
      role: '',
      image: localStorage.getItem('avatarUrl') || '',
      imageId: '',
    },
  }),

  actions: {
    // Acción de logout para limpiar los datos del usuario
    logout() {
      this.loginInfo = {
        loggedIn: false,
        token: '',
        username: '',
        email: '',
        role: '',
        image: '',
        imageId: ''
      };
      localStorage.removeItem('loginInfo'); // Eliminar loginInfo de localStorage
      localStorage.removeItem('token'); // Eliminar token de localStorage
      localStorage.removeItem('avatarUrl'); // Eliminar avatarUrl de localStorage (si es necesario)
    },

    // Acción para almacenar la información de login
    setLoginInfo({ loggedIn, token, username, email, role, image, imageId }) {
      this.loginInfo = { loggedIn, token, username, email, role, image, imageId };
      localStorage.setItem('loginInfo', JSON.stringify(this.loginInfo)); // Guardar en localStorage
      localStorage.setItem('token', token); // Guardar solo el token
      if (image) {
        localStorage.setItem('avatarUrl', image); // Guardar avatarUrl si hay imagen
      }
    },

    // Acción para almacenar la información de registro (si la necesitas)
    setRegistrationInfo({ loggedIn, token, username, email, role, image, imageId }) {
      this.registrationInfo = { loggedIn, token, username, email, role, image, imageId };
      localStorage.setItem('registrationInfo', JSON.stringify(this.registrationInfo));
    },
  },

  getters: {
    // Verificar si el usuario está autenticado
    isLoggedIn: (state) => state.loginInfo.loggedIn,

    // Obtener información del usuario logueado
    getLoginInfo: (state) => state.loginInfo,

    // Obtener información del registro (si la usas)
    getRegistrationInfo: (state) => state.registrationInfo,
  },
});
