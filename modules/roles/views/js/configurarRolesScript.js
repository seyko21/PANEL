var configurarRolesScript_ = function(){
    
   
   this.public = {};
   
   this.public.validateRol = function(obj){
        $(obj.form).validate({
            // Rules for form validation
            rules : {
                    CRDCRtxt_rol : {
                            required : true,
                            regular: true,
                            minlength: 3
                    }
            },

            // Messages for form validation
            messages : {
                    CRDCRtxt_rol : {
                            required : 'Rol requerido',
                            regular: 'No se permite caracteres inválidos'
                    }
            },

            // Do not change code below
            errorPlacement : function(error, element) {
                    error.insertAfter(element.parent());
            },
                    
            submitHandler: function(){
                eval(obj.evento);
            }   
        });
    };
   
   this.public.showAcciones = function(idRolOpciones){
       $('.accionesOpcion').hide();
       $('#acc_'+idRolOpciones).fadeIn();
   };
   
   return this.public;
    
};
var configurarRolesScript = new configurarRolesScript_();