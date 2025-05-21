<template>
  <nav>
    <ul>
      <li><NuxtLink to="/">Inicio</NuxtLink></li>
      <li><NuxtLink to="/about">Acerca de</NuxtLink></li>
      <li><NuxtLink to="/contact">Contacto</NuxtLink></li>
    </ul>
  </nav>
</template>

<script>
export default {
  name: 'Navbar',
}
</script>

<style scoped>
nav {
  background-color: #333;
  color: white;
  padding: 1rem;
}
ul {
  list-style: none;
  display: flex;
  gap: 1rem;
}
li {
  display: inline;
}
a {
  color: white;
  text-decoration: none;
}
a:hover {
  text-decoration: underline;
}
</style>
