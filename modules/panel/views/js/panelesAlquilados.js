/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 10-09-2014 14:09:04 
* Descripcion : panelesAlquilados.js
* ---------------------------------------
*/
var panelesAlquilados_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idPanelesAlquilados = 0;
    
    _private.config = {
        modulo: "panel/panelesAlquilados/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : PanelesAlquilados*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.PAAL,
            label: $(element).attr("title"),
            fnCallback: function(){
                panelesAlquilados.getContenido();
            }
        });
    };
    
    /*contenido de tab: PanelesAlquilados*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.PAAL+"_CONTAINER").html(data);
                panelesAlquilados.getGridPanelesAlquilados();
            }
        });
    };
    
    this.publico.getGridPanelesAlquilados = function (){
         $('#'+diccionario.tabs.PAAL+'gridPanelesAlquilados').dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [                
                {sTitle: "Código", sWidth: "8%"},
                {sTitle: "Ciudad", sWidth: "13%"},
                {sTitle: "Ubicación", sWidth: "35%"},
                {sTitle: "Elemento", sWidth: "10%"},
                {sTitle: "Area m2", sWidth: "4%",  sClass: "center"},
                {sTitle: "Precio", sWidth: "8%",  sClass: "right"},                
                {sTitle: "Iluminado", sWidth: "5%",  sClass: "center"},
                {sTitle: "Estado", sWidth: "5%",  sClass: "center"},
                {sTitle: "Acciones", sWidth: "10%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[2, 'asc']],
            sScrollY: "350px",
            sAjaxSource: _private.config.modulo+'getGridProducto',
            fnServerParams: function(aoData) {
                aoData.push({"name": "_tipoPanel", "value": $("#"+diccionario.tabs.PAAL+"lst_tipopanelsearch").val()});
            },
            fnDrawCallback: function() {
                $('#'+diccionario.tabs.PAAL+'gridPanelesAlquilados_filter').find('input').attr('placeholder','Buscar por Ciudad o Ubicación').css('width','350px');;                
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: '#widget_'+diccionario.tabs.PAAL, //widget del datagrid
                    typeElement: 'button'
                });
            }
        });
        setup_widgets_desktop();
    };
    
  
    
    return this.publico;
    
};
var panelesAlquilados = new panelesAlquilados_();