<template>
  <div class="gardens container-fluid">
  <heading>Gardens:</heading>
    <div class="gardensContainer row" v-if="this.gardens !== undefined">
      <router-link class="garden col-12 col-md-6 col-sm-12" v-for="garden in gardens" :key="garden.id"  :to="{name: 'ShowGarden', params: {id: garden.id, garden: garden, userPlants: userPlants}}">
        <garden ref="gardenRefForRefresh" v-bind:garden="garden" v-bind:userPlants="userPlants"></garden>
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
import userPlantsGetter from "@/components/userPlantsGetter";


export default {
  name: 'Gardens',
  data() {
    return {
      gardens: undefined,
      userPlants: undefined,
      responseAvailable: false,
    }
  },
  components: {
    floatingActionButton,
    garden,
    heading
  },
  methods: {
    setUserPlants() {
      console.log('setting user plants from Gardens')
      if (this.userPlants === undefined) {
        setTimeout(function () {
          userPlantsGetter.methods.populate()
          this.userPlants = userPlantsGetter.methods.get()
          this.setUserPlants();
        }.bind(this), 500);
      } else {
        console.log('successfully set userPlants in Gardens view')
      }
    }
  },
  mounted() {
    this.setUserPlants()
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
      this.gardens = response;
      console.log('logging all gardens:')
      for(let i=0; i<this.gardens.length; i++){
        console.log(this.gardens[i])
      }
      console.log('this.gardens[0].plantLocations is defined at Gardens view: ' + (this.gardens[0].plantLocations !== undefined).toString())
      console.log(this.gardens[0].plantLocations)
      console.log('this.gardens[0].dimensionX is defined at Gardens view: ' + (this.gardens[0].dimensionX !== undefined).toString())
      console.log(this.gardens[0].dimensionX)
      console.log('about to log a garden from gardens view:')
      console.log(this.gardens[0])
    })
    .catch(err => {
      console.log(err);
    });
    // fetch("http://localhost/api/plants", {
    //   method: "GET",
    //   headers: {
    //     'Content-Type': 'application/json',
    //     'Accept': 'application/json',
    //   }
    // })
    // .then(response => {
    //   if(response.ok){
    //     return response.json()
    //   } else{
    //     console.log(response); alert("Server returned " + response.status + " : " + response.statusText + " data: " + response.data);
    //   }
    // })
    // .then(response => {
    //   this.userPlants = response;
    //   this.responseAvailable = true
    // })
    // .catch(err => {
    //   console.log(err);
    // });

  },
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
