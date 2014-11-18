/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 12-09-2014 17:09:13 
* Descripcion : contrato.js
* ---------------------------------------
*/
var contrato_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idContrato = 0;
    
    _private.config = {
        modulo: "Configuracion/contrato/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : Contrato*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.CONTR,
            label: $(element).attr("title"),
            fnCallback: function(){
                contrato.getContenido();
            }
        });
    };
    
    /*contenido de tab: Contrato*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.CONTR+"_CONTAINER").html(data);
                contrato.getGridContrato();
            }
        });
    };
    
    this.publico.getGridContrato = function (){
        var oTable = $("#"+diccionario.tabs.CONTR+"gridContrato").dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "<input type='checkbox' id='"+diccionario.tabs.CONTR+"chk_all' onclick='simpleScript.checkAll(this,\"#"+diccionario.tabs.CONTR+"gridContrato\");'>", sWidth: "1%", sClass: "center", bSortable: false},
                {sTitle: "Nombre", sWidth: "40%"},
                {sTitle: "Fecha Creado", sClass: "center", sWidth: "15%"},
                {sTitle: "Visible", sWidth: "10%", sClass: "center", bSortable: false},    
                {sTitle: "Estado", sWidth: "10%", sClass: "center", bSortable: false},                
                {sTitle: "Acciones", sWidth: "15%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[2, "asc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridContrato",
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.CONTR+"gridContrato_filter").find("input").attr("placeholder","Buscar por nombre").css("width","300px");
                simpleScript.enterSearch("#"+diccionario.tabs.CONTR+"gridContrato",oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.CONTR,
                    typeElement: "button"
                });
                $('#'+diccionario.tabs.CONTR+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.CONTR+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getFormNewContrato = function(){        
        simpleScript.addTab({
            id : diccionario.tabs.CONTR+'new',
            label: 'Nuevo Contrato',
            fnCallback: function(){
                simpleAjax.send({
                    dataType: 'html',
                    root: _private.config.modulo+'getFormNewContrato',
                    fnCallback: function(data){
                        $('#'+diccionario.tabs.CONTR+'new_CONTAINER').html(data);
                    }
                });
            }
        });               
    };     
    
    this.publico.getFormEditContrato = function(id){
        _private.idContrato = id;
        
        simpleScript.addTab({
            id : diccionario.tabs.CONTR+'edit',
            label: 'Editar Contrato',
            fnCallback: function(){
                simpleAjax.send({
                    dataType: 'html',
                    root: _private.config.modulo+'getFormEditContrato',
                     data: '&_idContrato='+_private.idContrato,
                    fnCallback: function(data){
                        $('#'+diccionario.tabs.CONTR+'edit_CONTAINER').html(data);
                    }
                });
            }
        });                  
    };
    
    this.publico.getClonar = function(idd){
        //cerrar tab nuevo
        simpleScript.closeTab(diccionario.tabs.CONTR+'new');
        
        _private.idContrato = idd;
        
        simpleScript.addTab({
            id : diccionario.tabs.CONTR+'clon',
            label: 'Clonar Contrato',
            fnCallback: function(){
                 simpleAjax.send({
                    dataType: 'html',
                    root: _private.config.modulo+'getFormClonarContrato',
                    data: '&_idContrato='+_private.idContrato,
                    fnCallback: function(data){
                        $('#'+diccionario.tabs.CONTR+'clon_CONTAINER').html(data);
                    }
                });
            }
        });
    };
    
    this.publico.getFormAdjuntar = function(btn,id){
        simpleAjax.send({
            element: btn,
            dataType: 'html',
            gifProcess: true,
            data: '&_idContrato='+id,
            root: _private.config.modulo + 'getFormAdjuntar',
            fnCallback: function(data){
                $('#cont-modal').append(data);
                $('#'+diccionario.tabs.CONTR+'formAdjuntar').modal('show');
            }
        });
    };    
    
    this.publico.postNewContrato = function(){
                                
        simpleAjax.send({
            flag: 1,
            element: "#"+diccionario.tabs.CONTR+"btnGrContrato",
            root: _private.config.modulo + "postNewContrato",
            form: "#"+diccionario.tabs.CONTR+"formNewContrato",
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            simpleScript.closeTab(diccionario.tabs.CONTR+'new');  
                            contrato.getGridContrato();                                                        
                        }
                    });
                }else if(!isNaN(data.result) && parseInt(data.result) === 2){
                    simpleScript.notify.error({
                        content: mensajes.MSG_4
                    });
                }
            }
        });
    };
    
    this.publico.postEditContrato = function(){
        simpleAjax.send({
            flag: 2,
            element: "#"+diccionario.tabs.CONTR+"btnEdContrato",
            root: _private.config.modulo + "postEditContrato",
            form: "#"+diccionario.tabs.CONTR+"formEditContrato",
            data: "&_idContrato="+_private.idContrato,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            _private.idContrato = 0;
                            simpleScript.closeTab(diccionario.tabs.CONTR+'edit');  
                            simpleScript.reloadGrid("#"+diccionario.tabs.CONTR+"gridContrato");
                        }
                    });
                }else if(!isNaN(data.result) && parseInt(data.result) === 2){
                    simpleScript.notify.error({
                        content: mensajes.MSG_4
                    });
                }
            }
        });
    };
    
     this.publico.postClonContrato = function(){
                                
        simpleAjax.send({
            flag: 1,
            element: "#"+diccionario.tabs.CONTR+"btnCdContrato",
            root: _private.config.modulo + "postNewContrato",
            form: "#"+diccionario.tabs.CONTR+"formClonContrato",
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            simpleScript.closeTab(diccionario.tabs.CONTR+'clon');  
                            contrato.getGridContrato();                                                        
                        }
                    });
                }else if(!isNaN(data.result) && parseInt(data.result) === 2){
                    simpleScript.notify.error({
                        content: mensajes.MSG_4
                    });
                }
            }
        });
    };
    

    this.publico.postDeleteContratoAll = function(btn){
        simpleScript.validaCheckBox({
            id: "#"+diccionario.tabs.CONTR+"gridContrato",
            msn: mensajes.MSG_9,
            fnCallback: function(){
                simpleScript.notify.confirm({
                    content: mensajes.MSG_7,
                    callbackSI: function(){
                        simpleAjax.send({
                            flag: 3, //si se usa SP usar flag, sino se puede eliminar esta linea
                            element: btn,
                            form: "#"+diccionario.tabs.CONTR+"formGridContrato",
                            root: _private.config.modulo + "postDeleteContratoAll",
                            fnCallback: function(data) {
                                if(!isNaN(data.result) && parseInt(data.result) === 1){
                                    simpleScript.notify.ok({
                                        content: mensajes.MSG_8,
                                        callback: function(){
                                            contrato.getGridContrato();
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
               data: '&_idContrato='+id,
               clear: true,
               fnCallback: function(data) {
                   if(!isNaN(data.result) && parseInt(data.result) === 1){
                       simpleScript.notify.ok({
                           content: 'Contrato se desactivo correctamente',
                           callback: function(){
                               simpleScript.reloadGrid('#'+diccionario.tabs.CONTR+'gridContrato');
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
            data: '&_idContrato='+id,
            clear: true,
            fnCallback: function(data){
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: 'Contrato se activo correctamente',
                        callback: function(){
                            simpleScript.reloadGrid('#'+diccionario.tabs.CONTR+'gridContrato');
                        }
                    });
                }
            }
        });
    };   
    
    this.publico.postDesactivarVisible = function(btn,id){
           simpleAjax.send({
               element: btn,
               root: _private.config.modulo + 'postDesactivarVisible',
               data: '&_idContrato='+id,
               clear: true,
               fnCallback: function(data) {
                   if(!isNaN(data.result) && parseInt(data.result) === 1){
                       simpleScript.notify.ok({
                           content: 'Contrato se desactivo su Visibilidad correctamente',
                           callback: function(){
                               simpleScript.reloadGrid('#'+diccionario.tabs.CONTR+'gridContrato');
                           }
                       });
                   }
               }
           });
       };
    
    this.publico.postActivarVisible = function(btn,id){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postActivarVisible',
            data: '&_idContrato='+id,
            clear: true,
            fnCallback: function(data){
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: 'Contrato se activo su Visibilidad correctamente',
                        callback: function(){
                            simpleScript.reloadGrid('#'+diccionario.tabs.CONTR+'gridContrato');
                        }
                    });
                }
            }
        });
    };       
    
    this.publico.deleteAdjuntar = function(btn,id,img){
        simpleScript.notify.confirm({
            content: mensajes.MSG_7,
            callbackSI: function(){
                simpleAjax.send({
                    element: btn,
                    root: _private.config.modulo + 'deleteAdjuntar',
                    data: '&_idContrato='+id+'&_img='+img,
                    fnCallback: function(data) {
                        if(!isNaN(data.result) && parseInt(data.result) === 1){
                            simpleScript.notify.ok({
                                content: mensajes.MSG_8,
                                callback: function(){
                                    $('#'+diccionario.tabs.CONTR+'dow').attr('onclick','');
                                    $('#'+diccionario.tabs.CONTR+'dow').html(''); 
                                    $('#'+diccionario.tabs.CONTR+'btndow').css('display','none');
                                }
                            });
                        }
                    }
                });
            }
        });
    };
        
    return this.publico;
    
};
var contrato = new contrato_();