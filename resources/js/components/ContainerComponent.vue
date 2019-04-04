<template>

  <div class="super_container" style="    overflow: auto; height: 100vh;">
    <div class="menu"
    v-bind:class="{ active: isActive }"
    >
     <span @click='closeMenu()' style=" position: absolute;top: 13px;right: 22px;color:black; font-weight:bold; font-size:20px">X</span>
     <!-- Navigation -->
     <div class="menu_nav" >
       <h3>{{this.boughtList === undefined ? 'Aun no tienes productos comprados.': 'Productos comprados'}}</h3>
       <ul style="overflow-y: auto;  height: 200px;">
         <li v-for="(bought, index) in boughtList" :key="index">
           <a>{{bought.name}} - ${{bought.price}}</a>
         </li>
       </ul>
       <h3 v-if='boughtList != undefined'>Total = ${{this.boughtTotal}}</h3>

       <h4 @click='logout()' style="color:red; float:right; margin-top:100px">Logout <i class="fas fa-sign-out-alt"></i></h4>
     </div>

    </div>
    <!-- Modal Component-->
    <modal-component></modal-component>
    <edit-modal-component></edit-modal-component>
  	<!-- Header -->

  	<header class="header"
    v-bind:class="{ active: isActive }"
    >
  		<div class="header_overlay"></div>
  		<div class="header_content d-flex flex-row align-items-center justify-content-start">
  			<div class="logo">
  				<a href="#">
  					<div class="d-flex flex-row align-items-center justify-content-start">
  						<div><img src="images/logo_1.png" alt=""></div>
  						<div>Wish List</div>
  					</div>
  				</a>
  			</div>
        <div style="font-size: 20px; margin-left: 15px; display:inline-block; color: #2e2e2e;" @click.default='getBoughtList()'><i class="fa fa-bars" aria-hidden="true"></i></div>

  			<div class="header_right d-flex flex-row align-items-center justify-content-start ml-auto">

          <div class="budget" style="font-size:15px">${{this.budget}}</div>
         <!-- Edit   -->
          <div v-on:click="updateBudget" class="edit">
            <div style="cursor:pointer;  margin-left: 8px;  margin-bottom: 3px;">
               <img width='15px' src="images/pen-solid.svg" >
             </div>
           </div>

  			</div>
  		</div>
  	</header>
    <div class="super_container_inner"
      v-bind:class="{ active: isActive }"
    >
      <div style="height: 100000vh; z-index:2" @click='closeMenu()' class="super_overlay"></div>
      <!-- Button for add new wish-->
      <button data-toggle="modal" data-target="#modal-add" class='add_button'>+</button>

      <div class="products">
  			<div class="container">
          <div class="row products_bar_row">
            <div class="col">
              <div class="products_bar d-flex flex-lg-row flex-column align-items-lg-center align-items-start justify-content-lg-start justify-content-center">
                <div class="products_bar_links" style="padding-top: 50px; margin: auto;">
                  <ul class="d-flex flex-row align-items-start justify-content-start">
                    <li><a href="#" @click='filter = 2; getWishList()'>Todos</a></li>
                    <li><a href="#" @click='filter = 1 ; getWishList()' >Comprados</a></li>
                    <li><a href="#" @click='filter = 0 ;getWishList()'>No comprados</a></li>
                  </ul>
                </div>

              </div>
              <!-- Search -->
              <div class="menu_search">
            		<form  class="menu_search_form">
            			<input v-model='search' type="text" class="search_input" placeholder="Busca algun deseo" required="required">
            			<a @click.default='searchWishes()' style="text-align: center;" class="menu_search_button"><img style="margin-top: 8px;" src="images/search.png" alt=""></a>
            		</form>
            	</div>
            </div>

          </div>

          <div class="row products_row products_container grid">
            <!-- Product -->
  				  <wish-component v-for="wish in wishlist" :key="wish.id" :wish="wish"></wish-component>
            <clip-loader
              class="w-100 my-4"
              :loading="wishlist == undefined"
              :color="'black'"
              :size="'30px'"
            ></clip-loader>
            <h2 style="margin:10px;text-align:center" v-if="wishlist === true">{{filter === 2 ? 'Aun no tienes deseos, agrega algunos.':'No existen deseos en esta categoría.'}}</h2>
            <h2 style="margin:10px;text-align:center" v-if="wishlist === false">No se encontraron resultados.</h2>
          </div>
        </div>
      </div>
   </div>

  </div>

</template>

<script>
    export default {
      data() {
        return {
            budget: 0,
            wishlist:undefined,
            filter: 2,
            boughtList:undefined,
            boughtTotal:0,
            search:null,
            isActive:false
        }
      },
      created() {
         this.getBudget()
         this.getWishList()
         this.$eventHub.$on('updateWishList', () => {
             this.wishlist = undefined
             this.filter = 2
             this.getWishList()
             this.getBudget()
         })
      },
      methods: {
          closeMenu(){
            this.isActive = false
          },
          logout(){
            axios.post("/logout",{
            }).then(response => {
              location.reload()
            })
          },
          searchWishes(e){
            if(this.search != null){
              this.wishlist = undefined
              axios.post("/search",{
                search: this.search
              }).then(response => {
                 if(response.data.success){
                   this.wishlist = response.data.data
                 }else{
                   this.wishlist=false
                 }
              })
            }
          },
          getBoughtList(){
            this.isActive = true
            axios.get("/getboughtlist",{
            }).then(response => {
               if(response.data.success){
                 this.boughtList = response.data.data
                 if(this.boughtList != undefined){
                   var total = 0
                   this.boughtList.map(e=>{
                     total += e.price
                   })
                   this.boughtTotal = total
                 }

               }else{
                 this.boughtList=true
               }
            })
          },
          getWishList(){
            this.wishlist = undefined
            axios.get("/getwishlist",{
              params: {
                type: this.filter
              }
            }).then(response => {
               if(response.data.success){
                 this.wishlist = response.data.data
               }else{
                 this.wishlist=true
               }
            })
          },
          getBudget() {
            axios.get("/getbudget",{
            }).then(response => {
               if(response.data.success){
                 this.budget = response.data.budget
               }
            })
          },
          updateBudget(){
            swal({
              text: 'Agrega un nuevo presupuesto.',
              content: 'input',
              button: {
                text: "Cambiar",
                closeModal: true,
              },
            })
            .then(value => {
             if(value > 0){
               let bodyFormData = new FormData()
               bodyFormData.append('budget', value)
               axios.post("/updatebudget",bodyFormData,{
               }).then(response => {
                  if(response.data.success){
                    this.getBudget()
                    swal('Tu presupuesto fue actualizado', '' , 'success')
                  }
               })
             }else{
               if(value === null){
                 swal.close();
               }else{
                  swal('Tu presupuesto tiene que ser mayor a 0', '' , 'error')
               }
             }
            })
          }
      }
    }
</script>
