<template>
  <div class="level-container">
    <div v-for="level in levels" :key="level.id" class="level-button-container">
      <div
        class="level-button"
        :class="{ locked: level.locked }"
        @click="!level.locked && ir_nivel(level.id)"
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
      language: 'css',
      levels: Array.from({ length: 10 }, (_, i) => ({
        id: i + 11,
        locked: true, 
      })),
      userLevel: 11,
    };
  },
  async created() {
    await this.fetchUserLevel();
  },
  methods: {
    async fetchUserLevel() {
  try {
    const response = await fetch("http://localhost:8000/api/user", {
      headers: {
        Authorization: `Bearer ${localStorage.getItem("token")}`,
      },
    });

    if (!response.ok) throw new Error("Error al obtener el nivel del usuario");

    const data = await response.json();

    this.userLevel = data.user.nivel_css; 

    this.levels = this.levels.map((level) => ({
      ...level,
      locked: level.id > this.userLevel,
    }));
  } catch (error) {
    console.error("Error al cargar el nivel del usuario:", error);
  }
}
,ir_nivel(levelId) {
      this.$router.push(`/nivel/${this.language}/${levelId}`);
    },
    irAtras() {
      this.$router.push("/niveles");
    },
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
  background-image: url('/fondo.png');
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
  background-image: url("/boton.png");
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
  background-image: url("/boton.png");
  transform: scale(1.1);
  box-shadow: 0 6px 8px rgba(0, 0, 0, 0.3);
}

/* 🔒 Estilo para niveles bloqueados */
.level-button.locked {
  background-image: url("/boton_lock.png");
  cursor: not-allowed;
}

.level-button.locked:hover {
  background-image: url("/boton_lock.png");
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