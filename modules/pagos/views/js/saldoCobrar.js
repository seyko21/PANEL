/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 27-09-2014 23:09:28 
* Descripcion : saldoCobrar.js
* ---------------------------------------
*/
var saldoCobrar_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idSaldoCobrar = 0;
    
    _private.config = {
        modulo: "pagos/saldoCobrar/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : SaldoCobrar*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.SACOB,
            label: $(element).attr("title"),
            fnCallback: function(){
                saldoCobrar.getContenido();
            }
        });
    };
    
    /*contenido de tab: SaldoCobrar*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.SACOB+"_CONTAINER").html(data);
                saldoCobrar.getGridSaldoCobrar();
            }
        });
    };
    
    this.publico.getGridSaldoCobrar = function (){
        var _f1 = $("#"+diccionario.tabs.SACOB+"txt_f1").val();
        var _f2 = $("#"+diccionario.tabs.SACOB+"txt_f2").val();        
        var oTable = $("#"+diccionario.tabs.SACOB+"gridSaldoCobrar").dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "Cuota", sWidth: "5%"},
                {sTitle: "N° OS", sWidth: "10%"},
                {sTitle: "Beneficiario", sWidth: "25%"},
                {sTitle: "% Comisión", sWidth: "5%"},
                {sTitle: "Alquiler", sWidth: "10%"},                
                {sTitle: "Fecha", sWidth: "15%",sClass: "center" },
                {sTitle: "Comisión", sWidth: "10%", sClass: "right"},             
                {sTitle: "Pagado", sWidth: "10%", sClass: "right"},             
                {sTitle: "Saldo", sWidth: "10%", sClass: "right"}
            ],
            aaSorting: [[1, "asc"],[0, "asc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridSaldoCobrar",
             fnServerParams: function(aoData) {
                aoData.push({"name": "_f1", "value": _f1});                
                aoData.push({"name": "_f2", "value": _f2}); 
            },            
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.SACOB+"gridSaldoCobrar_filter").find("input").attr("placeholder","Buscar por N° OS").css("width","350px");
                simpleScript.enterSearch("#"+diccionario.tabs.SACOB+"gridSaldoCobrar",oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.SACOB,
                    typeElement: "button"
                });
                $('#'+diccionario.tabs.SACOB+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.SACOB+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getGridIndexSaldoCobrar = function (){

        var oTable = $("#"+diccionario.tabs.PANP+"gridSaldoCobrar").dataTable({
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
                {sTitle: "N° OS", sWidth: "8%",sClass: "center"},
                {sTitle: "% Comision", sWidth: "8%",sClass: "center" },
                {sTitle: "Total", sWidth: "15%", sClass: "right"},             
                {sTitle: "Pagado", sWidth: "15%", sClass: "right"},             
                {sTitle: "Saldo", sWidth: "15%", sClass: "right"}
            ],
            aaSorting: [[0, "asc"]],
            sScrollY: "125px",
            sAjaxSource: _private.config.modulo+"getGridIndexSaldoCobrar",
            fnDrawCallback: function() {
            $('#'+diccionario.tabs.PANP+'gridSaldoCobrar_refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.PANP+'gridSaldoCobrar_refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
    };    
    
    return this.publico;
    
};
var saldoCobrar = new saldoCobrar_();