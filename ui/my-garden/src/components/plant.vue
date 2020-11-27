<template>
  <div class="plant col-12 col-lg-4 col-xl-3 col-md-6 col-sm-12">
    <router-link :to="{name: 'ShowPlant', params: {id: plant.id}}">
      <div v-if="plant.imageLink !== 'add'">
        <div v-if="this.brokenImage === false">
          <img class="rounded mt-3" v-bind:src=plant.imageLink ref="img" @error="imageLoadError">
        </div>
        <div v-else>
          <div class='brokenImage mt-3 border border-success rounded'>Broken image link</div>
        </div>
      </div>
      <div v-else>
        <div class='add mt-3 border border-success rounded'>+</div>
      </div>
      <h1 class="mt-2 mb-0">{{ plant.englishName }}</h1>
      <h2 class="font-italic mb-4">{{ plant.latinName }}</h2>
    </router-link>
  </div>
</template>

<script>
export default {
  name: 'plant',
  props: ['plant'],
  data() {
    return {
      brokenImage : false
    }
  },
  methods: {
    imageLoadError() {
      this.brokenImage = true
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.plant {
  min-height: 450px;
  cursor: pointer;
  color: #006d00;
}
.plant img, .plant .add, .plant .brokenImage {
  width: 320px;
  height: 400px;
  object-fit: cover;
  max-width: 100%;
  /*max-width: 320px;*/
}
.add, .brokenImage {
  font-size: 200px;
  display: flex;
  justify-content: center;
  align-items: center;
  /*color: #006d00;*/
  margin: auto;
}
.brokenImage {
  font-size: 30px;
  color: grey;
}
.plant:hover {
  opacity: 80%;
  /*background-color: #28a745;*/
}
a:hover {
  text-decoration: unset;
  color: unset;
}
a {
  color: unset;
}

</style>