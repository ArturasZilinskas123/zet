<template>
  <div class="image_add">
    <div>
      Image name<input v-model="name" placeholder="">
    </div>
    <picture-input v-model="image"
      ref="pictureInput"
      @change="onChange"
      width="600"
      height="600"
      margin="16"
      accept="image/jpeg,image/png,image/gif"
      size="100"
      buttonClass="btn"
      hideChangeButton=true
      :customStrings="{
        upload: '<h1>Bummer!</h1>',
        drag: 'Drag'
      }">
    </picture-input>
    <div>
      <button v-on:click="submit_image">Submit</button>
    </div>
    <div>
      <div>
         {{ error }}
      </div>
      <div>
        {{ createdMessage }}
      </div>
    </div>
  </div>
</template>

<script>
import PictureInput from 'vue-picture-input'

export default {
  name: 'app',
  data () {
    return {
      error: '',
      createdMessage: '',
      image: '',
      name: ''
    }
  },
  components: {
    PictureInput
  },
  methods: {
    onChange (image) {
      if (image) {
        this.image = image
      } else {

      }
    },
    submit_image () {
      const vm = this
      vm.createdMessage = ''
      vm.error = ''
      var requestData = {
        name: this.name,
        image: this.image
      }
      this.axios.post(process.env.API + '/image/new', requestData).then(function (response) {
        if (response.status === 201) {
          vm.createdMessage = 'Created'
        }
      }).catch(function (error) {
        if (error.response.status === 422) {
          vm.error = error.response.data
        } else {
          vm.error = 'Error'
        }
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
</style>
