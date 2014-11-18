/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 22-09-2014 16:09:42 
* Descripcion : cotizacionVendedor.js
* ---------------------------------------
*/
var cotizacionVendedor_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idCotizacion = 0;
    
    _private.config = {
        modulo: "Cotizacion/cotizacionVendedor/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : CotizacionVendedor*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.COXVE,
            label: $(element).attr("title"),
            fnCallback: function(){
                cotizacionVendedor.getContenido();
            }
        });
    };
    
    /*contenido de tab: CotizacionVendedor*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.COXVE+"_CONTAINER").html(data);
                cotizacionVendedor.getGridCotizacionVendedor();
            }
        });
    };
    
    this.publico.getGridCotizacionVendedor = function (){
        var oTable = $("#"+diccionario.tabs.COXVE+"gridCotizacionVendedor").dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "Código", sClass: "center",sWidth: "12%"},
                {sTitle: "DNI", sWidth: "10%"},   
                {sTitle: "Vendedor", sWidth: "25%"},   
                {sTitle: "Fecha", sWidth: "10%", sClass: "center"},
                {sTitle: "Meses", sWidth: "10%", sClass: "center"},
                {sTitle: "Total", sWidth: "10%", sClass: "right"},
                {sTitle: "Estado", sWidth: "15%", sClass: "center"},
                {sTitle: "Acciones", sWidth: "10%", sClass: "center", bSortable: false}          
            ],
            aaSorting: [[0, "desc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridCotizacionVendedor",
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.COXVE+"gridCotizacionVendedor_filter").find("input").attr("placeholder","Buscar por Vendedor, DNI o Nro Cotizacion").css("width","350px");
                simpleScript.enterSearch("#"+diccionario.tabs.COXVE+"gridCotizacionVendedor",oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.COXVE,
                    typeElement: "button"
                });
                $('#'+diccionario.tabs.COXVE+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.COXVE+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getGridTICO = function (){
         $('#'+diccionario.tabs.COXVE+'gridTICO').dataTable({
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
                {sTitle: "Observacion", sWidth: "50%", sClass: "left",bSortable: false},
                {sTitle: "Estado", sWidth: "10%", bSortable: false}
            ],
            aaSorting: [[1, 'asc']],
            sScrollY: "350px",
            sAjaxSource: _private.config.modulo+'getGridTiempoCoti',
            fnServerParams: function(aoData) {
                aoData.push({"name": "_idCotizacion", "value": _private.idCotizacion });
            }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getConsulta = function(id, cod){
        _private.idCotizacion = id;               
        simpleAjax.send({
            gifProcess: true,
            dataType: 'html',
            root: _private.config.modulo + 'getConsulta',
            data: '&_idCotizacion='+_private.idCotizacion+'&_numeroCotizacion='+cod,
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.COXVE+'formTICO').modal('show');
                setTimeout(function(){                    
                    cotizacionVendedor.getGridTICO()
                }, 500);
                
            }
        });
    };   
        
    
    this.publico.postPDF = function(btn,idCot, num){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postPDF',
            data: '&_idCotizacion='+idCot+'&_num='+num,
            fnCallback: function(data){
                if(parseInt(data.result) === 1){
                    $('#'+diccionario.tabs.COXVE+'btnDowPDF').attr("onclick","window.open('public/files/"+data.archivo+"','_blank');generarCotizacion.deleteArchivo('"+data.archivo+"');");
                    $('#'+diccionario.tabs.COXVE+'btnDowPDF').click();
                }
            }
        });
    }; 
    
    return this.publico;
    
};
var cotizacionVendedor = new cotizacionVendedor_();