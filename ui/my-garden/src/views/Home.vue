<template>
  <div class="home">
    <navbar></navbar>
    <img alt="Vue logo" src="../assets/logo.png">
    <HelloWorld msg="Welcome to Your Vue.js App"/>
  </div>
</template>

<script>
// @ is an alias to /src
import navbar from '@/components/navbar.vue'
import HelloWorld from '@/components/HelloWorld.vue'

export default {
  name: 'Home',
  components: {
    navbar,
    HelloWorld
  }
}
</script>
