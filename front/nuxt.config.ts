// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2024-11-01',
  devtools: { enabled: true },
  ssr: false,
  runtimeConfig: {
    public: {
      apiBaseUrl: process.env.NUXT_PUBLIC_API_BASE_URL,
      iaApiUrl: process.env.NUXT_PUBLIC_IA_API_URL,
    }
  },
  plugins: [
    '~/plugins/pinia.js',  
  ],
  css: [
    '@/assets/normalize.css'
   ],
  build: {
    transpile: ['pinia'],
  }
});
