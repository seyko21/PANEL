var forgotPasswordScript_ = function(){
    
    var _private = {};
    
    this.public = {};
    
    this.public.validate = function(){    
        $("#login-form-password").validate({
                // Rules for form validation
                rules : {
                        txtUser : {
                                required : true,
                                email: true
                        }
                },

                // Messages for form validation
                messages : {
                        txtEmail : {
                                required : 'Ingrese su correo'
                        }
                },

                // Do not change code below
                errorPlacement : function(error, element) {
                        error.insertAfter(element.parent());
                },

                submitHandler: function(){
                    forgotPassword.postAcceso();
                }   
            });  
      };     
        
    return this.public;
};

var forgotPasswordScript = new forgotPasswordScript_();

forgotPasswordScript.validate();