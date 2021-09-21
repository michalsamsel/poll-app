<template>
  <div class="container-fluid px-0">
    <nav class="navbar navbar-expand navbar-dark bg-dark">
      <div class="container-fluid justify-content-start">
        <router-link class="navbar-brand" to="/">Poll App</router-link>
        <ul class="navbar-nav">
          <li class="nav-item">
            <router-link class="nav-link" to="/">Home</router-link>
          </li>
        </ul>
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <router-link class="nav-link" to="/poll/create"
              >Create Poll</router-link
            >
          </li>
        </ul>
      </div>
    </nav>
  </div>
  <div class="container mt-3">
    <router-view />
  </div>
</template>

<script>
export default {
  name: "App",
};
</script>