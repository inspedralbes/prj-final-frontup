<template>
  <div>
    <!-- Left Section -->
    <div class="left-section" :class="theme">
      <h2>CodeCod</h2>
      <button class="btn" :class="theme">Mis proyectos</button>
      <button class="btn" :class="theme" @click="navigateToLliure">Libre</button>
      <button class="btn" :class="theme">Niveles</button>
      <button class="btn" :class="theme">Proyectos favoritos</button>
    </div>      

    <!-- Header -->
    <header :class="theme">
      <div class="header-left">
        <!-- Buscador -->
        <input class="search-box" type="text" placeholder="Buscar...">
      </div>
      <div class="header-right">
        <button @click="toggleTheme" class="btn" :class="theme">{{ themeIcon }}</button>

        <!-- Mostrar botones de login/registro solo si el usuario NO está autenticado -->
        <button v-if="!isAuthenticated" class="btn" :class="theme" @click="goToRegister">{{ registerText }}</button>
        <button v-if="!isAuthenticated" class="btn" :class="theme" @click="goToLogin">{{ loginText }}</button>
        
        <!-- Mostrar botón de perfil solo si el usuario está autenticado -->
        <button v-if="isAuthenticated" class="btn" :class="theme" @click="goToProfile">Mi perfil</button>
      </div>
    </header>

    <!-- Main Content -->
    <div class="main-container">
      <!-- Body Content -->
      <div class="body-content">
        <h2>The best place to build <br>
           test, and discover front- <br>end code.</h2>
        <p>CodeCod is a social development environment for front-end<br> 
          designers and developers. Build and deploy a website, show off your <br>
          work, build test cases to learn and debug, and find inspiration.</p>
      </div>

      <!-- Card Container -->
      <div class="card-container">
        <div class="card" :class="theme">
          <h3 :class="theme">Build & Test</h3>
          <p :class="theme">
            Get work done quicker by building out entire projects or isolating
            code to test features and animations.
          </p>
          <button :class="theme">Try the Editor</button>
        </div>
        <div class="card" :class="theme">
          <h3 :class="theme">Learn & Discover</h3>
          <p :class="theme">
            Want to upgrade your skills and get noticed? Participating in
            CodePen Challenges is a great way to try something new.
          </p>
          <button :class="theme">Join this Week's Challenge</button>
        </div>
        <div class="card" :class="theme">
          <h3 :class="theme">Share Your Work</h3>
          <p :class="theme">
            Become a part of the most active front-end community in the world
            by sharing work.
          </p>
          <button :class="theme">Explore Trending</button>
        </div>
      </div>
    </div>
    <br><br>
    <!-- Footer -->
    <footer :class="theme">
      <p>{{ footerText }}</p>
    </footer>
  </div>
</template>

<script>
export default {
  name: 'HomePage',
  data() {
    return {
      theme: '',
      themeIcon: '🌙',
      footerText: '© 2025 Mi Página Web',
      registerText: 'Registro',
      loginText: 'Login',
      isAuthenticated: false, // Controla si el usuario está autenticado
    };
  },
  methods: {
    toggleTheme() {
      if (this.theme === '') {
        this.theme = 'light-mode';
        this.themeIcon = '☀️';
      } else {
        this.theme = '';
        this.themeIcon = '🌙';
      }
      document.body.className = this.theme;
    },
    navigateToLliure() {
      this.$router.push('/lliure'); 
    },
    goToRegister() {
      this.$router.push('/register'); 
    },
    goToLogin() {
      this.$router.push('/login'); 
    },
    goToProfile() {
      this.$router.push('/perfil'); // Redirige al perfil del usuario
    },
    // Simulación de autenticación (esto normalmente se gestionaría con una API o localStorage)
    authenticateUser() {
      this.isAuthenticated = true; // Establecer como verdadero cuando el usuario esté autenticado
    },
    logout() {
      this.isAuthenticated = false; // Al hacer logout, se pone en falso
    }
  },
  mounted() {
    // Si el usuario está autenticado (esto depende de tu lógica de autenticación)
    this.authenticateUser(); // Simulación de que el usuario ya está autenticado
  },
};
</script>

<style>
body {
  display: flex;
  flex-direction: row; /* Fixing flex-direction to 'row' for side-by-side layout */
  height: 100%;
  margin: 0;
  font-family: 'Arial', sans-serif;
  padding-left: 180px; /* Adds space for left section */
  background-color: #202020;
  overflow-x: hidden;
  color: white;
}

body.light-mode {
  background-color: #dbcccc;
  color: black;
}

header {
  background-color: black;
  color: white;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px 20px;
  height: 50px;
}

header.light-mode {
  background-color: white;
  color: black;
}

.header-left {
  display: flex;
  align-items: center;
}

.header-right {
  display: flex;
  gap: 20px;
}

.search-box {
  padding: 5px;
  font-size: 16px;
  border: none;
  border-radius: 4px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.btn {
  padding: 10px 15px;
  background-color: #333;
  color: #fff;
  cursor: pointer;
  text-transform: uppercase;
  border-radius: 4px;
}

.btn.light-mode {
  background-color: #ccc;
  color: black;
}

footer {
  background-color: #333;
  color: white;
  text-align: center;
  padding: 10px;
  bottom: 0;
  width: 100%;
}

footer.light-mode {
  background-color: #cfcdcd;
  color: black;
}

.left-section {
  position: fixed;
  left: 0;
  width: 180px;
  height: 100%;
  background-color: #404040;
  padding: 15px;
  box-sizing: border-box;
}

.left-section button {
  margin-bottom: 15px;
}

.left-section.light-mode {
  background-color: #cfcdcd;
  color: black;
}

.left-section button.light-mode {
  background-color: #ccc;
  color: black;
}

.main-container {
  display: flex;
  flex-direction: column;
  margin-left: 180px; /* Adds space for left section */
}

.body-content {
  margin: 50px;
}

.card-container {
  display: flex;
  justify-content: center;
  gap: 20px;
  margin-top: 20px;
  flex-wrap: wrap;
}

.card {
  background-color: #2C303A;
  padding: 20px;
  border-radius: 8px;
  text-align: center;
  width: 300px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.card p {
  color: #C7C9D3;
}

.card button {
  padding: 10px 15px;
  background-color: #333;
  color: #fff;
  cursor: pointer;
  text-transform: uppercase;
  border-radius: 4px;
}

.card.light-mode {
  background-color: #ffffff;
  color: black;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.card h3.light-mode {
  color: black;
}

.card p.light-mode {
  color: #333;
}

.card button.light-mode {
  background-color: #ddd;
  color: black;
}
</style>
