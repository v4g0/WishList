
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
window.Vue = require('vue');
/**
 * Create a Hub for vue to manage components
 */
Vue.prototype.$eventHub = new Vue();

//loaders
Vue.component('pulse-loader', require('vue-spinner/src/PulseLoader.vue'));
Vue.component('clip-loader', require('vue-spinner/src/ClipLoader.vue'));

//Container component
Vue.component('container-component', require('./components/ContainerComponent.vue').default);
Vue.component('wish-component', require('./components/WishComponent.vue').default);

//Axios responses
axios.interceptors.response.use(undefined, error => {
  if (error.message === "Network Error") {
    swal("¡Oops!", "Network Error", "error")
  }

  switch (error.response.status) {
    case 422:
       swal("¡Oops!", "Datos incorrectos", "error")
      break;
    case 405:
      swal("¡Oops!", "Acceso denegado", "error")
      return

    default:
      swal("¡Oops!", "Servicio no disponible", "error")
      break
  }

  return Promise.reject(error);
});


const app = new Vue({
    el: '#app'
});
