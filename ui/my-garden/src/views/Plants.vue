<template>
  <div class="plants container-fluid">
    <heading>Plants:</heading>
    <div class="plantsContainer row">
      <router-link class="plant col-12 col-lg-4 col-xl-3 col-md-6 col-sm-12" v-for="plant in plants" :key="plant.id"  :to="{name: 'ShowPlant', params: {id: plant.id, plant: plant}}">
        <plant  v-bind:plant="plant"></plant>
      </router-link>
    </div>
    <router-link to="/PlantForm">
      <floatingActionButton>
        <img src="@/assets/plus.png">
      </floatingActionButton>
    </router-link>
  </div>

</template>

<script>
import plant from '@/components/plant.vue';
import floatingActionButton from "@/components/floatingActionButton.vue";
import heading from "@/components/heading.vue";

export default {
  name: 'Plants',
  data() {
    return {
      plants: []
    }
  },
  components: {
    floatingActionButton,
    plant,
    heading
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
        console.log(response);
        alert("Server returned " + response.status + " : " + response.statusText + " data: " + response.data);
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
a:hover {
  text-decoration: unset;
  color: unset;
}
a {
  color: unset;
}
</style>
