/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 20-11-2014 00:11:17 
* Descripcion : reporteVentaFecha.js
* ---------------------------------------
*/
var reporteVentaFecha_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idReporteVentaFecha = 0;
    _private.fecha = '';
    _private.moneda = '';
    
    _private.config = {
        modulo: "ventas/reporteVentaFecha/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : ReporteVentaFecha*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.VRPT2,
            label: $(element).attr("title"),
            fnCallback: function(){
                reporteVentaFecha.getContenido();
            }
        });
    };
    
    /*contenido de tab: ReporteVentaFecha*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.VRPT2+"_CONTAINER").html(data);
                reporteVentaFecha.getGridReporteVentaFecha();
            }
        });
    };
    
    this.publico.getGridReporteVentaFecha = function (){
        var _f1 = $("#"+diccionario.tabs.VRPT2+"txt_f1").val();
        var _f2 = $("#"+diccionario.tabs.VRPT2+"txt_f2").val();  
        
        var oTable = $("#"+diccionario.tabs.VRPT2+"gridReporteVentaFecha").dataTable({
            bFilter: false, 
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "N°", sWidth: "1%",bSortable: false},              
                {sTitle: "Fecha", sWidth: "10%", sClass: "center"},
                {sTitle: "Numero Documentos", sWidth: "15%", sClass: "center"},
                {sTitle: "Moneda", sWidth: "20%"},
                {sTitle: "Inicial", sWidth: "10%", sClass: "right"},
                {sTitle: "Ingresos", sWidth: "10%", sClass: "right"},
                {sTitle: "Egresos", sWidth: "10%", sClass: "right"},
                {sTitle: "Saldo", sWidth: "10%", sClass: "right"},                
                {sTitle: "Acciones", sWidth: "8%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[1, "desc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridReporteVentaFecha",
             fnServerParams: function(aoData) {
                aoData.push({"name": "_f1", "value": _f1});                
                aoData.push({"name": "_f2", "value": _f2});
            },
            fnDrawCallback: function() {           
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.VRPT2,
                    typeElement: "button"
                });
                $('#'+diccionario.tabs.VRPT2+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.VRPT2+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getGridConsultaVentaFecha = function (){       
        
        var oTable = $("#"+diccionario.tabs.VRPT2+"gridConsultaVenta").dataTable({
            bFilter:false,
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "N°", sWidth: "1%",bSortable: false},
                {sTitle: "Código", sWidth: "7%"},
                {sTitle: "Cliente", sWidth: "20%"},
                {sTitle: "Moneda", sWidth: "7%"},                                
                {sTitle: "Total", sWidth: "11%",  sClass: "right"},  
                {sTitle: "Pagado", sWidth: "11%",  sClass: "right"},
                {sTitle: "Saldo", sWidth: "11%",  sClass: "right"},
                {sTitle: "Acciones", sWidth: "10%", sClass: "center", bSortable: false} 
            ],
            aaSorting: [[2, "asc"]],
            sScrollY: "280px",
            sAjaxSource: _private.config.modulo+"getGridConsultaVentaFecha",       
            fnServerParams: function(aoData) {
                aoData.push({"name": "_fecha", "value": _private.fecha });
                aoData.push({"name": "_moneda", "value": _private.moneda });
            },
            fnDrawCallback: function(){
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.VCSCL,
                    typeElement: "button"
                });               
            }
        });
        setup_widgets_desktop();
    };    
    
    this.publico.getGridIndexVentaFecha = function (){
        var oTable = $('#'+diccionario.tabs.PANP+'gridVentaFecha').dataTable({
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
                {sTitle: "Fecha", sWidth: "10%", sClass: "center"},
                {sTitle: "Numero Documentos", sWidth: "15%", sClass: "center"},
                {sTitle: "Moneda", sWidth: "20%"},
                {sTitle: "Inicial", sWidth: "10%", sClass: "right"},
                {sTitle: "Ingresos", sWidth: "10%", sClass: "right"},
                {sTitle: "Egresos", sWidth: "10%", sClass: "right"},
                {sTitle: "Utilidad", sWidth: "10%", sClass: "right"}
            ],
            aaSorting: [[0, 'desc']],
            sScrollY: "125px",
            sAjaxSource: _private.config.modulo+'getGridIndexVentaFecha',
            fnDrawCallback: function() {
              $('#'+diccionario.tabs.PAAL+'gridVentaFecha_refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.PAAL+'gridVentaFecha_refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });

    };     
    
   this.publico.getGridConsultaEgresos = function (){       
        
        var oTable = $("#"+diccionario.tabs.VRPT2+"gridConsultaEgresos").dataTable({
            bFilter:false,
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "N°", sWidth: "1%",bSortable: false},
                {sTitle: "Descripcion", sWidth: "30%"},
                {sTitle: "Fecha ", sWidth: "15%"},     
                {sTitle: "Moneda", sWidth: "7%"},                                
                {sTitle: "Monto", sWidth: "11%",  sClass: "right"}                        
            ],
            aaSorting: [[1, "asc"]],
            sScrollY: "280px",
            sAjaxSource: _private.config.modulo+"getConsultaEgresos",       
            fnServerParams: function(aoData) {
                aoData.push({"name": "_fecha", "value": _private.fecha });
                aoData.push({"name": "_moneda", "value": _private.moneda });
            },
            fnDrawCallback: function(){
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.VCSCL,
                    typeElement: "button"
                });               
            }
        });
        setup_widgets_desktop();
    };    
      
    
    this.publico.getGridConsultaCaja = function (){       
        
        var oTable = $("#"+diccionario.tabs.VRPT2+"gridConsultaCaja").dataTable({
            bFilter:false,
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "ID Caja", sWidth: "2%"},
                {sTitle: "Moneda", sWidth: "6%"},
                {sTitle: "Inicio", sWidth: "10%",sClass: "right"},
                {sTitle: "Ingresos", sWidth: "10%",sClass: "right"},                                
                {sTitle: "Egresos", sWidth: "10%",  sClass: "right"},  
                {sTitle: "Saldo", sWidth: "10%",  sClass: "right"},
                {sTitle: "Cierre", sWidth: "15%",  sClass: "center"}
            ],
            aaSorting: [[0, "desc"]],
            sScrollY: "280px",
            sAjaxSource: _private.config.modulo+"getConsultaCaja",       
            fnServerParams: function(aoData) {
                aoData.push({"name": "_fecha", "value": _private.fecha });
                aoData.push({"name": "_moneda", "value": _private.moneda });
            },
            fnDrawCallback: function(){
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.VCSCL,
                    typeElement: "button"
                });               
            }
        });
        setup_widgets_desktop();
    };    
          
    
    
    this.publico.getFormConsultaVenta = function(btn,fecha,moneda){
        _private.fecha = fecha;
        _private.moneda = moneda;
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormConsultaVenta",
            data:'&_fecha='+_private.fecha+'&_moneda='+_private.moneda,
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.VRPT2+"formConsultaVenta").modal("show");
                 setTimeout(function(){                    
                    reporteVentaFecha.getGridConsultaVentaFecha()
                }, 500);
            }
        });
    };
    
    this.publico.postPDFVenta = function(btn,id,cod){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postPDFUnaVenta',
            data: '&_idVenta='+id+'&_cod='+cod,
            fnCallback: function(data) {
                if(parseInt(data.result) === 1){
                    $('#'+diccionario.tabs.VRPT2+'btnDowPDF').off('click');
                    $('#'+diccionario.tabs.VRPT2+'btnDowPDF').attr("onclick","window.open('public/files/"+data.archivo+"','_blank');generarCotizacion.deleteArchivo('"+data.archivo+"');");
                    $('#'+diccionario.tabs.VRPT2+'btnDowPDF').click();
                }
            }
        });
    }; 
    
    
    this.publico.postPDF = function(btn,f,m){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postPDF',
            data: '&_fecha='+f+'&_moneda='+m,
            fnCallback: function(data) {
                if(parseInt(data.result) === 1){
                    $('#'+diccionario.tabs.VRPT2+'btnDowPDF').off('click');
                    $('#'+diccionario.tabs.VRPT2+'btnDowPDF').attr("onclick","window.open('public/files/"+data.archivo+"','_blank');generarCotizacion.deleteArchivo('"+data.archivo+"');");
                    $('#'+diccionario.tabs.VRPT2+'btnDowPDF').click();
                }
            }
        });
    };
    
    this.publico.postExcel = function(btn,f,m){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postExcel',
            data: '&_fecha='+f+'&_moneda='+m,
            fnCallback: function(data) {
                if(parseInt(data.result) === 1){
                    $('#'+diccionario.tabs.VRPT2+'btnDowExcel').off('click');
                    $('#'+diccionario.tabs.VRPT2+'btnDowExcel').attr("onclick","window.open('public/files/"+data.archivo+"','_self');generarCotizacion.deleteArchivo('"+data.archivo+"');");
                    $('#'+diccionario.tabs.VRPT2+'btnDowExcel').click();
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
var reporteVentaFecha = new reporteVentaFecha_();