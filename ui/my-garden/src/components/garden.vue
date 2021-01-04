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
    <button type="button" ref="myBtn" class="btn btn-primary" style="display: none;" data-toggle="modal" data-target="#exampleModalCenter">
      Launch demo modal
    </button>

    <!-- Modal -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <table class="table" id="modalTable">
              <tr>
                <td><a href="#" role="button" class="btn btn-secondary popover-test" title="Popover title" data-content="Popover body content is set in this attribute.">button</a></td>
              </tr>
            </table>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
          </div>
        </div>
      </div>
    </div>
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


// import userPlantsGetter from "@/components/userPlantsGetter";

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
      reload: 0,
      tableWidth: 15,
      brokenImage : false,
      solidContainerHeight: 15,
      plantLocations : {},
      plant:{}
    }
  },
  methods: {
    setPlantLocationsArray: function () {
      console.log('setting plant locations')
      this.reload++
      if (this.userPlants === undefined && this.reload < 20){
        // userPlantsGetter.methods.populate()
        // this.userPlants = userPlantsGetter.methods.get()
        console.log(this.userPlants)
        setTimeout(function(){
          this.setPlantLocationsArray();
        }.bind(this), 50);
      } else if(this.garden === undefined  && this.reload < 25){
        console.log('LOCATIONS')
          setTimeout(function(){
            this.setPlantLocationsArray();
          }.bind(this), 10);
      } else {
        console.log('setting plant locs')
        console.log(this.garden)
        for (let i = 0; i < this.garden.plantLocations.length; i++) {
          console.log('attempting to get plant ' + this.garden.plantLocations[i].id)
          for (let j =0; j < this.userPlants.length; j++){
            if (this.userPlants[j].id === this.garden.plantLocations[i].id){
              console.log('found plant locally')
              this.insertPlant(this.userPlants[j], this.garden.plantLocations[i].coordinateX, this.garden.plantLocations[i].coordinateY)
            }
          }
        }
      }
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
    addRowsToModal(){
      if (this.userPlants === undefined){
        setTimeout(function(){
          // console.log('here:')
          // console.log(this.userPlants)
          this.addRowsToModal();
        }.bind(this), 500);
      } else {
        // console.log('here:')
        // console.log(this.userPlants)
        let modalTable = document.getElementById('modalTable')
        for (let i = 0; i < this.userPlants.length; i++) {
          let plant = this.userPlants[i]
          modalTable.append("<tr>" + plant.englishName + "</tr>")
        }
      }
    },
    handleTdClick(event){

      let target = event.target
      while (target.nodeName !== 'TD'){
        target = target.parentElement
      }
      let td = target
      if(td.innerHTML === ''){
        this.$refs.myBtn.click()
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
      this.addRowsToModal()
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