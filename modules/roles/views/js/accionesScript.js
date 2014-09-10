var accionesScript_ = function(){
    
    this.public = {};
    
    this.public.validateAccion = function(obj){
        $(obj.form).validate({
            // Rules for form validation
            rules : {
                    CRDACtxt_accion : {
                            required : true,
                            regular: true,
                            minlength: 3
                    },
                    CRDACtxt_alias : {
                            required : true,
                            regular: true,
                            minlength: 2,
                            maxlength: 5
                    },
                    CRDACtxt_icono : {
                            required : true,
                            regular: true,
                            minlength: 3
                    },
                    CRDACtxt_theme : {
                            required : true,
                            regular: true,
                            minlength: 3
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
    
   return this.public;
   
};
var accionesScript = new accionesScript_();