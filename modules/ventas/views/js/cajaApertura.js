/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 06-12-2014 21:12:27 
* Descripcion : cajaApertura.js
* ---------------------------------------
*/
var cajaApertura_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idCajaApertura = 0;
    
    _private.config = {
        modulo: "ventas/cajaApertura/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : CajaApertura*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.CAJAA,
            label: $(element).attr("title"),
            fnCallback: function(){
                cajaApertura.getContenido();
            }
        });
    };
    
    /*contenido de tab: CajaApertura*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.CAJAA+"_CONTAINER").html(data);
                cajaApertura.getGridCajaApertura();
            }
        });
    };
    
    this.publico.getGridCajaApertura = function (){
        var oTable = $("#"+diccionario.tabs.CAJAA+"gridCajaApertura").dataTable({
            bFilter:false,
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "N°", sWidth: "1%",bSortable: false},
                {sTitle: "Hora", sWidth: "10%"},
                {sTitle: "Moneda", sWidth: "8%", sClass: "center"},
                {sTitle: "Inicial", sWidth: "10%", sClass: "right"},
                {sTitle: "Ingresos", sWidth: "10%", sClass: "right"},
                {sTitle: "Egresos", sWidth: "10%", sClass: "right"},
                {sTitle: "Saldo", sWidth: "10%", sClass: "right"},
                {sTitle: "Estado", sWidth: "10%", sClass: "center"},
                {sTitle: "Acciones", sWidth: "8%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[1, "asc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridCajaApertura",
            fnServerParams: function(aoData) {
                aoData.push({"name": "_fecha", "value": $("#"+diccionario.tabs.CAJAA+"txt_fechaGrid").val()});                
            },
            fnDrawCallback: function() {                                
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.CAJAA,
                    typeElement: "button"
                });
                $('#'+diccionario.tabs.CAJAA+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.CAJAA+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getFormEditCajaApertura = function(btn,id,mon){
        _private.idCajaApertura = id;
            
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormEditCajaApertura",
            data: "&_idCajaApertura="+_private.idCajaApertura+'&_moneda='+mon,
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.CAJAA+"formEditCajaApertura").modal("show");
            }
        });
    };
    
    this.publico.postGenerarApertura = function(){
        simpleScript.notify.confirm({
            content: mensajes.MSG_21,
            callbackSI: function(){
                simpleAjax.send({
                    flag: 1,
                    gifProcess: true,
                    root: _private.config.modulo + 'postGenerarApertura',
                    fnCallback: function(data) {
                        if(!isNaN(data.result) && parseInt(data.result) === 1){
                            simpleScript.notify.ok({
                                content: mensajes.MSG_20,
                                callback: function(){                                  
                                        cajaApertura.getGridCajaApertura();                                  
                                }
                            });
                       }else if(!isNaN(data.result) && parseInt(data.result) === 2){
                            simpleScript.notify.warning({
                                content: "La Caja ya fue aperturada para la fecha: "+ $("#"+diccionario.tabs.CAJAA+"txt_fechaGrid").val()
                            });
                        }
                    }
                });
            }
        });
    };
    
    this.publico.postEditCajaApertura = function(){
        simpleAjax.send({
            element: "#"+diccionario.tabs.CAJAA+"btnEdCajaApertura",
            root: _private.config.modulo + "postEditCajaApertura",
            form: "#"+diccionario.tabs.CAJAA+"formEditCajaApertura",
            data: "&_idCajaApertura="+_private.idCajaApertura,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_19,
                        callback: function(){
                            _private.idCajaApertura = 0;
                            simpleScript.closeModal("#"+diccionario.tabs.CAJAA+"formEditCajaApertura");
                            simpleScript.reloadGrid("#"+diccionario.tabs.CAJAA+"gridCajaApertura");
                        }
                    });
                }
            }
        });
    };        
    
    return this.publico;
    
};
var cajaApertura = new cajaApertura_();