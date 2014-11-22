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
    
    _private.idOS = 0;
    
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
       
        var oTable = $("#"+diccionario.tabs.MOVOS+"gridMovimientosOS").dataTable({
            bFilter: true,
            sSearch: true,
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "N° OS", sWidth: "8%",},                
                {sTitle: "Fecha ", sWidth: "10%",  sClass: "center"},
                {sTitle: "Total OS", sWidth: "12%",sClass: "right"},
                {sTitle: "Impuesto", sWidth: "12%", sClass: "right"}, 
                {sTitle: "Ingresos", sWidth: "12%",sClass: "right"},
                {sTitle: "Instalación", sWidth: "12%", sClass: "right"},                
                {sTitle: "Comision", sWidth: "12%", sClass: "right"}, 
                {sTitle: "Utilidad", sWidth: "12%", sClass: "right"},    
                {sTitle: "Estado", sWidth: "8%", sClass: "center", bSortable: false},
                {sTitle: "Acciones", sWidth: "7%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[0, "desc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridMovimientosOS",
             fnServerParams: function(aoData) {
                aoData.push({"name": "_f1", "value": _f1});                
                aoData.push({"name": "_f2", "value": _f2}); 
            },            
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.MOVOS+"gridMovimientosOS_filter").find("input").attr("placeholder","Buscar por N° OS").css("width","200px");
                simpleScript.enterSearch("#"+diccionario.tabs.MOVOS+'gridMovimientosOS',oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.MOVOS,
                    typeElement: "button"
                });
                 simpleScript.removeAttr.change({
                    container: "#widget_"+diccionario.tabs.MOVOS,
                    typeElement: "select"
                });
                $('#'+diccionario.tabs.MOVOS+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.MOVOS+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();
    };

    this.publico.getGridMOVInstalacion= function (){
         $('#'+diccionario.tabs.MOVOS+'gridMOVOI').dataTable({
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
                {sTitle: "Código", sWidth: "10%", sClass: "center"},
                {sTitle: "Instalación", sWidth: "10%", sClass: "center"},                                
                {sTitle: "Ubicación", sWidth: "35%"},
                {sTitle: "Fecha", sWidth: "10%", sClass: "center"},
                {sTitle: "Total", sWidth: "10%", sClass: "right"}
            ],
            aaSorting: [[0, 'asc']],
            sScrollY: "260px",
            sAjaxSource: _private.config.modulo+'getGridMovInstalacion',
            fnServerParams: function(aoData) {
                aoData.push({"name": "_idOS", "value": _private.idOS });
            }
        });
        setup_widgets_desktop();
    };    
    
    this.publico.getGridMOVComision= function (){
         $('#'+diccionario.tabs.MOVOS+'gridMOVCV').dataTable({
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
                {sTitle: "Código", sWidth: "8%", sClass: "center"},
                {sTitle: "Ubicación", sWidth: "25%"},
                {sTitle: "Alquiler", sWidth: "10%", sClass: "center"},
                {sTitle: "Importe", sWidth: "8%", sClass: "right"},
                {sTitle: "%Porcentaje", sWidth: "5%", sClass: "right"},
                {sTitle: "Comisión", sWidth: "8%", sClass: "right"}
            ],
            aaSorting: [[0, 'asc']],
            sScrollY: "260px",
            sAjaxSource: _private.config.modulo+'getGridMovComision',
            fnServerParams: function(aoData) {
                aoData.push({"name": "_idOS", "value": _private.idOS });
            }
        });
        setup_widgets_desktop();
    };        

    this.publico.getGridMOVIngresos= function (){
         $('#'+diccionario.tabs.MOVOS+'gridMOVIN').dataTable({
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
                {sTitle: "Cuota", sWidth: "7%", sClass: "center"},
                {sTitle: "Mora", sWidth: "7%", sClass: "right"},
                {sTitle: "Monto", sWidth: "12%", sClass: "right"},
                {sTitle: "F. Programada", sWidth: "11%", sClass: "center"},
                {sTitle: "F. Pagado", sWidth: "11%", sClass: "center"},
                {sTitle: "Estado", sWidth: "10%", sClass: "center"}
            ],
            aaSorting: [[0, 'asc']],
            sScrollY: "260px",
            sAjaxSource: _private.config.modulo+'getGridMovIngresos',
            fnServerParams: function(aoData) {
                aoData.push({"name": "_idOS", "value": _private.idOS });
            }
        });
        setup_widgets_desktop();
    };   
    
    this.publico.getGridMOVUtilidad = function (){
         $('#'+diccionario.tabs.MOVOS+'gridMOVUTILIDAD').dataTable({
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
                {sTitle: "Código", sWidth: "7%", sClass: "center"},
                {sTitle: "Tiempo", sWidth: "15%", sClass: "center"},
                {sTitle: "Alquiler + Prod", sWidth: "10%", sClass: "right"},
                {sTitle: "Impuesto", sWidth: "10%", sClass: "right"},
                {sTitle: "Egresos", sWidth: "10%", sClass: "right"},
                {sTitle: "Utilidad", sWidth: "10%", sClass: "right"}
            ],
            aaSorting: [[0, 'asc']],
            sScrollY: "260px",
            sAjaxSource: _private.config.modulo+'getGridMovUtilidad',
            fnServerParams: function(aoData) {
                aoData.push({"name": "_idOS", "value": _private.idOS });
            }
        });
        setup_widgets_desktop();
    };   
    
    this.publico.getGridHIOR= function (){
         $('#'+diccionario.tabs.MOVOS+'gridHIOR').dataTable({
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
                {sTitle: "N°", sWidth: "5%", sClass: "center",bSortable: false},
                {sTitle: "Fecha", sWidth: "23%", sClass: "center",bSortable: false},
                {sTitle: "Observacion", sWidth: "40%", sClass: "left",bSortable: false},
                {sTitle: "Estado", sWidth: "10%", bSortable: false}
            ],            
            sScrollY: "260px",
            sAjaxSource: _private.config.modulo+'getGridTiempoOrden',
            fnServerParams: function(aoData) {
                aoData.push({"name": "_idOrden", "value": _private.idOS});
            }
        });
        setup_widgets_desktop();
    };
        
    this.publico.getConsulta = function(btn,idd,cod){
        _private.idOS = idd;
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormConsulta",
            data: '&_idOS='+_private.idOS+'&_cod='+cod,
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.MOVOS+"formMOV").modal("show");                
                setTimeout(function(){movimientosOS.getGridHIOR();},500);
                
            }
        });
    };
    

    
    return this.publico;
    
};
var movimientosOS = new movimientosOS_();