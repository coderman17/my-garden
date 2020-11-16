<template>
  <div class="home">
<!--    <div class="plantsContainer row">-->
<!--        <plant v-for="plant in plants" :key="plant.id" v-bind:plant="plant"></plant>-->
<!--    </div>-->


<!--    <img class="logo col-12 col-md-6 mt-4" src="@/assets/garden-planning.png">-->
    <img class="logo col-5 col-md-2" src="@/assets/leaf transparent.png">
<!--    <img class="logo col-10 col-md-4 mt-2" src="@/assets/logo.png">-->
      <h1 class="title mt-4">My Garden</h1>
  </div>
</template>

<script>

export default {
  name: 'Home'
}
</script>

<style scoped>
.title {
  font-family: PMingLiU-ExtB;
  font-weight: bold;
  letter-spacing: 1.25px;
  color: #303030;
}
.home {
  padding-top: 150px;
}
</style>
