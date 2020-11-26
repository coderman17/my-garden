<template>
  <div class="plant col-12 col-lg-3">
    <div v-if="plant.imageLink !== 'add'">
      <img class="mt-3" v-bind:src=plant.imageLink>
    </div>
    <div v-else>
      <div class='add mt-3 border border-success rounded'>+</div>
    </div>
    <h1 class="mt-2 mb-0">{{ plant.englishName }}</h1>
    <h2 class="font-italic mb-4">{{ plant.latinName }}</h2>
  </div>
</template>

<script>
export default {
  name: 'plant',
  props: ['plant']
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.plant {
  min-height: 450px;
}
.plant img, .plant .add {
  width: 320px;
  height: 400px;
  object-fit: cover;
}
.add {
  font-size: 200px;
  display: flex;
  justify-content: center;
  align-items: center;
  color: #28a745;
  margin: auto;
}
.plant:hover {
  opacity: 80%;
  /*background-color: #28a745;*/
}
</style>