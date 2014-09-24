/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 24-09-2014 00:09:39 
* Descripcion : compromisoPagar.js
* ---------------------------------------
*/
var compromisoPagar_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idCompromisoPagar = 0;
    
    _private.config = {
        modulo: "ordenservicio/compromisoPagar/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : CompromisoPagar*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.COPAG,
            label: $(element).attr("title"),
            fnCallback: function(){
                compromisoPagar.getContenido();
            }
        });
    };
    
    /*contenido de tab: CompromisoPagar*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.COPAG+"_CONTAINER").html(data);
                compromisoPagar.getGridCompromisoPagar();
            }
        });
    };
    
    this.publico.getGridCompromisoPagar = function (){
        var _f1 = $("#"+diccionario.tabs.COPAG+"txt_f1").val();
        var _f2 = $("#"+diccionario.tabs.COPAG+"txt_f2").val();        
        var f1, f2;
        f1 = $.datepicker.parseDate('dd/mm/yy', _f1);
        f2 = $.datepicker.parseDate('dd/mm/yy', _f2);        
        if( f1 > f2 ){
           simpleScript.notify.warning({
                  content: 'La fecha inicio no puede ser mayor que la fecha final.'      
            });           
       }
        
        var oTable = $("#"+diccionario.tabs.COPAG+"gridCompromisoPagar").dataTable({
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
                {sTitle: "N° OS", sWidth: "10%",},                
                {sTitle: "Cuota", sWidth: "5%",  sClass: "center"},
                {sTitle: "Fecha", sWidth: "10%",  sClass: "center"},
                {sTitle: "Cliente", sWidth: "30%"},
                {sTitle: "Mora", sWidth: "15%",sClass: "right"},
                {sTitle: "Total", sWidth: "15%",sClass: "right"},
                {sTitle: "Estado", sWidth: "10%", sClass: "center"},                
                {sTitle: "Acciones", sWidth: "8%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[0, "desc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridCompromisoPagar",
             fnServerParams: function(aoData) {
                aoData.push({"name": "_f1", "value": _f1});                
                aoData.push({"name": "_f2", "value": _f2}); 
                aoData.push({"name": "_estadocb", "value": $("#"+diccionario.tabs.COPAG+"lst_estadosearch").val()}); 
            },            
            fnDrawCallback: function() {
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.COPAG,
                    typeElement: "button"
                });
            }
        });
        setup_widgets_desktop();
    };
    
  
    return this.publico;
    
};
var compromisoPagar = new compromisoPagar_();