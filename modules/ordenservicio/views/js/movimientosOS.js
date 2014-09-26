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
        
        var _f1 = $("#"+diccionario.tabs.MOVOS+"txt_f1").val();
        var _f2 = $("#"+diccionario.tabs.MOVOS+"txt_f2").val();        
        var f1, f2;
        f1 = $.datepicker.parseDate('dd/mm/yy', _f1);
        f2 = $.datepicker.parseDate('dd/mm/yy', _f2);        
        if( f1 > f2 ){
           simpleScript.notify.warning({
                  content: 'La fecha inicio no puede ser mayor que la fecha final.'      
            });           
       }
       
        var oTable = $("#"+diccionario.tabs.MOVOS+"gridMovimientosOS").dataTable({
            bFilter: false,
            sSearch: false,
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "N° OS", sWidth: "8%",},                
                {sTitle: "Fecha ", sWidth: "5%",  sClass: "center"},
                {sTitle: "Total OS", sWidth: "9%",sClass: "right"},
                {sTitle: "Impuesto", sWidth: "9%", sClass: "right"}, 
                {sTitle: "Ingresos", sWidth: "9%",sClass: "right"},
                {sTitle: "Egresos", sWidth: "9%", sClass: "right"},                
                {sTitle: "Comision", sWidth: "9%", sClass: "right"}, 
                {sTitle: "Utilidad 1", sWidth: "9%", sClass: "right"},    
                {sTitle: "Otros Ing.", sWidth: "9%",sClass: "right"},
                {sTitle: "Otros Egr.", sWidth: "9%", sClass: "right"}, 
                {sTitle: "Utilidad 2", sWidth: "9%", sClass: "right"}, 
                {sTitle: "Acciones", sWidth: "6%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[0, "desc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridMovimientosOS",
             fnServerParams: function(aoData) {
                aoData.push({"name": "_f1", "value": _f1});                
                aoData.push({"name": "_f2", "value": _f2}); 
            },            
            fnDrawCallback: function() {
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