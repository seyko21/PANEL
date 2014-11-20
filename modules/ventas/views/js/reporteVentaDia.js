/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 19-11-2014 22:11:59 
* Descripcion : reporteVentaDia.js
* ---------------------------------------
*/
var reporteVentaDia_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idReporteVentaDia = 0;
    
    _private.config = {
        modulo: "ventas/reporteVentaDia/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : ReporteVentaDia*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.VRPT1,
            label: $(element).attr("title"),
            fnCallback: function(){
                reporteVentaDia.getContenido();
            }
        });
    };
    
    /*contenido de tab: ReporteVentaDia*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.VRPT1+"_CONTAINER").html(data);
                //reporteVentaDia.getGridReporteVentaDia();
            }
        });
    };
    
    this.publico.getGraficoReporteVentaDia = function (){
        
    };
    
  
    
    return this.publico;
    
};
var reporteVentaDia = new reporteVentaDia_();