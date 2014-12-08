/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 06-12-2014 23:12:35 
* Descripcion : cajaCierre.js
* ---------------------------------------
*/
var cajaCierre_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idCajaCierre = 0;
    
    _private.config = {
        modulo: "ventas/cajaCierre/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : CajaCierre*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.CAJAC,
            label: $(element).attr("title"),
            fnCallback: function(){
                cajaCierre.getContenido();
            }
        });
    };
    
    /*contenido de tab: CajaCierre*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.CAJAC+"_CONTAINER").html(data);
                cajaCierre.getGridCajaCierre();
            }
        });
    };
    
    this.publico.getGridCajaCierre = function (){
        var oTable = $("#"+diccionario.tabs.CAJAC+"gridCajaCierre").dataTable({
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
                {sTitle: "Fecha / Hora", sWidth: "15%"},
                {sTitle: "Moneda", sWidth: "8%", sClass: "center"},
                {sTitle: "Inicial", sWidth: "10%", sClass: "right"},
                {sTitle: "Ingresos", sWidth: "10%", sClass: "right"},
                {sTitle: "Egresos", sWidth: "10%", sClass: "right"},
                {sTitle: "Saldo", sWidth: "10%", sClass: "right"},
                {sTitle: "Estado", sWidth: "10%", sClass: "center"}           
            ],
            aaSorting: [[0, "desc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridCajaCierre",
            fnServerParams: function(aoData) {
                aoData.push({"name": "_fecha1", "value": $("#"+diccionario.tabs.CAJAC+"txt_f1").val()});   
                aoData.push({"name": "_fecha2", "value": $("#"+diccionario.tabs.CAJAC+"txt_f2").val()});
            },
            fnDrawCallback: function() {
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.CAJAC,
                    typeElement: "button"
                });
                $('#'+diccionario.tabs.CAJAC+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.CAJAC+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();
    };
    
     this.publico.postGenerarCierre = function(){
        simpleScript.notify.confirm({
            content: mensajes.MSG_21,
            callbackSI: function(){
                simpleAjax.send({
                    flag: 1,
                    gifProcess: true,
                    root: _private.config.modulo + 'postGenerarCierre',
                    fnCallback: function(data) {
                        if(!isNaN(data.result) && parseInt(data.result) === 1){
                            simpleScript.notify.ok({
                                content: mensajes.MSG_20,
                                callback: function(){          
                                    if($('#'+diccionario.tabs.VGEVE+'_CONTAINER').length > 0)
                                        simpleScript.closeTab(diccionario.tabs.VGEVE);
                                    if($('#'+diccionario.tabs.VGEVE+'cotizacion_CONTAINER').length > 0)
                                        simpleScript.closeTab(diccionario.tabs.VGEVE+'cotizacion');
                                    if($('#'+diccionario.tabs.VGEVE+'_CONTAINER').length > 0)
                                        simpleScript.closeTab(diccionario.tabs.VGEVE);
                                    if($('#'+diccionario.tabs.VGEVE+'new_CONTAINER').length > 0)
                                        simpleScript.closeTab(diccionario.tabs.VGEVE+'new');
                                    if($('#'+diccionario.tabs.VGEVE+'edit_CONTAINER').length > 0)
                                        simpleScript.closeTab(diccionario.tabs.VGEVE+'edit');
                                    if($('#'+diccionario.tabs.VSEVE+'_CONTAINER').length > 0)
                                        simpleScript.closeTab(diccionario.tabs.VSEVE);                                        
                                    if($('#'+diccionario.tabs.VEGRE+'_CONTAINER').length > 0)
                                        simpleScript.closeTab(diccionario.tabs.VEGRE);                                                                            
                                    cajaCierre.getGridCajaCierre();                                  
                                }
                            });
                       }
                    }
                });
            }
        });
    };
    
  this.publico.postGenerarReajuste = function(){
        simpleScript.notify.confirm({
            content: mensajes.MSG_21,
            callbackSI: function(){
                simpleAjax.send({
                    flag: 1,
                    gifProcess: true,
                    root: _private.config.modulo + 'postGenerarReajuste',
                    fnCallback: function(data) {
                        if(!isNaN(data.result) && parseInt(data.result) === 1){
                            simpleScript.notify.ok({
                                content: mensajes.MSG_20,
                                callback: function(){      
                                    if($('#'+diccionario.tabs.VCOTI+'_CONTAINER').length > 0)
                                        simpleScript.closeTab(diccionario.tabs.VCOTI);
                                    if($('#'+diccionario.tabs.VCOTI+'edit_CONTAINER').length > 0)
                                        simpleScript.closeTab(diccionario.tabs.VCOTI+'edit');                                    
                                    if($('#'+diccionario.tabs.VGEVE+'_CONTAINER').length > 0)
                                        simpleScript.closeTab(diccionario.tabs.VGEVE);
                                    if($('#'+diccionario.tabs.VGEVE+'new_CONTAINER').length > 0)
                                        simpleScript.closeTab(diccionario.tabs.VGEVE+'new');
                                    if($('#'+diccionario.tabs.VGEVE+'edit_CONTAINER').length > 0)
                                        simpleScript.closeTab(diccionario.tabs.VGEVE+'edit');
                                    if($('#'+diccionario.tabs.VSEVE+'_CONTAINER').length > 0)
                                        simpleScript.closeTab(diccionario.tabs.VSEVE);                                        
                                    if($('#'+diccionario.tabs.VEGRE+'_CONTAINER').length > 0)
                                        simpleScript.closeTab(diccionario.tabs.VEGRE);                                                                            
                                    cajaCierre.getGridCajaCierre();                                  
                                }
                            });
                       } //2...
                    }
                });
            }
        });
    };    
    
    
    return this.publico;
    
};
var cajaCierre = new cajaCierre_();