<template>
  <div class="plants container-fluid">
    <div class="plantsContainer row justify-content-center">
      <plant class="col-12 mt-3" v-bind:plant="plant"></plant>
      <button v-on:click=this.delete class="col-3 col-sm-2 pr-0 pl-0 col-md-1 mt-4 mr-5 btn btn-danger">Delete</button>
      <router-link class="col-3 col-sm-2 pr-0 pl-0 col-md-1 mt-4" :to="{name: 'PlantForm', params: {plant: plant}}">
        <button type="submit" class="btn btn-primary" style="width:100%;">Edit</button>
      </router-link>
    </div>
  </div>
</template>

<script>
import plant from '@/components/plant.vue'
import router from "@/router";

export default {
  name: 'Plants',
  id: '',
  components: {
    plant,
  },
  methods: {
    delete() {
      fetch("http://localhost/api/plant?id=" + this.$route.params.id, {
        method: "DELETE",
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        }
      });
      router.push('/Plants');
    }
  },
  props: ['plant'],
  mounted() {
    if (this.plant === undefined) {
      fetch("http://localhost/api/plant?id=" + this.$route.params.id, {
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
        this.plant = response;
      })
      .catch(err => {
        console.log(err);
      });
    }
  }
}
</script>

<style scoped>
.plants {
  margin-bottom: 100px;
}
</style>


