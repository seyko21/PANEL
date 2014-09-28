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
    
    _private.idSaldoVendedor = 0;
    
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
            var f1, f2;
            f1 = $.datepicker.parseDate('dd/mm/yy', _f1);
            f2 = $.datepicker.parseDate('dd/mm/yy', _f2);        
            if( f1 > f2 ){
               simpleScript.notify.warning({
                      content: 'La fecha inicio no puede ser mayor que la fecha final.'      
                });           
           }        
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
                {sTitle: "N°", sWidth: "5%", bSortable: false},
                {sTitle: "N° OS", sWidth: "10%"},
                {sTitle: "Vendedor", sWidth: "30%"},
                {sTitle: "Fecha", sWidth: "15%",sClass: "center" },
                {sTitle: "Porcentaje", sWidth: "5%",sClass: "center" },
                {sTitle: "Comision", sWidth: "10%", sClass: "right"},             
                {sTitle: "Pagado", sWidth: "10%", sClass: "right"},             
                {sTitle: "Saldo", sWidth: "10%", sClass: "right"},             
                {sTitle: "Acciones", sWidth: "5%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[0, "asc"]],
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
            }
        });
        setup_widgets_desktop();
    };
        
    this.publico.getFormEditSaldoVendedor = function(btn,id){
        _private.idSaldoVendedor = id;
            
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormEditSaldoVendedor",
            data: "&_idSaldoVendedor="+_private.idSaldoVendedor,
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.SAVEN+"formEditSaldoVendedor").modal("show");
            }
        });
    };
    
   
    
    return this.publico;
    
};
var saldoVendedor = new saldoVendedor_();