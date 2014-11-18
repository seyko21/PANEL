/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 11-10-2014 05:10:22 
* Descripcion : pagoSocio.js
* ---------------------------------------
*/
var pagoSocio_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idComision = 0;    
    _private.saldo = 0;
    _private.idPersona = 0;
    _private.idBoleta = 0;
    
    _private.config = {
        modulo: "pagos/pagoSocio/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : PagoSocio*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.GPASO,
            label: $(element).attr("title"),
            fnCallback: function(){
                pagoSocio.getContenido();
            }
        });
    };
    
    /*contenido de tab: PagoSocio*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.GPASO+"_CONTAINER").html(data);
                pagoSocio.getGridPagoSocio();
            }
        });
    };
    
    this.publico.getGridPagoSocio = function (){
        
        var _cb = $("#"+diccionario.tabs.GPASO+"lst_estadosearch").val();
        
        var oTable = $("#"+diccionario.tabs.GPASO+"gridPagoSocio").dataTable({
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
                {sTitle: "Socio", sWidth: "28%"},
                {sTitle: "Fecha", sWidth: "15%",sClass: "center" },
                {sTitle: "Porcentaje", sWidth: "5%",sClass: "center" },
                {sTitle: "Comision", sWidth: "10%", sClass: "right"},             
                {sTitle: "Pagado", sWidth: "10%", sClass: "right"},             
                {sTitle: "Saldo", sWidth: "10%", sClass: "right"},             
                {sTitle: "Acciones", sWidth: "8%", sClass: "center", bSortable: false}      
            ],
            aaSorting: [[2, "desc"],[3, "asc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridPagoSocio",
            fnServerParams: function(aoData) {
                aoData.push({"name": "_estadocb", "value": _cb});
            },
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.GPASO+"gridPagoSocio_filter").find("input").attr("placeholder","Buscar por N° OS o socio").css("width","200px");
                simpleScript.enterSearch("#"+diccionario.tabs.GPASO+"gridPagoSocio",oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.GPASO,
                    typeElement: "button"
                });
                simpleScript.removeAttr.change({
                    container: "#widget_"+diccionario.tabs.GPASO,
                    typeElement: "select"
                });
                $('#'+diccionario.tabs.GPASO+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.GPASO+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();
    };    
    
    this.publico.getFormPagar = function(btn,id,socio,saldo,persona){
      _private.idComision = id;  
      _private.saldo = saldo;
      _private.idPersona = persona;
        
        simpleAjax.send({
            element : btn,
            gifProcess: true,
            dataType: 'html',
            root: _private.config.modulo + 'getFormPagar',
            data: '&_idComision='+id+'&_socio='+socio+'&_saldo='+saldo+'&_idPersona='+persona,
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.GPASO+'formPagarSocio').modal('show');
            }
        });
    };    
    
    this.publico.postPagoSocio = function(){
        if(parseFloat($('#'+diccionario.tabs.GPASO+'txt_monto').val()) > parseFloat(_private.saldo)){
            simpleScript.notify.warning({
                content: 'Monto es mayor que saldo.'
            });
            return false;
        }
        simpleAjax.send({
            element: "#"+diccionario.tabs.GPASO+"btnGrPag",
            root: _private.config.modulo + "postPagoVendedor",
            form: "#"+diccionario.tabs.GPASO+"formPagarSocio",
            data: "&_idComision="+_private.idComision+'&_idPersona='+_private.idPersona,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            simpleScript.reloadGrid('#'+diccionario.tabs.GPASO+'gridPagoSocio');                           
                            simpleScript.closeModal('#'+diccionario.tabs.GPASO+'formPagarSocio');
                            _private.idComision = 0;
                        }
                    });
                }
            }
        });
    };
    
    this.publico.postAnularPagoAll = function(btn){
        simpleScript.validaCheckBox({
            id: '#'+diccionario.tabs.GPASO+'gridPagoSocio',
            msn: mensajes.MSG_9,
            fnCallback: function(){
                simpleScript.notify.confirm({
                    content: mensajes.MSG_13,
                    callbackSI: function(){
                        simpleAjax.send({
                            element: btn,
                            form: '#'+diccionario.tabs.GPASO+'formGridPagoSocio',
                            root: _private.config.modulo + 'postAnularPagoAll',
                            fnCallback: function(data) {
                                if(!isNaN(data.result) && parseInt(data.result) === 1){
                                    simpleScript.notify.ok({
                                        content: mensajes.MSG_14,
                                        callback: function(){
                                            simpleScript.reloadGrid('#'+diccionario.tabs.GPASO+'gridPagoSocio');
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
                                    saldoSocio.getGridBoleta();
                                    if($('#'+diccionario.tabs.SASOC+'_CONTAINER').length > 0){
                                        setTimeout(function(){simpleScript.reloadGrid('#'+diccionario.tabs.SASOC+'gridSaldoSocio');},500);                                        
                                    }
                                    if($('#'+diccionario.tabs.GPASO+'_CONTAINER').length > 0){
                                       setTimeout(function(){simpleScript.reloadGrid('#'+diccionario.tabs.GPASO+'gridPagoSocio');},500);                                        
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
var pagoSocio = new pagoSocio_();