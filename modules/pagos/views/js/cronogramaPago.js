var cronogramaPago_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idSeguimientoPago = 0;
    
    _private.nOrden = 0;
    
    _private.idOrden = 0;
    
    _private.idCompromiso = 0;
    
    _private.fila = 0;
    
    _private.boton = 0;
    
    _private.config = {
        modulo: "pagos/cronogramaPago/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : SeguimientoPago*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.CROPA,
            label: $(element).attr("title"),
            fnCallback: function(){
                cronogramaPago.getContenido();
            }
        });
    };
    
    /*contenido de tab: SeguimientoPago*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.CROPA+"_CONTAINER").html(data);
                cronogramaPago.getGridOrdenes();
            }
        });
    };
     
    this.publico.getGridOrdenes = function (){
        var oTable = $("#"+diccionario.tabs.CROPA+"getGridOrdenes").dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "N°", sWidth: "1%",bSortable: false},
                {sTitle: "Código", sWidth: "10%",sClass: "center"},
                {sTitle: "Cliente", sWidth: "30%"},
                {sTitle: "Creado por", sWidth: "20%"},
                {sTitle: "Estado", sWidth: "10%"},
                {sTitle: "Fecha", sWidth: "10%"},
                {sTitle: "Mora", sWidth: "10%", sClass: "right"},
                {sTitle: "Monto", sWidth: "10%", sClass: "right"},
                {sTitle: "Acciones", sWidth: "8%", sClass: "center", bSortable: false}            
            ],
            aaSorting: [[1, "desc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridOrdenes",
            fnServerParams: function(aoData) {
                aoData.push({"name": "_estadocb", "value": $("#"+diccionario.tabs.CROPA+"lst_estadosearch").val()});
            },
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.CROPA+"getGridOrdenes_filter").find("input").attr("placeholder","Buscar por N° OS o Cliente").css("width","350px");
                simpleScript.enterSearch("#"+diccionario.tabs.CROPA+'getGridOrdenes',oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.CROPA,
                    typeElement: "button"
                });
            $('#'+diccionario.tabs.CROPA+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.CROPA+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getFormPagarOrden = function(btn,id,norden){
        _private.nOrden = norden;
        _private.idOrden = id;
        
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormPagarOrden",
            data: "&_idOrden="+_private.idOrden+'&_norden='+_private.nOrden,
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.CROPA+"formPagarOrden").modal("show");
            }
        });
    };
    
    this.publico.getFormPagarOrdenParametros = function(btn,fila,idCompromiso,cuota){
        _private.idCompromiso = idCompromiso;
        _private.fila = fila;
        _private.boton = btn;
        
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormPagarOrdenParametros",
            data: '&_norden='+_private.nOrden+'&_ncuota='+cuota,
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.CROPA+"formPagarOrdenParametros").modal("show");
            }
        });
    };
    
    this.publico.getFormReprogramar = function(btn,idc,cuota,fila, fecha){
        _private.idCompromiso = idc;
        _private.fila = fila;
        
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormReprogramar",
            data: '&_ncuota='+cuota+'&_fecha='+fecha,
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.CROPA+"formReprogramar").modal("show");
            }
        });
    };
    
    this.publico.getTableCronograma = function(){
        simpleAjax.send({
            gifProcess: true,
            dataType: "html",
            root: _private.config.modulo + "getTableCronograma",
            data: "&_idOrden="+_private.idOrden,
            fnCallback: function(data){
                $("#"+diccionario.tabs.CROPA+"tabCronograma").html(data);
            }
        });
    };
    
    this.publico.postPagarOrden = function(){
        simpleAjax.send({
            element: "#"+diccionario.tabs.CROPA+"btnGpr",
            root: _private.config.modulo + "postPagarOrden",
            form: "#"+diccionario.tabs.CROPA+"formPagarOrdenParametros",
            data: "&_idCompromiso="+_private.idCompromiso,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){                            
                            setTimeout(function(){
                                        simpleScript.reloadGrid('#'+diccionario.tabs.CROPA+'getGridOrdenes');
                                    },1000);
                            simpleScript.closeModal('#'+diccionario.tabs.CROPA+'formPagarOrdenParametros');
                            $("#"+_private.fila+diccionario.tabs.CROPA+"dfecha").html(data.fecha);
                            $("#"+_private.fila+diccionario.tabs.CROPA+"tr_estado").html('<span class="label label-success">Pagado</span>');
                            $(_private.boton).off('click');
                            $(_private.boton).addClass('disabled');
                            
//                            $('#'+diccionario.tabs.CROPA+_private.fila+'btnAnular').removeClass('disabled');
//                            $('#'+diccionario.tabs.CROPA+_private.fila+'btnAnular').click(function(){
//                                cronogramaPago.postAnularPago(this,_private.idCompromiso);
//                            });
                            cronogramaPago.getTableCronograma();                            
                            _private.idCompromiso = 0;
                            _private.boton = 0;
                            _private.fila = 0;
                        }
                    });
                }
            }
        });
    };
    
    this.publico.postReprogramar = function(){
        
        var fechaPrev = $.trim($('#tr_cuotas'+_private.fila+diccionario.tabs.CROPA).find('td:eq(2)').html());
        var fechaAct  = $('#CROPAtxt_fechare').val();
        var fechaNext = $.trim($('#tr_cuotas'+(parseInt(_private.fila) + 1)+diccionario.tabs.CROPA).find('td:eq(2)').html());
        
        var diffAntes   = simpleScript.dateDiff(fechaPrev,fechaAct);
        var diffDespues = simpleScript.dateDiff(fechaNext,fechaAct);
        
        if(diffAntes <= 0){/*no pasa, fecha reprogramada es menor que fecha anterior*/
            simpleScript.notify.warning({
                content: 'Fecha a reprogramar es menor o igual que fecha actual de cuota'
            });
            return false;
        }
        if(diffDespues >= 0){/*no pasa, fecha reprogramada es mayor que fecha siguiente*/
            simpleScript.notify.warning({
                content: 'Fecha a reprogramar es mayor o igual que fecha siguiente'
            });
            return false;
        }

        simpleAjax.send({
            element: "#"+diccionario.tabs.CROPA+"btnGrep",
            root: _private.config.modulo + "postReprogramar",
            form: "#"+diccionario.tabs.CROPA+"formReprogramar",
            data: "&_idCompromiso="+_private.idCompromiso,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            cronogramaPago.getTableCronograma();
                            setTimeout(function(){
                                        simpleScript.reloadGrid('#'+diccionario.tabs.CROPA+'getGridOrdenes');
                                    },1000);
                            simpleScript.closeModal('#'+diccionario.tabs.CROPA+'formReprogramar');
                            _private.idCompromiso = 0;
                            _private.fila = 0;
                        }
                    });
                }
            }
        });
    };
    
    this.publico.postAnularPago = function(btn,id){
        simpleScript.notify.confirm({
            content: mensajes.MSG_18,
            callbackSI: function(){
                simpleAjax.send({
                    element: btn,
                    root: _private.config.modulo + "postAnularPago",
                    data: "&_idCompromiso="+id,
                    fnCallback: function(data) {
                        if(!isNaN(data.result) && parseInt(data.result) === 1){
                            simpleScript.notify.ok({
                                content: mensajes.MSG_17,
                                callback: function(){
                                    cronogramaPago.getTableCronograma();
                                    setTimeout(function(){
                                        simpleScript.reloadGrid('#'+diccionario.tabs.CROPA+'getGridOrdenes');
                                    },1000);
                                }
                            });
                        }else if(!isNaN(data.result) && parseInt(data.result) === 2){
                            simpleScript.notify.warning({
                                content: 'No se puede anular este pago. Debe de Anular sus ordenes de instalación para realizar este proceso.'
                            });
                        }
                    }
                });
            }
        }); 
    };
    
    return this.publico;
    
};
var cronogramaPago = new cronogramaPago_();