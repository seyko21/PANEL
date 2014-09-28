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
        var f1, f2;
        f1 = $.datepicker.parseDate('dd/mm/yy', _f1);
        f2 = $.datepicker.parseDate('dd/mm/yy', _f2);        
        if( f1 > f2 ){
           simpleScript.notify.warning({
                  content: 'La fecha inicio no puede ser mayor que la fecha final.'      
            });           
       }
        var oTable = $("#"+diccionario.tabs.PAGRE+"gridPagosRecibidos").dataTable({
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
                {sTitle: "Vendedor", sWidth: "25%"},
                {sTitle: "Fecha", sWidth: "15%",sClass: "center" },
                {sTitle: "Porcentaje", sWidth: "5%",sClass: "center" },
                {sTitle: "Comision", sWidth: "10%", sClass: "right"},             
                {sTitle: "Pagado", sWidth: "10%", sClass: "right"},             
                {sTitle: "Saldo", sWidth: "10%", sClass: "right"},
                {sTitle: "Acciones", sWidth: "8%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[1, "asc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridPagosRecibidos",
             fnServerParams: function(aoData) {
                aoData.push({"name": "_f1", "value": _f1});                
                aoData.push({"name": "_f2", "value": _f2}); 
            },            
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.PAGRE+"gridPagosRecibidos_filter").find("input").attr("placeholder","Buscar por N° OS").css("width","350px");
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.PAGRE,
                    typeElement: "button"
                });
            }
        });
        setup_widgets_desktop();
    };

    
    this.publico.getFormEditPagosRecibidos = function(btn,id){
        _private.idPagosRecibidos = id;
            
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormEditPagosRecibidos",
            data: "&_idPagosRecibidos="+_private.idPagosRecibidos,
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.PAGRE+"formEditPagosRecibidos").modal("show");
            }
        });
    };
    
 
    
    return this.publico;
    
};
var pagosRecibidos = new pagosRecibidos_();