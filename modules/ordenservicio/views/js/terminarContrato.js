/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 20-09-2014 20:09:46 
* Descripcion : terminarContrato.js
* ---------------------------------------
*/
var terminarContrato_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idOrden = 0;
    
    _private.config = {
        modulo: "ordenservicio/terminarContrato/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : TerminarContrato*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.TERCO,
            label: $(element).attr("title"),
            fnCallback: function(){
                terminarContrato.getContenido();
            }
        });
    };
    
    /*contenido de tab: TerminarContrato*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.TERCO+"_CONTAINER").html(data);
                terminarContrato.getGridTerminarContrato();
            }
        });
    };
    
    this.publico.getGridTerminarContrato = function (){
        var oTable = $("#"+diccionario.tabs.TERCO+"gridTerminarContrato").dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "Nro. OS", sWidth: "10%",sClass: "center"},
                {sTitle: "Nro. Cotización", sWidth: "10%",sClass: "center"},
                {sTitle: "Representante", sWidth: "20%"},
                {sTitle: "Descuento", sWidth: "10%", sClass: "right"},
                {sTitle: "Fecha", sWidth: "10%"},
                {sTitle: "Monto", sWidth: "8%", sClass: "right", bSortable: false},
                {sTitle: "Estado", sWidth: "8%", sClass: "center", bSortable: false},
                {sTitle: "Acciones", sWidth: "15%", sClass: "center", bSortable: false}              
            ],
            aaSorting: [[2, "asc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridTerminarContrato",
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.TERCO+"gridTerminarContrato_filter").find("input").attr("placeholder","Buscar por OS, cotización o representante").css("width","290px");
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.TERCO,
                    typeElement: "button"
                });
            }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getFormTerminarContrato = function(btn,id){
        _private.idOrden = id;
        
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormTerminarContrato",
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.TERCO+"formTerminarContrato").modal("show");
            }
        });
    };
    
    this.publico.postTerminarContrato = function(){
        simpleAjax.send({
            element: "#"+diccionario.tabs.TERCO+"btnGrTerminarContrato",
            root: _private.config.modulo + "postTerminarContrato",
            form: "#"+diccionario.tabs.TERCO+"formTerminarContrato",
            data: '&_idOrden='+_private.idOrden,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            simpleScript.reloadGrid("#"+diccionario.tabs.TERCO+"gridTerminarContrato");
                            simpleScript.closeModal('#'+diccionario.tabs.T100+'formTerminarContrato');
                            _private.idOrden = 0;
                        }
                    });
                }
            }
        });
    };
   
    return this.publico;
    
};
var terminarContrato = new terminarContrato_();