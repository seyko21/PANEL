/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 27-09-2014 07:09:56 
* Descripcion : liquidacionCliente.js
* ---------------------------------------
*/
var liquidacionCliente_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idLiquidacionCliente = 0;
    
    _private.config = {
        modulo: "pagos/liquidacionCliente/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : LiquidacionCliente*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.LICL,
            label: $(element).attr("title"),
            fnCallback: function(){
                liquidacionCliente.getContenido();
            }
        });
    };
    
    /*contenido de tab: LiquidacionCliente*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.LICL+"_CONTAINER").html(data);
                liquidacionCliente.getGridLiquidacionCliente();
            }
        });
    };
    
    this.publico.getGridLiquidacionCliente = function (){
        
        var _f1 = $("#"+diccionario.tabs.LICL+"txt_f1").val();
        var _f2 = $("#"+diccionario.tabs.LICL+"txt_f2").val();        
        var f1, f2;
        f1 = $.datepicker.parseDate('dd/mm/yy', _f1);
        f2 = $.datepicker.parseDate('dd/mm/yy', _f2);        
        if( f1 > f2 ){
           simpleScript.notify.warning({
                  content: 'La fecha inicio no puede ser mayor que la fecha final.'      
            });           
       }
        
        var oTable = $("#"+diccionario.tabs.LICL+"gridLiquidacionCliente").dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "N° OS", sWidth: "10%",},                
                {sTitle: "Fecha", sWidth: "10%",  sClass: "center"},
                {sTitle: "Cliente", sWidth: "40%"},
                {sTitle: "Total", sWidth: "15%",sClass: "right"},
                {sTitle: "Estado", sWidth: "10%", sClass: "center"},                
                {sTitle: "Acciones", sWidth: "8%", sClass: "center", bSortable: false}           
            ],
            aaSorting: [[0, "asc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridLiquidacionCliente",
            fnServerParams: function(aoData) {
                aoData.push({"name": "_f1", "value": _f1});                
                aoData.push({"name": "_f2", "value": _f2}); 
            },
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.LICL+"gridLiquidacionCliente_filter").find("input").attr("placeholder","Buscar por Cliente").css("width","200px");
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.LICL,
                    typeElement: "button"
                });
            }
        });
        setup_widgets_desktop();
    };
    
    
  
    return this.publico;
    
};
var liquidacionCliente = new liquidacionCliente_();