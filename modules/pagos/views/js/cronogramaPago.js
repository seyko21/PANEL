var cronogramaPago_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idSeguimientoPago = 0;
    
    _private.nOrden = 0;
    
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
                {sTitle: "Representante", sWidth: "25%"},
                {sTitle: "Cliente", sWidth: "25%"},
                {sTitle: "Estado", sWidth: "10%"},
                {sTitle: "Fecha", sWidth: "10%"},
                {sTitle: "Monto", sWidth: "10%", sClass: "right", bSortable: false},
                {sTitle: "Acciones", sWidth: "8%", sClass: "center", bSortable: false}            
            ],
            aaSorting: [[2, "asc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridOrdenes",
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.CROPA+"getGridOrdenes_filter").find("input").attr("placeholder","Buscar por código o representante").css("width","250px");
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.CROPA,
                    typeElement: "button"
                });
            }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getFormPagarOrden = function(btn,id,norden){
        _private.nOrden = norden;
        
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormPagarOrden",
            data: "&_idOrden="+id+'&_norden='+_private.nOrden,
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
    
    this.publico.getFormReprogramar = function(btn,idc,cuota){
        _private.idCompromiso = idc;
        
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormReprogramar",
            data: '&_ncuota='+cuota,
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.CROPA+"formReprogramar").modal("show");
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
                            cronogramaPago.getGridOrdenes();
                            simpleScript.closeModal('#'+diccionario.tabs.CROPA+'formPagarOrdenParametros');
                            $("#"+_private.fila+diccionario.tabs.CROPA+"dfecha").html(data.fecha);
                            $(_private.boton).off('click');
                            $(_private.boton).addClass('disabled');
                            _private.idCompromiso = 0;
                            _private.boton = 0;
                        }
                    });
                }
            }
        });
    };
    
    
    
    return this.publico;
    
};
var cronogramaPago = new cronogramaPago_();