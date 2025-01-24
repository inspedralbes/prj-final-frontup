// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2024-11-01',
  devtools: { enabled: true },
  plugins: [
    '~/plugins/pinia.js',  // Asegúrate de que el plugin esté registrado
  ],
  build: {
    transpile: ['pinia'],  // Transpila Pinia si es necesario
  }
});
