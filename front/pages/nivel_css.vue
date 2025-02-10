<template>
  <div class="level-container">
    <button class="back-button" @click="irAtras">Atrás</button>
    <div
      v-for="level in levels"
      :key="level.id"
      class="level-button-container"
    >
      <div
        class="level-button"
        :class="{ locked: level.locked }"
        @click="!level.locked && ir_nivel(level.id)"
        @mouseenter="hoveredLevel = level.id"
        @mouseleave="hoveredLevel = null"
      >
        {{ level.id }}
        <div v-if="level.locked" class="lock-icon">🔒</div>
      </div>
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
  height: 99vh;
  width: 90%;
  margin-left: auto;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  background-color: #202020;
  border: 1px solid #ddd;
  border-radius: 10px;
  background-size: cover;
  background-position: center;
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
  background-color: #3498db;
  transform: scale(1.03);
  border-radius: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  font-weight: bold;
  color: white;
  cursor: pointer;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
  transition: background-color 0.3s, transform 0.3s, box-shadow 0.3s;
}

.level-button:hover {
  background-color: #2980b9;
  transform: scale(1.1);
  box-shadow: 0 6px 8px rgba(0, 0, 0, 0.3);
}

/* 🔒 Estilo para niveles bloqueados */
.level-button.locked {
  background-color: #7f8c8d;
  cursor: not-allowed;
}

.level-button.locked:hover {
  background-color: #7f8c8d;
  transform: scale(1.03);
}

.lock-icon {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  font-size: 24px;
}

.level-button-container:nth-child(2) { top: 50%; left: 10%; }
.level-button-container:nth-child(3) { top: 30%; left: 20%; }
.level-button-container:nth-child(4) { top: 50%; left: 30%; }
.level-button-container:nth-child(5) { top: 30%; left: 40%; }
.level-button-container:nth-child(6) { top: 50%; left: 50%; }
.level-button-container:nth-child(7) { top: 30%; left: 60%; }
.level-button-container:nth-child(8) { top: 50%; left: 70%; }
.level-button-container:nth-child(9) { top: 30%; left: 80%; }
.level-button-container:nth-child(10) { top: 50%; left: 90%; }
.level-button-container:nth-child(11) { top: 50%; left: 90%; }
.back-button {
  position: absolute;
  top: 20px;
  left: 75%;
  padding: 10px 20px;
  background-color: #34495e;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
  transition: background-color 0.3s;
  z-index: 10; /* Asegura que el botón esté por encima de otros elementos */
}

.back-button:hover {
  background-color: #2c3e50;
}
</style>