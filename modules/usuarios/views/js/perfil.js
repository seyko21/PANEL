var perfil_ = function(){
    
    var _private = {};
    
    _private.config = {
        modulo: 'usuarios/perfil/'
    };
    
    this.publico = {};
    
    this.publico.main = function(){
        simpleAjax.send({
            dataType: 'html',
            root: _private.config.modulo,
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.PERF+'indexPerfil').modal('show');
            }
        });
    };
    
    this.publico.postPerfil = function(){
        simpleAjax.send({
            element: '#'+diccionario.tabs.PERF+'btnEper',
            root: _private.config.modulo + 'postPerfil',
            form: '#'+diccionario.tabs.PERF+'indexPerfil',
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_19
                    });
                    simpleScript.closeModal('#'+diccionario.tabs.PERF+'indexPerfil');
                }
            }
        });
    };
    
    return this.publico;
    
};
var perfil = new perfil_();