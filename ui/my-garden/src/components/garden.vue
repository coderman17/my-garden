<template>
  <div>
<!--    <router-link :to="{name: 'ShowGarden', params: {id: garden.id, garden: garden}}">-->
<!--      <h1 class="mt-2 mb-0">{{ garden.name }}</h1>-->
    <div ref="container" v-bind:style="{ height: this.solidContainerHeight + 'px'}" class="border border-success solidContainer col-12 col-md-9 col-lg-8 col-xl-4">
      <table v-bind:style="{ width: tableWidth + 'px', marginTop: 0.5 * (this.solidContainerHeight - this.cellWidth * this.garden.dimensionY) + 'px'}" class="table-bordered">
        <tr v-for="n in parseInt(garden.dimensionY)" :key="n">
        <td v-bind:style="{ height: cellWidth + 'px' }" v-for="n in parseInt(garden.dimensionX)" :key="n"></td>
        </tr>
      </table>
    </div>

<!--      <h2 class="font-italic mb-4">{{ garden.dimensionY }}</h2>-->
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
      brokenImage : false
    }
  },
  methods: {
    imageLoadError() {
      this.brokenImage = true
    },
    calculateCellWidth: function() {
      this.solidContainerHeight = this.$refs.container.clientWidth
      this.cellWidth = this.$refs.container.clientWidth / 10;
      this.tableWidth = this.cellWidth * this.garden.dimensionX;
    }
  },
  mounted() {
    setTimeout(function(){ this.calculateCellWidth(); }.bind(this), 500);
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
table {
  margin: auto;
  table-layout: fixed;
  display: inline-table;
}
.solidContainer {
  padding: 0;
  margin: auto;
}
a {
  color: unset;
}

</style>