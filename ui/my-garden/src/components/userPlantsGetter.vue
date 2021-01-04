<script>
export default {
  name: 'userPlantsGetter',
  methods: {
    populate() {
      // alert()
      fetch("http://localhost/api/plants", {
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
          console.log(response); alert("Server returned " + response.status + " : " + response.statusText + " data: " + response.data);
        }
      })
      .then(response => {
        this.array = response;
      })
      .catch(err => {
        console.log(err);
      });
    },
    get() {
      return this.array
    }
  }
}
</script>
