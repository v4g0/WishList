<template>
  <div :class="'col-xl-4 col-md-6 grid-item ' + type">
    <div class="product">
      <div style="border: solid 2px #dbdbdb; border-bottom: none;" class="product_image"><img :src="'uploads/products/thumbnail/' + wish.image" alt=""></div>
      <div class="product_content">
        <div class="product_info d-flex flex-row align-items-start justify-content-start">
          <div>
            <div>
              <div class="product_name"><a>{{wish.name}}</a></div>
              <div class="product_category"><a>{{wish.description}}</a></div>
            </div>
          </div>
          <div class="ml-auto text-right">
            <div class="rating_r rating_r_4 home_item_rating">Creado : {{wish.created_at.substr(0,10)}}</div>
            <div class="rating_r rating_r_4 home_item_rating">Modificado : {{wish.updated_at.substr(0,10)}}</div>
            <div class="product_price text-right">${{wish.price}}</div>
          </div>
        </div>
        <div class="product_buttons">
          <div class="text-right d-flex flex-row align-items-start justify-content-start">
            <div @click='deleteWish()' style="background:white!important" class="product_button product_fav text-center d-flex flex-column align-items-center justify-content-center">
              <div><div style="width:auto"><i style="font-size: 27px;  color: red; margin-right:25px" class="fas fa-times-circle"></i></div></div>
            </div>
            <div style="background:white!important;border-right: solid 1px #ededed;" class="product_button product_fav text-center d-flex flex-column align-items-center justify-content-center">
              <div><div style="width:auto"><i style="font-size: 27px;  color: darkblue;" class="fas fa-pen"></i></div></div>
            </div>
            <div v-on:click="discountWish" v-bind:style='this.style' class="product_button product_cart text-center d-flex flex-column align-items-center justify-content-center">
              <div><div><img src="images/cart.svg" class="svg" alt=""><div  v-bind:style='this.plus'>+</div></div></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
    export default {
      props:['wish'],
      data() {
        return {
            style: this.wish.bought === 1 ? 'background:gray' : '',
            plus: this.wish.bought === 1 ? 'display:none' : '',
            type: this.wish.bought === 1 ? 'bought' : 'wish',

        }
      },
      methods: {
          deleteWish(){
            swal({
                      title: "¿Estas seguro que deseas eliminar este deseo?",
                      icon: "warning",
                      buttons: true,
                      dangerMode: true,
                      })
                      .then((willDelete) => {
                      if (willDelete) {
                        axios.post('/removefwishlist', {
                             id : this.wish.id,
                           }).then((response) =>{
                              if(response.data.success){
                                this.$eventHub.$emit('updateWishList')
                              }
                           })
                      }
              });
          },
          discountWish(){
            if(this.wish.bought === 0){
              let bodyFormData = new FormData()
              bodyFormData.append('id', this.wish.id)
              axios.post("/buyproduct",bodyFormData,{
              }).then(response => {
                 if(response.data.success){
                   this.$eventHub.$emit('updateWishList')
                 }else{
                   swal('Este producto supera el presupuesto' , 'Favor de ajustar presupuesto para poder continuar.','error')
                 }
              })
            }
          },
      }

    }
</script>
