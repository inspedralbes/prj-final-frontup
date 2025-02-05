<template>
  <div class="level-container">
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
        locked: i > 0
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
  background-image: url('/fondo2.png');
  height: 99vh;
  width: 90%;
  margin-left: auto;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
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
  background-image: url('/boton.png');
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
  background-image: url('/boton.png');
  transform: scale(1.1);
  box-shadow: 0 6px 8px rgba(0, 0, 0, 0.3);
}

/* 🔒 Estilo para niveles bloqueados */
.level-button.locked {
  background-image: url('/boton_lock.png');
  cursor: not-allowed;
}

.level-button.locked:hover {
  background-image: url('/boton_lock.png');
  transform: scale(1.03);
}

.lock-icon {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  font-size: 24px;
}


.level-button-container:nth-child(1) { top: 40%; left: 10%; }
.level-button-container:nth-child(2) { top: 60%; left: 18%; }
.level-button-container:nth-child(3) { top: 40%; left: 26%; }
.level-button-container:nth-child(4) { top: 60%; left: 34%; }
.level-button-container:nth-child(5) { top: 40%; left: 42%; }
.level-button-container:nth-child(6) { top: 60%; left: 50%; }
.level-button-container:nth-child(7) { top: 40%; left: 58%; }
.level-button-container:nth-child(8) { top: 60%; left: 66%; }
.level-button-container:nth-child(9) { top: 40%; left: 74%; }
.level-button-container:nth-child(10) { top: 60%; left: 82%; }

</style>