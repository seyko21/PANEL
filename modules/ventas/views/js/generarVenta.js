/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 05-09-2014 23:09:58 
* Descripcion : generarVenta.js
* ---------------------------------------
*/
var generarVenta_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idVenta = 0;
    _private.idCotizacion = 0;
    
    _private.tab = '';
    
    _private.config = {
        modulo: "ventas/generarVenta/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : GenerarVenta*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.VGEVE,
            label: $(element).attr("title"),
            fnCallback: function(){
                generarVenta.getContenido();
            }
        });
    };
    
    /*contenido de tab: GenerarVenta*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.VGEVE+"_CONTAINER").html(data);
                generarVenta.getGridGenerarVenta();
            }
        });
    };
    
    this.publico.getGridGenerarVenta = function (){
       var oTable = $('#'+diccionario.tabs.VGEVE+'gridGenerarVenta').dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "<input type='checkbox' id='"+diccionario.tabs.VGEVE+"chk_all' onclick='simpleScript.checkAll(this,\"#"+diccionario.tabs.VGEVE+"gridGenerarVenta\");'>", sWidth: "1%", sClass: "center", bSortable: false},                
                {sTitle: "Código", sWidth: "7%"},
                {sTitle: "Cliente", sWidth: "20%"},
                {sTitle: "Tipo Doc", sWidth: "8%"},
                {sTitle: "Fecha", sWidth: "10%",  sClass: "center"},
                {sTitle: "Moneda", sWidth: "7%"},                                
                {sTitle: "Total", sWidth: "11%",  sClass: "right"},  
                {sTitle: "Saldo", sWidth: "11%",  sClass: "right"},
                {sTitle: "Estado", sWidth: "8%",  sClass: "center"},
                {sTitle: "Acciones", sWidth: "10%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[4, 'desc'],[1, 'desc']],
            sScrollY: "350px",
            sAjaxSource: _private.config.modulo+'getGridGenerarVenta',
            fnDrawCallback: function() {
                $('#'+diccionario.tabs.VGEVE+'gridGenerarVenta_filter').find('input').attr('placeholder','Buscar por Código o Cliente').css('width','400px');               
                simpleScript.enterSearch("#"+diccionario.tabs.VGEVE+"gridGenerarVenta",oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: '#widget_'+diccionario.tabs.VGEVE, //widget del datagrid
                    typeElement: 'button, #'+diccionario.tabs.VGEVE+'chk_all'
                });
                $('#'+diccionario.tabs.VGEVE+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                });
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.VGEVE+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
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
                $('#'+diccionario.tabs.VGEVE+'formBuscarProductos').modal('show');
            }
        });
    };
    
    this.publico.getFormNewGenerarVenta = function(btn){
        generarVentaScript.resetArrayProducto();
        simpleScript.addTab({
            id : diccionario.tabs.VGEVE+'new',
            label: 'Nueva Venta',
            fnCallback: function(){
                generarVenta.getContProd();
            }
        });
    };
    
    this.publico.getContProd = function(){
        simpleAjax.send({
            dataType: 'html',
            root: _private.config.modulo+'getFormNewGenerarVenta',
            fnCallback: function(data){
                $('#'+diccionario.tabs.VGEVE+'new_CONTAINER').html(data);
            }
        });
    };
    
    this.publico.getFormEditarGenerarVenta = function(btn,id){
        _private.idCotizacion = id;
        
        generarVentaScript.resetArrayProducto();
        simpleScript.addTab({
            id : diccionario.tabs.VGEVE+'cotizacion',
            label: 'Nueva Venta - Cotización',
            fnCallback: function(){
                generarVenta.getContEditProd();
            }
        });
    };
    
    this.publico.getContEditProd = function(){
        simpleAjax.send({
            dataType: 'html',
            root: _private.config.modulo+'getFormEditGenerarVenta',
            data: '&_idCotizacion='+_private.idCotizacion,
            fnCallback: function(data){
                $('#'+diccionario.tabs.VGEVE+'cotizacion_CONTAINER').html(data);
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
                $('#'+diccionario.tabs.VGEVE+'formBuscarCliente').modal('show');
            }
        });
    };    
    
    this.publico.getClientes = function(){
        $('#'+diccionario.tabs.VGEVE+'gridClientesFound').dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            bFilter: false, 
            sServerMethod: "POST",
            bPaginate: false,
            aoColumns: [
                {sTitle: "Nro.", sClass: "center",sWidth: "2%",  bSortable: false},
                {sTitle: "Cliente", sWidth: "50%"},
                {sTitle: "N° Documento", sWidth: "20%"}
            ],
            aaSorting: [[1, 'asc']],
            sScrollY: "250px",
            sAjaxSource: _private.config.modulo+'getClientes',
            fnServerParams: function(aoData) {
                aoData.push({"name": diccionario.tabs.VGEVE+"_term", "value": $('#'+diccionario.tabs.VGEVE+'txt_search').val()});
                aoData.push({"name": "_tab", "value": _private.tab});
            },
            fnDrawCallback: function() {
                $('#'+diccionario.tabs.VGEVE+'gridClientesFound_wrapper').find('.dataTables_scrollBody').css('overflow-x','hidden');
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: '#'+diccionario.tabs.VGEVE+'gridClientesFound',
                    typeElement: 'a'
                });
            }
        });
        $('#'+diccionario.tabs.VGEVE+'gridClientesFound_filter').remove();
    };    
    
    
    this.publico.postNewGenerarVenta = function(){
        var acuenta = $('#'+diccionario.tabs.VGEVE+'formNewGenerarVenta #'+diccionario.tabs.VGEVE+'txt_pago').val();
        if(totalPagado <= 0){
             simpleScript.notify.warning({
                        content: 'El total a pagar no debe ser Cero'
             });
             return false;
         }else if(parseFloat(totalPagado) < acuenta){
            simpleScript.notify.warning({
                        content: 'El pago inicial sobrepasa el pago Total.'
             });
             return false;          
        }else if(acuenta < pago ){
            simpleScript.notify.warning({
                        content: 'El pago inicial debe ser mayor al 50% ('+pago.toFixed(2)+')'
             });
             return false;  
        }
                         
        simpleAjax.send({
            element: "#"+diccionario.tabs.VGEVE+"btnGrVenta",
            root: _private.config.modulo + "postNewGenerarVenta",
            form: "#"+diccionario.tabs.VGEVE+"formNewGenerarVenta",
            data: '&_idVenta='+_private.idVenta,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            simpleScript.closeTab(diccionario.tabs.VGEVE+'new');
                            simpleScript.closeTab(diccionario.tabs.VGEVE+'edit');
                            generarVenta.getGridGenerarVenta();
                            _private.idVenta = 0;
                        }
                    });
                }else if(!isNaN(data.result) && parseInt(data.result) === 2){
                    simpleScript.notify.warning({
                        content: "No existe CAJA aperturada. Debe Aperturarla para generar Ventas.",
                         callback: function(){
                            simpleScript.closeTab(diccionario.tabs.VGEVE+'new');
                            simpleScript.closeTab(diccionario.tabs.VGEVE+'edit');
                            generarVenta.getGridGenerarVenta();
                            _private.idVenta = 0;
                        }
                    });
                }
            }
        });
    };
    
    this.publico.postNewGenerarVenta2 = function(){
        var acuenta = $('#'+diccionario.tabs.VGEVE+'formNewGenerarVenta2 #'+diccionario.tabs.VGEVE+'txt_pago').val();
        if(totalPagado <= 0){
             simpleScript.notify.warning({
                        content: 'El total a pagar no debe ser Cero'
             });
             return false;
         }else if(parseFloat(totalPagado) < acuenta){
            simpleScript.notify.warning({
                        content: 'El pago inicial sobrepasa el pago Total.'
             });
             return false;          
        }else if(acuenta < pago ){
            simpleScript.notify.warning({
                        content: 'El pago inicial debe ser mayor al 50% ('+pago.toFixed(2)+')'
             });
             return false;  
        }
                         
        simpleAjax.send({
            element: "#"+diccionario.tabs.VGEVE+"btnGrVenta2",
            root: _private.config.modulo + "postNewGenerarVenta",
            form: "#"+diccionario.tabs.VGEVE+"formNewGenerarVenta2",
            data: '&_idCotizacion='+_private.idCotizacion,
            clear: true,
            fnCallback: function(data){
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            simpleScript.closeTab(diccionario.tabs.VGEVE+'cotizacion');
                             simpleScript.reloadGrid("#"+diccionario.tabs.VCOTI+"gridVGenerarCotizacion");
                            _private.idCotizacion = 0;
                        }
                    });
                }else if(!isNaN(data.result) && parseInt(data.result) === 2){
                    simpleScript.notify.warning({
                        content: "No existe CAJA aperturada. Debe Aperturarla para generar Ventas.",
                         callback: function(){
                           simpleScript.closeTab(diccionario.tabs.VGEVE+'cotizacion');
                            simpleScript.reloadGrid("#"+diccionario.tabs.VCOTI+"gridVGenerarCotizacion");
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
            data: '&_idVenta='+id+'&_cod='+cod,
            fnCallback: function(data) {
                if(parseInt(data.result) === 1){
                    $('#'+diccionario.tabs.VGEVE+'btnDowPDF').off('click');
                    $('#'+diccionario.tabs.VGEVE+'btnDowPDF').attr("onclick","window.open('public/files/"+data.archivo+"','_blank');generarCotizacion.deleteArchivo('"+data.archivo+"');");
                    $('#'+diccionario.tabs.VGEVE+'btnDowPDF').click();
                }
            }
        });
    };
    
    this.publico.postExcel = function(btn,id,cod){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postExcel',
            data: '&_idVenta='+id+'&_cod='+cod,
            fnCallback: function(data) {
                if(parseInt(data.result) === 1){
                    $('#'+diccionario.tabs.VGEVE+'btnDowExcel').off('click');
                    $('#'+diccionario.tabs.VGEVE+'btnDowExcel').attr("onclick","window.open('public/files/"+data.archivo+"','_self');generarCotizacion.deleteArchivo('"+data.archivo+"');");
                    $('#'+diccionario.tabs.VGEVE+'btnDowExcel').click();
                }
                if(!isNaN(data.result) && parseInt(data.result) === 2){
                    simpleScript.notify.error({
                        content: 'Ocurrió un error al exportar Venta.'
                    });
                }
            }
        });
    };
    
    this.publico.postAnularGenerarVentaAll = function(btn){
        simpleScript.validaCheckBox({
            id: "#"+diccionario.tabs.VGEVE+"gridGenerarVenta",
            msn: mensajes.MSG_9,
            fnCallback: function(){
                simpleScript.notify.confirm({
                    content: mensajes.MSG_13,
                    callbackSI: function(){
                        simpleAjax.send({
                            element: btn,
                            form: "#"+diccionario.tabs.VGEVE+"formGridGenerarVenta",
                            root: _private.config.modulo + "postAnularGenerarVentaAll",
                            fnCallback: function(data) {
                                if(!isNaN(data.result) && parseInt(data.result) === 1){
                                    simpleScript.notify.ok({
                                        content: mensajes.MSG_14,
                                        callback: function(){
                                            generarVenta.getGridGenerarVenta();
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
var generarVenta = new generarVenta_();