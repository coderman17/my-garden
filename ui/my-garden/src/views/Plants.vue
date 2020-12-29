<template>
  <div class="plants container-fluid">

  <h1 class="mt-5 mb-2">
    Garden Plants:
  </h1>
    <div class="plantsContainer row">
      <plant class="plant col-12 col-lg-4 col-xl-3 col-md-6 col-sm-12" v-for="plant in plants" :key="plant.id" v-bind:plant="plant"></plant>
    </div>
    <router-link to="/PlantForm">
      <floatingActionButton>
        <img src="@/assets/plus.png">
      </floatingActionButton>
    </router-link>
  </div>

</template>

<script>
// @ is an alias to /src
import plant from '@/components/plant.vue';
import floatingActionButton from "@/components/floatingActionButton.vue";

export default {
  name: 'Plants',
  data() {
    return {
      plants: []
    }
  },
  components: {
    floatingActionButton,
    plant
  },
  mounted() {
    this.responseAvailable = false;
    fetch("http://localhost/api/plants", {
      method: "GET",
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      }
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

<style scoped>
.plants {
  margin-bottom: 100px;
}
</style>
