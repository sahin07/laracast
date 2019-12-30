
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';

Vue.use(VueRouter);

import Profile from './components/Profile.vue'
import Dashboard from './components/Dashboard.vue'
import Users from './components/Users.vue'

const router = new VueRouter({
  mode: 'history',
  routes: [
      {
          path: '/dashboard',
          name: 'dashboard',
          component: Dashboard
      },
      {
          path: '/profile',
          name: 'profile',
          component: Profile,
      },
      {
        path: '/users',
        name: 'users',
        component: Users,
    },
  ],
});

// export default new VueRouter({
//   routes
// });
  
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router
});
