var registrarVendedorScript_ = function(){
    
    this.publico = {};
    
    this.publico.validaChangePass = function(){
//        $('#fromchange_pass').validate({
//            // Rules for form validation
//            rules : {
//                    txtNewClave : {
//                        required: true,
//                        regular: true,
//                        minlenght: 5
//                    }       
//            },
//
//            // Do not change code below
//            errorPlacement : function(error, element) {
//                    error.insertAfter(element.parent());
//            },
//
//            submitHandler: function(){
//                registrarVendedor.postPassVendedor();
//            }   
//        });
        if($('#txtNewClave').val().length === 0){
            simpleScript.notify.error({
                content: 'Ingrese su clave'
            });
        }else if($('#txtNewClave').val().length < 6){
            simpleScript.notify.error({
                content: 'La clave debe tener como mínimo 6 caracteres.'
            });
        }else{
            registrarVendedor.postPassVendedor();
        }
    };
    
    return this.publico;
    
};
var registrarVendedorScript = new registrarVendedorScript_();