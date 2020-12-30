<template>
  <div>
<!--    <router-link :to="{name: 'ShowGarden', params: {id: garden.id, garden: garden}}">-->
<!--      <h1 class="mt-2 mb-0">{{ garden.name }}</h1>-->
      <table ref="table" class="table-bordered col-12 col-md-9 col-lg-8 col-xl-5">
        <tr v-for="n in parseInt(garden.dimensionY)" :key="n">
        <td v-bind:style="{ height: cellWidth + 'px' }" v-for="n in parseInt(garden.dimensionX)" :key="n"></td>
        </tr>
      </table>

<!--      <h2 class="font-italic mb-4">{{ garden.dimensionY }}</h2>-->
<!--    </router-link>-->
  </div>
</template>

<script>
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
      brokenImage : false
    }
  },
  methods: {
    imageLoadError() {
      this.brokenImage = true
    },
    calculateCellWidth: function() {
      this.cellWidth = this.$refs.table.clientWidth / this.garden.dimensionX;
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
  width: 100%;
}
tr {
  /*height: calc(100% / 10);*/
}
td {
  /*width: calc(100% / 10);*/
}
a {
  color: unset;
}

</style>