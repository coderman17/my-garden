<template>
  <div>
    <router-link :to="{name: 'ShowPlant', params: {id: plant.id, plant: plant}}">
      <plantImage :imageLink=plant.imageLink></plantImage>
      <h1 class="mt-2 mb-0">{{ plant.englishName }}</h1>
      <h2 class="font-italic mb-4">{{ plant.latinName }}</h2>
    </router-link>
  </div>
</template>

<script>
import plantImage from '@/components/plantImage.vue'

export default {
  name: 'plant',
  components: {
    plantImage
  },
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
//TODO figure out an opacity solution which works without weirdness when deployed
/*.plant:hover {*/
/*  opacity: 0.8;*/
/*}*/
a:hover {
  text-decoration: unset;
  color: unset;
}
a {
  color: unset;
}

</style>