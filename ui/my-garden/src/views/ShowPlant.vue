<template>
  <div class="plants container-fluid">
    <heading>Plant Details:</heading>
    <div class="plantsContainer row justify-content-center">
      <plant v-bind:plant="plant"></plant>
    </div>
<!--    <router-link to="/PlantForm">-->
    <router-link :to="{name: 'PlantForm', params: {plant: plant}}">
<!--      <floatingActionButton @click="this.$router.push({name:'PlantForm', params:{plant}});">-->
      <floatingActionButton>
        <img src="@/assets/pencil transparent2.png">
      </floatingActionButton>
    </router-link>
  </div>
</template>

<script>
// @ is an alias to /src
import plant from '@/components/plant.vue'
import floatingActionButton from '@/components/floatingActionButton.vue'
import heading from '@/components/heading.vue'

export default {
  name: 'Plants',
  id: '',
  data() {
    return {
      plant: ''
    }
  },
  components: {
    plant,
    floatingActionButton,
    heading
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
      this.plant = response;
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


