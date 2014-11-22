/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 27-09-2014 07:09:56 
* Descripcion : liquidacionCliente.js
* ---------------------------------------
*/
var liquidacionCliente_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idLiquidacionCliente = 0;
    
    _private.config = {
        modulo: "pagos/liquidacionCliente/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : LiquidacionCliente*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.LICL,
            label: $(element).attr("title"),
            fnCallback: function(){
                liquidacionCliente.getContenido();
            }
        });
    };
    
    /*contenido de tab: LiquidacionCliente*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.LICL+"_CONTAINER").html(data);
                liquidacionCliente.getGridLiquidacionCliente();
            }
        });
    };
    
    this.publico.getGridLiquidacionCliente = function (){
        
        var _f1 = $("#"+diccionario.tabs.LICL+"txt_f1").val();
        var _f2 = $("#"+diccionario.tabs.LICL+"txt_f2").val();        
    
        var oTable = $("#"+diccionario.tabs.LICL+"gridLiquidacionCliente").dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "N° OS", sWidth: "10%",},                
                {sTitle: "Fecha", sWidth: "10%",  sClass: "center"},
                {sTitle: "Cliente", sWidth: "40%"},
                {sTitle: "Total", sWidth: "15%",sClass: "right"},
                {sTitle: "Estado", sWidth: "10%", sClass: "center"},                
                {sTitle: "Acciones", sWidth: "8%", sClass: "center", bSortable: false}           
            ],
            aaSorting: [[0, "asc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridLiquidacionCliente",
            fnServerParams: function(aoData) {
                aoData.push({"name": "_f1", "value": _f1});                
                aoData.push({"name": "_f2", "value": _f2}); 
            },
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.LICL+"gridLiquidacionCliente_filter").find("input").attr("placeholder","Buscar por razón social").css("width","200px");
                simpleScript.enterSearch("#"+diccionario.tabs.LICL+"gridLiquidacionCliente",oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.LICL,
                    typeElement: "button, a"
                });
              $('#'+diccionario.tabs.LICL+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.LICL+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();
    };
    
    this.publico.postPDF = function(btn,idd,os){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postPDF',
            data: '&_idOrden='+idd+'&_numOrden='+os,
            fnCallback: function(data) {
                if(parseInt(data.result) === 1){
                    $('#'+diccionario.tabs.LICL+'btnDowPDF').attr("onclick","window.open('public/files/"+data.archivo+"','_blank');compromisoPagar.deleteArchivo('"+data.archivo+"');");
                    $('#'+diccionario.tabs.LICL+'btnDowPDF').click();
                }
            }
        });
    };
  
    return this.publico;
    
};
var liquidacionCliente = new liquidacionCliente_();