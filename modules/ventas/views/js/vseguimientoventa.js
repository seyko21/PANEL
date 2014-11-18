/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 18-11-2014 17:11:41 
* Descripcion : vseguimientoventa.js
* ---------------------------------------
*/
var vseguimientoventa_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idVseguimientoventa = 0;
    _private.saldo = 0;
     
    _private.config = {
        modulo: "ventas/vseguimientoventa/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : Vseguimientoventa*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.VSEVE,
            label: $(element).attr("title"),
            fnCallback: function(){
                vseguimientoventa.getContenido();
            }
        });
    };
    
    /*contenido de tab: Vseguimientoventa*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.VSEVE+"_CONTAINER").html(data);
                vseguimientoventa.getGridVseguimientoventa();
            }
        });
    };
    
    this.publico.getGridVseguimientoventa = function (){
        var oTable = $("#"+diccionario.tabs.VSEVE+"gridVseguimientoventa").dataTable({
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
                {sTitle: "Fecha", sWidth: "10%",  sClass: "center"},
                {sTitle: "Moneda", sWidth: "7%"},                                
                {sTitle: "Total", sWidth: "11%",  sClass: "right"},  
                {sTitle: "Saldo", sWidth: "11%",  sClass: "right"},
                {sTitle: "Estado", sWidth: "8%",  sClass: "center"},
                {sTitle: "Acciones", sWidth: "10%", sClass: "center", bSortable: false}             
            ],
            aaSorting: [[3, "desc"],[1, "desc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridVseguimientoventa",
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.VSEVE+"gridVseguimientoventa_filter").find("input").attr("placeholder","Buscar por Cliente").css("width","350px");
                simpleScript.enterSearch("#"+diccionario.tabs.VSEVE+"gridVseguimientoventa",oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.VSEVE,
                    typeElement: "button"
                });
                $('#'+diccionario.tabs.VSEVE+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                });
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.VSEVE+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();
    };
    
  
    
    this.publico.getFormPagarVenta = function(btn,id,s){
        _private.idVseguimientoventa = id;
        _private.saldo = s;    
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormPagarVenta",
            data: "&_idVseguimientoventa="+_private.idVseguimientoventa+'&_saldo='+_private.saldo,
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.VSEVE+"formPagarVenta").modal("show");
            }
        });
    };    
    
    this.publico.postEditVseguimientoventa = function(){
        simpleAjax.send({
            element: "#"+diccionario.tabs.VSEVE+"btnEdVseguimientoventa",
            root: _private.config.modulo + "postEditVseguimientoventa",
            form: "#"+diccionario.tabs.VSEVE+"formEditVseguimientoventa",
            data: "&_idVseguimientoventa="+_private.idVseguimientoventa,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_10,
                        callback: function(){
                            _private.idVseguimientoventa = 0;
                            simpleScript.closeModal("#"+diccionario.tabs.VSEVE+"formEditVseguimientoventa");
                            simpleScript.reloadGrid("#"+diccionario.tabs.VSEVE+"gridVseguimientoventa");
                        }
                    });
                }else if(!isNaN(data.result) && parseInt(data.result) === 2){
                    simpleScript.notify.error({
                        content: "Vseguimientoventa ya existe."
                    });
                }
            }
        });
    };
       
    
    return this.publico;
    
};
var vseguimientoventa = new vseguimientoventa_();