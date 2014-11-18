/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 28-09-2014 00:09:14 
* Descripcion : pagoRealizado.js
* ---------------------------------------
*/
var pagoRealizado_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idPagoRealizado = 0;
    
    _private.config = {
        modulo: "pagos/pagoRealizado/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : PagoRealizado*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.MIPR,
            label: $(element).attr("title"),
            fnCallback: function(){
                pagoRealizado.getContenido();
            }
        });
    };
    
    /*contenido de tab: PagoRealizado*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.MIPR+"_CONTAINER").html(data);
                pagoRealizado.getGridPagoRealizado();
            }
        });
    };
    
    this.publico.getGridPagoRealizado = function (){
            
        var _f1 = $("#"+diccionario.tabs.MIPR+"txt_f1").val();
        var _f2 = $("#"+diccionario.tabs.MIPR+"txt_f2").val();        

        var oTable = $("#"+diccionario.tabs.MIPR+"gridPagoRealizado").dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "Cuota", sWidth: "8%", sClass: "center"},
                {sTitle: "N° OS", sWidth: "8%"},                
                {sTitle: "Fecha Programada", sWidth: "9%", sClass: "center"},
                {sTitle: "Cliente", sWidth: "25%"},
                {sTitle: "Mora", sWidth: "7%", sClass: "right"},
                {sTitle: "Monto", sWidth: "10%", sClass: "right"},
                {sTitle: "Fecha Pago", sWidth: "9%", sClass: "center"},
                {sTitle: "Estado", sWidth: "8%", sClass: "center"},
                {sTitle: "Acciones", sWidth: "8%", sClass: "center", bSortable: false}     
            ],
            aaSorting: [[1, "asc"],[0, "asc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridPagoRealizado",
            fnServerParams: function(aoData) {
                aoData.push({"name": "_f1", "value": _f1});                
                aoData.push({"name": "_f2", "value": _f2});             
            },
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.MIPR+"gridPagoRealizado_filter").find("input").attr("placeholder","Buscar por N° OS").css("width","250px");
                simpleScript.enterSearch("#"+diccionario.tabs.MIPR+"gridPagoRealizado",oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.MIPR,
                    typeElement: "button"
                });
                $('#'+diccionario.tabs.MIPR+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.MIPR+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();
    };
    
     this.publico.postPDF = function(btn,idd){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postPDF',
            data: '&_idCompromiso='+idd,
            fnCallback: function(data) {
                if(parseInt(data.result) === 1){
                    $('#'+diccionario.tabs.MIPR+'btnDowPDF').attr("onclick","window.open('public/files/"+data.archivo+"','_blank');compromisoPagar.deleteArchivo('"+data.archivo+"');");
                    $('#'+diccionario.tabs.MIPR+'btnDowPDF').click();
                }
            }
        });
    };
    
    this.publico.postExcel = function(btn,idd){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postExcel',
            data: '&_idCompromiso='+idd,
            fnCallback: function(data) {
                if(parseInt(data.result) === 1){
                    $('#'+diccionario.tabs.MIPR+'btnDowExcel').attr("onclick","window.open('public/files/"+data.archivo+"','_self');compromisoPagar.deleteArchivo('"+data.archivo+"');");
                    $('#'+diccionario.tabs.MIPR+'btnDowExcel').click();
                }
                if(!isNaN(data.result) && parseInt(data.result) === 2){
                    simpleScript.notify.error({
                        content: 'Ocurrió un error al exportar.'
                    });
                }
            }
        });
    };

    
    return this.publico;
    
};
var pagoRealizado = new pagoRealizado_();