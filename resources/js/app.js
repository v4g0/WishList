
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
Vue.component('pulse-loader', require('vue-spinner/src/PulseLoader.vue').default);
Vue.component('clip-loader', require('vue-spinner/src/ClipLoader.vue').default);
//Container component
Vue.component('container-component', require('./components/ContainerComponent.vue').default);
//Component for wishes
Vue.component('wish-component', require('./components/WishComponent.vue').default);
//component modal for add new products
Vue.component('modal-component', require('./components/ModalComponent.vue').default);
//component modal for edit wishes
Vue.component('edit-modal-component', require('./components/EditModalComponent.vue').default);

//Axios responses
axios.interceptors.response.use(undefined, error => {
  if (error.message === "Network Error") {
    swal("¡Oops!", "Network Error", "error")
  }

  switch (error.response.status) {
    case 401:
        location.reload()
      break
    case 422:
      swal("¡Oops!", "Datos incorrectos", "error")
      break
    case 405:
      swal("¡Oops!", "Acceso denegado", "error")
      return
      break
    default:
      swal("¡Oops!", "Servicio no disponible", "error")
      break
  }

  return Promise.reject(error);
});


const app = new Vue({
    el: '#app'
});
