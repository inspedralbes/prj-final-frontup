import { defineStore } from 'pinia';

export const useLliureStore = defineStore('lliure', {
  state: () => ({
    lliure: false, 
  }),
  actions: {
    toggleLliure() {
      this.lliure = !this.lliure;
    },
    setLliure(value) {
      this.lliure = value; 
    },
  },
});

export const useAppStore = defineStore('app', {
  state: () => ({
    loginInfo: typeof window !== 'undefined' && localStorage.getItem('loginInfo')
      ? JSON.parse(localStorage.getItem('loginInfo'))
      : {
          loggedIn: false,
          id: null,
          token: '',
          name: '',
          email: '',
          nivel_html: '',
          nivel_css: '',
          nivel_js: '',
          avatar: typeof window !== 'undefined' ? localStorage.getItem('avatarUrl') || '' : '',
        },
  }),

  actions: {
    logout() {
      this.loginInfo = {
        loggedIn: false,
        token: '',
        name: '',
        id: '',
        email: '',
        nivel_html: '',
        nivel_css: '',
        nivel_js: '',
        avatar: '',
      };
      if (typeof window !== 'undefined') {
        localStorage.removeItem('loginInfo');
        localStorage.removeItem('token');
        localStorage.removeItem('avatarUrl');
      }
    },

    setLoginInfo({ id, loggedIn, token, name, email, nivel_html, nivel_css, nivel_js, avatar }) {
      this.loginInfo = { loggedIn, token, name, email, nivel_html, nivel_css, nivel_js, avatar, id};
      if (typeof window !== 'undefined') {
        localStorage.setItem('loginInfo', JSON.stringify(this.loginInfo));
        localStorage.setItem('token', token);
        if (avatar) {
          localStorage.setItem('avatarUrl', avatar);
        }
      }
    },
  },

  getters: {
    isLoggedIn: (state) => state.loginInfo.loggedIn,
    getLoginInfo: (state) => state.loginInfo,
  },
});

export const useBuscadorStore = defineStore('buscador', {
  state: () => ({
    mostrarBuscador: false,
  }),
  actions: {
    activarBuscador() {
      this.mostrarBuscador = true;
    },
    desactivarBuscador() {
      this.mostrarBuscador = false;
    }
  }
});
