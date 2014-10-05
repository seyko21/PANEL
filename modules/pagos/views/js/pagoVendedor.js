var pagoVendedor_ = function(){
    
    var _private = {};
    
    _private.config = {
        modulo: "pagos/pagoVendedor/"
    };
    
    _private.idComision = 0;
    
    this.publico = {};
    
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.GPAVE,
            label: $(element).attr("title"),
            fnCallback: function(){
                pagoVendedor.getContenido();
            }
        });
    };
    
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.GPAVE+"_CONTAINER").html(data);
                pagoVendedor.getGridPagosVendedor();
            }
        });
    };
    
    this.publico.getGridPagosVendedor = function(){
        var _f1 = $("#"+diccionario.tabs.GPAVE+"txt_f1").val();
        var _f2 = $("#"+diccionario.tabs.GPAVE+"txt_f2").val();        

        var oTable = $("#"+diccionario.tabs.GPAVE+"gridPagosVendedor").dataTable({
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
                {sTitle: "Vendedor", sWidth: "28%"},
                {sTitle: "Fecha", sWidth: "15%",sClass: "center" },
                {sTitle: "Porcentaje", sWidth: "5%",sClass: "center" },
                {sTitle: "Comision", sWidth: "10%", sClass: "right"},             
                {sTitle: "Pagado", sWidth: "10%", sClass: "right"},             
                {sTitle: "Saldo", sWidth: "10%", sClass: "right"},             
                {sTitle: "Acciones", sWidth: "8%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[0, "asc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridPagosVendedor",
            fnServerParams: function(aoData) {
                aoData.push({"name": "_f1", "value": _f1});                
                aoData.push({"name": "_f2", "value": _f2}); 
            },
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.GPAVE+"gridPagosVendedor_filter").find("input").attr("placeholder","Buscar por N° OS o vendedor").css("width","200px");
                simpleScript.enterSearch("#"+diccionario.tabs.GPAVE+"gridPagosVendedor",oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.GPAVE,
                    typeElement: "button, select"
                });
            }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getFormPagar = function(btn,id,vendedor,saldo,persona){
        _private.idComision = id;               
        simpleAjax.send({
            element : btn,
            gifProcess: true,
            dataType: 'html',
            root: _private.config.modulo + 'getFormPagar',
            data: '&_idComision='+_private.idComision+'&_vendedor='+vendedor+'&_saldo='+saldo+'&_idPersona='+persona,
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.GPAVE+'formPagarVendedor').modal('show');
            }
        });
    };
    
    this.publico.postPagoVendedor = function(){
        simpleAjax.send({
            element: "#"+diccionario.tabs.GPAVE+"btnGrPag",
            root: _private.config.modulo + "postPagoVendedor",
            form: "#"+diccionario.tabs.GPAVE+"formPagarVendedor",
            data: "&_idComision="+_private.idComision,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            pagoVendedor.getGridPagosVendedor();
                            simpleScript.closeModal('#'+diccionario.tabs.GPAVE+'formPagarVendedor');
                            _private.idComision = 0;
                        }
                    });
                }
            }
        });
    };
    
    return this.publico;
    
};
var pagoVendedor = new pagoVendedor_();