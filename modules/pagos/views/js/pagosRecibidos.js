/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 28-09-2014 00:09:34 
* Descripcion : pagosRecibidos.js
* ---------------------------------------
*/
var pagosRecibidos_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idPagosRecibidos = 0;
    
    _private.config = {
        modulo: "pagos/pagosRecibidos/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : PagosRecibidos*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.PAGRE,
            label: $(element).attr("title"),
            fnCallback: function(){
                pagosRecibidos.getContenido();
            }
        });
    };
    
    /*contenido de tab: PagosRecibidos*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.PAGRE+"_CONTAINER").html(data);
                pagosRecibidos.getGridPagosRecibidos();
            }
        });
    };
    
    this.publico.getGridPagosRecibidos = function (){
        var _f1 = $("#"+diccionario.tabs.PAGRE+"txt_f1").val();
        var _f2 = $("#"+diccionario.tabs.PAGRE+"txt_f2").val();        
     
        var oTable = $("#"+diccionario.tabs.PAGRE+"gridPagosRecibidos").dataTable({
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
                {sTitle: "Saldo", sWidth: "10%", sClass: "right"},
                {sTitle: "Acciones", sWidth: "8%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[1, "asc"],[0, "asc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridPagosRecibidos",
             fnServerParams: function(aoData) {
                aoData.push({"name": "_f1", "value": _f1});                
                aoData.push({"name": "_f2", "value": _f2}); 
            },            
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.PAGRE+"gridPagosRecibidos_filter").find("input").attr("placeholder","Buscar por N° OS").css("width","350px");
                simpleScript.enterSearch("#"+diccionario.tabs.PAGRE+"gridPagosRecibidos",oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.PAGRE,
                    typeElement: "button"
                });
              $('#'+diccionario.tabs.PAGRE+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.PAGRE+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
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
                    pagosRecibidos.getGridBoleta()
                }, 500);
                
            }
        });
    };   
    
 
    
    return this.publico;
    
};
var pagosRecibidos = new pagosRecibidos_();