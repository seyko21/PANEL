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
    
    _private.idCaratula = 0;
    
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
        var oTable = $('#'+diccionario.tabs.PAAL+'gridPanelesAlquilados').dataTable({
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
                {sTitle: "Ubicación", sWidth: "27%"},
                {sTitle: "Elemento", sWidth: "12%"},
                {sTitle: "Area m2", sWidth: "4%",  sClass: "center"},                
                {sTitle: "Iluminado", sWidth: "5%",  sClass: "center"},
                {sTitle: "Estado", sWidth: "5%",  sClass: "center"},
                {sTitle: "Imagen", sWidth: "8%",  sClass: "center", bSortable: false},
                {sTitle: "Acciones", sWidth: "8%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[2, 'asc']],
            sScrollY: "350px",
            sAjaxSource: _private.config.modulo+'getGridProducto',
            fnServerParams: function(aoData) {
                aoData.push({"name": "_tipoPanel", "value": $("#"+diccionario.tabs.PAAL+"lst_tipopanelsearch").val()});
            },
            fnDrawCallback: function() {
                $('#'+diccionario.tabs.PAAL+'gridPanelesAlquilados_filter').find('input').attr('placeholder','Buscar por Código o Ciudad o Ubicación').css('width','350px');
                simpleScript.enterSearch("#"+diccionario.tabs.PAAL+'gridPanelesAlquilados',oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: '#widget_'+diccionario.tabs.PAAL, //widget del datagrid
                    typeElement: 'button'
                });
                $('#'+diccionario.tabs.PAAL+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.PAAL+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();
    }; 
    
    this.publico.getGridPAOS = function (){
       var oTable = $('#'+diccionario.tabs.PAAL+'gridPAOS').dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,   
            sSearch: false,
            bFilter: false,
            aoColumns: [                
                {sTitle: "N° OS", sWidth: "8%"},
                {sTitle: "Cliente", sWidth: "40%"},
                {sTitle: "Precio", sWidth: "10%", sClass: "right"},
                {sTitle: "Inicio", sWidth: "10%", sClass: "center"},
                {sTitle: "Termino", sWidth: "10%", sClass: "center"},
                {sTitle: "Vista", sWidth: "10%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[1, 'asc']],
            sScrollY: "350px",
            sAjaxSource: _private.config.modulo+'getGridOrdenServicio',
            fnServerParams: function(aoData) {
                aoData.push({"name": "_idCaratula", "value": _private.idCaratula});
            },
             fnDrawCallback: function() {
              $('#'+diccionario.tabs.PAAL+'gridPAOS_refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.PAAL+'gridPAOS_refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getConsulta = function(btn,id, cod){
        _private.idCaratula = id;               
        simpleAjax.send({
            element: btn,
            dataType: 'html',
            root: _private.config.modulo + 'getConsulta',
            data: '&_idCaratula='+_private.idCaratula+'&_codCaratula='+cod,
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.PAAL+'formPAOS').modal('show');
                setTimeout(function(){                    
                    panelesAlquilados.getGridPAOS()
                }, 500);
                
            }
        });
    };   
    
    this.publico.getGridIndexPA = function (){
        var oTable = $('#'+diccionario.tabs.PANP+'gridPanelAlquilado').dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,   
            sSearch: false,
            bFilter: false,
            aoColumns: [                                
                {sTitle: "Código", sWidth: "10%"},
                {sTitle: "N° OS", sWidth: "10%"},                
                {sTitle: "F. Instalado", sWidth: "12%", sClass: "center"},
                {sTitle: "F. Retiro", sWidth: "12%", sClass: "center"}                
            ],
            aaSorting: [[2, 'desc']],
            sScrollY: "125px",
            sAjaxSource: _private.config.modulo+'getGridIndexPanelAlquilado',
            fnDrawCallback: function() {
              $('#'+diccionario.tabs.PAAL+'gridPanelAlquilado_refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.PAAL+'gridPanelAlquilado_refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });

    }; 
    
    this.publico.getGridIndexPAS = function (){
        var oTable = $('#'+diccionario.tabs.PANP+'gridPanelAlquilado').dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,   
            sSearch: false,
            bFilter: false,
            aoColumns: [                                
                {sTitle: "Código", sWidth: "10%"},
                {sTitle: "N° OS", sWidth: "10%"},                
                {sTitle: "F. Instalado", sWidth: "12%", sClass: "center"},
                {sTitle: "F. Retiro", sWidth: "12%", sClass: "center"}                
            ],
            aaSorting: [[2, 'desc']],
            sScrollY: "125px",
            sAjaxSource: _private.config.modulo+'getGridIndexPanelAlquiladoSocio',
             fnDrawCallback: function() {
              $('#'+diccionario.tabs.PAAL+'gridPanelAlquilado_refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.PAAL+'gridPanelAlquilado_refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });

    };     
    
    return this.publico;
    
};
var panelesAlquilados = new panelesAlquilados_();