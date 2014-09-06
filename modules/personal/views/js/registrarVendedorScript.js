var registrarVendedorScript_ = function(){
    
    this.publico = {};
    
    this.publico.validaChangePass = function(){
        $('#fromchange_pass').validate({
            // Rules for form validation
            rules : {
                    txtNewClave : {
                        required: true,
                        regular: true,
                        minlenght: 5
                    }       
            },

            // Do not change code below
            errorPlacement : function(error, element) {
                    error.insertAfter(element.parent());
            },

            submitHandler: function(){
                registrarVendedor.postPassVendedor();
            }   
        });
    };
    
    return this.publico;
    
};
var registrarVendedorScript = new registrarVendedorScript_();
registrarVendedorScript.validaChangePass();