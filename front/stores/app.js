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
          token: '',
          name: '',
          email: '',
          nivel_html: '',
          nivel_css: '',
          nivel_javascript: '',
          avatar: typeof window !== 'undefined' ? localStorage.getItem('avatarUrl') || '' : '',
          imageId: '',
        },
  }),

  actions: {
    logout() {
      this.loginInfo = {
        loggedIn: false,
        token: '',
        name: '',
        email: '',
        nivel_html: '',
        nivel_css: '',
        nivel_javascript: '',
        avatar: '',
        imageId: '',
      };
      if (typeof window !== 'undefined') {
        localStorage.removeItem('loginInfo');
        localStorage.removeItem('token');
        localStorage.removeItem('avatarUrl');
      }
    },

    setLoginInfo({ loggedIn, token, name, email, nivel_html, nivel_css, nivel_javascript, avatar, imageId }) {
      this.loginInfo = { loggedIn, token, name, email, nivel_html, nivel_css, nivel_javascript, avatar, imageId };
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
