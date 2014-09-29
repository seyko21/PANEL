/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 29-09-2014 17:09:58 
* Descripcion : cronogramaCliente.js
* ---------------------------------------
*/
var cronogramaCliente_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idCronogramaCliente = 0;
    
    _private.config = {
        modulo: "pagos/cronogramaCliente/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : CronogramaCliente*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.CRPG,
            label: $(element).attr("title"),
            fnCallback: function(){
                cronogramaCliente.getContenido();
            }
        });
    };
    
    /*contenido de tab: CronogramaCliente*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.CRPG+"_CONTAINER").html(data);
                cronogramaCliente.getGridCronogramaCliente();
            }
        });
    };
    
    this.publico.getGridCronogramaCliente = function (){
        var oTable = $("#"+diccionario.tabs.CRPG+"gridCronogramaCliente").dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "N° OS", sWidth: "8%"},
                {sTitle: "Cuota", sWidth: "8%", sClass: "center"},
                {sTitle: "Fecha Programada", sWidth: "9%", sClass: "center"},
                {sTitle: "Cliente", sWidth: "25%"},
                {sTitle: "Mora", sWidth: "7%", sClass: "right"},
                {sTitle: "Monto", sWidth: "10%", sClass: "right"},
                {sTitle: "Reprogramado", sWidth: "9%", sClass: "center"},
                {sTitle: "Estado", sWidth: "8%", sClass: "center"}
            ],
            aaSorting: [[2, "asc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridCronogramaCliente",
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.CRPG+"gridCronogramaCliente_filter").find("input").attr("placeholder","Buscar por N° OS").css("width","350px");
                simpleScript.enterSearch("#"+diccionario.tabs.CRPG+"gridCronogramaCliente",oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.CRPG,
                    typeElement: "button"
                });
            }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getFormNewCronogramaCliente = function(btn){
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormNewCronogramaCliente",
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.CRPG+"formNewCronogramaCliente").modal("show");
            }
        });
    };
    
    this.publico.getFormEditCronogramaCliente = function(btn,id){
        _private.idCronogramaCliente = id;
            
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormEditCronogramaCliente",
            data: "&_idCronogramaCliente="+_private.idCronogramaCliente,
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.CRPG+"formEditCronogramaCliente").modal("show");
            }
        });
    };
    
 
    return this.publico;
    
};
var cronogramaCliente = new cronogramaCliente_();