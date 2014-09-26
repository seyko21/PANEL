/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 25-09-2014 23:09:15 
* Descripcion : movimientosOS.js
* ---------------------------------------
*/
var movimientosOS_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idMovimientosOS = 0;
    
    _private.config = {
        modulo: "ordenservicio/movimientosOS/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : MovimientosOS*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.MOVOS,
            label: $(element).attr("title"),
            fnCallback: function(){
                movimientosOS.getContenido();
            }
        });
    };
    
    /*contenido de tab: MovimientosOS*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.MOVOS+"_CONTAINER").html(data);
                movimientosOS.getGridMovimientosOS();
            }
        });
    };
    
    this.publico.getGridMovimientosOS = function (){
        var oTable = $("#"+diccionario.tabs.MOVOS+"gridMovimientosOS").dataTable({
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
            sAjaxSource: _private.config.modulo+"getGridMovimientosOS",
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.MOVOS+"gridMovimientosOS_filter").find("input").attr("placeholder","Buscar por MovimientosOS").css("width","250px");
                simpleScript.enterSearch("#"+diccionario.tabs.MOVOS+"gridMovimientosOS",oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.MOVOS,
                    typeElement: "button"
                });
            }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getFormNewMovimientosOS = function(btn){
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormNewMovimientosOS",
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.MOVOS+"formNewMovimientosOS").modal("show");
            }
        });
    };
    

    
    return this.publico;
    
};
var movimientosOS = new movimientosOS_();