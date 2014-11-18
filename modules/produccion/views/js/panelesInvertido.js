/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 02-10-2014 20:10:49 
* Descripcion : panelesInvertido.js
* ---------------------------------------
*/
var panelesInvertido_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idProducto = 0;
    
    _private.config = {
        modulo: "Produccion/panelesInvertido/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : PanelesInvertido*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.PAINV,
            label: $(element).attr("title"),
            fnCallback: function(){
                panelesInvertido.getContenido();
            }
        });
    };
    
    /*contenido de tab: PanelesInvertido*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.PAINV+"_CONTAINER").html(data);
                panelesInvertido.getGridPanelesInvertido();
            }
        });
    };
    
    this.publico.getGridPanelesInvertido = function (){
        var oTable = $("#"+diccionario.tabs.PAINV+"gridPanelesInvertido").dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "N°", sWidth: "1%",bSortable: false},                
                {sTitle: "Socio", sWidth: "15%"},                
                {sTitle: "Produccion", sWidth: "8%"},
                {sTitle: "Ubicación", sWidth: "25%"},
                {sTitle: "Codigos", sWidth: "8%"},
                {sTitle: "Fecha", sWidth: "8%", sClass:"center"},
                {sTitle: "% Ganancia", sWidth: "5%", sClass:"right"},
                {sTitle: "Invertido", sWidth: "8%", sClass:"right"},                              
                {sTitle: "Acciones", sWidth: "8%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[1, "asc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridPanelesInvertido",
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.PAINV+"gridPanelesInvertido_filter").find("input").attr("placeholder","Buscar por Codigos o Ubicacion o N° produccion").css("width","400px");
                simpleScript.enterSearch("#"+diccionario.tabs.PAINV+"gridPanelesInvertido",oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.PAINV,
                    typeElement: "button"
                });
               $('#'+diccionario.tabs.PAINV+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.PAINV+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getConsulta = function(btn, id){
        _private.idProducto = id;               
        simpleAjax.send({
            element : btn,
            gifProcess: true,
            dataType: 'html',
            root: _private.config.modulo + 'getConsulta',
            data: '&_idProducto='+_private.idProducto,
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.PAINV+'formConsultaPanelSocio').modal('show');                
                
            }
        });
    };   
   
    this.publico.postPDF = function(btn,idd, cod){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postPDF',
            data: '&_idProduccion='+idd+'&_cod='+cod,
            fnCallback: function(data){
                if(parseInt(data.result) === 1){
                    $('#'+diccionario.tabs.PAINV+'btnDowPDF').attr("onclick","window.open('public/files/"+data.archivo+"','_blank');generarCotizacion.deleteArchivo('"+data.archivo+"');");
                    $('#'+diccionario.tabs.PAINV+'btnDowPDF').click();
                }
            }
        });
    };  
   
    return this.publico;
    
};
var panelesInvertido = new panelesInvertido_();