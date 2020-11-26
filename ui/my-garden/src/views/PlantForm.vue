<template>
  <div class="plants container-fluid">

  <h1 class="mt-5 mb-2">
    Create Plant:
  </h1>
    <div class="plantsContainer row justify-content-center">
      <div v-if="imageLink === ''">
        <div class='add mt-4 border border-success rounded'>preview</div>
      </div>
      <div v-else>
        <img class="rounded mt-4" @error="imageLoadError" :src=imageLink>
      </div>
      <form class="col-12">
        <div class="form-group">
          <input type="text" class="mt-4 text-center form-control offset-md-2 col-md-8" id="imageLink" aria-describedby="imageLink" placeholder="Link to picture" v-model="imageLink">
<!--          <label for="englishName">English Name:</label>-->
          <input type="text" class="mt-4 text-center form-control offset-md-2 col-md-8" id="englishName" aria-describedby="englishName" placeholder="English Name">
<!--          <label for="latinName">Latin Name:</label>-->
          <input type="text" class="mt-4 text-center form-control offset-md-2 col-md-8" id="latinName" aria-describedby="latinName" placeholder="Latin Name">
          <button type="submit" class="mt-4 btn btn-primary">Submit</button>
        </div>
      </form>
<!--      <plant :plant="{id:'', englishName:'Add New Plant', latinName: '', imageLink:'add'}"></plant>-->
<!--      <plant v-for="plant in plants" :key="plant.id" v-bind:plant="plant"></plant>-->
    </div>
  </div>
</template>

<script>
// @ is an alias to /src
// import plant from '@/components/plant.vue'

export default {
  name: 'PlantForm',
  data() {
    return {
      plants: [],
      imageLink: ''
    }
  },
  methods: {
    imageLoadError () {
      console.log('Image failed to load');
    }
  },
  components: {
    // plant
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

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.plantsContainer img, .add {
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