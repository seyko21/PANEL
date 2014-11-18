/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 30-09-2014 03:09:35 
* Descripcion : liquidacionSocio.js
* ---------------------------------------
*/
var liquidacionSocio_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idLiquidacionSocio = 0;
    
    _private.config = {
        modulo: "pagos/liquidacionSocio/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : LiquidacionSocio*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.LISOC,
            label: $(element).attr("title"),
            fnCallback: function(){
                liquidacionSocio.getContenido();
            }
        });
    };
    
    /*contenido de tab: LiquidacionSocio*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.LISOC+"_CONTAINER").html(data);
                liquidacionSocio.getGridLiquidacionSocio();
            }
        });
    };
    
    this.publico.getGridLiquidacionSocio = function (){
         
        var _f1 = $("#"+diccionario.tabs.LISOC+"txt_f1").val();
        var _f2 = $("#"+diccionario.tabs.LISOC+"txt_f2").val();        
       
        var oTable = $("#"+diccionario.tabs.LISOC+"gridLiquidacionSocio").dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "N° OS", sWidth: "10%",},                
                {sTitle: "Socio", sWidth: "15%"},
                {sTitle: "Fecha", sWidth: "10%",  sClass: "center"},
                {sTitle: "Cliente", sWidth: "25%"},                
                {sTitle: "Total Pagado", sWidth: "15%",sClass: "right"},
                {sTitle: "Estado", sWidth: "10%", sClass: "center"},                
                {sTitle: "Acciones", sWidth: "8%", sClass: "center", bSortable: false}               
            ],
            aaSorting: [[0, "desc"],[1, "asc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridLiquidacionSocio",
              fnServerParams: function(aoData) {
                aoData.push({"name": "_f1", "value": _f1});                
                aoData.push({"name": "_f2", "value": _f2}); 
            },
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.LISOC+"gridLiquidacionSocio_filter").find("input").attr("placeholder","Buscar por nombre").css("width","200px");
                simpleScript.enterSearch("#"+diccionario.tabs.LISOC+"gridLiquidacionSocio",oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.LISOC,
                    typeElement: "button"
                });
                $('#'+diccionario.tabs.LISOC+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.LISOC+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();
    };       
    
    this.publico.postPDF = function(btn,idd,idP,os){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postPDF',
            data: '&_idOrden='+idd+'&_idSocio='+idP+'&_numOrden='+os,
            fnCallback: function(data) {
                if(parseInt(data.result) === 1){
                    $('#'+diccionario.tabs.LISOC+'btnDowPDF').attr("onclick","window.open('public/files/"+data.archivo+"','_blank');compromisoPagar.deleteArchivo('"+data.archivo+"');");
                    $('#'+diccionario.tabs.LISOC+'btnDowPDF').click();
                }
            }
        });
    };
      
    
    return this.publico;
    
};
var liquidacionSocio = new liquidacionSocio_();