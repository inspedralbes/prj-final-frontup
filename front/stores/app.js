import { defineStore } from 'pinia';

export const useAppStore = defineStore('app', {
  state: () => ({
    loginInfo: JSON.parse(localStorage.getItem('loginInfo')) || {
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
      localStorage.removeItem('loginInfo'); 
      localStorage.removeItem('token');
      localStorage.removeItem('avatarUrl'); 
    },

    setLoginInfo({ loggedIn, token, username, email, role, image, imageId }) {
      this.loginInfo = { loggedIn, token, username, email, role, image, imageId };
      localStorage.setItem('loginInfo', JSON.stringify(this.loginInfo)); 
      localStorage.setItem('token', token);
      if (image) {
        localStorage.setItem('avatarUrl', image); n
      }
    },
  },

  getters: {
    isLoggedIn: (state) => state.loginInfo.loggedIn,

    getLoginInfo: (state) => state.loginInfo,
  },
});
