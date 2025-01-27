import { defineStore } from 'pinia';

export const useLliureStore = defineStore('lliure', {
  state: () => ({
    lliure: false, // Variable booleana
  }),
  actions: {
    toggleLliure() {
      this.lliure = !this.lliure; // Cambia el valor de la variable
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
          token: '',
          username: '',
          email: '',
          role: '',
          image: typeof window !== 'undefined' ? localStorage.getItem('avatarUrl') || '' : '',
          imageId: '',
        },
  }),

  actions: {
    logout() {
      this.loginInfo = {
        loggedIn: false,
        token: '',
        username: '',
        email: '',
        role: '',
        image: '',
        imageId: '',
      };
      if (typeof window !== 'undefined') {
        localStorage.removeItem('loginInfo');
        localStorage.removeItem('token');
        localStorage.removeItem('avatarUrl');
      }
    },

    setLoginInfo({ loggedIn, token, username, email, role, image, imageId }) {
      this.loginInfo = { loggedIn, token, username, email, role, image, imageId };
      if (typeof window !== 'undefined') {
        localStorage.setItem('loginInfo', JSON.stringify(this.loginInfo));
        localStorage.setItem('token', token);
        if (image) {
          localStorage.setItem('avatarUrl', image);
        }
      }
    },
  },

  getters: {
    isLoggedIn: (state) => state.loginInfo.loggedIn,
    getLoginInfo: (state) => state.loginInfo,
  },
});
