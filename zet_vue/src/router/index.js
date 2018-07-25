import Vue from 'vue'
import Router from 'vue-router'
import Hello from '@/components/Hello'
import Images from '@/components/Images'
import ImageAdd from '@/components/ImageAdd'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      component: Hello
    },
    {
      path: '/images',
      component: Images
    },
    {
      path: '/image_add',
      component: ImageAdd
    }
  ]
})
