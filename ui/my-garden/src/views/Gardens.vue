<template>
  <div class="gardens container-fluid">
  <heading>Gardens:</heading>
    <div class="gardensContainer row">
      <router-link class="garden col-12 col-md-6 col-sm-12" v-for="garden in gardens" :key="garden.id"  :to="{name: 'ShowGarden', params: {id: garden.id, garden: garden}}">
        <garden ref="gardenRefForRefresh" v-bind:garden="garden"></garden>
      </router-link>
    </div>
    <router-link to="/gardenForm">
      <floatingActionButton>
        <img src="@/assets/plus.png">
      </floatingActionButton>
    </router-link>
  </div>

</template>

<script>
// @ is an alias to /src
import garden from '@/components/garden.vue';
import floatingActionButton from "@/components/floatingActionButton.vue";
import heading from "@/components/heading.vue";

export default {
  name: 'Gardens',
  data() {
    return {
      gardens: []
    }
  },
  components: {
    floatingActionButton,
    garden,
    heading
  },
  mounted() {
    this.responseAvailable = false;
    fetch("http://localhost/api/gardens", {
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
        console.log(response); alert("Server returned " + response.status + " : " + response.statusText + " data: " + response.data);
      }
    })
    .then(response => {
      console.log(response)
      this.gardens = response;
      this.responseAvailable = true;
    })
    .catch(err => {
      console.log(err);
    });
  }
}
</script>

<style scoped>
.gardens {
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
