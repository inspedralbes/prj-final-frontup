<template>
  <div class="todo">
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
      <div class="card">
        <h3>Build & Test</h3>
        <p>
          Get work done quicker by building out entire projects or isolating
          code to test features and animations.
        </p>
        <button>Try the Editor</button>
      </div>
      <div class="card">
        <h3>Learn & Discover</h3>
        <p>
          Want to upgrade your skills and get noticed? Participating in
          CodePen Challenges is a great way to try something new.
        </p>
        <button>Join this Week's Challenge</button>
      </div>
      <div class="card">
        <h3>Share Your Work</h3>
        <p>
          Become a part of the most active front-end community in the world
          by sharing work.
        </p>
        <button>Explore Trending</button>
      </div>
    </div>
    <br><br>
    <!-- Footer -->
    <footer>
      <p>© 2025 FrontUp</p>
    </footer>
  </div>
</div>
</template>

<script>
export default {
  name: 'Index',
};
</script>

<style scoped>

.todo{
  background-color: rgb(177, 162, 162);
  margin-right: -8px;
  margin-bottom: -8px;
}
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

footer {
  background-color: #404040;
  text-align: center;
  padding: 10px 20px;
  color: white;
  margin-top: 150px;
  margin-left: -50px;
  margin-right : -20px; 
  margin-bottom: -20px;
}
</style>
