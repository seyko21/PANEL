var genararCotizacion_ = function(){
    
    var _private = {};
    
    _private.id = 0;
    
    _private.config = {
        modulo: 'cotizacion/genararCotizacion/'
    };

    this.publico = {};
    
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.T8,
            label: $(element).attr('title'),
            fnCallback: function(){
                genararCotizacion.getCont();
            }
        });
    };
    
    this.publico.getCont = function(){
        simpleAjax.send({
            dataType: 'html',
            root: _private.config.modulo,
            fnCallback: function(data){
                $('#'+diccionario.tabs.T8+'_CONTAINER').html(data);
//                genararCotizacion.getGridGenararCotizacion();
            }
        });
    };
    
    this.publico.getNuevoGenerarCotizacion = function(btn){
        simpleAjax.send({
            element: btn,
            dataType: 'html',
            root: _private.config.modulo + 'getNuevoGenerarCotizacion',
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.T8+'formGenerarCotizacion').modal('show');
            }
        });
    };
    
    return this.publico;
    
};
var genararCotizacion = new genararCotizacion_();