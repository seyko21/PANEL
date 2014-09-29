var cambiarClave_ = function(){
    
    var _private = {};
    
    _private.config = {
        modulo: 'usuarios/cambiarClave/'
    };
    
    this.publico = {};
    
    this.publico.main = function(){
        simpleAjax.send({
            dataType: 'html',
            root: _private.config.modulo,
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.CLAV+'indexCambiarClave').modal('show');
            }
        });
    };
    
    this.publico.postCambiarClave = function(){
        simpleAjax.send({
            element: '#'+diccionario.tabs.CLAV+'btnEClav',
            root: _private.config.modulo + 'postCambiarClave',
            form: '#'+diccionario.tabs.CLAV+'indexCambiarClave',
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_19
                    });
                    simpleScript.closeModal('#'+diccionario.tabs.CLAV+'indexCambiarClave');
                }
            }
        });
    };
    
    return this.publico;
    
};
var cambiarClave = new cambiarClave_();