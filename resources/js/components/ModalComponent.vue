<template>
  <div class="modal" tabindex="-1" role="dialog"  id="modal-add">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal" >Agregar a Wish List</h4>
                    <button type="button" class="close" data-dismiss="modal"  aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <!-- <form v-if="type === 1"> -->
                      <form >
                        <div class="form-group">
                            <label for="recipient-name" class="control-label">Nombre</label>
                            <div class="input-group mb-3">
                              <input type="text" class="form-control" v-model="name"  aria-describedby="basic-addon2">

                            </div>
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="control-label">Descripción</label>
                            <div class="input-group mb-3">
                              <textarea type="text" class="form-control" v-model="description"  aria-describedby="basic-addon2"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="control-label">Precio $</label>
                            <div class="input-group mb-3">
                              <input type="number" class="form-control" v-model="price"  aria-describedby="basic-addon2">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="control-label">Imagen</label>
                            <div class="input-group mb-3">
                              <input type="file" class="form-control" @change="processFile($event)" aria-describedby="basic-addon2">
                            </div>
                        </div>
                        <div class="form-group">
                            <center>
                            <button @click.prevent="addWish()" :data-dismiss="checkmodal" type="button" class="btn btn-success">Agregar</button>
                            </center>
                        </div>

                    </form>
                </div>

            </div>
        </div>
      </div>
</template>


<script>
export default {
//  props:['question','ddq','type','status'],
  data() {
    return {
        name: '',
        description: '',
        price: 0,
        image:null,
        checkmodal: ''
    }
  },
  methods : {
      processFile(event) {
          this.image = event.target.files[0]
      },
      addWish : function (){
        if (this.name.length === 0 ||
            this.description.length === 0 ||
            this.price.length === 0 ||
            !this.image)
        {
          swal("Oops","Todos los campos son necesarios","error")
          return
        }

        if( this.price < 0 ){
          swal("Oops","El precio debe ser mayor a 0.","error")
          return
        }

        if(this.name.length > 255 || this.description.length > 255){
          swal("Oops","El límite de caracteres es: 255","error")
          return
        }
        this.checkmodal = 'modal'
        let bodyFormData = new FormData();
        bodyFormData.append('name', this.name);
        bodyFormData.append('description', this.description);
        bodyFormData.append('price', this.price);
        bodyFormData.append('image', this.image);
        swal({
             closeOnClickOutside: false,
             buttons: false,
             icon: "info",
             text: "Procesando..",
            });

        axios.post("/createnewproduct", bodyFormData,{  headers: { 'content-type': 'multipart/form-data' }})
        .then(r => {
          if(r.data.success) {
            this.name= ''
            this.description= ''
            this.price= 0
            this.image=null
            this.checkmodal= ''
            swal("Hecho","¡Deseo Agregado!","success")
            this.$eventHub.$emit('updateWishList')
          }
        })

      }
  }
};
</script>
