/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 28-09-2014 00:09:01 
* Descripcion : contratos.js
* ---------------------------------------
*/
var contratos_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idContratos = 0;
    
    _private.config = {
        modulo: "ordenservicio/contratos/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : Contratos*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.CONTR,
            label: $(element).attr("title"),
            fnCallback: function(){
                contratos.getContenido();
            }
        });
    };
    
    /*contenido de tab: Contratos*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.CONTR+"_CONTAINER").html(data);
                contratos.getGridContratos();
            }
        });
    };
    
    this.publico.getGridContratos = function (){
        var _f1 = $("#"+diccionario.tabs.CONTR+"txt_f1").val();
        var _f2 = $("#"+diccionario.tabs.CONTR+"txt_f2").val();        
       
        var oTable = $("#"+diccionario.tabs.CONTR+"gridContratos").dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "N° OS", sWidth: "10%",},                
                {sTitle: "Fecha", sWidth: "8%",  sClass: "center"},
                {sTitle: "Cliente", sWidth: "28%"},
                {sTitle: "Creado por", sWidth: "25%"},
                {sTitle: "Total", sWidth: "15%",sClass: "right"},
                {sTitle: "Estado", sWidth: "8%", sClass: "center"},                
                {sTitle: "Acciones", sWidth: "8%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[0, "desc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridContratos",
            fnServerParams: function(aoData) {
                aoData.push({"name": "_f1", "value": _f1});                
                aoData.push({"name": "_f2", "value": _f2}); 
            },            
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.CONTR+"gridContratos_filter").find("input").attr("placeholder","Buscar por N° OS").css("width","200px");
                simpleScript.enterSearch("#"+diccionario.tabs.CONTR+'gridContratos',oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.CONTR,
                    typeElement: "button"
                });
                $('#'+diccionario.tabs.CONTR+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.CONTR+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getGridIndexContratos = function (){
        
       var oTable = $("#"+diccionario.tabs.PANP+"gridContratos").dataTable({
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
                {sTitle: "N° OS", sWidth: "10%",},                
                {sTitle: "Fecha", sWidth: "8%",  sClass: "center"},               
                {sTitle: "Total", sWidth: "15%",sClass: "right"},
                {sTitle: "Estado", sWidth: "8%", sClass: "center"},                
                {sTitle: "Acciones", sWidth: "8%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[1, "desc"]],
            sScrollY: "125px",
            sAjaxSource: _private.config.modulo+"getGridIndexContratos",
              fnDrawCallback: function() {
              $('#'+diccionario.tabs.PANP+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.PANP+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
    };        
    
    
    this.publico.postExportarContratoPDF = function(btn,id,num){
        simpleAjax.send({
            element: btn,
            root: 'ordenservicio/generarOrden/postExportarContratoPDF',
            data: '&_idOrden='+id+'&_numOrden='+num,
            fnCallback: function(data) {
                if(parseInt(data.result) === 1){
                    $('#'+diccionario.tabs.CONTR+'btnDowPDF').off('onclick');
                    $('#'+diccionario.tabs.CONTR+'btnDowPDF').off('click');
                    $('#'+diccionario.tabs.CONTR+'btnDowPDF').attr("onclick","window.open('public/files/"+data.archivo+"','_blank');generarCotizacion.deleteArchivo('"+data.archivo+"');");
                    $('#'+diccionario.tabs.CONTR+'btnDowPDF').click();
                }
            }
        });
    };     
    
     this.publico.postPDF = function(btn,id,num){
        simpleAjax.send({
            element: btn,
            root: 'ordenservicio/generarOrden/postExportarContratoPDF',
            data: '&_idOrden='+id+'&_numOrden='+num,
            fnCallback: function(data) {
                if(parseInt(data.result) === 1){
                    $('#'+diccionario.tabs.CONTR+'btnDowPDF').off('onclick');
                    $('#'+diccionario.tabs.CONTR+'btnDowPDF').off('click');
                    $('#'+diccionario.tabs.CONTR+'btnDowPDF').attr("onclick","window.open('public/files/"+data.archivo+"','_blank');generarCotizacion.deleteArchivo('"+data.archivo+"');");
                    $('#'+diccionario.tabs.CONTR+'btnDowPDF').click();
                }
            }
        });
    };    
    
    return this.publico;
    
};
var contratos = new contratos_();