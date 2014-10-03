/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 02-10-2014 23:10:16 
* Descripcion : retornoInversion.js
* ---------------------------------------
*/
var retornoInversion_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idRetornoInversion = 0;
    
    _private.config = {
        modulo: "Produccion/retornoInversion/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : RetornoInversion*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.REINV,
            label: $(element).attr("title"),
            fnCallback: function(){
                retornoInversion.getContenido();
            }
        });
    };
    
    /*contenido de tab: RetornoInversion*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.REINV+"_CONTAINER").html(data);
                retornoInversion.getGridRetornoInversion();
            }
        });
    };
    
    this.publico.getGridRetornoInversion = function (){
        var oTable = $("#"+diccionario.tabs.REINV+"gridRetornoInversion").dataTable({
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
            sAjaxSource: _private.config.modulo+"getGridRetornoInversion",
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.REINV+"gridRetornoInversion_filter").find("input").attr("placeholder","Buscar por RetornoInversion").css("width","250px");
                simpleScript.enterSearch("#"+diccionario.tabs.REINV+"gridRetornoInversion",oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.REINV,
                    typeElement: "button"
                });
            }
        });
        setup_widgets_desktop();
    };
    
 
    
    return this.publico;
    
};
var retornoInversion = new retornoInversion_();