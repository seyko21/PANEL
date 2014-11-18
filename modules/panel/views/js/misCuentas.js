/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 09-09-2014 04:09:19 
* Descripcion : misCuentas.js
* ---------------------------------------
*/
var misCuentas_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idMisCuentas = 0;
    
    _private.config = {
        modulo: "panel/misCuentas/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : MisCuentas*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.MISPA,
            label: $(element).attr("title"),
            fnCallback: function(){
                misCuentas.getContenido();
            }
        });
    };
    
    /*contenido de tab: MisCuentas*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.MISPA+"_CONTAINER").html(data);
                misCuentas.getGridMisCuentas();                
            }
        });
    };
    
    this.publico.getGridMisCuentas = function (){
        
        var oTable = $('#'+diccionario.tabs.MISPA+'gridMisCuentas').dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [                
                {sTitle: "Código", sWidth: "8%"},
                {sTitle: "Ubicación", sWidth: "30%"},
                {sTitle: "Elemento", sWidth: "12%"},
                {sTitle: "Area m2", sWidth: "4%",  sClass: "center"},
                {sTitle: "Precio", sWidth: "8%",  sClass: "right"},                
                {sTitle: "Iluminado", sWidth: "5%",  sClass: "center"},
                {sTitle: "Estado", sWidth: "5%",  sClass: "center"},
                {sTitle: "Imagen", sWidth: "8%",  sClass: "center", bSortable: false},
                {sTitle: "Acciones", sWidth: "8%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[2, 'asc']],
            sScrollY: "350px",
            sAjaxSource: _private.config.modulo+'getGridProducto',
            fnServerParams: function(aoData) {
                aoData.push({"name": "_tipoPanel", "value": $("#"+diccionario.tabs.MISPA+"lst_tipopanelsearch").val()});
            },
            fnDrawCallback: function() {
                $('#'+diccionario.tabs.MISPA+'gridMisCuentas_filter').find('input').attr('placeholder','Buscar por Código o Ciudad o Ubicación').css('width','350px');;                
                simpleScript.enterSearch("#"+diccionario.tabs.MISPA+'gridMisCuentas',oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: '#widget_'+diccionario.tabs.MISPA, //widget del datagrid
                    typeElement: 'button'
                });
                $('#'+diccionario.tabs.MISPA+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.MISPA+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();       
    };
    
      
    return this.publico;
    
};
var misCuentas = new misCuentas_();