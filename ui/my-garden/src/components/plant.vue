<template>
  <div>
      <plantImage :imageLink=plant.imageLink></plantImage>
      <h1 class="mt-2 mb-0">{{ plant.englishName }}</h1>
      <h2 class="font-italic mb-4">{{ plant.latinName }}</h2>
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

<style scoped>
/*TODO remove this commented section?*/
/*.plant {*/
/*  min-height: 450px;*/
/*  cursor: pointer;*/
/*  color: #006d00;*/
/*}*/
h1, h2 {
  word-break: break-word;
}

</style>