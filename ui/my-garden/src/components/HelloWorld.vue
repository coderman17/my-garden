<template>
  <div class="hello">
    <h1>{{ msg }}</h1>
    <button type = "button" id = "get-joke" @click = "fetchAPIData">Get a Joke!!</button>
    <div v-if = "responseAvailable == true">
      <hr>
      <p>
        <i>{{result}}</i>
      </p>
      <hr>
    </div>
  </div>

</template>

<script>
export default {
  name: 'HelloWorld',
  props: {
    msg: String
  },
  data: function() {
    return {
      responseAvailable: false
    }
  },
  methods: {
    fetchAPIData() {
      this.responseAvailable = false;
      fetch("http://localhost/api/plants/", {
        "method": "GET",
        "headers": {
          //"x-rapidapi-host": "jokes-database.p.rapidapi.com",
        }
      })
      .then(response => {
        if(response.ok){
          return response.json()
        } else{
          alert("Server returned " + response.status + " : " + response.statusText + " data: " + response.data);
        }
      })
      .then(response => {
        console.log(response)
        this.result = response;
        this.responseAvailable = true;
      })
      .catch(err => {
        console.log(err);
      });
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
h3 {
  margin: 40px 0 0;
}
ul {
  list-style-type: none;
  padding: 0;
}
li {
  display: inline-block;
  margin: 0 10px;
}
a {
  color: #42b983;
}
</style>
