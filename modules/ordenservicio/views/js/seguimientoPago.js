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
                {sTitle: "Acciones", sWidth: "8%", sClass: "center", bSortable: false},
                {sTitle: "CAMPO 1", sWidth: "25%"},
                {sTitle: "CAMPO 2", sWidth: "25%", bSortable: false},
                {sTitle: "Estado", sWidth: "10%", sClass: "center", bSortable: false}                
            ],
            aaSorting: [[2, "asc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridSeguimientoPago",
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.SEGPA+"gridSeguimientoPago_filter").find("input").attr("placeholder","Buscar por SeguimientoPago").css("width","250px");
                simpleScript.enterSearch("#"+diccionario.tabs.SEGPA+"gridSeguimientoPago",oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.SEGPA,
                    typeElement: "button"
                });
            }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getFormNewSeguimientoPago = function(btn){
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormNewSeguimientoPago",
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.SEGPA+"formNewSeguimientoPago").modal("show");
            }
        });
    };
    
    this.publico.getFormEditSeguimientoPago = function(btn,id){
        _private.idSeguimientoPago = id;
            
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormEditSeguimientoPago",
            data: "&_idSeguimientoPago="+_private.idSeguimientoPago,
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.SEGPA+"formEditSeguimientoPago").modal("show");
            }
        });
    };
    
    this.publico.postNewSeguimientoPago = function(){
        simpleAjax.send({
            flag: 1,
            element: "#"+diccionario.tabs.SEGPA+"btnGrSeguimientoPago",
            root: _private.config.modulo + "postNewSeguimientoPago",
            form: "#"+diccionario.tabs.SEGPA+"formNewSeguimientoPago",
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            simpleScript.reloadGrid("#"+diccionario.tabs.SEGPA+"gridSeguimientoPago");
                        }
                    });
                }else if(!isNaN(data.result) && parseInt(data.result) === 2){
                    simpleScript.notify.error({
                        content: "SeguimientoPago ya existe."
                    });
                }
            }
        });
    };
    
    this.publico.postEditSeguimientoPago = function(){
        simpleAjax.send({
            flag: 2,
            element: "#"+diccionario.tabs.SEGPA+"btnEdSeguimientoPago",
            root: _private.config.modulo + "postEditSeguimientoPago",
            form: "#"+diccionario.tabs.SEGPA+"formEditSeguimientoPago",
            data: "&_idSeguimientoPago="+_private.idSeguimientoPago,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_10,
                        callback: function(){
                            _private.idSeguimientoPago = 0;
                            simpleScript.closeModal("#"+diccionario.tabs.SEGPA+"formEditSeguimientoPago");
                            simpleScript.reloadGrid("#"+diccionario.tabs.SEGPA+"gridSeguimientoPago");
                        }
                    });
                }else if(!isNaN(data.result) && parseInt(data.result) === 2){
                    simpleScript.notify.error({
                        content: "SeguimientoPago ya existe."
                    });
                }
            }
        });
    };
    
    this.publico.postDeleteSeguimientoPago = function(btn,id){
        simpleScript.notify.confirm({
            content: mensajes.MSG_5,
            callbackSI: function(){
                simpleAjax.send({
                    flag: 3,
                    element: btn,
                    gifProcess: true,
                    data: "&_idSeguimientoPago="+id,
                    root: _private.config.modulo + "postDeleteSeguimientoPago",
                    fnCallback: function(data) {
                        if(!isNaN(data.result) && parseInt(data.result) === 1){
                            simpleScript.notify.ok({
                                content: mensajes.MSG_6,
                                callback: function(){
                                    simpleScript.reloadGridDelete("#"+diccionario.tabs.SEGPA+"gridSeguimientoPago");
                                }
                            });
                        }
                    }
                });
            }
        });
    };
    
    this.publico.postDeleteSeguimientoPagoAll = function(btn){
        simpleScript.validaCheckBox({
            id: "#"+diccionario.tabs.SEGPA+"gridSeguimientoPago",
            msn: mensajes.MSG_9,
            fnCallback: function(){
                simpleScript.notify.confirm({
                    content: mensajes.MSG_7,
                    callbackSI: function(){
                        simpleAjax.send({
                            flag: 3, //si se usa SP usar flag, sino se puede eliminar esta linea
                            element: btn,
                            form: "#"+diccionario.tabs.SEGPA+"formGridSeguimientoPago",
                            root: _private.config.modulo + "postDeleteSeguimientoPagoAll",
                            fnCallback: function(data) {
                                if(!isNaN(data.result) && parseInt(data.result) === 1){
                                    simpleScript.notify.ok({
                                        content: mensajes.MSG_8,
                                        callback: function(){
                                            seguimientoPago.getGridSeguimientoPago();
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
var seguimientoPago = new seguimientoPago_();