/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 29-09-2014 22:09:59 
* Descripcion : panelcliente.js
* ---------------------------------------
*/
var panelcliente_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idCaratula = 0;
    
    _private.config = {
        modulo: "Panel/panelcliente/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : Panelcliente*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.MIPAL,
            label: $(element).attr("title"),
            fnCallback: function(){
                panelcliente.getContenido();
            }
        });
    };
    
    /*contenido de tab: Panelcliente*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.MIPAL+"_CONTAINER").html(data);
                panelcliente.getGridPanelcliente();
            }
        });
    };
    
    this.publico.getGridPanelcliente = function (){
        var oTable = $("#"+diccionario.tabs.MIPAL+"gridPanelcliente").dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "Código", sWidth: "8%"},                
                {sTitle: "N° OS", sWidth: "8%"},
                {sTitle: "Ubicación", sWidth: "32%"},
                {sTitle: "Elemento", sWidth: "12%"},                
                {sTitle: "Iluminado", sWidth: "4%",  sClass: "center"},                                
                {sTitle: "Inicio", sWidth: "8%", sClass: "center"},
                {sTitle: "Termino", sWidth: "8%", sClass: "center"},
                {sTitle: "Acciones", sWidth: "8%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[0, "asc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridPanelcliente",
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.MIPAL+"gridPanelcliente_filter").find("input").attr("placeholder","Buscar por Ubicacion").css("width","350px");
                simpleScript.enterSearch("#"+diccionario.tabs.MIPAL+"gridPanelcliente",oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.MIPAL,
                    typeElement: "button"
                });
                $('#'+diccionario.tabs.MIPAL+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.MIPAL+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();
    };
    
    
    this.publico.getConsulta = function(btn, id, img){
        _private.idCaratula = id;               
        simpleAjax.send({
            element : btn,
            gifProcess: true,
            dataType: 'html',
            root: _private.config.modulo + 'getConsulta',
            data: '&_idCaratula='+_private.idCaratula+'&_imagen='+img,
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.MIPAL+'formConsultaPanelCliente').modal('show');                
                
            }
        });
    };   
    
    
  
    return this.publico;
    
};
var panelcliente = new panelcliente_();