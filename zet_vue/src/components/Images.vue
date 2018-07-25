<template>
  <div class="images">
      <div v-for="image in images">
        <div>{{image.name}}</div>
        <img class='img_src' :src="image.imagePath">
      </div>
      <div v-if="images.length == 0">
        <p>No elements</p>
      </div>
      <div>
        <button v-on:click="submit_prev">prev</button>
      </div>
      <div>
        <button v-on:click="submit_next">next</button>
      </div>
  </div>
</template>

<script>
export default {
  name: 'images',
  data () {
    return {
      images: [],
      offset: 0,
      limit: 9
    }
  },
  mounted () {
    this.getImages()
  },
  methods: {
    submit_prev () {
      if (this.offset <= this.limit) {
        this.offset = 0
      } else {
        this.offset -= this.limit
      }
      this.getImages()
    },
    submit_next () {
      if (this.images.length !== 0) {
        this.offset += this.limit
      }
      this.getImages()
    },
    getImages () {
      const vm = this
      this.axios.get(process.env.API + '/image/' + this.limit + '/' + this.offset).then(function (response) {
        var data = JSON.parse(response.data)
        data.forEach(function (element) {
          element.imagePath = process.env.API + element.imagePath
        })
        vm.images = data
      })
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style>
h1, h2 {
  font-weight: normal;
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
  color: #35495E;
}
.img_src {
  width:600px;
}
</style>
