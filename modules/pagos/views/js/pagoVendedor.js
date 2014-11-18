var pagoVendedor_ = function(){
    
    var _private = {};
    
    _private.config = {
        modulo: "pagos/pagoVendedor/"
    };
    
    _private.idComision = 0;
    
    _private.saldo = 0;
    
    _private.idPersona = 0;
    
    _private.idBoleta = 0;
    
    this.publico = {};
    
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.GPAVE,
            label: $(element).attr("title"),
            fnCallback: function(){
                pagoVendedor.getContenido();
            }
        });
    };
    
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.GPAVE+"_CONTAINER").html(data);
                pagoVendedor.getGridPagosVendedor();
            }
        });
    };
    
    this.publico.getGridPagosVendedor = function(){
        
        var _cb = $("#"+diccionario.tabs.GPAVE+"lst_estadosearch").val();
        
        var oTable = $("#"+diccionario.tabs.GPAVE+"gridPagosVendedor").dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button  
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "N°", sWidth: "5%", bSortable: false},
                {sTitle: "N° OS", sWidth: "10%"},
                {sTitle: "Vendedor", sWidth: "28%"},
                {sTitle: "Fecha", sWidth: "15%",sClass: "center" },
                {sTitle: "Porcentaje", sWidth: "5%",sClass: "center" },
                {sTitle: "Comision", sWidth: "10%", sClass: "right"},             
                {sTitle: "Pagado", sWidth: "10%", sClass: "right"},             
                {sTitle: "Saldo", sWidth: "10%", sClass: "right"},             
                {sTitle: "Acciones", sWidth: "8%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[2, "desc"],[3, "asc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridPagosVendedor",
            fnServerParams: function(aoData) {             
                aoData.push({"name": "_estadocb", "value": _cb});
            },
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.GPAVE+"gridPagosVendedor_filter").find("input").attr("placeholder","Buscar por N° OS o vendedor").css("width","200px");
                simpleScript.enterSearch("#"+diccionario.tabs.GPAVE+"gridPagosVendedor",oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.GPAVE,
                    typeElement: "button, select"
                });
                simpleScript.removeAttr.change({
                    container: "#widget_"+diccionario.tabs.GPAVE,
                    typeElement: "select"
                });
                 $('#'+diccionario.tabs.GPAVE+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.GPAVE+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getFormPagar = function(btn,id,vendedor,saldo,persona,osnum){
        _private.idComision = id;  
        _private.saldo = saldo;
        _private.idPersona = persona;
        simpleAjax.send({
            element : btn,
            gifProcess: true,
            dataType: 'html',
            root: _private.config.modulo + 'getFormPagar',
            data: '&_idComision='+_private.idComision+'&_vendedor='+vendedor+'&_saldo='+saldo+'&_idPersona='+_private.idPersona+'&_idOrdenServicio='+osnum,
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.GPAVE+'formPagarVendedor').modal('show');
            }
        });
    };
    
    this.publico.postPagoVendedor = function(){
        if(parseFloat($('#'+diccionario.tabs.GPAVE+'txt_monto').val()) > parseFloat(_private.saldo)){
            simpleScript.notify.warning({
                content: 'Monto es mayor que saldo.'
            });
            return false;
        }
        simpleAjax.send({
            element: "#"+diccionario.tabs.GPAVE+"btnGrPag",
            root: _private.config.modulo + "postPagoVendedor",
            form: "#"+diccionario.tabs.GPAVE+"formPagarVendedor",
            data: "&_idComision="+_private.idComision+'&_idPersona='+_private.idPersona,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            simpleScript.reloadGrid('#'+diccionario.tabs.GPAVE+'gridPagosVendedor');
                            simpleScript.closeModal('#'+diccionario.tabs.GPAVE+'formPagarVendedor');
                            _private.idComision = 0;
                        }
                    });
                }
            }
        });
    };
    
    this.publico.postAnularPagoAll = function(btn){
        simpleScript.validaCheckBox({
            id: '#'+diccionario.tabs.GPAVE+'gridPagosVendedor',
            msn: mensajes.MSG_9,
            fnCallback: function(){
                simpleScript.notify.confirm({
                    content: mensajes.MSG_13,
                    callbackSI: function(){
                        simpleAjax.send({
                            element: btn,
                            form: '#'+diccionario.tabs.GPAVE+'formGridSaldoVendedor',
                            root: _private.config.modulo + 'postAnularPagoAll',
                            fnCallback: function(data) {
                                if(!isNaN(data.result) && parseInt(data.result) === 1){
                                    simpleScript.notify.ok({
                                        content: mensajes.MSG_14,
                                        callback: function(){
                                            simpleScript.reloadGrid('#'+diccionario.tabs.GPAVE+'gridPagosVendedor');
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
    
   this.publico.postDeletePago = function(){
        _private.idBoleta = simpleScript.getParam(arguments[0]);
        
        simpleScript.notify.confirm({
            content: mensajes.MSG_5,
            callbackSI: function(){
                simpleAjax.send({
                    flag: 3,
                    gifProcess: true,
                    data: '&_idBoleta='+_private.idBoleta,
                    root: _private.config.modulo + 'postDeletePago',
                    fnCallback: function(data) {
                        if(!isNaN(data.result) && parseInt(data.result) === 1){
                            simpleScript.notify.ok({
                                content: mensajes.MSG_6,
                                callback: function(){
                                    saldoVendedor.getGridBoleta();
                                    if($('#'+diccionario.tabs.SAVEN+'_CONTAINER').length > 0){
                                        setTimeout(function(){simpleScript.reloadGrid('#'+diccionario.tabs.SAVEN+'gridSaldoVendedor');},500);                                        
                                    }
                                    if($('#'+diccionario.tabs.GPAVE+'_CONTAINER').length > 0){
                                       setTimeout(function(){simpleScript.reloadGrid('#'+diccionario.tabs.GPAVE+'gridPagosVendedor');},500);                                        
                                    }
                                }
                            });
                        }
                    }
                });
            }
        });
    };    
    
    
    return this.publico;
    
};
var pagoVendedor = new pagoVendedor_();