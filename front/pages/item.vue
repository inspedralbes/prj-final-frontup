<template>
  <router-link :to="`/lliure/${project.id}`" class="project-card-link">
    <div class="project-card">
      <h2 class="project-title">{{ project.nombre }}</h2>
      <p class="project-date">
        <strong>Data de creació:</strong> {{ formattedDate }}
      </p>
      <p class="project-status">
        <strong>Status:</strong> {{ project.statuts === 0 ? 'Públic' : 'Privat' }}
      </p>

      <div class="like-button">
        <input class="on" id="heart" type="checkbox" />
        <label class="like" for="heart">
          <svg class="like-icon" fill-rule="nonzero" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path
              d="m11.645 20.91-.007-.003-.022-.012a15.247 15.247 0 0 1-.383-.218 25.18 25.18 0 0 1-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0 1 12 5.052 5.5 5.5 0 0 1 16.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 0 1-4.244 3.17 15.247 15.247 0 0 1-.383.219l-.022.012-.007.004-.003.001a.752.752 0 0 1-.704 0l-.003-.001Z">
            </path>
          </svg>
          <span class="like-text">Likes</span>
        </label>
        <span class="like-count one">68</span>
        <span class="like-count two">69</span>
      </div>

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

#heart {
  display: none;
}

.like-button {
  position: relative;
  cursor: pointer;
  display: flex;
  height: 40px;
  width: 120px;
  border-radius: 12px;
  border: 1px solid rgba(255, 255, 255, 0.1);
  background: linear-gradient(139deg, rgba(36, 40, 50, 0.9) 0%, rgba(37, 28, 40, 0.9) 100%);
  backdrop-filter: blur(8px);
  overflow: hidden;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  margin: 15px auto 0;
}

.like-button:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
}

.like {
  display: flex;
  align-items: center;
  gap: 8px;
}

.like-icon {
  width: 25px;
  height: 24px;
  fill: url(#heartGradient);
  stroke: #bd89ff;
  stroke-width: 0.5px;
  transition: all 0.3s ease;
}

.like-text {
  color: #a0a0a0;
  font-size: 14px;
  font-weight: 600;
  letter-spacing: 0.5px;
  transition: color 0.3s ease;
}

.like-count {
  position: absolute;
  right: 0;
  width: 30%;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  color: #bd89ff;
  font-size: 14px;
  font-weight: 700;
  background: rgba(189, 137, 255, 0.1);
  border-left: 1px solid rgba(189, 137, 255, 0.2);
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.on:checked ~ .like .like-icon {
  fill: url(#heartActiveGradient);
  stroke: transparent;
  transform: scale(1.15);
}

.on:checked ~ .like-count {
  color: #ffffff;
  background: linear-gradient(45deg, #5353ff, #8a2be2);
}

.on:checked ~ .like-count.two {
  transform: translateY(0);
  opacity: 1;
}

.on:checked ~ .like-count.one {
  transform: translateY(-40px);
  opacity: 0;
}

@keyframes pulse {
  0% { transform: scale(1); }
  50% { transform: scale(1.1); }
  100% { transform: scale(1); }
}

.on:checked ~ .like .like-icon {
  animation: pulse 0.4s ease;
}
</style>
