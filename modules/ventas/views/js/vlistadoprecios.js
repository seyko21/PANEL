/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 22-11-2014 19:11:31 
* Descripcion : vlistadoprecios.js
* ---------------------------------------
*/
var vlistadoprecios_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idVlistadoprecios = 0;
    
    _private.config = {
        modulo: "ventas/vlistadoprecios/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : Vlistadoprecios*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.VRPT5,
            label: $(element).attr("title"),
            fnCallback: function(){
                vlistadoprecios.getContenido();
            }
        });
    };
    
    /*contenido de tab: Vlistadoprecios*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.VRPT5+"_CONTAINER").html(data);
                vlistadoprecios.getGridVlistadoprecios();
            }
        });
    };
    
    this.publico.getGridVlistadoprecios = function (){
        var oTable = $("#"+diccionario.tabs.VRPT5+"gridVlistadoprecios").dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "N°", sWidth: "1%",bSortable: false},         
                {sTitle: "Descripción del producto", sWidth: "25%"},
                {sTitle: "Unid. Med.", sWidth: "20%"},
                {sTitle: "Incl. Igv", sWidth: "10%"},
                {sTitle: "Moneda", sWidth: "10%"},
                {sTitle: "Precio", sWidth: "15%",sClass: "right"}
            ],
            aaSorting: [[0, "asc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridVlistadoprecios",
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.VRPT5+"gridVlistadoprecios_filter").find("input").attr("placeholder","Buscar por Descripción ").css("width","350px");
                simpleScript.enterSearch("#"+diccionario.tabs.VRPT5+"gridVlistadoprecios",oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.VRPT5,
                    typeElement: "button"
                });
                $('#'+diccionario.tabs.VRPT5+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.VRPT5+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();
    };
    
    this.publico.postPDF = function(btn){
           simpleAjax.send({
               element: btn,
               root: _private.config.modulo + 'postPDF',
               fnCallback: function(data) {
                   if(parseInt(data.result) === 1){
                       $('#'+diccionario.tabs.VRPT5+'btnDowPDF').off('click');
                       $('#'+diccionario.tabs.VRPT5+'btnDowPDF').attr("onclick","window.open('public/files/"+data.archivo+"','_blank');generarCotizacion.deleteArchivo('"+data.archivo+"');");
                       $('#'+diccionario.tabs.VRPT5+'btnDowPDF').click();
                   }
               }
           });
       };
    
    this.publico.postExcel = function(btn){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postExcel',
            fnCallback: function(data) {
                if(parseInt(data.result) === 1){
                    $('#'+diccionario.tabs.VRPT5+'btnDowExcel').off('click');
                    $('#'+diccionario.tabs.VRPT5+'btnDowExcel').attr("onclick","window.open('public/files/"+data.archivo+"','_self');generarCotizacion.deleteArchivo('"+data.archivo+"');");
                    $('#'+diccionario.tabs.VRPT5+'btnDowExcel').click();
                }
                if(!isNaN(data.result) && parseInt(data.result) === 2){
                    simpleScript.notify.error({
                        content: 'Ocurrió un error al exportar Venta.'
                    });
                }
            }
        });
    };      
    
    return this.publico;
    
};
var vlistadoprecios = new vlistadoprecios_();