/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 20-11-2014 17:11:31 
* Descripcion : reporteProductoMes.js
* ---------------------------------------
*/
var reporteProductoMes_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idReporteProductoMes = 0;
    
    _private.config = {
        modulo: "ventas/reporteProductoMes/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : ReporteProductoMes*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.VRPT4,
            label: $(element).attr("title"),
            fnCallback: function(){
                reporteProductoMes.getContenido();
            }
        });
    };
    
    /*contenido de tab: ReporteProductoMes*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.VRPT4+"_CONTAINER").html(data);
                reporteProductoMes.getGridReporteProductoMes();
            }
        });
    };
    
    this.publico.getGridReporteProductoMes = function (){
        var oTable = $("#"+diccionario.tabs.VRPT4+"gridReporteProductoMes").dataTable({
            bFilter: false, 
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "N°", sWidth: "2%",bSortable: false},
                {sTitle: "Fecha", sWidth: "15%",sClass: "center"},
                {sTitle: "Producto", sWidth: "35%"},                
                {sTitle: "N° Veces Vendido", sWidth: "15%",sClass: "right" },
                {sTitle: "Cantidad Promedio", sWidth: "15%",sClass: "right"},
                {sTitle: "Unidad Medida", sWidth: "10%",sClass: "center"}
            ],
            aaSorting: [[1, "asc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridReporteProductoMes",
            fnDrawCallback: function() {
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.VRPT4,
                    typeElement: "button"
                });
                $('#'+diccionario.tabs.VRPT4+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.VRPT4+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();
    };
    
   
    
    return this.publico;
    
};
var reporteProductoMes = new reporteProductoMes_();