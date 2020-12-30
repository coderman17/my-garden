<template>
  <div class="gardens container-fluid">
    <heading>{{garden.name}}</heading>
    <div class="gardensContainer row justify-content-center">
      <garden class="col-12" v-bind:garden="garden"></garden>
      <button v-on:click=this.delete class="col-3 col-sm-2 pr-0 pl-0 col-md-1 mt-4 mr-5 btn btn-danger">Delete</button>
      <router-link class="col-3 col-sm-2 pr-0 pl-0 col-md-1 mt-4" :to="{name: 'GardenForm', params: {garden: garden}}">
        <button type="submit" class="btn btn-primary" style="width:100%;">Edit</button>
      </router-link>
    </div>
  </div>
</template>

<script>
// @ is an alias to /src
import garden from '@/components/garden.vue'
import heading from '@/components/heading.vue'
import router from "@/router";

export default {
  name: 'gardens',
  id: '',
  components: {
    garden,
    heading
  },
  methods: {
    delete() {
      fetch("http://localhost/api/garden?id=" + this.$route.params.id, {
        method: "DELETE",
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        }
      });
      router.push('/gardens');
    }
  },
  props: ['garden'],
  mounted() {
    if (this.garden === undefined) {
      this.responseAvailable = false;
      fetch("http://localhost/api/garden?id=" + this.$route.params.id, {
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
        this.garden = response;
        this.responseAvailable = true;
      })
      .catch(err => {
        console.log(err);
      });
    }
  }
}
</script>

<style scoped>
.gardens {
  margin-bottom: 100px;
}
</style>


