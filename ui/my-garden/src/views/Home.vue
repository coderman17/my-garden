<template>
  <div class="home">
    <navbar></navbar>
    <HelloWorld msg="Welcome to Your Vue.js App"/>
<!--    <plant></plant>-->
    <ul>
      <li v-for="plant in plants" :key="plant.id">
        <plant v-bind:plant="plant"></plant>
      </li>
    </ul>
  </div>
</template>

<script>
// @ is an alias to /src
import navbar from '@/components/navbar.vue'
import HelloWorld from '@/components/HelloWorld.vue'
import plant from '@/components/plant.vue'

export default {
  name: 'Home',
  data() {
    return {
      plants: [
        {
          id: '1',
          englishName: 'hedge'
        },
        {
          id: '2',
          englishName: 'grass'
        }
      ]
    }
  },
  components: {
    navbar,
    HelloWorld,
    plant
  }
}
</script>
