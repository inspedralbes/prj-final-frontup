<template>
  <router-link :to="`/proyecto/${project.id}`" class="project-card-link">
    <div class="project-card">
      <h2 class="project-title">{{ project.nombre }}</h2>
      <p class="project-date"><strong>Data de creació:</strong> {{ formattedDate }}</p>
      <p class="project-status">
        <strong>Status:</strong> {{ project.statuts === 0 ? 'Públic' : 'Privat' }}
      </p>
    </div>
  </router-link>
</template>


<script>
export default {
  name: "Item",
  props: {
    project: {
      type: Object,
      required: true,
    },
  },
  computed: {
    formattedDate() {
      return new Date(this.project.created_at).toLocaleDateString("ca-ES", {
        year: "numeric",
        month: "long",
        day: "numeric",
      });
    },
  },
};
</script>

<style scoped>
.project-card {
  background-color: #2c2c2c;
  padding: 15px;
  border-radius: 10px;
  text-align: center;
  color: #ddd;
  box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
  transition: transform 0.2s ease-in-out;
}

.project-card:hover {
  transform: scale(1.05);
}

.project-title {
  font-size: 20px;
  font-weight: bold;
  margin-bottom: 10px;
}

.project-date {
  font-size: 14px;
  color: #aaa;
}

.project-card-link {
  text-decoration: none;
}
</style>
