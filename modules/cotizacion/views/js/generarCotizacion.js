var generarCotizacion_ = function(){
    
    var _private = {};
    
    _private.id = 0;
    
    _private.config = {
        modulo: 'cotizacion/generarCotizacion/'
    };

    this.publico = {};
    
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.T8,
            label: $(element).attr('title'),
            fnCallback: function(){
                generarCotizacion.getCont();
            }
        });
    };
    
    this.publico.getCont = function(){
        simpleAjax.send({
            dataType: 'html',
            root: _private.config.modulo,
            fnCallback: function(data){
                $('#'+diccionario.tabs.T8+'_CONTAINER').html(data);
                generarCotizacion.getGridCotizacion();
            }
        });
    };
    
    this.publico.getGridCotizacion = function(){
        var oTable = $('#'+diccionario.tabs.T8+'gridGenerarCotizacion').dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "Código", sClass: "center",sWidth: "15%"},
                {sTitle: "Apellidos y Nombres", sWidth: "40%"},
                {sTitle: "Meses", sWidth: "10%",sClass: "center", bSortable: false},
                {sTitle: "Oferta", sWidth: "10%", sClass: "center", bSortable: false},
                {sTitle: "Acciones", sWidth: "15%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[0, 'desc']],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+'getGridCotizacion',
            fnDrawCallback: function() {
                $('#'+diccionario.tabs.T8+'gridGenerarCotizacion_filter').find('input').attr('placeholder','Buscar por código o appellidos y nombres').css('width','280px');
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: '#widget_'+diccionario.tabs.T8,
                    typeElement: 'button'
                });
            }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getNuevoGenerarCotizacion = function(btn){
        generarCotizacionScript.resetArrayProducto();
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
    
    this.publico.getFormBuscarMisProductos = function(btn,tab){
        _private.tab = tab;
        simpleAjax.send({
            element: btn,
            dataType: 'html',
            root: _private.config.modulo + 'getFormBuscarMisProductos',
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.T8+'formBuscarProducto').modal('show');
            }
        });
    };
    
    this.publico.getTableMisProductos = function(){
        simpleAjax.send({
            gifProcess: true,
            dataType: 'html',
            root: _private.config.modulo + 'getTableMisProductos',
            form: '#'+diccionario.tabs.T8+'formBuscarProducto',
            data: '&_tab='+_private.tab,
            fnCallback: function(data){
                $('#'+diccionario.tabs.T8+'gridProductosFound').find('tbody').html(data);
                $('#'+diccionario.tabs.T8+'chk_all').prop('checked',false);
            }
        });
    };
    
    this.publico.postGenerarCotizacion = function(){
        simpleScript.validaTable({
            id: '#'+diccionario.tabs.T8+'gridProductos',
            msn: mensajes.MSG_10,
            fnCallback: function(){
                simpleScript.notify.confirm({
                    content: '¿Está seguro de generar cotización?',
                    callbackSI: function(){
                        simpleAjax.send({
                            flag: 1,
                            element: '#'+diccionario.tabs.T8+'btnGcoti',
                            form: '#'+diccionario.tabs.T8+'formGenerarCotizacion',
                            root: _private.config.modulo + 'postGenerarCotizacion',
                            fnCallback: function(data) {
                                if(!isNaN(data.result) && parseInt(data.result) === 1){
                                    simpleScript.notify.ok({
                                        content: mensajes.MSG_3,
                                        callback: function(){
                                            generarCotizacion.getGridCotizacion();
                                            simpleScript.closeModal('#'+diccionario.tabs.T8+'formGenerarCotizacion');
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
    
    this.publico.postEmail = function(btn,idCot){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postEmail',
            data: '&_idCotizacion='+idCot,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: 'Cotización se envió correctamente.'
                    });
                }
                if(!isNaN(data.result) && parseInt(data.result) === 2){
                    simpleScript.notify.error({
                        content: 'Ocurrió un error con el envío de la cotización.'
                    });
                }
            }
        });
    };
    
    this.publico.postPDF = function(btn,idCot){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postPDF',
            data: '&_idCotizacion='+idCot,
            fnCallback: function(data) {
                if(parseInt(data.result) === 1){
                    $('#'+diccionario.tabs.T8+'btnDowPDF').click();
                }
            }
        });
    };
    
    this.publico.postExcel = function(btn,idCot){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postExcel',
            data: '&_idCotizacion='+idCot,
            fnCallback: function(data) {
                if(parseInt(data.result) === 1){
                    $('#'+diccionario.tabs.T8+'btnDowExcel').click();
                }
                if(!isNaN(data.result) && parseInt(data.result) === 2){
                    simpleScript.notify.error({
                        content: 'Ocurrió un error al exportar cotización.'
                    });
                }
            }
        });
    };
    
    return this.publico;
    
};
var generarCotizacion = new generarCotizacion_();