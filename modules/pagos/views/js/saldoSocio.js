/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 26-09-2014 23:09:38 
* Descripcion : saldoSocio.js
* ---------------------------------------
*/
var saldoSocio_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idSaldoSocio = 0;
    
    _private.config = {
        modulo: "pagos/saldoSocio/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : SaldoSocio*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.SASOC,
            label: $(element).attr("title"),
            fnCallback: function(){
                saldoSocio.getContenido();
            }
        });
    };
    
    /*contenido de tab: SaldoSocio*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.SASOC+"_CONTAINER").html(data);
                saldoSocio.getGridSaldoSocio();
            }
        });
    };
    
    this.publico.getGridSaldoSocio = function (){
        
        var _cb = $("#"+diccionario.tabs.SASOC+"lst_estadosearch").val();
        if (_cb == 'T'){
            $("#"+diccionario.tabs.SASOC+"txt_f1").prop('disabled',true);
            $("#"+diccionario.tabs.SASOC+"txt_f2").prop('disabled',true);
        }else{                
            $("#"+diccionario.tabs.SASOC+"txt_f1").prop('disabled',false);
            $("#"+diccionario.tabs.SASOC+"txt_f2").prop('disabled',false);
            var _f1 = $("#"+diccionario.tabs.SASOC+"txt_f1").val();
            var _f2 = $("#"+diccionario.tabs.SASOC+"txt_f2").val();        
            var f1, f2;
            f1 = $.datepicker.parseDate('dd/mm/yy', _f1);
            f2 = $.datepicker.parseDate('dd/mm/yy', _f2);        
            if( f1 > f2 ){
               simpleScript.notify.warning({
                      content: 'La fecha inicio no puede ser mayor que la fecha final.'      
                });           
           }        
        }
        var oTable = $("#"+diccionario.tabs.SASOC+"gridSaldoSocio").dataTable({
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
                {sTitle: "Socio", sWidth: "30%"},
                {sTitle: "Fecha", sWidth: "15%",sClass: "center" },
                {sTitle: "Porcentaje", sWidth: "5%",sClass: "center" },
                {sTitle: "Comision", sWidth: "10%", sClass: "right"},             
                {sTitle: "Pagado", sWidth: "10%", sClass: "right"},             
                {sTitle: "Saldo", sWidth: "10%", sClass: "right"},             
                {sTitle: "Acciones", sWidth: "5%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[2, "asc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridSaldoSocio",
            fnServerParams: function(aoData){
                aoData.push({"name": "_f1", "value": _f1});                
                aoData.push({"name": "_f2", "value": _f2}); 
                aoData.push({"name": "_estadocb", "value": _cb});
            },
            fnDrawCallback: function(){
                $("#"+diccionario.tabs.SASOC+"gridSaldoSocio_filter").find("input").attr("placeholder","Buscar por N° OS o Socio").css("width","200px");
                simpleScript.enterSearch("#"+diccionario.tabs.SASOC+"gridSaldoSocio",oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.SASOC,
                    typeElement: "button"
                });
                simpleScript.removeAttr.change({
                    container: "#widget_"+diccionario.tabs.SASOC,
                    typeElement: "select"
                });
            }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getFormEditSaldoSocio = function(btn,id){
        _private.idSaldoSocio = id;
            
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormEditSaldoSocio",
            data: "&_idSaldoSocio="+_private.idSaldoSocio,
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.SASOC+"formEditSaldoSocio").modal("show");
            }
        });
    };       
    
    return this.publico;
    
};
var saldoSocio = new saldoSocio_();