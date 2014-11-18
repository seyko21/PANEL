/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 26-09-2014 21:09:55 
* Descripcion : saldoVendedor.js
* ---------------------------------------
*/
var saldoVendedor_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idComision = 0;
    
    _private.config = {
        modulo: "pagos/saldoVendedor/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : SaldoVendedor*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.SAVEN,
            label: $(element).attr("title"),
            fnCallback: function(){
                saldoVendedor.getContenido();
            }
        });
    };
    
    /*contenido de tab: SaldoVendedor*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.SAVEN+"_CONTAINER").html(data);
                saldoVendedor.getGridSaldoVendedor();
            }
        });
    };
    
    this.publico.getGridSaldoVendedor = function (){
        
        var _cb = $("#"+diccionario.tabs.SAVEN+"lst_estadosearch").val();
        if (_cb == 'T'){
            $("#"+diccionario.tabs.SAVEN+"txt_f1").prop('disabled',true);
            $("#"+diccionario.tabs.SAVEN+"txt_f2").prop('disabled',true);
        }else{                
            $("#"+diccionario.tabs.SAVEN+"txt_f1").prop('disabled',false);
            $("#"+diccionario.tabs.SAVEN+"txt_f2").prop('disabled',false);
            var _f1 = $("#"+diccionario.tabs.SAVEN+"txt_f1").val();
            var _f2 = $("#"+diccionario.tabs.SAVEN+"txt_f2").val();                  
        }
        var oTable = $("#"+diccionario.tabs.SAVEN+"gridSaldoVendedor").dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "Cuota", sWidth: "5%"},
                {sTitle: "N° OS", sWidth: "5%"},
                {sTitle: "Vendedor", sWidth: "20%"},
                {sTitle: "Fecha", sWidth: "15%",sClass: "center" },
                {sTitle: "Porcentaje", sWidth: "5%",sClass: "center" },
                {sTitle: "Alquiler", sWidth: "10%"},
                {sTitle: "Comision", sWidth: "10%", sClass: "right"},             
                {sTitle: "Pagado", sWidth: "10%", sClass: "right"},             
                {sTitle: "Saldo", sWidth: "10%", sClass: "right"},             
                {sTitle: "Acciones", sWidth: "8%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[1, "asc"],[0, "asc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridSaldoVendedor",
            fnServerParams: function(aoData) {
                aoData.push({"name": "_f1", "value": _f1});                
                aoData.push({"name": "_f2", "value": _f2}); 
                aoData.push({"name": "_estadocb", "value": _cb});
            },
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.SAVEN+"gridSaldoVendedor_filter").find("input").attr("placeholder","Buscar por N° OS o vendedor").css("width","200px");
                simpleScript.enterSearch("#"+diccionario.tabs.SAVEN+"gridSaldoVendedor",oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.SAVEN,
                    typeElement: "button"
                });
                simpleScript.removeAttr.change({
                    container: "#widget_"+diccionario.tabs.SAVEN,
                    typeElement: "select"
                });
                $('#'+diccionario.tabs.SAVEN+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.SAVEN+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();
    };

    this.publico.getGridBoleta = function (){
         $('#'+diccionario.tabs.SAVEN+'gridPagoVendedor').dataTable({
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
                {sTitle: "N° Boleta", sWidth: "15%"},
                {sTitle: "Fecha", sWidth: "20%"},
                {sTitle: "Numero", sWidth: "10%"},
                {sTitle: "Serie", sWidth: "10%"},
                {sTitle: "Exonerado", sWidth: "5%", sClass: "center"},
                {sTitle: "Total", sWidth: "10%", sClass: "right"},
                {sTitle: "Retencion", sWidth: "10%", sClass: "right"},
                {sTitle: "T. Neto", sWidth: "10%", sClass: "right"},
                {sTitle: "Acciones", sWidth: "10%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[0, 'asc']],
            sScrollY: "150px",
            sAjaxSource: _private.config.modulo+'gridPagoVendedor',
            fnServerParams: function(aoData) {
                aoData.push({"name": "_idComision", "value": _private.idComision});
            }
        });
        setup_widgets_desktop();
    };    
        
    this.publico.getConsulta = function(btn, id, nom){
        _private.idComision = id;               
        simpleAjax.send({
            element : btn,
            gifProcess: true,
            dataType: 'html',
            root: _private.config.modulo + 'getConsulta',
            data: '&_idComision='+_private.idComision+'&_persona='+nom,
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.SAVEN+'formPagoVendedor').modal('show');
                setTimeout(function(){                    
                    saldoVendedor.getGridBoleta()
                }, 500);
                
            }
        });
    };   
    
    this.publico.postPDF = function(btn,idd,nb){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postPDF',
            data: '&_idBoleta='+idd+'&_numBoleta='+nb,
            fnCallback: function(data) {
                if(parseInt(data.result) === 1){
                    $('#'+diccionario.tabs.SAVEN+'btnDowPDF').attr("onclick","window.open('public/files/"+data.archivo+"','_blank');compromisoPagar.deleteArchivo('"+data.archivo+"');");
                    $('#'+diccionario.tabs.SAVEN+'btnDowPDF').click();
                }
            }
        });
    };
   
    
    return this.publico;
    
};
var saldoVendedor = new saldoVendedor_();