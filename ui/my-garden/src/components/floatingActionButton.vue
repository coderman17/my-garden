<template>
  <div id="floatingActionButton">
      <slot></slot>
  </div>
</template>

<script>
export default {
  name: 'floatingActionButton'
}
</script>

<style scoped>
#floatingActionButton {
  position: fixed;
  z-index: 100;
  bottom: 30px;
  left: calc(50% - 40px);
  border-radius: 200px;
  width: 80px;
  height: 80px;
  cursor: pointer;
  font-size: 50px;
  padding: 0;
  margin: 0;
  color: white;
  line-height: 70px;
  border: 0;
  background-color: #28a745;
}
img {
  width: 55%;
}
</style>