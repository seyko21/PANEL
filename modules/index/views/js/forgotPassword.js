var forgotPassword_  = function(){
    
    var _private = {};
    
    _private.config = {
       modulo: '../../index/login/'
    };
    
    this.public = {};
    
   
    this.public.postAcceso = function(){
        simpleAjax.send({
            element: '#btnClave',
            root: _private.config.modulo+'postAcceso',
            form: '#login-form-password',
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: 'Solicitud enviada correctamente'
                    });
                    setTimeout(function(){
                        simpleScript.redirect('../../');
                    }, 1000);                    
                    
                } else if(!isNaN(data.result) && parseInt(data.result) === 2){
                    simpleScript.notify.error({
                        content: 'Error al enviar E-mail.'
                    });
                } else if(!isNaN(data.result) && parseInt(data.result) === 3){
                    simpleScript.notify.error({
                        content: 'E-mail no existe en nuestra Base de datos.'
                    });
                }
            }
        });
    };
    return this.public;
};

var forgotPassword = new forgotPassword_();