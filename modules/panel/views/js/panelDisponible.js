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
        modulo: "panel/panelDisponible/"
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
                {sTitle: "Ubicación", sWidth: "35%"},
                {sTitle: "Area", sWidth: "8%"},
                {sTitle: "Ciudad", sWidth: "15%"},
                {sTitle: "Elemento", sWidth: "15%"},
                {sTitle: "Códigos", sWidth: "10%"}
            ],
            aaSorting: [[2, "asc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridPanelDisponible",
            fnServerParams: function(aoData) {
                aoData.push({"name": "_ciudad", "value": $("#"+diccionario.tabs.PANED+"lst_ciudad").val()});
            },
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.PANED+"gridPanelDisponible_filter").find("input").attr("placeholder","Buscar por Ubicación").css("width","250px");
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
       
    
    return this.publico;
    
};
var panelDisponible = new panelDisponible_();