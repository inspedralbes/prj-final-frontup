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
