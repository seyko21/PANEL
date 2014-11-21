var generarCotizacion_ = function(){
    
    var _private = {};
    
    _private.idCotizacion = 0;
    
    _private.numeroCotizacion = 0;
    
    _private.tab = null;
    
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
        var oTable = $('#'+diccionario.tabs.T8+'xgridGenerarCotizacion').dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "<input type='checkbox' id='"+diccionario.tabs.T8+"chk_all' onclick='simpleScript.checkAll(this,\"#"+diccionario.tabs.T8+"xgridGenerarCotizacion\");'>", sWidth: "1%", sClass: "center", bSortable: false},
                {sTitle: "Código", sClass: "center",sWidth: "10%"},
                {sTitle: "Prospecto", sWidth: "28%"},
                {sTitle: "Fecha", sWidth: "8%",sClass: "center"},                
                {sTitle: "F. Venc.", sWidth: "8%", sClass: "center"},
                {sTitle: "Incl. IGV", sWidth: "6%", sClass: "center"},                
                {sTitle: "Total", sWidth: "12%", sClass: "right"},
                {sTitle: "Estado", sWidth: "9%", sClass: "center"},
                {sTitle: "Acciones", sWidth: "10%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[1, 'desc']],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+'getGridCotizacion',
            fnDrawCallback: function() {
                $('#'+diccionario.tabs.T8+'xgridGenerarCotizacion_filter').find('input').attr('placeholder','Buscar por código o prospecto').css('width','280px');
                simpleScript.enterSearch("#"+diccionario.tabs.T8+"xgridGenerarCotizacion",oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: '#widget_'+diccionario.tabs.T8,
                    typeElement: 'button'
                });
                $('#'+diccionario.tabs.T8+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.T8+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
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
        
    this.publico.getNuevoGenerarCotizacion2 = function(element){
        //cerrartab clon
        simpleScript.closeTab(diccionario.tabs.T8+'clon');
        
        generarCotizacionScript.resetArrayProducto();
        simpleScript.addTab({
            id : diccionario.tabs.T8+'new',
            label: 'Nueva Cotización',
            fnCallback: function(){
                generarCotizacion.getContNew();
            }
        });
    };
    
    this.publico.getNuevoCliente = function(element){

        simpleScript.addTab({
            id : diccionario.tabs.REGCL,
            label: 'Registrar Cliente',
            fnCallback: function(){
                cliente.getContenido();
            }
        });
        
    };    
    
    this.publico.getContNew = function(){
        simpleAjax.send({
            dataType: 'html',
            root: _private.config.modulo+'getFormNewCotizacion',
            fnCallback: function(data){
                $('#'+diccionario.tabs.T8+'new_CONTAINER').html(data);
            }
        });
    };
    
    this.publico.getClonar = function(numCoti,idCot){
        //cerrartab nuevo
        simpleScript.closeTab(diccionario.tabs.T8+'new');
        
        _private.idCotizacion = idCot;
        _private.numeroCotizacion = numCoti;
        
        simpleScript.addTab({
            id : diccionario.tabs.T8+'clon',
            label: 'Clonar Cotización',
            fnCallback: function(){
                generarCotizacion.getContClonar();
                generarCotizacionScript.resetArrayProducto();
            }
        });
    };
    
    this.publico.getContClonar = function(){
        simpleAjax.send({
            dataType: 'html',
            root: _private.config.modulo+'getFormClonarCotizacion',
            data: '&_idCotizacion='+_private.idCotizacion,
            fnCallback: function(data){
                $('#'+diccionario.tabs.T8+'clon_CONTAINER').html(data);
            }
        });
    };
    
    this.publico.getFormBuscarMisProductos = function(btn,tab){
        var cant = parseFloat($('#'+diccionario.tabs.T8+'txt_meses').val());
      
        if(isNaN(cant)){
            simpleScript.notify.warning({
                content: 'Ingrese cantidad de meses.'
            });
        }else{
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
        }
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
    
    this.publico.getFormBuscarCliente = function(btn,tab){
        _private.tab = tab;
        simpleAjax.send({
            element: btn,
            dataType: 'html',
            root: _private.config.modulo + 'getFormBuscarCliente',
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.T8+'formBuscarCliente').modal('show');
            }
        });
    };
    
    this.publico.getClientes = function(){
        $('#'+diccionario.tabs.T8+'gridClientesFound').dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            bFilter: false, 
            sServerMethod: "POST",
            bPaginate: false,
            aoColumns: [
                {sTitle: "Nro.", sClass: "center",sWidth: "2%",  bSortable: false},
                {sTitle: "Cliente", sWidth: "40%"},
                {sTitle: "Razón Social", sWidth: "40%"}
            ],
            aaSorting: [[1, 'asc']],
            sScrollY: "250px",
            sAjaxSource: _private.config.modulo+'getClientes',
            fnServerParams: function(aoData) {
                aoData.push({"name": diccionario.tabs.T8+"_term", "value": $('#'+diccionario.tabs.T8+'txt_search').val()});
                aoData.push({"name": "_tab", "value": _private.tab});
            },
            fnDrawCallback: function() {
//                $('#'+diccionario.tabs.T8+'gridEmpleadosFound_wrapper').find('.dt-bottom-row').remove();
                $('#'+diccionario.tabs.T8+'gridClientesFound_wrapper').find('.dataTables_scrollBody').css('overflow-x','hidden');
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: '#'+diccionario.tabs.T8+'gridClientesFound',
                    typeElement: 'a'
                });
            }
        });
        $('#'+diccionario.tabs.T8+'gridEmpleadosFound_filter').remove();
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
                                            simpleScript.closeTab(diccionario.tabs.T8+'new');
                                            generarCotizacion.getGridCotizacion();
                                            //simpleScript.closeModal('#'+diccionario.tabs.T8+'formGenerarCotizacion');
                                            /*si tab seguimiento cotizaciones esta abienrto recargar grila de seguimiento*/
                                            if($('#'+diccionario.tabs.SEGCO+'gridSeguimientoCotizacion').length > 0){
                                                setTimeout(function(){
                                                    seguimientoCotizacion.getGridSeguimientoCotizacion();
                                                    //simpleScript.reloadGrid('#'+diccionario.tabs.SEGCO+'gridSeguimientoCotizacion');
                                                },2000);
                                            }
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
    
    this.publico.postClonarCotizacion = function(){
        simpleScript.validaTable({
            id: '#'+diccionario.tabs.T8+'gridProductos',
            msn: mensajes.MSG_10,
            fnCallback: function(){
                simpleScript.notify.confirm({
                    content: 'Se anulará la cotización N° '+_private.numeroCotizacion+' ¿Está seguro de generar cotización?',
                    callbackSI: function(){
                        simpleAjax.send({
                            flag: 11,
                            element: '#'+diccionario.tabs.T8+'btnGcoti',
                            form: '#'+diccionario.tabs.T8+'formGenerarCotizacion',
                            root: _private.config.modulo + 'postGenerarCotizacion',
                            data: '&_idCotizacion='+_private.idCotizacion,
                            fnCallback: function(data) {
                                if(!isNaN(data.result) && parseInt(data.result) === 1){
                                    simpleScript.notify.ok({
                                        content: mensajes.MSG_3,
                                        callback: function(){
                                            simpleScript.closeTab(diccionario.tabs.T8+'clon');
                                            _private.idCotizacion = 0;
                                            _private.numeroCotizacion = 0;
                                            simpleScript.reloadGrid('#'+diccionario.tabs.T8+'xgridGenerarCotizacion');
                                            //simpleScript.closeModal('#'+diccionario.tabs.T8+'formGenerarCotizacion');
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
            data: '&_idCotizacion='+idCot+'&_num='+num,
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
    
    this.publico.postPDF = function(btn,idCot, num){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postPDF',
            data: '&_idCotizacion='+idCot+'&_num='+num,
            fnCallback: function(data) {
                if(parseInt(data.result) === 1){
                    $('#'+diccionario.tabs.T8+'btnDowPDF').attr("onclick","window.open('public/files/"+data.archivo+"','_blank');generarCotizacion.deleteArchivo('"+data.archivo+"');");
                    $('#'+diccionario.tabs.T8+'btnDowPDF').click();
                }
            }
        });
    };
    
    this.publico.postExcel = function(btn,idCot, num){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postExcel',
            data: '&_idCotizacion='+idCot+'&_num='+num,
            fnCallback: function(data) {
                if(parseInt(data.result) === 1){
                    $('#'+diccionario.tabs.T8+'btnDowExcel').attr("onclick","window.open('public/files/"+data.archivo+"','_self');generarCotizacion.deleteArchivo('"+data.archivo+"');");
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
    
    this.publico.postAnularCotizacionAll = function(btn){
        simpleScript.validaCheckBox({
            id: '#'+diccionario.tabs.T8+'xgridGenerarCotizacion',
            msn: mensajes.MSG_9,
            fnCallback: function(){
                simpleScript.notify.confirm({
                    content: mensajes.MSG_13,
                    callbackSI: function(){
                        simpleAjax.send({
                            element: btn,
                            form: '#'+diccionario.tabs.T8+'formGridGenerarCotizacion',
                            root: _private.config.modulo + 'postAnularCotizacionAll',
                            fnCallback: function(data) {
                                if(!isNaN(data.result) && parseInt(data.result) === 1){
                                    simpleScript.notify.ok({
                                        content: mensajes.MSG_14,
                                        callback: function(){
                                            simpleScript.reloadGrid('#'+diccionario.tabs.T8+'xgridGenerarCotizacion');
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
    
    this.publico.deleteArchivo = function(archivo){
        setTimeout(function(){
            simpleAjax.send({
                root: _private.config.modulo + 'deleteArchivo',
                data: '&_archivo='+archivo
            });
        },7000);
    };
    
    return this.publico;
    
};
var generarCotizacion = new generarCotizacion_();