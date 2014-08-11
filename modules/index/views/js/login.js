var login_  = function(){
    
    var _private = {};
    
    _private.config = {
        moduloIndex: 'index'
    };
    
    this.public = {};
    
    this.public.postEntrar = function(){
           simpleAjax.send({
                flag: 1,
                element: '#btnEntrar',
                root: _private.config.moduloIndex + '/login',
                //form: '#login-form',
                data: '&_user='+simpleAjax.stringPost($('#txtUser').val())+'&_clave='+simpleAjax.stringPost($('#txtClave').val()),
                fnCallback: function(data) {
                    if(!isNaN(data.id_usuario) && data.id_usuario > 0){
                        simpleScript.notify.ok({
                            content: mensajes.MSG_2,
                            callback: function(){
                                simpleScript.redirect('index');
                            }
                        });
                    }else{
                        simpleScript.notify.error({
                            content: mensajes.MSG_1
                        });
                    }
                }
            });
    };
    
    return this.public;
};

var login = new login_();