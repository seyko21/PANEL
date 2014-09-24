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
                {sTitle: "Fecha", sWidth: "10%",  sClass: "center"},
                {sTitle: "Cliente", sWidth: "40%"},
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