<template>
  <nav class="navbar navbar-expand-lg navbar-dark bg-success">
    <router-link class="navbar-brand text-white" to="/">My Garden</router-link>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#myGardenNavbarToggler" aria-controls="myGardenNavbarToggler" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="myGardenNavbarToggler">
      <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
        <li class="nav-item active">
          <router-link data-toggle="collapse" data-target="#myGardenNavbarToggler" class="nav-link text-white" to="/Plants">Plants<span class="sr-only">(current)</span></router-link>
        </li>
        <li class="nav-item active">
          <router-link data-toggle="collapse" data-target="#myGardenNavbarToggler" class="nav-link text-white" to="/Gardens">Gardens<span class="sr-only">(current)</span></router-link>
        </li>
      </ul>
    </div>
  </nav>
</template>

<script>
export default {
  name: 'navbar',
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.navbar-nav {
  font-size: 1.25rem;
}
</style>
