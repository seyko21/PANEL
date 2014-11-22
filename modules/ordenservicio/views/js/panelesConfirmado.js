/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 27-09-2014 18:09:14 
* Descripcion : panelesConfirmado.js
* ---------------------------------------
*/
var panelesConfirmado_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idPanelesConfirmado = 0;
    
    _private.config = {
        modulo: "ordenservicio/panelesConfirmado/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : PanelesConfirmado*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.PANCO,
            label: $(element).attr("title"),
            fnCallback: function(){
                panelesConfirmado.getContenido();
            }
        });
    };
    
    /*contenido de tab: PanelesConfirmado*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.PANCO+"_CONTAINER").html(data);
                panelesConfirmado.getGridPanelesConfirmado();
            }
        });
    };
    
    this.publico.getGridPanelesConfirmado = function (){
        var _f1 = $("#"+diccionario.tabs.PANCO+"txt_f1").val();
        var _f2 = $("#"+diccionario.tabs.PANCO+"txt_f2").val();               
       
        var oTable = $("#"+diccionario.tabs.PANCO+"gridPanelesConfirmado").dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "N° OS", sWidth: "5%"},
                {sTitle: "Vendedor", sWidth: "25%", sClass: "left" },
                {sTitle: "Codigo", sWidth: "8%"},
                {sTitle: "Ubicacion", sWidth: "20%" },
                {sTitle: "Fecha Instalacion", sWidth: "10%", sClass: "center" },                
                {sTitle: "Confirmacion", sWidth: "15%", sClass: "center" },
                {sTitle: "Imagen", sWidth: "10%", sClass: "center" }                
            ],
            aaSorting: [[0, "asc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridPanelesConfirmado",
            fnServerParams: function(aoData) {
                aoData.push({"name": "_f1", "value": _f1});                
                aoData.push({"name": "_f2", "value": _f2}); 
            },            
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.PANCO+"gridPanelesConfirmado_filter").find("input").attr("placeholder","Buscar por N° OS o codigo o Vendedor").css("width","350px");
                simpleScript.enterSearch("#"+diccionario.tabs.PANCO+'gridPanelesConfirmado',oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.PANCO,
                    typeElement: "button"
                });
                $('#'+diccionario.tabs.PANCO+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.PANCO+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();
    };       
    
    return this.publico;
    
};
var panelesConfirmado = new panelesConfirmado_();