var renovacion_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idOrden = 0;
    
    _private.config = {
        modulo: "ordenservicio/renovacion/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.GENRE,
            label: $(element).attr("title"),
            fnCallback: function(){
                renovacion.getContenido();
            }
        });
    };
    
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.GENRE+"_CONTAINER").html(data);
                renovacion.getGridRenovacion();
            }
        });
    };
    
    this.publico.getGridRenovacion = function (){
        var oTable = $("#"+diccionario.tabs.GENRE+"gridRenovacion").dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "<input type='checkbox' id='"+diccionario.tabs.GENRE+"chk_all' onclick='simpleScript.checkAll(this,\"#"+diccionario.tabs.GENRE+"gridRenovacion\");'>", sWidth: "1%", sClass: "center", bSortable: false},
                {sTitle: "Código OS", sWidth: "10%",sClass: "center"},
                {sTitle: "Representante", sWidth: "20%"},
                {sTitle: "Fecha", sWidth: "10%"},
                {sTitle: "Total", sWidth: "15%", sClass: "right", bSortable: false},
                {sTitle: "Estado", sWidth: "8%", sClass: "center", bSortable: false},
                {sTitle: "Acciones", sWidth: "15%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[1, "desc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"gridRenovacion",
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.GENRE+"gridRenovacion_filter").find("input").attr("placeholder","Buscar por código OS o representante").css("width","340px");
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.GENRE,
                    typeElement: "button"
                });
            }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getFormRenovacion = function(id,numOr){
        _private.idOrden = id;
        _private.numeroOrden = numOr;
        
        simpleScript.addTab({
            id : diccionario.tabs.GENRE+'reno',
            label: 'Renovar OS',
            fnCallback: function(){
                renovacion.getContRenovar();
                //generarCotizacionScript.resetArrayProducto();
            }
        });
    };
    
    this.publico.getContRenovar = function(){
        simpleAjax.send({
            dataType: 'html',
            root: _private.config.modulo+'getContRenovar',
            data: '&_idOrden='+_private.idOrden,
            fnCallback: function(data){
                $('#'+diccionario.tabs.GENRE+'reno_CONTAINER').html(data);
            }
        });
    };
    
    this.publico.postRenovacion = function(){
        simpleScript.validaTable({
            id: '#'+diccionario.tabs.GENRE+'gridProductos',
            msn: mensajes.MSG_10,
            fnCallback: function(){
                simpleScript.notify.confirm({
                    content: '¿Está seguro de renovar OS N° '+_private.numeroOrden+'?',
                    callbackSI: function(){
                        simpleAjax.send({
                            element: '#'+diccionario.tabs.GENRE+'btnGcoti',
                            form: '#'+diccionario.tabs.GENRE+'formRenovacion',
                            root: _private.config.modulo + 'postRenovacion',
                            data: '&_idOrden='+_private.idOrden,
                            fnCallback: function(data) {
                                if(!isNaN(data.result) && parseInt(data.result) === 1){
                                    simpleScript.notify.ok({
                                        content: mensajes.MSG_3,
                                        callback: function(){
                                            simpleScript.closeTab(diccionario.tabs.GENRE+'reno');
                                            _private.idOrden = 0;
                                            _private.numeroOrden = 0;
                                            simpleScript.reloadGrid('#'+diccionario.tabs.GENRE+'gridRenovacion');
                                        }
                                    });
                                }
                            }
                        });
                    }
                });
            }
        });
    };
    
    return this.publico;
    
};
var renovacion = new renovacion_();