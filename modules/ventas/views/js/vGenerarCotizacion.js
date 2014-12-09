/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 05-09-2014 23:09:58 
* Descripcion : vGenerarCotizacion.js
* ---------------------------------------
*/
var vGenerarCotizacion_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idCotizacion = 0;
    
    _private.tab = '';
    
    _private.config = {
        modulo: "ventas/generarCotizacion/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : VGenerarCotizacion*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.VCOTI,
            label: $(element).attr("title"),
            fnCallback: function(){
                vGenerarCotizacion.getContenido();
            }
        });
    };
    
    /*contenido de tab: VGenerarCotizacion*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.VCOTI+"_CONTAINER").html(data);
                vGenerarCotizacion.getGridVGenerarCotizacion();
            }
        });
    };
    
    this.publico.getGridVGenerarCotizacion = function (){
       var oTable = $('#'+diccionario.tabs.VCOTI+'gridVGenerarCotizacion').dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "<input type='checkbox' id='"+diccionario.tabs.VCOTI+"chk_all' onclick='simpleScript.checkAll(this,\"#"+diccionario.tabs.VCOTI+"gridVGenerarCotizacion\");'>", sWidth: "1%", sClass: "center", bSortable: false},                
                {sTitle: "Código", sWidth: "7%"},
                {sTitle: "Cliente", sWidth: "20%"},                
                {sTitle: "Fecha", sWidth: "8%",  sClass: "center"},
                {sTitle: "Moneda", sWidth: "7%"},                                
                {sTitle: "Total", sWidth: "10%",  sClass: "right"}, 
                {sTitle: "Cod. Impr.", sWidth: "8%"},
                {sTitle: "Estado", sWidth: "8%",  sClass: "center"},                
                {sTitle: "Acciones", sWidth: "10%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[4, 'desc'],[1, 'desc']],
            sScrollY: "350px",
            sAjaxSource: _private.config.modulo+'getGridGenerarCotizacion',
            fnDrawCallback: function() {
                $('#'+diccionario.tabs.VCOTI+'gridVGenerarCotizacion_filter').find('input').attr('placeholder','Buscar por Prospecto o Codigo de Impresion').css('width','400px');               
                simpleScript.enterSearch("#"+diccionario.tabs.VCOTI+"gridVGenerarCotizacion",oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: '#widget_'+diccionario.tabs.VCOTI, //widget del datagrid
                    typeElement: 'button, #'+diccionario.tabs.VCOTI+'chk_all'
                });
                $('#'+diccionario.tabs.VCOTI+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                });
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.VCOTI+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();                
        
    };    
    
    this.publico.getFormBuscarProductos = function(btn,tab,idMoneda){
        _private.tab = tab;
        simpleAjax.send({
            element: btn,
            dataType: 'html',
            root: _private.config.modulo + 'getFormBuscarProductos',
            data: '&_idMoneda='+idMoneda,
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.VCOTI+'formBuscarProductos').modal('show');
            }
        });
    };
    
    this.publico.getFormNewVGenerarCotizacion = function(btn){
        vGenerarCotizacionScript.resetArrayProducto();
        simpleScript.addTab({
            id : diccionario.tabs.VCOTI+'new',
            label: 'Nueva Cotización',
            fnCallback: function(){
                vGenerarCotizacion.getContProd();
            }
        });
    };
    
  this.publico.getContProd = function(){
        simpleAjax.send({
            dataType: 'html',
            root: _private.config.modulo+'getFormNewVGenerarCotizacion',
            fnCallback: function(data){
                $('#'+diccionario.tabs.VCOTI+'new_CONTAINER').html(data);
            }
        });
    };    
    
    this.publico.getContGenerar = function(){
        simpleAjax.send({
            dataType: 'html',
            root: _private.config.modulo+'getFormGenerarVenta',
            data: '&_idCotizacion='+_private.idCotizacion,
            fnCallback: function(data){
                $('#'+diccionario.tabs.VCOTI+'edit_CONTAINER').html(data);
            }
        });
    };           
    
    this.publico.getFormEditarVGenerarCotizacion = function(btn,id){
        _private.idCotizacion = id;
        
        vGenerarCotizacionScript.resetArrayProducto();
        simpleScript.addTab({
            id : diccionario.tabs.VCOTI+'edit',
            label: 'Clonar Cotización',
            fnCallback: function(){
                vGenerarCotizacion.getContEditProd();
            }
        });
    };
    
    this.publico.getContEditProd = function(){
        simpleAjax.send({
            dataType: 'html',
            root: _private.config.modulo+'getFormEditVGenerarCotizacion',
            data: '&_idCotizacion='+_private.idCotizacion,
            fnCallback: function(data){
                $('#'+diccionario.tabs.VCOTI+'edit_CONTAINER').html(data);
            }
        });
    };           
    
    this.publico.getFormBuscarCliente = function(btn,tab){
        _private.tab = tab;
        simpleAjax.send({
            element: btn,
            dataType: 'html',
            root: _private.config.modulo + 'getFormBuscarCliente',
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.VCOTI+'formBuscarCliente').modal('show');
            }
        });
    };    
    
    this.publico.getClientes = function(){
        $('#'+diccionario.tabs.VCOTI+'gridClientesFound').dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            bFilter: false, 
            sServerMethod: "POST",
            bPaginate: false,
            aoColumns: [
                {sTitle: "Nro.", sClass: "center",sWidth: "2%",  bSortable: false},
                {sTitle: "Cliente", sWidth: "40%"},
                {sTitle: "E-mail", sWidth: "30%"}
            ],
            aaSorting: [[1, 'asc']],
            sScrollY: "250px",
            sAjaxSource: _private.config.modulo+'getClientes',
            fnServerParams: function(aoData) {
                aoData.push({"name": diccionario.tabs.VCOTI+"_term", "value": $('#'+diccionario.tabs.VCOTI+'txt_search').val()});
                aoData.push({"name": "_tab", "value": _private.tab});
            },
            fnDrawCallback: function() {
                $('#'+diccionario.tabs.VCOTI+'gridClientesFound_wrapper').find('.dataTables_scrollBody').css('overflow-x','hidden');
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: '#'+diccionario.tabs.VCOTI+'gridClientesFound',
                    typeElement: 'a'
                });
            }
        });
        $('#'+diccionario.tabs.VCOTI+'gridClientesFound_filter').remove();
    };    
    
    
    this.publico.postNewVGenerarCotizacion = function(f){      
                         
        simpleAjax.send({
            flag: f,
            element: "#"+diccionario.tabs.VCOTI+"btnGrCotizacion",
            root: _private.config.modulo + "postNewGenerarCotizacion",
            form: "#"+diccionario.tabs.VCOTI+"formNewGenerarCotizacion",
            data: '&_idCotizacion='+_private.idCotizacion,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            simpleScript.closeTab(diccionario.tabs.VCOTI+'new');
                            simpleScript.closeTab(diccionario.tabs.VCOTI+'edit');
                            vGenerarCotizacion.getGridVGenerarCotizacion();
                            _private.idCotizacion = 0;
                        }
                    });
                }
            }
        });
    };
    
    this.publico.postPDF = function(btn,id,cod){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postPDF',
            data: '&_idCotizacion='+id+'&_cod='+cod,
            fnCallback: function(data) {
                if(parseInt(data.result) === 1){
                    $('#'+diccionario.tabs.VCOTI+'btnDowPDF').off('click');
                    $('#'+diccionario.tabs.VCOTI+'btnDowPDF').attr("onclick","window.open('public/files/"+data.archivo+"','_blank');generarCotizacion.deleteArchivo('"+data.archivo+"');");
                    $('#'+diccionario.tabs.VCOTI+'btnDowPDF').click();
                }
            }
        });
    };
    
    this.publico.postExcel = function(btn,id,cod){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postExcel',
            data: '&_idCotizacion='+id+'&_cod='+cod,
            fnCallback: function(data) {
                if(parseInt(data.result) === 1){
                    $('#'+diccionario.tabs.VCOTI+'btnDowExcel').off('click');
                    $('#'+diccionario.tabs.VCOTI+'btnDowExcel').attr("onclick","window.open('public/files/"+data.archivo+"','_self');generarCotizacion.deleteArchivo('"+data.archivo+"');");
                    $('#'+diccionario.tabs.VCOTI+'btnDowExcel').click();
                }
                if(!isNaN(data.result) && parseInt(data.result) === 2){
                    simpleScript.notify.error({
                        content: 'Ocurrió un error al exportar Venta.'
                    });
                }
            }
        });
    };
    
    this.publico.postAnularVGenerarCotizacionAll = function(btn){
        simpleScript.validaCheckBox({
            id: "#"+diccionario.tabs.VCOTI+"gridVGenerarCotizacion",
            msn: mensajes.MSG_9,
            fnCallback: function(){
                simpleScript.notify.confirm({
                    content: mensajes.MSG_13,
                    callbackSI: function(){
                        simpleAjax.send({
                            element: btn,
                            form: "#"+diccionario.tabs.VCOTI+"formGridVGenerarCotizacion",
                            root: _private.config.modulo + "postAnularGenerarCotizacionAll",
                            fnCallback: function(data) {
                                if(!isNaN(data.result) && parseInt(data.result) === 1){
                                    simpleScript.notify.ok({
                                        content: mensajes.MSG_14,
                                        callback: function(){
                                            vGenerarCotizacion.getGridVGenerarCotizacion();
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
    
    this.publico.postEmail = function(btn,idCot,num){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postEmail',
            data: '&_idCotizacion='+idCot+'&_cod='+num,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: 'Cotización se envió correctamente.'
                    });
                    generarCotizacion.deleteArchivo(data.archivo);
                }
                if(!isNaN(data.result) && parseInt(data.result) === 2){
                    simpleScript.notify.error({
                        content: 'Ocurrió un error con el envío de la cotización.'
                    });
                }
            }
        });
    };    
    
    return this.publico;
    
};
var vGenerarCotizacion = new vGenerarCotizacion_();