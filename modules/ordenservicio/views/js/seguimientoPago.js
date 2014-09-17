/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 16-09-2014 22:09:43 
* Descripcion : seguimientoPago.js
* ---------------------------------------
*/
var seguimientoPago_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idSeguimientoPago = 0;
    
    _private.config = {
        modulo: "ordenservicio/seguimientoPago/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : SeguimientoPago*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.SEGPA,
            label: $(element).attr("title"),
            fnCallback: function(){
                seguimientoPago.getContenido();
            }
        });
    };
    
    /*contenido de tab: SeguimientoPago*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.SEGPA+"_CONTAINER").html(data);
                seguimientoPago.getGridSeguimientoPago();
            }
        });
    };
    
    this.publico.getGridSeguimientoPago = function (){
        var oTable = $("#"+diccionario.tabs.SEGPA+"gridSeguimientoPago").dataTable({
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
                {sTitle: "Monto", sWidth: "10%", sClass: "right", bSortable: false},
                {sTitle: "Acciones", sWidth: "8%", sClass: "center", bSortable: false}            
            ],
            aaSorting: [[2, "asc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridSeguimientoPago",
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.SEGPA+"gridSeguimientoPago_filter").find("input").attr("placeholder","Buscar por código o representante").css("width","250px");
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.SEGPA,
                    typeElement: "button"
                });
            }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getFormPagarOrden = function(btn,id,norden){
        _private.idSeguimientoPago = id;
            
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormPagarOrden",
            data: "&_idOrden="+_private.idSeguimientoPago+'&_norden='+norden,
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.SEGPA+"formPagarOrden").modal("show");
            }
        });
    };
    
    this.publico.postPagarOrden = function(btn,fila,idCompromiso){
        var fech = $("#"+fila+diccionario.tabs.SEGPA+"txt_fechapago").val();
        
        if(!$.validator.prototype.dateValid(fech)){
            simpleScript.notify.warning({
                content: 'Fecha es incorrecta'
            });
            return false;
        }
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + "postPagarOrden",
            data: "&_fecha="+fech+"&_idCompromiso="+idCompromiso,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            //simpleScript.closeModal('#'+diccionario.tabs.SEGPA+'formPagarOrden');
                        }
                    });
                }
            }
        });
    };
    
    return this.publico;
    
};
var seguimientoPago = new seguimientoPago_();