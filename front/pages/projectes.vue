<template>
  <body>
      <h1>Els meus projectes</h1>

      <button class="btn" :class="theme" @click="goBack">Tornar</button>
      <div class="grid-container">
          <div class="grid-item">
              <p>nom projecte</p>
              <br>
              <div class="projecte"></div>
          </div>
          <div class="grid-item">
              <p>nom projecte</p>
              <br>
              <div class="projecte"></div>
          </div>
          <div class="grid-item">
              <p>nom projecte</p>
              <br>
              <div class="projecte"></div>
          </div>
      </div>
  </body>
</template>

<script>
export default {
  name: "projectes",
  methods: {
      goBack(){
          this.$router.push('/'); 
      }
  },
};

</script>

<style scoped>
body {
  background-color: #202020;
  margin-top: -20px;
  font-family: Arial, sans-serif;
}

h1 {
  text-align: center;
  color: white;
}

.grid-container {
  display: grid;
  grid-template-columns: 3fr 3fr 3fr;
  gap: 10px;
  margin: 10%;
  margin-top: 5%;
}

.grid-item {
  background-color: #404040;
  padding: 20px;
  width: 300px;
  height: 200px;
  text-align: center;
  border: 1px solid #000000;
  display: flex;
  flex-direction: column;
}

p {
  text-align: left;
  margin-top: 0;
}

.projecte {
  background-color: #303030;
  width: 100%;
  height: 100%;
}

.btn {
padding: 10px 15px;
background-color: #333;
color: #fff;
cursor: pointer;
text-transform: uppercase;
border-radius: 4px;
margin-left: 10px;
}
</style>
