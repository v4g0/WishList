<template>
  <div class="super_container" style="    overflow: visible!important;">

  	<!-- Header -->

  	<header class="header">
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
    <div class="super_container_inner">
      <div class="super_overlay"></div>
      <div class="products">
  			<div class="container">
          <div class="row products_bar_row">
            <div class="col">
              <div class="products_bar d-flex flex-lg-row flex-column align-items-lg-center align-items-start justify-content-lg-start justify-content-center">
                <div class="products_bar_links">
                  <ul class="d-flex flex-row align-items-start justify-content-start">
                    <li><a href="#">All</a></li>
                    <li><a href="#">Hot Products</a></li>
                    <li><a href="#">Sale Products</a></li>
                  </ul>
                </div>

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
            wishlist:undefined
        };
      },
      created() {
         this.getBudget()
         this.getWishList()
         this.$eventHub.$on('updateWishList', () => {
             this.wishlist = undefined
             this.getWishList()
             this.getBudget()
         })
      },
      methods: {
          getWishList(){
            axios.get("/getwishlist",{
            }).then(response => {
               if(response.data.success){
                 this.wishlist = response.data.data
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
