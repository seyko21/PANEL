/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 21-11-2014 23:11:45 
* Descripcion : panelDisponible.js
* ---------------------------------------
*/
var panelDisponible_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idPanelDisponible = 0;
    
    _private.config = {
        modulo: "Panel/panelDisponible/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : PanelDisponible*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.PANED,
            label: $(element).attr("title"),
            fnCallback: function(){
                panelDisponible.getContenido();
            }
        });
    };
    
    /*contenido de tab: PanelDisponible*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.PANED+"_CONTAINER").html(data);
                panelDisponible.getGridPanelDisponible();
            }
        });
    };
    
    this.publico.getGridPanelDisponible = function (){
        var oTable = $("#"+diccionario.tabs.PANED+"gridPanelDisponible").dataTable({
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
            sAjaxSource: _private.config.modulo+"getGridPanelDisponible",
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.PANED+"gridPanelDisponible_filter").find("input").attr("placeholder","Buscar por PanelDisponible").css("width","250px");
                simpleScript.enterSearch("#"+diccionario.tabs.PANED+"gridPanelDisponible",oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.PANED,
                    typeElement: "button"
                });
            }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getFormNewPanelDisponible = function(btn){
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormNewPanelDisponible",
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.PANED+"formNewPanelDisponible").modal("show");
            }
        });
    };
    
    this.publico.getFormEditPanelDisponible = function(btn,id){
        _private.idPanelDisponible = id;
            
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormEditPanelDisponible",
            data: "&_idPanelDisponible="+_private.idPanelDisponible,
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.PANED+"formEditPanelDisponible").modal("show");
            }
        });
    };
    
    this.publico.postNewPanelDisponible = function(){
        simpleAjax.send({
            element: "#"+diccionario.tabs.PANED+"btnGrPanelDisponible",
            root: _private.config.modulo + "postNewPanelDisponible",
            form: "#"+diccionario.tabs.PANED+"formNewPanelDisponible",
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            simpleScript.reloadGrid("#"+diccionario.tabs.PANED+"gridPanelDisponible");
                        }
                    });
                }else if(!isNaN(data.result) && parseInt(data.result) === 2){
                    simpleScript.notify.error({
                        content: "PanelDisponible ya existe."
                    });
                }
            }
        });
    };
    
    this.publico.postEditPanelDisponible = function(){
        simpleAjax.send({
            element: "#"+diccionario.tabs.PANED+"btnEdPanelDisponible",
            root: _private.config.modulo + "postEditPanelDisponible",
            form: "#"+diccionario.tabs.PANED+"formEditPanelDisponible",
            data: "&_idPanelDisponible="+_private.idPanelDisponible,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_10,
                        callback: function(){
                            _private.idPanelDisponible = 0;
                            simpleScript.closeModal("#"+diccionario.tabs.PANED+"formEditPanelDisponible");
                            simpleScript.reloadGrid("#"+diccionario.tabs.PANED+"gridPanelDisponible");
                        }
                    });
                }else if(!isNaN(data.result) && parseInt(data.result) === 2){
                    simpleScript.notify.error({
                        content: "PanelDisponible ya existe."
                    });
                }
            }
        });
    };
    
    this.publico.postDeletePanelDisponible = function(btn,id){
        simpleScript.notify.confirm({
            content: mensajes.MSG_5,
            callbackSI: function(){
                simpleAjax.send({                    
                    element: btn,
                    gifProcess: true,
                    data: "&_idPanelDisponible="+id,
                    root: _private.config.modulo + "postDeletePanelDisponible",
                    fnCallback: function(data) {
                        if(!isNaN(data.result) && parseInt(data.result) === 1){
                            simpleScript.notify.ok({
                                content: mensajes.MSG_6,
                                callback: function(){
                                    simpleScript.reloadGrid("#"+diccionario.tabs.PANED+"gridPanelDisponible");
                                }
                            });
                        }
                    }
                });
            }
        });
    };
    
    this.publico.postDeletePanelDisponibleAll = function(btn){
        simpleScript.validaCheckBox({
            id: "#"+diccionario.tabs.PANED+"gridPanelDisponible",
            msn: mensajes.MSG_9,
            fnCallback: function(){
                simpleScript.notify.confirm({
                    content: mensajes.MSG_7,
                    callbackSI: function(){
                        simpleAjax.send({
                            flag: 3, //si se usa SP usar flag, sino se puede eliminar esta linea
                            element: btn,
                            form: "#"+diccionario.tabs.PANED+"formGridPanelDisponible",
                            root: _private.config.modulo + "postDeletePanelDisponibleAll",
                            fnCallback: function(data) {
                                if(!isNaN(data.result) && parseInt(data.result) === 1){
                                    simpleScript.notify.ok({
                                        content: mensajes.MSG_8,
                                        callback: function(){
                                            panelDisponible.getGridPanelDisponible();
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
var panelDisponible = new panelDisponible_();