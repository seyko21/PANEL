/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 09-09-2014 06:09:13 
* Descripcion : generarOrden.js
* ---------------------------------------
*/
var generarOrden_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idGenerarOrden = 0;
    
    _private.config = {
        modulo: "ordenservicio/generarOrden/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : GenerarOrden*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.GNOSE,
            label: $(element).attr("title"),
            fnCallback: function(){
                generarOrden.getContenido();
            }
        });
    };
    
    /*contenido de tab: GenerarOrden*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.GNOSE+"_CONTAINER").html(data);
                generarOrden.getGridGenerarOrden();
            }
        });
    };
    
    this.publico.getGridGenerarOrden = function (){
        var oTable = $("#"+diccionario.tabs.GNOSE+"gridGenerarOrden").dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "N°", sWidth: "1%",bSortable: false},
                {sTitle: "Código", sWidth: "10%"},
                {sTitle: "Representante", sWidth: "25%"},
                {sTitle: "Cliente", sWidth: "25%"},
                {sTitle: "Estado", sWidth: "10%", sClass: "center", bSortable: false},
                {sTitle: "Acciones", sWidth: "8%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[2, "asc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridGenerarOrden",
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.GNOSE+"gridGenerarOrden_filter").find("input").attr("placeholder","Buscar por GenerarOrden").css("width","250px");
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.GNOSE,
                    typeElement: "button"
                });
            }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getFormNewGenerarOrden = function(btn){
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormNewGenerarOrden",
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.GNOSE+"formNewGenerarOrden").modal("show");
            }
        });
    };
    
    this.publico.postNewGenerarOrden = function(){
        simpleAjax.send({
            flag: 1,
            element: "#"+diccionario.tabs.GNOSE+"btnGrGenerarOrden",
            root: _private.config.modulo + "postNewGenerarOrden",
            form: "#"+diccionario.tabs.GNOSE+"formNewGenerarOrden",
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            simpleScript.reloadGrid("#"+diccionario.tabs.GNOSE+"gridGenerarOrden");
                        }
                    });
                }else if(!isNaN(data.result) && parseInt(data.result) === 2){
                    simpleScript.notify.error({
                        content: "GenerarOrden ya existe."
                    });
                }
            }
        });
    };
    
    this.publico.postDeleteGenerarOrdenAll = function(btn){
        simpleScript.validaCheckBox({
            id: "#"+diccionario.tabs.GNOSE+"gridGenerarOrden",
            msn: mensajes.MSG_9,
            fnCallback: function(){
                simpleScript.notify.confirm({
                    content: mensajes.MSG_7,
                    callbackSI: function(){
                        simpleAjax.send({
                            flag: 3, //si se usa SP usar flag, sino se puede eliminar esta linea
                            element: btn,
                            form: "#"+diccionario.tabs.GNOSE+"formGridGenerarOrden",
                            root: _private.config.modulo + "postDeleteGenerarOrdenAll",
                            fnCallback: function(data) {
                                if(!isNaN(data.result) && parseInt(data.result) === 1){
                                    simpleScript.notify.ok({
                                        content: mensajes.MSG_8,
                                        callback: function(){
                                            generarOrden.getGridGenerarOrden();
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
var generarOrden = new generarOrden_();