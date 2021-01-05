<template>
  <div>
    <div ref="container" v-bind:style="{ height: this.solidContainerHeight + 'px'}" class="solidContainer col-12 col-md-9 col-lg-8 col-xl-6">
      <table ref="gardenTable" v-bind:style="{width: tableWidth + 'px', marginTop: Math.floor(0.5 * (this.solidContainerHeight - this.cellWidth * this.garden.dimensionY)) - 1 + 'px'}" class="table-bordered">
        <tr v-for="n in parseInt(garden.dimensionY)" :key="n">
          <td v-bind:style="{ height: cellWidth + 'px' }" v-for="m in parseInt(garden.dimensionX)" :key="m" @click="handleTdClick">
          </td>
        </tr>
      </table>
    </div>
    <h1 class="mt-2 mb-0">{{ garden.name }}</h1>
    <button type="button" ref="myBtn" class="btn btn-primary" style="display: none;" data-toggle="modal" data-target="#exampleModalCenter">
      Launch demo modal
    </button>
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Select Plant</h5>
          </div>
          <div class="modal-body">
            <table class="table" id="modalTable">
            </table>
          </div>
          <div class="modal-footer">
            <button type="button" ref="closeModal" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
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
    }
  },
  methods: {
    setPlantLocationsArray: function () {
      this.reload++
      if (this.userPlants === undefined && this.reload < 20){
        setTimeout(function(){
          this.setPlantLocationsArray();
        }.bind(this), 500);
      } else {
        for (let i = 0; i < this.garden.plantLocations.length; i++) {
          let plant = this.getPlant(this.garden.plantLocations[i].id)
          this.insertPlant(plant, this.garden.plantLocations[i].coordinateX, this.garden.plantLocations[i].coordinateY)
        }
      }
    },
    getPlant(id){
      for (let j =0; j < this.userPlants.length; j++){
        if (this.userPlants[j].id === id){
          return this.userPlants[j]
        }
      }
    },
    insertPlant: function (response, x, y) {
      let table = this.$refs.gardenTable
      table.children[table.childElementCount - y].children[x - 1].innerHTML = "<img style='width:100%;object-fit: cover;border-radius: 0px;height:100%' src=" + response.imageLink + "></img>"
    },
    imageLoadError() {
      this.brokenImage = true
    },
    calculateCellWidth: function() {
      if(this.$refs.container === undefined){
        setTimeout(function(){
          this.calculateCellWidth();
        }.bind(this), 1000);
      } else {
        this.solidContainerHeight = this.$refs.container.clientWidth + 2
        this.cellWidth = Math.floor(this.$refs.container.clientWidth / 10);
        this.tableWidth = this.cellWidth * this.garden.dimensionX;
      }
    },
    addRowsToModal(){
      if (this.userPlants === undefined){
        setTimeout(function(){
          this.addRowsToModal();
        }.bind(this), 500);
      } else {
        let modalTable = document.getElementById('modalTable')
        for (let i = 0; i < this.userPlants.length; i++) {
          let plant = this.userPlants[i]
          let tr = document.createElement("TR")
          let td = document.createElement("TD")
          let a = document.createElement("A")
          a.classList.add("btn", "btn-success", "btn-block")
          a.setAttribute("role", "button")
          a.addEventListener("click", this.addPlant, false)
          a.setAttribute("plantId", plant.id)
          a.innerHTML = plant.englishName
          td.append(a)
          tr.append(td)
          modalTable.append(tr)
        }
      }
    },
    addPlant(event){
      let plant = this.getPlant(event.target.attributes.plantId.value)
      this.insertPlant(plant, this.xCoordinate, this.yCoordinate)
      this.$refs.closeModal.click()
      if (this.garden.plantLocations === undefined){
        this.garden.plantLocations = []
      }
      this.garden.plantLocations.push({
        id: plant.id,
        coordinateX: this.xCoordinate,
        coordinateY: this.yCoordinate
      })
    },

    handleTdClick(event){
      //work-around until the modal is a self-contained component with a <td> listener:
      if(this.$route.name!== 'GardenForm'){
        return
      }
      let target = event.target
      while (target.nodeName !== 'TD'){
        target = target.parentElement
      }
      let td = target
      this.xCoordinate = td.cellIndex + 1
      this.yCoordinate = this.garden.dimensionY - td.parentElement.rowIndex
      if(td.innerHTML === ''){
        this.$refs.myBtn.click()
      } else{
        if (confirm('Are you sure you would like to remove the plant from that location?')) {
          for (let i = 0; i < this.garden.plantLocations.length; i++) {
            if (this.garden.plantLocations[i].coordinateX === this.xCoordinate && this.garden.plantLocations[i].coordinateY === this.yCoordinate) {
              this.garden.plantLocations.splice(i, 1)
              td.innerHTML = ''
            }
          }
        }
      }
    }
  },
  mounted() {
      this.calculateCellWidth();
      this.setPlantLocationsArray();
      this.addRowsToModal()
  }
}
</script>

<style scoped>
table {
  margin: auto;
  table-layout: fixed;
  display: inline-table;
  background-size: cover;
  background-image: url('~@/assets/soil 1.jpg');
}
.table-bordered td, .table-bordered th {
  border: 1px solid #868686;
}
.table-bordered td {
  padding: 0px;
}
.solidContainer {
  padding: 0;
  margin: auto;
  line-height: 0;
}
h1 {
  word-break: break-word;
}
#modalTable {
  color: white
}
.table-bordered td img {
  width: 320px;
  height: 320px;
  object-fit: cover;
  max-width: 100%;
}
</style>