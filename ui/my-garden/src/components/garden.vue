<template>
  <div>
    <div ref="container" v-bind:style="{ height: this.solidContainerHeight + 'px'}" class="border border-success solidContainer col-12 col-md-9 col-lg-8 col-xl-4">
      <table v-bind:style="{ width: tableWidth + 'px', marginTop: Math.floor(0.5 * (this.solidContainerHeight - this.cellWidth * this.garden.dimensionY)) - 1 + 'px'}" class="table-bordered" id="gardenTable">
        <tr v-for="n in parseInt(garden.dimensionY)" :key="n">

          <td v-bind:style="{ height: cellWidth + 'px' }" v-for="moo in parseInt(garden.dimensionX)" :key="moo">
<!--            {{ concat(n, moo) }}-->
<!--            <h2>{{ this.plantLocations[moo][n] }}</h2>-->
          </td>
        </tr>
      </table>
    </div>
    <h1 class="mt-2 mb-0">{{ garden.name }}</h1>
<!--    <h3>{{this.plantLocations[2][4].englishName}}</h3>-->
<!--    <router-link class="plant col-12 col-lg-4 col-xl-3 col-md-6 col-sm-12" v-for="plantLocation in this.plantLocations" :key="plantLocation.id"  :to="{name: 'ShowPlant', params: {id: plant.id, plant: plant}}">-->
<!--      <plant  v-bind:plant="plant"></plant>-->
<!--    </router-link>-->
  </div>
</template>



<script>
/*
 * must convert back to 'initial working garden edit... commit for the show garden view so pics are big' use the set width and height only for plot edit
 *
 *
 *
 *
 */


/**
 * @property {int} dimensionX
 * @property {int} dimensionY
 */
export default {
  name: 'garden',
  props: ['garden'],
  data() {
    return {
      cellWidth: 15,
      tableWidth: 15,
      brokenImage : false,
      solidContainerHeight: 15,
      plantLocations : {},
      plant:{}
    }
  },
  methods: {
    setPlantLocationsArray: function () {
      let plantLocationObject = {}
      console.log(plantLocationObject)
      for (let i = 0; i < this.garden.plantLocations.length; i++) {
        let innerObj = {}
        // innerObj[this.garden.plantLocations[i].coordinateY] = this.garden.plantLocations[i].id
        console.log('attempting to get plant ' + this.garden.plantLocations[i].id)
        this.getPlant(this.garden.plantLocations[i].id, this.garden.plantLocations[i].coordinateX, this.garden.plantLocations[i].coordinateY)
        innerObj[this.garden.plantLocations[i].coordinateY] = this.plant
        plantLocationObject[this.garden.plantLocations[i].coordinateX] = innerObj
        console.log(plantLocationObject)
      }

      this.plantLocations = plantLocationObject
      console.log(this.plantLocations)
    },
    getPlant: function(id, x, y) {
      this.responseAvailable = false;
      fetch("http://localhost/api/plant?id=" + id, {
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
            this.insertPlant(response, x, y)
            this.plant = response;
          })
          .catch(err => {
            console.log(err);
          });
    },
    insertPlant: function (response, x, y) {
      let table = document.getElementById('gardenTable')
      table.children[table.childElementCount - y].children[x - 1].innerHTML = "<img style='width:100%;object-fit: cover;height:100%' src=" + response.imageLink + "></img>"
    },
    imageLoadError() {
      this.brokenImage = true
    },
    calculateCellWidth: function() {
      this.solidContainerHeight = this.$refs.container.clientWidth + 2
      this.cellWidth = Math.floor(this.$refs.container.clientWidth / 10);
      this.tableWidth = this.cellWidth * this.garden.dimensionX;
    }
  },
  mounted() {
    setTimeout(function(){
      this.calculateCellWidth();
      this.setPlantLocationsArray();
    }.bind(this), 2000);
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
table {
  margin: auto;
  table-layout: fixed;
  display: inline-table;
  background-color: #6d4620;
}
.table-bordered td, .table-bordered th {
  border: 1px solid #462d14;
}
.solidContainer {
  padding: 0;
  margin: auto;
  line-height: 0;
}
h1 {
  word-break: break-word;
}
img {
  width: 320px;
  height: 320px;
  object-fit: cover;
  max-width: 100%;
}
</style>