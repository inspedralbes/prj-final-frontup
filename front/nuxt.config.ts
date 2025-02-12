// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2024-11-01',
  devtools: { enabled: true },
  plugins: [
    '~/plugins/pinia.js',  // Asegúrate de que el plugin esté registrado
  ],
  css: [
    '@/assets/normalize.css'
   ],
  build: {
    transpile: ['pinia'],  // Transpila Pinia si es necesario
  },
  app: {
    head: {
      link: [
        { rel: "stylesheet", href: "https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" },
        { rel: "stylesheet", href: "https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&display=swap" },
        { rel: "stylesheet", href: "https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;500;700&display=swap" },
        { rel: "stylesheet", href: "https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" },
        { rel: "stylesheet", href: "https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;700&display=swap" },
        { rel: "stylesheet", href: "https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;700&display=swap" },
        { rel: "stylesheet", href: "https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700&display=swap" },
        { rel: "stylesheet", href: "https://fonts.googleapis.com/css2?family=Raleway:wght@300;400;700&display=swap" },
        { rel: "stylesheet", href: "https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap" },
        { rel: "stylesheet", href: "https://fonts.googleapis.com/css2?family=Fira+Sans:wght@300;400;700&display=swap" },
      ],
    },
  },
});
