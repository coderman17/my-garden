<template>
  <div class="plants container-fluid">

  <h1 class="mt-5 mb-2">
    Plant Details:
  </h1>
    <div class="plantsContainer row justify-content-center">

      <plant v-for="plant in plants" :key="plant.id" v-bind:plant="plant"></plant>
    </div>
  </div>
</template>

<script>
// @ is an alias to /src
import plant from '@/components/plant.vue'
// import router from '@/router'

export default {
  name: 'Plants',
  data() {
    return {
      plants: []
    }
  },
  components: {
    plant
  },
  mounted() {
    this.responseAvailable = false;
    fetch("http://localhost/api/plant?id=" + this.$route.params.id, {
      "method": "GET",
    })
    .then(response => {
      if(response.ok){
        return response.json()
      } else{
        alert("Server returned " + response.status + " : " + response.statusText);
      }
    })
    .then(response => {
      console.log(response)
      this.plants = [response];
      this.responseAvailable = true;
    })
    .catch(err => {
      console.log(err);
    });
  }
}
</script>

