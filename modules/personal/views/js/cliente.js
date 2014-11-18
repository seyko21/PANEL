/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 21-08-2014 07:08:16 
* Descripcion : cliente.js
* ---------------------------------------
*/
var cliente_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idCliente = 0;
    _private.idRepresentante = 0; 
    _private.idAncestro = 0;
        
    _private.nameCliente = '';
    
    _private.config = {
        modulo: "personal/cliente/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : Cliente*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.REGCL,
            label: $(element).attr("title"),
            fnCallback: function(){
                cliente.getContenido();
            }
        });
    };
    
    /*contenido de tab: Cliente*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.REGCL+"_CONTAINER").html(data);
                cliente.getGridCliente();
            }
        });
    };
    
    this.publico.getGridCliente = function (){
        var oTable = $('#'+diccionario.tabs.REGCL+'gridCliente').dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,          
            aoColumns: [
                {sTitle: "<input type='checkbox' id='"+diccionario.tabs.REGCL+"chk_all' onclick='simpleScript.checkAll(this,\"#"+diccionario.tabs.REGCL+"gridCliente\");'>", sWidth: "1%", sClass: "center", bSortable: false},
                {sTitle: "Nro. RUC", sClass: "center", sWidth: "10%", bSortable: false},
                {sTitle: "Razón Social", sWidth: "35%"},
                {sTitle: "Estado", sWidth: "8%",  sClass: "center", bSortable: false},
                {sTitle: "Acciones", sWidth: "15%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[1, 'asc']],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+'getGridCliente',
            fnDrawCallback: function() {
                $('#'+diccionario.tabs.REGCL+'gridCliente_filter').find('input').attr('placeholder','Buscar por Razón Social').css('width','200px');        
                simpleScript.enterSearch("#"+diccionario.tabs.REGCL+'gridCliente',oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: '#widget_'+diccionario.tabs.REGCL, //widget del datagrid
                    typeElement: 'button, #'+diccionario.tabs.REGCL+'chk_all'
                });
                $('#'+diccionario.tabs.REGCL+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.REGCL+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();
    };       
    
    this.publico.getGridRepresentantes = function(id,cl){
        _private.idAncestro = id;
        _private.nameCliente = cl;
        
        var oTable = $('#'+diccionario.tabs.REGCL+'gridRepresentantes').dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            bFilter:false, 
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "<input type='checkbox' id='"+diccionario.tabs.REGCL+"chk_allr' onclick='simpleScript.checkAll(this,\"#"+diccionario.tabs.REGCL+"gridRepresentantes\");'>", sWidth: "1%", sClass: "center", bSortable: false},
                {sTitle: "DNI", sClass: "center", sWidth: "10%", bSortable: false},
                {sTitle: "Representante", sWidth: "35%"},
                {sTitle: "Estado", sWidth: "8%",  sClass: "center", bSortable: false},
                {sTitle: "Acciones", sWidth: "15%", sClass: "center", bSortable: false}
            ],
            fnServerParams: function(aoData) {
                aoData.push({"name": "_idPersona", "value": _private.idAncestro});
            },
            aaSorting: [[1, 'asc']],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+'getGridRepresentantes',
            fnDrawCallback: function() {
                $('#'+diccionario.tabs.REGCL+'cliente-cont').html(cl);
                $('#'+diccionario.tabs.REGCL+'tollRepresentante').show();
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: '#'+diccionario.tabs.REGCL+'Representantes', //widget del datagrid
                    typeElement: 'button'
                });
            }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getPersona = function(){
        $('#'+diccionario.tabs.REGCL+'gridPersonasFound_filter').remove();
        $('#'+diccionario.tabs.REGCL+'gridPersonasFound').dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sServerMethod: "POST",
            bPaginate: false,
            aoColumns: [
                {sTitle: "Nro.", sClass: "center",sWidth: "2%",  bSortable: false},
                {sTitle: "Nombres y Apellidos", sWidth: "88%"}
            ],
            aaSorting: [[1, 'asc']],
            sScrollY: "250px",
            sAjaxSource: _private.config.modulo+'getPersonas',
            fnServerParams: function(aoData) {
                aoData.push({"name": diccionario.tabs.REGCL+"_term", "value": $('#'+diccionario.tabs.REGCL+'txt_search').val()});
                aoData.push({"name": "_tab", "value": _private.tab});                
            },
            fnDrawCallback: function() {
                $('#'+diccionario.tabs.REGCL+'gridPersonasFound_filter').remove();
                $('#'+diccionario.tabs.REGCL+'gridPersonasFound_wrapper').find('.dt-bottom-row').remove();
                $('#'+diccionario.tabs.REGCL+'gridPersonasFound_wrapper').find('.dataTables_scrollBody').css('overflow-x','hidden');
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: '#'+diccionario.tabs.REGCL+'gridPersonasFound',
                    typeElement: 'a'
                });
            }
        });
        $('#'+diccionario.tabs.REGCL+'gridPersonasFound_filter').remove();
    };    
    
    this.publico.getFormNewCliente = function(btn){
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormNewCliente",
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.REGCL+"formNewCliente").modal("show");
            }
        });
    };
    
    this.publico.getFormNewRepresentante = function(btn){
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormNewRepresentante",
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.REGCL+"formNewRepresentante").modal("show");
            }
        });
    };
    
    this.publico.getEditarCliente = function(btn,id){
        _private.idCliente = id;
       
        simpleAjax.send({
            element: btn,
            dataType: 'html',
            gifProcess: true,
            data: '&_idPersona='+_private.idCliente,
            root: _private.config.modulo + 'getFormEditCliente',
            fnCallback: function(data){
                $('#cont-modal').append(data);
                $('#'+diccionario.tabs.REGCL+'formEditCliente').modal('show');
            }
        });
    };
    
    this.publico.getEditarRepresentante = function(btn,id){
        _private.idRepresentante = id;
       
        simpleAjax.send({
            element: btn,
            dataType: 'html',
            gifProcess: true,
            data: '&_idPersona='+_private.idRepresentante,
            root: _private.config.modulo + 'getEditarRepresentante',
            fnCallback: function(data){
                $('#cont-modal').append(data);
                $('#'+diccionario.tabs.REGCL+'formEditarRepresentante').modal('show');
            }
        });
    };
    
    this.publico.getFormPersona = function(btn,label){
         simpleAjax.send({
            element: btn,
            dataType: 'html',
            root: _private.config.modulo + 'getFormPersona',            
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/                
                $('#'+diccionario.tabs.REGCL+'formBuscarPersona').modal('show');
                $('#'+diccionario.tabs.REGCL+'formBuscarPersona h4.modal-title').html(label);
            }
        });
    };
    this.publico.postNewCliente = function(){
        simpleAjax.send({
            flag: 1,
            element: '#'+diccionario.tabs.REGCL+'btnGrCliente',
            root: _private.config.modulo + 'postNewCliente',
            form: '#'+diccionario.tabs.REGCL+'formNewCliente',
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            cliente.getGridCliente();
                            simpleScript.closeModal('#'+diccionario.tabs.REGCL+'formNewCliente');
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
    
    this.publico.postNewRepresentante = function(){
        simpleAjax.send({
            flag: 1,
            element: '#'+diccionario.tabs.REGCL+'btnGrRep',
            root: _private.config.modulo + 'postNewRepresentante',
            form: '#'+diccionario.tabs.REGCL+'formNewRepresentante',
            data : '&_ancestro='+_private.idAncestro,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            cliente.getGridRepresentantes(_private.idAncestro,_private.nameCliente);
                            simpleScript.closeModal('#'+diccionario.tabs.REGCL+'formNewRepresentante');
                        }
                    });
                }else if(!isNaN(data.result) && parseInt(data.result) === 2){
                    simpleScript.notify.error({
                        content: 'Numero de Documento ya existe en la Base de datos'
                    });
                }else if(!isNaN(data.result) && parseInt(data.result) === 3){
                    simpleScript.notify.error({
                        content:  'E-mail ya existe en la Base de datos'
                    });
                }
            }
        });
    };
    
    this.publico.postEditarCliente = function(){
        simpleAjax.send({
            flag: 2,
            element: '#'+diccionario.tabs.REGCL+'btnEcli',
            root: _private.config.modulo + 'postNewCliente',
            form: '#'+diccionario.tabs.REGCL+'formEditCliente',
            data: '&_idPersona='+_private.idCliente,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){                           
                            simpleScript.reloadGrid('#'+diccionario.tabs.REGCL+'gridCliente');
                            simpleScript.closeModal('#'+diccionario.tabs.REGCL+'formEditCliente');
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
    
    this.publico.postEditRepresentante = function(){
        simpleAjax.send({
            flag: 2,
            element: '#'+diccionario.tabs.REGCL+'btnERep',
            root: _private.config.modulo + 'postNewRepresentante',
            form: '#'+diccionario.tabs.REGCL+'formEditarRepresentante',
            data: '&_idPersona='+_private.idRepresentante,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            simpleScript.reloadGrid('#'+diccionario.tabs.REGCL+'gridRepresentantes');
                            simpleScript.closeModal('#'+diccionario.tabs.REGCL+'formEditarRepresentante');
                        }
                    });
                }else if(!isNaN(data.result) && parseInt(data.result) === 2){
                    simpleScript.notify.error({
                        content: 'Numero de Documento ya existe en la Base de datos'
                    });
                }else if(!isNaN(data.result) && parseInt(data.result) === 3){
                    simpleScript.notify.error({
                        content:  'E-mail ya existe en la Base de datos'
                    });
                }
            }
        });
    };
    
    this.publico.postNewResponsable2 = function(id){
        simpleAjax.send({
            root: _private.config.modulo + 'postActualizarRepresentante',            
            data: '&_ancestro='+_private.idAncestro+'&_idPersona='+id,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){                            
                            cliente.getGridRepresentantes(_private.idAncestro,_private.nameCliente);                            
                            simpleScript.closeModal('#'+diccionario.tabs.REGCL+'formBuscarPersona');
                            simpleScript.closeModal('#'+diccionario.tabs.REGCL+'formNewRepresentante');
                        }
                    });
                }
            }
        });
    };
    
    
    this.publico.postDesactivarCliente = function(btn,id){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postDesactivarCliente',
            data: '&_idPersona='+id,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: 'Cliente se desactivo correctamente',
                        callback: function(){
                            simpleScript.reloadGrid('#'+diccionario.tabs.REGCL+'gridCliente'); 
                        }
                    });
                }
            }
        });
    };
    
    this.publico.postActivarCliente = function(btn,id){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postActivarCliente',
            data: '&_idPersona='+id,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: 'Cliente se activo correctamente',
                        callback: function(){
                            simpleScript.reloadGrid('#'+diccionario.tabs.REGCL+'gridCliente'); 
                        }
                    });
                }
            }
        });
    };
    
    this.publico.postDesactivarRepresentante = function(btn,id){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postDesactivarCliente',
            data: '&_idPersona='+id,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: 'Representante se desactivo correctamente',
                        callback: function(){
                            cliente.getGridRepresentantes(_private.idAncestro,_private.nameCliente);
                        }
                    });
                }
            }
        });
    };
    
    this.publico.postActivarRepresentante = function(btn,id){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postActivarCliente',
            data: '&_idPersona='+id,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: 'Representante se activo correctamente',
                        callback: function(){
                            cliente.getGridRepresentantes(_private.idAncestro,_private.nameCliente);
                        }
                    });
                }
            }
        });
    };
    
    this.publico.postDeleteClienteAll = function(btn){
        simpleScript.validaCheckBox({
            id: "#"+diccionario.tabs.REGCL+"gridCliente",
            msn: mensajes.MSG_9,
            fnCallback: function(){
                simpleScript.notify.confirm({
                    content: mensajes.MSG_7,
                    callbackSI: function(){
                        $("#"+diccionario.tabs.REGCL+"tollRepresentante").hide();
                        $("#"+diccionario.tabs.REGCL+"cont-gridrepre").html('<table id="'+diccionario.tabs.REGCL+'gridRepresentantes" class="table table-striped table-bordered table-hover table-condensed" style="width:100%"></table>');
                        simpleAjax.send({
                            flag: 3, //si se usa SP usar flag, sino se puede eliminar esta linea
                            element: btn,
                            form: "#"+diccionario.tabs.REGCL+"formGridCliente",
                            root: _private.config.modulo + "postDeleteClienteAll",
                            fnCallback: function(data) {
                                if(!isNaN(data.result) && parseInt(data.result) === 1){
                                    simpleScript.notify.ok({
                                        content: mensajes.MSG_8,
                                        callback: function(){
                                            cliente.getGridCliente();
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
    
    this.publico.postDeleteRepresentanteAll = function(btn){
        simpleScript.validaCheckBox({
            id: "#"+diccionario.tabs.REGCL+"gridRepresentantes",
            msn: mensajes.MSG_9,
            fnCallback: function(){
                simpleScript.notify.confirm({
                    content: mensajes.MSG_7,
                    callbackSI: function(){
                        simpleAjax.send({
                            flag: 3, //si se usa SP usar flag, sino se puede eliminar esta linea
                            element: btn,
                            form: "#"+diccionario.tabs.REGCL+"formGridCliente",
                            root: _private.config.modulo + "postDeleteRepresentanteAll",
                            fnCallback: function(data) {
                                if(!isNaN(data.result) && parseInt(data.result) === 1){
                                    simpleScript.notify.ok({
                                        content: mensajes.MSG_8,
                                        callback: function(){
                                            cliente.getGridRepresentantes(_private.idAncestro,_private.nameCliente);
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
    
    return this.publico;
    
};
var cliente = new cliente_();