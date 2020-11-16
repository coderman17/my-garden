<template>
  <div id="imageContainer">
      <img v-if="this.check === true" class="rounded mt-3" :src="imageLink" ref="img" @error="imageLoadError">
      <div v-if="this.imageMessage === true" class='bg-white brokenImage mt-3 border border-success rounded'>{{msg}}</div>
  </div>
</template>

<script>
export default {
  name: 'plantImage',
  props: ['imageLink'],
  data() {
    return {
      imageMessage : false,
      check: true,
      msg: 'Image preview'
    }
  },
  methods: {
    recheck() {
      if(this.imageLink === '') {
        this.msg = 'Image preview'
        this.imageMessage = true;
      } else {
        this.imageMessage = false;
        this.check = true;
      }
    },
    imageLoadError() {
      this.check = false
      if(this.imageLink !== '') {
        this.msg = 'Broken image link'
      }
      this.imageMessage = true;
    }
  }
}
</script>

<style scoped>
img, .brokenImage {
  width: 320px;
  height: 400px;
  object-fit: cover;
  max-width: 100%;
}
.brokenImage {
  font-size: 30px;
  display: flex;
  justify-content: center;
  align-items: center;
  margin: auto;
  color: grey;
}
</style>