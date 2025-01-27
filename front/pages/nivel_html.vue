<template>
  <button class="back-button" @click="irAtras">Atrás</button>
  <div class="level-container">
    <div
      v-for="level in levels"
      :key="level.id"
      class="level-button-container"
    >
      <div
        class="level-button"
        @click="ir_nivel(level.id)"
        @mouseenter="hoveredLevel = level.id"
        @mouseleave="hoveredLevel = null"
      >
        {{ level.id }}
      </div>
      <div v-if="hoveredLevel === level.id" class="hover-text">Nivel {{ level.id }}</div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      levels: Array.from({ length: 10 }, (_, i) => ({
        id: i + 1,
      })),
      hoveredLevel: null,
    };
  },
  methods: {
    ir_nivel(levelId) {
      this.$router.push(`/nivel/${levelId}`);
    },
    irAtras() {
      this.$router.push("/niveles");
    }
  },
};
</script>

<style>
.level-container {
  position: relative;
  width: 600px;
  height: 800px;
  margin: auto;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;

  background-color: #f9f9f9;  
  border: 1px solid #ddd;
  border-radius: 10px;
}

.level-button-container {
  position: absolute;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.level-button {
  width: 80px;
  height: 80px;
  background-color: gray;
  border-radius: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  font-weight: bold;
  color: white;
  cursor: pointer;
  transition: transform 0.3s, box-shadow 0.3s;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
}

.level-button:hover {
  transform: scale(1.2);
  background-color: black;
}

.hover-text {
  margin-top: 10px;
  font-size: 14px;
  color: black;
  font-weight: bold;
}

.level-button-container:nth-child(1) { top: 90%; left: 55%; transform: translate(-50%, -50%); }
.level-button-container:nth-child(2) { top: 75%; left: 20%; }
.level-button-container:nth-child(3) { top: 70%; left: 80%; }
.level-button-container:nth-child(4) { top: 60%; left: 25%; }
.level-button-container:nth-child(5) { top: 50%; left: 75%; }
.level-button-container:nth-child(6) { top: 40%; left: 30%; }
.level-button-container:nth-child(7) { top: 30%; left: 70%; }
.level-button-container:nth-child(8) { top: 20%; left: 35%; }
.level-button-container:nth-child(9) { top: 10%; left: 65%; }
.level-button-container:nth-child(10) { top: 0%; left: 50%; }

.back-button {
  position: absolute;
  top: 10px;
  left: 10px;
  padding: 10px 20px;
  background-color: gray;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
  transition: background-color 0.3s;
}

.back-button:hover {
  background-color: black;
}
</style>
