/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 29-08-2014 02:08:11 
* Descripcion : consultaPermisos.js
* ---------------------------------------
*/
var consultaPermisos_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idProducto = 0;
    
    _private.config = {
        modulo: "panel/consultaPermisos/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : ConsultaPermisos*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.TAB_CONPER,
            label: $(element).attr("title"),
            fnCallback: function(){
                consultaPermisos.getContenido();
            }
        });
    };
    
    /*contenido de tab: ConsultaPermisos*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.TAB_CONPER+"_CONTAINER").html(data);
                consultaPermisos.getGridConsultaPermisos();
            }
        });
    };
    
    this.publico.getGridConsultaPermisos = function (){
     var oTable  = $('#'+diccionario.tabs.TAB_CONPER+'gridConsultaPermisos').dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [                
                {sTitle: "Ciudad", sWidth: "20%"},
                {sTitle: "Ubicación", sWidth: "30%"},                
                {sTitle: "Fecha Inicio", sWidth: "8%",  sClass: "center"},
                {sTitle: "Fecha Final", sWidth: "8%",  sClass: "center"},
                {sTitle: "Area m2", sWidth: "8%",  sClass: "center"},                                
                {sTitle: "Acciones", sWidth: "15%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[3, 'asc']],      
            sScrollY: "350px",
            sAjaxSource: _private.config.modulo+'getGridConsultaPermiso',
            fnDrawCallback: function() {
                $('#'+diccionario.tabs.TAB_CONPER+'gridConsultaPermisos_filter').find('input').attr('placeholder','Buscar por Ciudad o Ubicación').css('width','350px');
                simpleScript.enterSearch("#"+diccionario.tabs.TAB_CONPER+'gridConsultaPermisos',oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: '#widget_'+diccionario.tabs.TAB_CONPER, //widget del datagrid
                    typeElement: 'button, #'+diccionario.tabs.TAB_CONPER+'chk_all'
                });
                $('#'+diccionario.tabs.TAB_CONPER+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.TAB_CONPER+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();                
        
    };

    this.publico.getGridIndexPermisos = function (){
       var oTable  =  $('#'+diccionario.tabs.PANP+'gridPermisoMunicipal').dataTable({
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
                {sTitle: "Ciudad", sWidth: "10%"},
                {sTitle: "Ubicación", sWidth: "25%"},                
                {sTitle: "F. termino", sWidth: "8%",  sClass: "center"}                                
            ],
            aaSorting: [[0, 'asc']],
            sScrollY: "125px",
            sAjaxSource: _private.config.modulo+'getGridIndexConsultaPermiso',
            fnDrawCallback: function() {
            $('#'+diccionario.tabs.PANP+'gridPermisoMunicipal_refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.PANP+'gridPermisoMunicipal_refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });

    };          
   
    this.publico.postPDF = function(btn,id){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postPDF',
            data: '&_idProducto='+id,
            fnCallback: function(data) {
                if(parseInt(data.result) === 1){
                     $('#'+diccionario.tabs.TAB_CONPER+'btnDowPDF').attr("onclick","window.open('public/files/"+data.archivo+"','_blank');fichaTecnica.deleteArchivo('"+data.archivo+"');");
                     $('#'+diccionario.tabs.TAB_CONPER+'btnDowPDF').click();                    
                }                
            }
        });
    };
    
    this.publico.postExcel = function(btn,id){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postExcel',
            data: '&_idProducto='+id,
            fnCallback: function(data) {
                if(parseInt(data.result) === 1){
                   $('#'+diccionario.tabs.TAB_CONPER+'btnDowExcel').attr("onclick","window.open('public/files/"+data.archivo+"','_self');fichaTecnica.deleteArchivo('"+data.archivo+"');");
                   $('#'+diccionario.tabs.TAB_CONPER+'btnDowExcel').click();                                       
                }
                if(!isNaN(data.result) && parseInt(data.result) === 2){
                    simpleScript.notify.error({
                        content: 'Ocurrió un error al exportar Ficha Tecnica.'
                    });
                }
            }
        });
    };
 
    
    return this.publico;
    
};
var consultaPermisos = new consultaPermisos_();