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
      <form class="col-12" @submit.prevent="processForm" method="get">
        <div class="form-group">
          <input type="text" class="mt-4 text-center form-control offset-md-2 col-md-8" id="imageLink" aria-describedby="imageLink" placeholder="Link to picture" v-model="imageLink">
          <input type="text" class="mt-4 text-center form-control offset-md-2 col-md-8" id="englishName" aria-describedby="englishName" placeholder="English Name" v-model="englishName">
          <input type="text" class="mt-4 text-center form-control offset-md-2 col-md-8" id="latinName" aria-describedby="latinName" placeholder="Latin Name" v-model="latinName">
          <button type="submit" class="mt-4 btn btn-primary">Submit</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
// @ is an alias to /src
// import plant from '@/components/plant.vue'
import router from '@/router'

export default {
  name: 'PlantForm',
  data() {
    return {
      englishName: '',
      latinName: '',
      imageLink: '',
    }
  },
  methods: {
    processForm(){
      this.responseAvailable = false;
      this.requestOptions = {
        method: "POST",
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          'englishName': this.englishName,
          'latinName': this.latinName,
          'imageLink': this.imageLink
        })
      };
      fetch("http://localhost/api/plant",this.requestOptions)
          .then(response => {
            if(response.ok){
              router.push('Plants');
              return response;
            } else{
              alert("Server returned " + response.status + " : " + response.statusText);
            }
          })
          .then(response => {
            console.log(response.json())
            // this.plants = response;
            // this.responseAvailable = true;
          })
          .catch(err => {
            console.log(err);
          });
    },
    imageLoadError () {
      console.log('Image failed to load');
    }
  },
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