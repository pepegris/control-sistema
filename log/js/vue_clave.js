

const app = new Vue({
    

    el:'.fieldset',

    data:{

       
   clave1:'Clave',
   clave2:'Clave',
   mensaje:'No son iguales!',
   button:'<button type="submit" class="btn btn-success">Save</button>'

    },

     methods: {
        sabana:function () {
            this.contenido_mapa=this.mapa_sab;
             
         },
        
       
        
    },
    computed:{

       comparando(){
          if (this.clave1 == this.clave2) {

        
          }else if (this.clave1 != this.clave2) {
             return this.mensaje;
          } 
       },

       boton(){
         if (this.clave1 == this.clave2) {

            if (this.clave1 =='Clave' && this.clave2 == 'Clave') {
               
            }else{
               return this.button;
            }

             
          }

       }
    }
 
   
})