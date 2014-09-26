/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 25-09-2014 23:09:26 
* Descripcion : alquilerCulminar.js
* ---------------------------------------
*/
var alquilerCulminar_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idAlquilerCulminar = 0;
    
    _private.config = {
        modulo: "ordenservicio/alquilerCulminar/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : AlquilerCulminar*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.CALCU,
            label: $(element).attr("title"),
            fnCallback: function(){
                alquilerCulminar.getContenido();
            }
        });
    };
    
    /*contenido de tab: AlquilerCulminar*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.CALCU+"_CONTAINER").html(data);
                alquilerCulminar.getGridAlquilerCulminar();
            }
        });
    };
    
    this.publico.getGridAlquilerCulminar = function (){
        var oTable = $("#"+diccionario.tabs.CALCU+"gridAlquilerCulminar").dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "Caratula", sWidth: "5%", sClass: "center"},
                {sTitle: "N° OS", sWidth: "8%", sClass: "center"},
                {sTitle: "Cliente", sWidth: "35%"},
                {sTitle: "F. Inicio", sWidth: "10%"},
                {sTitle: "F. Final", sWidth: "10%" },
                {sTitle: "Tiempo", sWidth: "15%" },
                {sTitle: "Importe", sWidth: "15%", sClass: "right" }                
            ],
            aaSorting: [[2, "desc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridAlquilerCulminar",
            fnServerParams: function(aoData) {
                aoData.push({"name": "_tipo", "value": $("#"+diccionario.tabs.CALCU+"lst_tiposearch").val()});                
            },     
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.CALCU+"gridAlquilerCulminar_filter").find("input").attr("placeholder","Buscar por N° OS o Caratula o Cliente").css("width","350px");
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.CALCU,
                    typeElement: "button"
                });
            }
        });
        setup_widgets_desktop();
    };
      
    
    return this.publico;
    
};
var alquilerCulminar = new alquilerCulminar_();