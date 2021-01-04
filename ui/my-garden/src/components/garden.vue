<template>
  <div>
    <div ref="container" v-bind:style="{ height: this.solidContainerHeight + 'px'}" class="solidContainer col-12 col-md-9 col-lg-8 col-xl-4">
      <table v-bind:style="{width: tableWidth + 'px', marginTop: Math.floor(0.5 * (this.solidContainerHeight - this.cellWidth * this.garden.dimensionY)) - 1 + 'px'}" class="table-bordered" id="gardenTable">
        <tr v-for="n in parseInt(garden.dimensionY)" :key="n">

          <td v-bind:style="{ height: cellWidth + 'px' }" v-for="moo in parseInt(garden.dimensionX)" :key="moo" @click="handleTdClick">
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
  props: ['garden', 'userPlants'],
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
        if(this.garden === undefined || this.userPlants === undefined){
          setTimeout(function(){
            this.setPlantLocationsArray();
          }.bind(this), 10);
        } else {
          console.log('setting plant locs')
          console.log(this.garden)
          for (let i = 0; i < this.garden.plantLocations.length; i++) {
            console.log('attempting to get plant ' + this.garden.plantLocations[i].id)
            let found = false
            for (let j =0; j < this.userPlants.length; j++){
              if (this.userPlants[j].id === this.garden.plantLocations[i].id){
                console.log('found plant locally')
                found = true
                this.insertPlant(this.userPlants[j], this.garden.plantLocations[i].coordinateX, this.garden.plantLocations[i].coordinateY)
              }
            }
            if (found === false) {
              this.getPlant(this.garden.plantLocations[i].id, this.garden.plantLocations[i].coordinateX, this.garden.plantLocations[i].coordinateY)
            }
          }
        }
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
              alert("Unfortunately, the server returned " + response.status + " : " + response.statusText + " data: " + response.json());
            }
          })
          .then(response => {
            this.insertPlant(response, x, y)
          })
          .catch(err => {
            console.log(err);
          });
    },
    insertPlant: function (response, x, y) {
      let table = document.getElementById('gardenTable')
      table.children[table.childElementCount - y].children[x - 1].innerHTML = "<img style='width:100%;object-fit: cover;border-radius: 60px;height:100%' src=" + response.imageLink + "></img>"
    },
    imageLoadError() {
      this.brokenImage = true
    },
    calculateCellWidth: function() {
      console.log('calculating cell width')
      if(this.$refs.container === undefined){
        setTimeout(function(){
          this.calculateCellWidth();
        }.bind(this), 10);
      } else {
        this.solidContainerHeight = this.$refs.container.clientWidth + 2
        this.cellWidth = Math.floor(this.$refs.container.clientWidth / 10);
        this.tableWidth = this.cellWidth * this.garden.dimensionX;
      }
    },
    handleTdClick(event){

      let target = event.target
      while (target.nodeName !== 'TD'){
        target = target.parentElement
      }
      let td = target
      if(td.innerHTML === ''){
        alert('adding plant')
      } else{
        if (confirm('Are you sure you would like to remove the plant from that location?')) {
          td.innerHTML = ''
          for (let i = 0; i < this.garden.plantLocations.length; i++) {
            if (this.garden.plantLocations[i].coordinateX === (td.cellIndex + 1) && this.garden.plantLocations[i].coordinateY === (this.garden.dimensionY - td.parentElement.rowIndex)) {
              this.garden.plantLocations.splice(i, 1)
            }
          }
        }
      }
    }
  },
  mounted() {
    console.log(this.userPlants)
    // setTimeout(function(){
      this.calculateCellWidth();
      this.setPlantLocationsArray();
    // }.bind(this), 2000);
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
table {
  margin: auto;
  table-layout: fixed;
  display: inline-table;
  /*background-color: #6d4620;*/
  background-size: cover;
  background-image: url('~@/assets/soil 1.jpg');
}
.table-bordered td, .table-bordered th {
  border: 1px solid #868686;
}
.table-bordered td {
  padding: 4px;
  /*background-size: cover;*/
  /*background-image: url('~@/assets/soil 1.jpg');*/
}
.solidContainer {
  padding: 0;
  margin: auto;
  line-height: 0;
}
h1 {
  word-break: break-word;
}
.table-bordered td img {
  width: 320px;
  height: 320px;
  object-fit: cover;
  max-width: 100%;
}
</style>