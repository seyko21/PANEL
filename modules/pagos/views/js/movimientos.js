/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 27-09-2014 05:09:34 
* Descripcion : movimientos.js
* ---------------------------------------
*/
var movimientos_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idMovimientos = 0;
    
    _private.config = {
        modulo: "pagos/movimientos/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : Movimientos*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.MOVIM,
            label: $(element).attr("title"),
            fnCallback: function(){
                movimientos.getContenido();
            }
        });
    };
    
    /*contenido de tab: Movimientos*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.MOVIM+"_CONTAINER").html(data);
                movimientos.getGridMovimientos();
            }
        });
    };
    
    this.publico.getGridMovimientos = function (){
        
        var _f1 = $("#"+diccionario.tabs.MOVIM+"txt_f1").val();
        var _f2 = $("#"+diccionario.tabs.MOVIM+"txt_f2").val();        
    
        var oTable = $("#"+diccionario.tabs.MOVIM+"gridMovimientos").dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "ID", sWidth: "2%",bSortable: false},
                {sTitle: "Fecha", sWidth: "10%", sClass: "center"},
                {sTitle: "N° OS", sWidth: "10%", sClass: "center"},
                {sTitle: "Código", sWidth: "10%"},                
                {sTitle: "Tipo", sWidth: "10%"},                
                {sTitle: "Moneda", sWidth: "5%"},
                {sTitle: "Monto", sWidth: "10%",sClass: "right"},
                {sTitle: "Estado", sWidth: "5%", sClass: "center"},
                {sTitle: "Observación", sWidth: "30%"}
            ],
            aaSorting: [[1, "desc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridMovimientos",
            fnServerParams: function(aoData) {
                aoData.push({"name": "_f1", "value": _f1});                
                aoData.push({"name": "_f2", "value": _f2});
                aoData.push({"name": "_tipocb", "value": $("#"+diccionario.tabs.MOVIM+"lst_tiposearch").val()}); 
            },
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.MOVIM+"gridMovimientos_filter").find("input").attr("placeholder","Buscar por N° OS").css("width","200px");
                simpleScript.enterSearch("#"+diccionario.tabs.MOVIM+"gridMovimientos",oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.MOVIM,
                    typeElement: "button"
                });
                simpleScript.removeAttr.change({
                    container: "#widget_"+diccionario.tabs.COPAG,
                    typeElement: "select"
                });
                $('#'+diccionario.tabs.COPAG+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.COPAG+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getFormEditMovimientos = function(btn,id){
        _private.idMovimientos = id;
            
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormEditMovimientos",
            data: "&_idMovimientos="+_private.idMovimientos,
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.MOVIM+"formEditMovimientos").modal("show");
            }
        });
    };
    
  
    
    return this.publico;
    
};
var movimientos = new movimientos_();