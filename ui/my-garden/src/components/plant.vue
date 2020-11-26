<template>
  <div class="plant">
    <h1>English Name: {{ plant.englishName }}</h1>
  </div>
</template>

<script>
export default {
  name: 'plant',
  props: ['plant']
}
</script>