/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 26-09-2014 15:09:21 
* Descripcion : saldoCliente.js
* ---------------------------------------
*/
var saldoCliente_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idCompromiso = 0;
    
    _private.config = {
        modulo: "pagos/saldoCliente/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : SaldoCliente*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.SACLI,
            label: $(element).attr("title"),
            fnCallback: function(){
                saldoCliente.getContenido();
            }
        });
    };
    
    /*contenido de tab: SaldoCliente*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.SACLI+"_CONTAINER").html(data);
                saldoCliente.getGridSaldoCliente();
            }
        });
    };
    
    this.publico.getGridSaldoCliente = function (){
        var oTable = $("#"+diccionario.tabs.SACLI+"gridSaldoCliente").dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "N° OS", sWidth: "8%"},
                {sTitle: "Cuota", sWidth: "8%", sClass: "center"},
                {sTitle: "Fecha Programada", sWidth: "9%", sClass: "center"},
                {sTitle: "Cliente", sWidth: "25%"},
                {sTitle: "Mora", sWidth: "7%", sClass: "right"},
                {sTitle: "Monto", sWidth: "10%", sClass: "right"},
                {sTitle: "Fecha Pago", sWidth: "9%", sClass: "center"},
                {sTitle: "Estado", sWidth: "8%", sClass: "center"},
                {sTitle: "Acciones", sWidth: "8%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[2, "asc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridSaldoCliente",
            fnServerParams: function(aoData) {
                aoData.push({"name": "_estadocb", "value": $("#"+diccionario.tabs.SACLI+"lst_estadosearch").val()});
            },
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.SACLI+"gridSaldoCliente_filter").find("input").attr("placeholder","Buscar por Cliente o Representante").css("width","350px");
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.SACLI,
                    typeElement: "button"
                });
                simpleScript.removeAttr.change({
                    container: "#widget_"+diccionario.tabs.SACLI,
                    typeElement: "select"
                });
            }
        });
        setup_widgets_desktop();
    };
    this.publico.postPDF = function(btn,idd){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postPDF',
            data: '&_idCompromiso='+idd,
            fnCallback: function(data) {
                if(parseInt(data.result) === 1){
                    $('#'+diccionario.tabs.SACLI+'btnDowPDF').attr("onclick","window.open('public/files/"+data.archivo+"','_blank');compromisoPagar.deleteArchivo('"+data.archivo+"');");
                    $('#'+diccionario.tabs.SACLI+'btnDowPDF').click();
                }
            }
        });
    };
    
    this.publico.postExcel = function(btn,idd){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postExcel',
            data: '&_idCompromiso='+idd,
            fnCallback: function(data) {
                if(parseInt(data.result) === 1){
                    $('#'+diccionario.tabs.SACLI+'btnDowExcel').attr("onclick","window.open('public/files/"+data.archivo+"','_self');compromisoPagar.deleteArchivo('"+data.archivo+"');");
                    $('#'+diccionario.tabs.SACLI+'btnDowExcel').click();
                }
                if(!isNaN(data.result) && parseInt(data.result) === 2){
                    simpleScript.notify.error({
                        content: 'Ocurrió un error al exportar.'
                    });
                }
            }
        });
    };
    
    
    return this.publico;
    
};
var saldoCliente = new saldoCliente_();