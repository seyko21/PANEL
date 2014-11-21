var loginScript_ = function(){
    
    var _private = {};
    
    this.public = {};
    
    this.public.validate = function(){
        $("#login-form").validate({
            rules : {
                    txtUser : {
                            required : true,
                            email: true
                    },
                    txtClave : {
                            required : true,
                            minlength : 3,
                            maxlength : 20
                    }
            },

            messages : {
                    txtEmail : {
                            required : 'Ingrese su correo'
                    },
                    txtClave : {
                            required : 'Ingrese su clave'
                    }
            },

            errorPlacement : function(error, element) {
                    error.insertAfter(element.parent());
            },
                    
            submitHandler: function(){
                login.postEntrar();
            }   
        });
                
    };
    
    return this.public;
};

var loginScript = new loginScript_();

loginScript.validate();