<template>
  <div class="gardens container-fluid">
    <div class="gardensContainer row justify-content-center">
      <garden ref="garden" class="col-12 mt-3" v-bind:garden="garden" v-bind:userPlants="userPlants"></garden>
      <form class="col-12 mb-4" @submit.prevent="processForm" method="get">
        <div class="form-group">
          <input type="text" class="mt-4 text-center form-control offset-md-2 col-md-8" id="name" aria-describedby="name" placeholder="Garden Name" v-model="garden.name">
          <input v-on:blur="recheckImage" type="text" class="mt-4 text-center form-control offset-md-2 col-md-8" id="dimensionX" aria-describedby="dimensionX" placeholder="X Dimension ('width')" v-model="garden.dimensionX">
          <input v-on:blur="recheckImage" type="text" class="mt-4 text-center form-control offset-md-2 col-md-8" id="dimensionY" aria-describedby="dimensionY" placeholder="Y Dimension ('height')" v-model="garden.dimensionY">
          <button type="submit" class="mt-4 btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
// @ is an alias to /src
import router from '@/router'
import garden from "@/components/garden";
import userPlantsGetter from "@/components/userPlantsGetter";

export default {
  name: 'gardenForm',
  data() {
    return {
      headingText: 'Create garden:',
      name: '',
      dimensionX: '',
      dimensionY: '',
      method: 'POST',
      apiUrl: 'http://localhost/api/garden'
    }
  },
  props: ['garden', 'userPlants'],
  components: {
    garden,
  },
  mounted() {
    if(this.garden !== undefined){
      this.headingText = 'Edit garden:'
      this.method = 'PUT'
      this.apiUrl = this.apiUrl + '?id=' + this.garden.id
      this.name = this.garden.name
      this.dimensionX = this.garden.dimensionX
      this.dimensionY = this.garden.dimensionY
    } else {
      this.garden = {
        name: '',
        dimensionX: 10,
        dimensionY: 10
      }
    }
    console.log(this.userPlants)
    this.setUserPlants()
  },
  methods: {
    recheckImage() {
      this.$refs.garden.calculateCellWidth();
    },
    setUserPlants() {
      if (this.userPlants === undefined) {
        setTimeout(function () {
          userPlantsGetter.methods.populate()
          this.userPlants = userPlantsGetter.methods.get()
          this.setUserPlants();
        }.bind(this), 50);
      }
    },
    processForm(){
      this.responseAvailable = false;
      let plantLocations = []
      if (this.garden.plantLocations !== undefined){
        plantLocations = this.garden.plantLocations
      }
      this.requestOptions = {
        method: this.method,
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify({
          'name': this.garden.name,
          'dimensionX': parseInt(this.garden.dimensionX),
          'dimensionY': parseInt(this.garden.dimensionY),
          'plantLocations': plantLocations
        })
      };
      console.log(this.requestOptions);
      fetch(this.apiUrl,this.requestOptions)
          .then(response => {
            if(response.ok){
              router.push('gardens');
              return response;
            } else{
              console.log(response.json());
              alert("Server returned " + response.status + " : " + response.statusText);
            }
          })
          .then(response => {
            console.log(response.json())
          })
          .catch(err => {
            console.log(err);
          });
    },
  },
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.gardensContainer img, .add {
  width: 320px;
  height: 400px;
  object-fit: cover;
  max-width: 100%;
}
.add {
  font-size: 75px;
  display: flex;
  justify-content: center;
  align-items: center;
  color: #28a745;
  margin: auto;
}

</style>