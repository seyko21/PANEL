/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 20-11-2014 00:11:17 
* Descripcion : reporteVentaFecha.js
* ---------------------------------------
*/
var reporteVentaFecha_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idReporteVentaFecha = 0;
    
    _private.config = {
        modulo: "ventas/reporteVentaFecha/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : ReporteVentaFecha*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.VRPT2,
            label: $(element).attr("title"),
            fnCallback: function(){
                reporteVentaFecha.getContenido();
            }
        });
    };
    
    /*contenido de tab: ReporteVentaFecha*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.VRPT2+"_CONTAINER").html(data);
                reporteVentaFecha.getGridReporteVentaFecha();
            }
        });
    };
    
    this.publico.getGridReporteVentaFecha = function (){
        var _f1 = $("#"+diccionario.tabs.VRPT2+"txt_f1").val();
        var _f2 = $("#"+diccionario.tabs.VRPT2+"txt_f2").val();  
        
        var oTable = $("#"+diccionario.tabs.VRPT2+"gridReporteVentaFecha").dataTable({
            bFilter: false, 
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "N°", sWidth: "1%",bSortable: false},              
                {sTitle: "Fecha", sWidth: "15%", sClass: "center"},
                {sTitle: "Numero Documentos", sWidth: "15%", sClass: "center"},
                {sTitle: "Moneda", sWidth: "20%"},
                {sTitle: "Total Ingresado", sWidth: "15%", sClass: "right"},
                {sTitle: "Acciones", sWidth: "8%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[1, "asc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridReporteVentaFecha",
             fnServerParams: function(aoData) {
                aoData.push({"name": "_f1", "value": _f1});                
                aoData.push({"name": "_f2", "value": _f2});
            },
            fnDrawCallback: function() {           
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.VRPT2,
                    typeElement: "button"
                });
                $('#'+diccionario.tabs.VRPT2+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.VRPT2+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getFormConsultaVenta = function(btn){
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormConsultaVenta",
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.VRPT2+"formConsultaVenta").modal("show");
            }
        });
    };
    
   
    
    return this.publico;
    
};
var reporteVentaFecha = new reporteVentaFecha_();