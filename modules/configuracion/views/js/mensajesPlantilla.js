/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 09-12-2014 17:12:42 
* Descripcion : mensajesPlantilla.js
* ---------------------------------------
*/
var mensajesPlantilla_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idMensajes = 0;
    
    _private.config = {
        modulo: "configuracion/mensajesPlantilla/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : Mensajes*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.PMSJ,
            label: $(element).attr("title"),
            fnCallback: function(){
                mensajesPlantilla.getContenido();
            }
        });
    };
    
    /*contenido de tab: Mensajes*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.PMSJ+"_CONTAINER").html(data);
                mensajesPlantilla.getGridMensajes();
            }
        });
    };
    
    this.publico.getGridMensajes = function (){
        var oTable = $("#"+diccionario.tabs.PMSJ+"gridMensajes").dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "<input type='checkbox' id='"+diccionario.tabs.PMSJ+"chk_all' onclick='simpleScript.checkAll(this,\"#"+diccionario.tabs.PMSJ+"gridMensajes\");'>", sWidth: "1%", sClass: "center", bSortable: false},
                {sTitle: "Asunto", sWidth: "35%"},
                {sTitle: "Alias", sWidth: "15%",},
                {sTitle: "Estado", sWidth: "10%", sClass: "center"},
                {sTitle: "Acciones", sWidth: "8%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[2, "asc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridMensajes",
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.PMSJ+"gridMensajes_filter").find("input").attr("placeholder","Buscar por Asunto o Alias").css("width","250px");
                simpleScript.enterSearch("#"+diccionario.tabs.PMSJ+"gridMensajes",oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.PMSJ,
                    typeElement: "button"
                });
                $('#'+diccionario.tabs.PMSJ+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.PMSJ+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();
    };
    
     this.publico.getFormNewMensajes = function(){        
        simpleScript.addTab({
            id : diccionario.tabs.PMSJ+'new',
            label: 'Nuevo Mensaje',
            fnCallback: function(){
                simpleAjax.send({
                    dataType: 'html',
                    root: _private.config.modulo+'getFormNewMensajes',
                    fnCallback: function(data){
                        $('#'+diccionario.tabs.PMSJ+'new_CONTAINER').html(data);
                    }
                });
            }
        });               
    };     
    
    this.publico.getFormEditMensajes = function(id){
        _private.idMensajes = id;
        
        simpleScript.addTab({
            id : diccionario.tabs.PMSJ+'edit',
            label: 'Editar Mensaje',
            fnCallback: function(){
                simpleAjax.send({
                    dataType: 'html',
                    root: _private.config.modulo+'getFormEditMensajes',
                     data: '&_idMensajes='+_private.idMensajes,
                    fnCallback: function(data){
                        $('#'+diccionario.tabs.PMSJ+'edit_CONTAINER').html(data);
                    }
                });
            }
        });                  
    };
    this.publico.postNewMensajes = function(){
        simpleAjax.send({
            element: "#"+diccionario.tabs.PMSJ+"btnGrMensajes",
            root: _private.config.modulo + "postNewMensajes",
            form: "#"+diccionario.tabs.PMSJ+"formNewMensajes",
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            simpleScript.closeTab(diccionario.tabs.PMSJ+'new');  
                            mensajePlantilla.getGridMensajes();                                                  
                        }
                    });
                }else if(!isNaN(data.result) && parseInt(data.result) === 2){
                    simpleScript.notify.error({
                        content: "Mensajes ya existe."
                    });
                }
            }
        });
    };
    
    this.publico.postEditMensajes = function(){
        simpleAjax.send({
            element: "#"+diccionario.tabs.PMSJ+"btnEdMensajes",
            root: _private.config.modulo + "postEditMensajes",
            form: "#"+diccionario.tabs.PMSJ+"formEditMensajes",
            data: "&_idMensajes="+_private.idMensajes,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_19,
                        callback: function(){

                            _private.idMensajes = 0;
                            simpleScript.closeTab(diccionario.tabs.PMSJ+'edit');  
                            simpleScript.reloadGrid("#"+diccionario.tabs.PMSJ+"gridMensajes");
                            
                        }
                    });
                }else if(!isNaN(data.result) && parseInt(data.result) === 2){
                    simpleScript.notify.error({
                        content: "Mensajes ya existe."
                    });
                }
            }
        });
    };
    
    this.publico.postDeleteMensajes = function(btn,id){
        simpleScript.notify.confirm({
            content: mensajes.MSG_5,
            callbackSI: function(){
                simpleAjax.send({                    
                    element: btn,
                    gifProcess: true,
                    data: "&_idMensajes="+id,
                    root: _private.config.modulo + "postDeleteMensajes",
                    fnCallback: function(data) {
                        if(!isNaN(data.result) && parseInt(data.result) === 1){
                            simpleScript.notify.ok({
                                content: mensajes.MSG_6,
                                callback: function(){
                                    simpleScript.reloadGrid("#"+diccionario.tabs.PMSJ+"gridMensajes");
                                }
                            });
                        }
                    }
                });
            }
        });
    };
    
    this.publico.postDeleteMensajesAll = function(btn){
        simpleScript.validaCheckBox({
            id: "#"+diccionario.tabs.PMSJ+"gridMensajes",
            msn: mensajes.MSG_9,
            fnCallback: function(){
                simpleScript.notify.confirm({
                    content: mensajes.MSG_7,
                    callbackSI: function(){
                        simpleAjax.send({
                            element: btn,
                            form: "#"+diccionario.tabs.PMSJ+"formGridMensajes",
                            root: _private.config.modulo + "postDeleteMensajesAll",
                            fnCallback: function(data) {
                                if(!isNaN(data.result) && parseInt(data.result) === 1){
                                    simpleScript.notify.ok({
                                        content: mensajes.MSG_8,
                                        callback: function(){
                                            mensajesPlantilla.getGridMensajes();
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
               data: '&_idMensajes='+id,
               fnCallback: function(data) {
                   if(!isNaN(data.result) && parseInt(data.result) === 1){
                       simpleScript.notify.ok({
                           content: 'Plantilla de Mensaje se desactivo correctamente',
                           callback: function(){
                               simpleScript.reloadGrid('#'+diccionario.tabs.PMSJ+'gridMensajes');
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
            data: '&_idMensajes='+id,
            fnCallback: function(data){
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: 'Plantilla de Mensaje se activo correctamente',
                        callback: function(){
                            simpleScript.reloadGrid('#'+diccionario.tabs.PMSJ+'gridMensajes');
                        }
                    });
                }
            }
        });
    };       
    
    return this.publico;
    
};
var mensajesPlantilla = new mensajesPlantilla_();