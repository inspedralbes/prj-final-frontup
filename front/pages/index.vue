<template>
  
  <div class="todo">
    <!-- Left Section -->
    <div class="left-section" :class="theme">
      <h2>FrontUp</h2>
      <button class="btn" :class="theme">Els meus projectes</button>
      <button class="btn" :class="theme" @click="navigateToLliure">Lliure</button>
      <button class="btn" :class="theme" @click="navigateToNiveles">Nivells</button>
      <button class="btn" :class="theme">Projectes favorits</button>
    
    </div>      

    <!-- Header -->
    <header :class="theme">
      <div class="header-left">
        <!-- Buscador -->
        <input class="search-box" type="text" placeholder="Buscar...">
      </div>
      <div class="header-right">
        <button @click="toggleTheme" class="btn" :class="theme">{{ themeIcon }}</button>
        <button class="btn" :class="theme">{{ registerText }}</button>
        <button class="btn" :class="theme">{{ loginText }}</button>
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
      <br><br><br>
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
      themeIcon: '☀️',
      footerText: '© 2025 FrontUp',
      registerText: 'Registro',
      loginText: 'Login',
    };
  },
  methods: {
    toggleTheme() {
      if (this.theme === '') {
        this.theme = 'light-mode';
        this.themeIcon = '🌙';
      } else {
        this.theme = '';
        this.themeIcon = '☀️';
      }
      document.body.className = this.theme;
    },
    navigateToLliure() {
      this.$router.push('/lliure'); 
    },
    navigateToNiveles() {
      this.$router.push('/niveles'); 
    },
    

  },
};
</script>

<style scoped>

.todo {
  height: 130vh;  
  display: flex;
  flex-direction: column;
  margin-right: -8px;
  background-color: rgb(161, 152, 152);
}

.left-section { 
  position: fixed;
  top: 0;
  left: 0;
  width: 180px;
  height: 100%;  
  background-color: #404040;
  padding: 15px;
  box-sizing: border-box;
}

.left-section .btn {
  padding: 10px 15px;
  background-color: #333;
  color: #fff;
  cursor: pointer;
  text-transform: uppercase;
  border-radius: 4px;
}

.left-section button {
  margin-bottom: 15px;
}

.left-section h2{
  color: white;
}
/* Header */
header {
  position: absolute;
  top: 0;
  left: 180px;    
  right: 0;
  height: 80px;  
  background-color: black;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 20px;  
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
  padding: 8px;
  width: 300px;
  border: 1px solid #ddd;
  border-radius: 4px;
}

.header-right .btn {
  padding: 10px 15px;
  background-color: #333;
  color: #fff;
  cursor: pointer;
  text-transform: uppercase;
  border-radius: 4px;
}

.header-right .btn:hover {
  background-color: #0056b3;
}

/* Main Content */
.main-container {
  margin-top: 80px;  
  margin-left: 200px;  
  padding: 20px;
  flex-direction: column;
  justify-content: space-between;
}

.body-content {
  text-align: left;
  margin-bottom: 40px;
}
.body-content h2 {
  font-size: 2em;
  margin-bottom: 20px;
}
.body-content p {
  font-size: 1em;
  color: #666;
}

/* Card Container */
.card-container {
  display: flex;
  justify-content: space-around;
  gap: 20px;
  margin-right: 30px;
}

.card {
  background-color: #4b4747;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  width: 300px;
  text-align: center;
}

.card h3 {
  margin-bottom: 15px;
  font-size: 25px;
}

.card p {
  color: #d4cece;
  margin-bottom: 20px;
}
.card button {
  padding: 10px 20px;
  border: none;
  border-radius: 4px;
  background-color: rgb(161, 152, 152);
  color: black;
  cursor: pointer;
}

/* Footer */
footer {
  margin-left: 172px;
  background-color: #404040;
  text-align: center;
  padding: 10px 20px;
  position: relative;
  color: white;
  margin-top: 150px;
}

/* Light Mode */
.light-mode {
  background-color: #f7f7f7;  
  color: #333;  
}

.light-mode .todo{
  background-color: #f5ebeb;
}

.light-mode .left-section {
  background-color: #eee0e0; 
}

.light-mode .btn {  
  background-color: #eee;  
  color: #333;  
  border: 1px solid #ccc; 
}

.light-mode .btn:hover {
  background-color: #ddd;  
}

.light-mode header {
  background-color: #cfc8c8;  
}

.light-mode h2{
  color: black;
}
.light-mode .search-box {
  background-color: #fff;  
  border: 1px solid #ddd;  
  color: #333;  
}

.light-mode .card {
  background-color: #eee0e0;  
  color: #333;    
}

.light-mode footer {
  background-color: #eee0e0; 
  color: #333;
}
</style>



<style>
/* Light Mode */
.light-mode {
  background-color: #f7f7f7;  
  color: #333;  
}

.light-mode .todo{
  background-color: #f5ebeb;
}

.light-mode .left-section {
  background-color: #eee0e0; 
}

.light-mode .btn {  
  background-color: #eee;  
  color: #333;  
  border: 1px solid #ccc; 
}

.light-mode .btn:hover {
  background-color: #ddd;  
}

.light-mode header {
  background-color: #cfc8c8;  
}

.light-mode h2{
  color: black;
}
.light-mode .search-box {
  background-color: #fff;  
  border: 1px solid #ddd;  
  color: #333;  
}

.light-mode .card {
  background-color: #eee0e0;  
  color: #333;    
}

.light-mode footer {
  background-color: #eee0e0; 
  color: #333;
}
</style>