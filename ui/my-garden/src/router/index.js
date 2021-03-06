import Vue from 'vue'
import VueRouter from 'vue-router'
// import Home from '../views/Home.vue'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Home',
    // component: Home
    component: () => import(/* webpackChunkName: "about" */ '../views/Home.vue')
  },
  {
    path: '/Plants',
    name: 'Plants',
    component: () => import(/* webpackChunkName: "about" */ '../views/Plants.vue')
  },
  {
    path: '/Plant/:id',
    name: 'ShowPlant',
    props: true,
    component: () => import(/* webpackChunkName: "about" */ '../views/ShowPlant.vue')
  },
  {
    path: '/PlantForm',
    name: 'PlantForm',
    props: true,
    component: () => import(/* webpackChunkName: "about" */ '../views/PlantForm.vue')
  },
  {
    path: '/Garden/:id',
    name: 'ShowGarden',
    props: true,
    component: () => import(/* webpackChunkName: "about" */ '../views/ShowGarden.vue')
  },
  {
    path: '/GardenForm',
    name: 'GardenForm',
    props: true,
    component: () => import(/* webpackChunkName: "about" */ '../views/GardenForm.vue')
  },
  {
    path: '/Gardens',
    name: 'Gardens',
    component: () => import(/* webpackChunkName: "about" */ '../views/Gardens.vue')
  },
]

const router = new VueRouter({
  routes
})

export default router
