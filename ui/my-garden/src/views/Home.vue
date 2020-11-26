<template>
  <div class="home">
    <navbar></navbar>
    <ul>
      <li v-for="plant in plants" :key="plant.id">
        <plant v-bind:plant="plant"></plant>
      </li>
    </ul>
  </div>
</template>

<script>
// @ is an alias to /src
import navbar from '@/components/navbar.vue'
import plant from '@/components/plant.vue'

export default {
  name: 'Home',
  data() {
    return {
      plants: []
    }
  },
  components: {
    navbar,
    plant
  },
  mounted() {
    this.responseAvailable = false;
    fetch("http://localhost/api/plants/", {
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
      this.plants = response;
      this.responseAvailable = true;
    })
    .catch(err => {
      console.log(err);
    });
  }
}
</script>
