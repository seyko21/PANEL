/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 22-09-2014 16:09:42 
* Descripcion : cotizacionVendedor.js
* ---------------------------------------
*/
var cotizacionVendedor_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idCotizacionVendedor = 0;
    
    _private.config = {
        modulo: "Cotizacion/cotizacionVendedor/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : CotizacionVendedor*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.COXVE,
            label: $(element).attr("title"),
            fnCallback: function(){
                cotizacionVendedor.getContenido();
            }
        });
    };
    
    /*contenido de tab: CotizacionVendedor*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.COXVE+"_CONTAINER").html(data);
                cotizacionVendedor.getGridCotizacionVendedor();
            }
        });
    };
    
    this.publico.getGridCotizacionVendedor = function (){
        var oTable = $("#"+diccionario.tabs.COXVE+"gridCotizacionVendedor").dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "Código", sClass: "center",sWidth: "15%"},
                {sTitle: "DNI", sWidth: "10%"},   
                {sTitle: "Vendedor", sWidth: "30%"},   
                {sTitle: "Fecha", sWidth: "10%", sClass: "center"},
                {sTitle: "Meses", sWidth: "10%", sClass: "center"},
                {sTitle: "Total", sWidth: "10%", sClass: "right"},
                {sTitle: "Estado", sWidth: "15%", sClass: "center"},
                {sTitle: "Acciones", sWidth: "10%", sClass: "center", bSortable: false}          
            ],
            aaSorting: [[0, "desc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridCotizacionVendedor",
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.COXVE+"gridCotizacionVendedor_filter").find("input").attr("placeholder","Buscar por Vendedor, DNI o Nro Cotizacion").css("width","350px");
                simpleScript.enterSearch("#"+diccionario.tabs.COXVE+"gridCotizacionVendedor",oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.COXVE,
                    typeElement: "button"
                });
            }
        });
        setup_widgets_desktop();
    };
    

    
    return this.publico;
    
};
var cotizacionVendedor = new cotizacionVendedor_();