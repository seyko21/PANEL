/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 17-11-2014 17:11:18 
* Descripcion : vcliente.js
* ---------------------------------------
*/
var vcliente_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idVcliente = 0;
    _private.callbackData = null;
    
    _private.config = {
        modulo: "ventas/vcliente/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : Vcliente*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.VRECL,
            label: $(element).attr("title"),
            fnCallback: function(){
                vcliente.getContenido();
            }
        });
    };
    
    /*contenido de tab: Vcliente*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.VRECL+"_CONTAINER").html(data);
                vcliente.getGridVcliente();
            }
        });
    };
    
    this.publico.getGridVcliente = function (){
        var oTable = $("#"+diccionario.tabs.VRECL+"gridVcliente").dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "<input type='checkbox' id='"+diccionario.tabs.VRECL+"chk_all' onclick='simpleScript.checkAll(this,\"#"+diccionario.tabs.VRECL+"gridVcliente\");'>", sWidth: "1%", sClass: "center", bSortable: false},                        
                {sTitle: "Cliente", sWidth: "30%"},
                {sTitle: "Tipo", sWidth: "15%"},
                {sTitle: "Telefonos", sWidth: "15%"},
                {sTitle: "N° Documento", sWidth: "15%"},
                {sTitle: "Ciudad", sWidth: "20%"},
                {sTitle: "Estado", sWidth: "10%", sClass: "center", bSortable: false},
                {sTitle: "Acciones", sWidth: "8%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[1, "asc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridVcliente",
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.VRECL+"gridVcliente_filter").find("input").attr("placeholder","Buscar por Nombre o Razon Social").css("width","300px");
                simpleScript.enterSearch("#"+diccionario.tabs.VRECL+"gridVcliente",oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.VRECL,
                    typeElement: "button"
                });
                $('#'+diccionario.tabs.VRECL+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.VRECL+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();                      
    };
           
    this.publico.getFormNewVcliente = function(btn,callbackData){
        _private.callbackData = callbackData;   
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormNewVcliente",
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.VRECL+"formNewVcliente").modal("show");
            }
        });
    };
    
    this.publico.getFormEditVcliente = function(btn,id){
        _private.idVcliente = id;
            
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormEditVcliente",
            data: "&_idVcliente="+_private.idVcliente,
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.VRECL+"formEditVcliente").modal("show");
            }
        });
    };
    
    this.publico.postNewVcliente = function(){
        simpleAjax.send({
            element: "#"+diccionario.tabs.VRECL+"btnGrVcliente",
            root: _private.config.modulo + "postNewVcliente",
            form: "#"+diccionario.tabs.VRECL+"formNewVcliente",
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            
                            if(_private.callbackData.length > 0){       
                                 if($('#'+diccionario.tabs.VRECL+'_CONTAINER').length > 0){
                                    $("#"+diccionario.tabs.VGEVE+"txt_idpersona").val(simpleAjax.stringPost(data.idPersona));       
                                    $("#"+diccionario.tabs.VGEVE+"txt_cliente").val(data.nombre); 
                                }
                                if($('#'+diccionario.tabs.VCOTI+'_CONTAINER').length > 0){
                                    $("#"+diccionario.tabs.VCOTI+"txt_idpersona").val(simpleAjax.stringPost(data.idPersona));       
                                    $("#"+diccionario.tabs.VCOTI+"txt_cliente").val(data.nombre); 
                                }
                            }
                            /*se verifica si existe tabb para recargar grid*/
                            if($('#'+diccionario.tabs.VRECL+'_CONTAINER').length > 0){
                               vcliente.getGridVcliente();
                            }                                                        
                            simpleScript.closeModal("#"+diccionario.tabs.VRECL+"formNewVcliente");
                        }
                    });
                }else if(!isNaN(data.result) && parseInt(data.result) === 2){
                    simpleScript.notify.error({
                        content: "Cliente ya existe."
                    });
                }
            }
        });
    };
    
    this.publico.postEditVcliente = function(){
        simpleAjax.send({
            element: "#"+diccionario.tabs.VRECL+"btnEdVcliente",
            root: _private.config.modulo + "postEditVcliente",
            form: "#"+diccionario.tabs.VRECL+"formEditVcliente",
            data: "&_idVcliente="+_private.idVcliente,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_19,
                        callback: function(){
                            _private.idVcliente = 0;
                            simpleScript.closeModal("#"+diccionario.tabs.VRECL+"formEditVcliente");
                            simpleScript.reloadGrid("#"+diccionario.tabs.VRECL+"gridVcliente");
                        }
                    });
                }else if(!isNaN(data.result) && parseInt(data.result) === 2){
                    simpleScript.notify.error({
                        content: "Cliente ya existe."
                    });
                }
            }
        });
    };
    
    this.publico.postDeleteVcliente = function(btn,id){
        simpleScript.notify.confirm({
            content: mensajes.MSG_5,
            callbackSI: function(){
                simpleAjax.send({                    
                    element: btn,
                    gifProcess: true,
                    data: "&_idVcliente="+id,
                    root: _private.config.modulo + "postDeleteVcliente",
                    fnCallback: function(data) {
                        if(!isNaN(data.result) && parseInt(data.result) === 1){
                            simpleScript.notify.ok({
                                content: mensajes.MSG_6,
                                callback: function(){
                                    vcliente.getGridVcliente();
                                }
                            });
                        }
                    }
                });
            }
        });
    };
    
    this.publico.postDeleteVclienteAll = function(btn){
        simpleScript.validaCheckBox({
            id: "#"+diccionario.tabs.VRECL+"gridVcliente",
            msn: mensajes.MSG_9,
            fnCallback: function(){
                simpleScript.notify.confirm({
                    content: mensajes.MSG_7,
                    callbackSI: function(){
                        simpleAjax.send({
                            flag: 3, //si se usa SP usar flag, sino se puede eliminar esta linea
                            element: btn,
                            form: "#"+diccionario.tabs.VRECL+"formGridVcliente",
                            root: _private.config.modulo + "postDeleteVclienteAll",
                            fnCallback: function(data) {
                                if(!isNaN(data.result) && parseInt(data.result) === 1){
                                    simpleScript.notify.ok({
                                        content: mensajes.MSG_8,
                                        callback: function(){
                                            vcliente.getGridVcliente();
                                        }
                                    });
                                }
                            }
                        });
                    }
                });
            }
        });
    };
    
    this.publico.postDesactivar = function(btn,id){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postDesactivar',
            data: '&_idVcliente='+id,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: 'Cliente se desactivo correctamente',
                        callback: function(){
                           simpleScript.reloadGrid('#'+diccionario.tabs.VRECL+'gridVcliente'); 
                        }
                    });
                }
            }
        });
    };
    
    this.publico.postActivar = function(btn,id){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postActivar',
            data: '&_idVcliente='+id,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: 'Cliente se activo correctamente',
                        callback: function(){
                            simpleScript.reloadGrid('#'+diccionario.tabs.VRECL+'gridVcliente'); 
                        }
                    });
                }
            }
        });
    };    
    
    return this.publico;
    
};
var vcliente = new vcliente_();