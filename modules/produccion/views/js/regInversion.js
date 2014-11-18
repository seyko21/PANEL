/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 03-09-2014 14:09:13 
* Descripcion : regInversion.js
* ---------------------------------------
*/
var regInversion_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idSocio = 0;       
    _private.nameSocio = '';
    _private.idInversion = 0;
    
    _private.config = {
        modulo: "produccion/regInversion/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : RegInversion*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.REINV,
            label: $(element).attr("title"),
            fnCallback: function(){
                regInversion.getContenido();
            }
        });
    };
    
    /*contenido de tab: RegInversion*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.REINV+"_CONTAINER").html(data);
                regInversion.getGridSocio();
            }
        });
    };
    
    this.publico.getGridSocio = function (){
      var oTable = $('#'+diccionario.tabs.REINV+'gridSocio').dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,          
            aoColumns: [
                {sTitle: "<input type='checkbox' id='"+diccionario.tabs.REINV+"chk_all' onclick='simpleScript.checkAll(this,\"#"+diccionario.tabs.REINV+"gridSocio\");'>", sWidth: "1%", sClass: "center", bSortable: false},
                {sTitle: "RUC", sClass: "center", sWidth: "15%"},               
                {sTitle: "Nombres y Apellidos", sWidth: "25%"},                
                {sTitle: "Invertido", sWidth: "15%",  sClass: "right"},
                {sTitle: "Estado", sWidth: "8%",  sClass: "center", bSortable: false},                
                {sTitle: "Acciones", sWidth: "15%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[1, 'asc']],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+'getGridSocio',
            fnDrawCallback: function() {
                $('#'+diccionario.tabs.REINV+'gridSocio_filter').find('input').attr('placeholder','Buscar por nombre').css('width','200px');                
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: '#widget_'+diccionario.tabs.REINV, //widget del datagrid
                    typeElement: 'button, #'+diccionario.tabs.REINV+'chk_all'
                });
                $('#'+diccionario.tabs.REINV+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.REINV+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getGridInversion = function(id,cl){
        _private.idSocio = id;
        _private.nameSocio = cl;
        
       var oTable = $('#'+diccionario.tabs.REINV+'gridInversion').dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            bFilter:false, 
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "<input type='checkbox' id='"+diccionario.tabs.REINV+"chk_allr' onclick='simpleScript.checkAll(this,\"#"+diccionario.tabs.REINV+"gridInversion\");'>", sWidth: "1%", sClass: "center", bSortable: false},                
                {sTitle: "Fecha", sClass: "center", sWidth: "10%"},
                {sTitle: "Invertido", sClass: "right", sWidth: "20%"},   
                {sTitle: "Asignado", sClass: "right", sWidth: "20%"},
                {sTitle: "Saldo", sClass: "right", sWidth: "20%"},
                {sTitle: "Acciones", sWidth: "15%", sClass: "center", bSortable: false}
            ],
            fnServerParams: function(aoData) {
                aoData.push({"name": "_idPersona", "value": _private.idSocio});
            },
            aaSorting: [[1, 'asc']],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+'getGridInversion',
            fnDrawCallback: function() {
                $('#'+diccionario.tabs.REINV+'regInversion-cont').html(cl);
                $('#'+diccionario.tabs.REINV+'tollInversion').show();
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: '#'+diccionario.tabs.REINV+'gridInversion', //widget del datagrid
                    typeElement: 'button'
                });
            }
        });
        setup_widgets_desktop();
    };    
    
    this.publico.getFormNewSocio = function(btn){
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormNewSocio",
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.REINV+"formNewSocio").modal("show");
            }
        });
    }; 
    
   //Ventana Editar Socio
     this.publico.getFormEditSocio = function(btn,id){
        _private.idSocio = id;
       
        simpleAjax.send({
            element: btn,
            dataType: 'html',
            gifProcess: true,
            data: '&_idPersona='+_private.idSocio,
            root: _private.config.modulo + 'getFormEditSocio',
            fnCallback: function(data){
                $('#cont-modal').append(data);
                $('#'+diccionario.tabs.REINV+'formEditSocio').modal('show');
            }
        });
    };    
    
    this.publico.getFormNewRegInversion = function(btn){
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormNewRegInversion",
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.REINV+"formNewRegInversion").modal("show");
            }
        });
    };
    
    this.publico.getEditarInversion = function(btn,id){
        _private.idInversion = id;       
        simpleAjax.send({
            element: btn,
            dataType: 'html',
            gifProcess: true,
            data: '&_idInversion='+_private.idInversion,
            root: _private.config.modulo + 'getFormEditInversion',
            fnCallback: function(data){
                $('#cont-modal').append(data);
                $('#'+diccionario.tabs.REINV+'formEditInversion').modal('show');
            }
        });
    };  
    
   this.publico.getProvincias = function(obj){
        simpleAjax.send({
            gifProcess: true,
            root: _private.config.modulo + 'getProvincias',
            data: '&_idDepartamento='+obj.idDepartamento,
            fnCallback: function(data){
                simpleScript.listBox({
                    data: data,
                    optionSelec: true,
                    content: obj.content,
                    attr:{
                        id: obj.idElement,
                        name: obj.nameElement
                    },
                    dataView:{
                        etiqueta: 'provincia',
                        value: 'id_provincia'
                    },
                    fnCallback: function(){
                        simpleScript.setEvent.change({
                            element: '#'+obj.idElement,
                            event: function(){
                                socio.getUbigeo({
                                    idProvincia: $('#'+obj.idElement).val(),
                                    content: obj.contentUbigeo,
                                    idElement: obj.idUbigeo,
                                    nameElement: obj.idUbigeo
                                });
                            }
                        });
                    }
                });
            }
        });
    };
    
    this.publico.getUbigeo = function(obj){
        simpleAjax.send({
            gifProcess: true,
            root: _private.config.modulo + 'getUbigeo',
            data: '&_idProvincia='+obj.idProvincia,
            fnCallback: function(data){
                simpleScript.listBox({
                    data: data,
                    optionSelec: true,
                    content: obj.content,
                    attr:{
                        id: obj.idElement,
                        name: obj.nameElement
                    },
                    dataView:{
                        etiqueta: 'distrito',
                        value: 'id_ubigeo'
                    }
                });
            }
        });
    };    
    
    
    
    
    this.publico.postNuevoSocio = function(){
        simpleAjax.send({
            flag: 1,
            element: '#'+diccionario.tabs.REINV+'btnGsocio',
            root: _private.config.modulo + 'postNewSocio',
            form: '#'+diccionario.tabs.REINV+'formNewSocio',
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                           regInversion.getGridSocio();
                           simpleScript.closeModal('#'+diccionario.tabs.REINV+'formNewSocio');
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
    
    this.publico.postEditarSocio = function(){
        simpleAjax.send({
            flag: 2,
            element: '#'+diccionario.tabs.REINV+'btnGsocio',
            root: _private.config.modulo + 'postEditSocio',
            form: '#'+diccionario.tabs.REINV+'formEditSocio',
            data: '&_idPersona='+_private.idSocio,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            _private.idSocio = 0;
                            simpleScript.reloadGrid('#'+diccionario.tabs.REINV+'gridSocio'); 
                            simpleScript.closeModal('#'+diccionario.tabs.REINV+'formEditSocio');
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
    
    this.publico.postDeleteSocioAll = function(btn){
        simpleScript.validaCheckBox({
            id: "#"+diccionario.tabs.REINV+"gridSocio",
            msn: mensajes.MSG_9,
            fnCallback: function(){
                simpleScript.notify.confirm({
                    content: mensajes.MSG_7,
                    callbackSI: function(){
                        $("#"+diccionario.tabs.REINV+"tollInversion").hide();
                        $("#"+diccionario.tabs.REINV+"cont-gridInversion").html('<table id="'+diccionario.tabs.REINV+'gridInversion" class="table table-striped table-bordered table-hover table-condensed" style="width:100%"></table>');                      
                        simpleAjax.send({
                            element: btn,
                            form: "#"+diccionario.tabs.REINV+"formGridSocio",
                            root: _private.config.modulo + "postDeleteSocioAll",
                            fnCallback: function(data) {
                                if(!isNaN(data.result) && parseInt(data.result) === 1){
                                    simpleScript.notify.ok({
                                        content: mensajes.MSG_8,
                                        callback: function(){
                                            regInversion.getGridSocio();
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
    
    this.publico.postNewRegInversion = function(){
       simpleAjax.send({
            flag: 1,
            element: '#'+diccionario.tabs.REINV+'btnGrRegInversion',
            root: _private.config.modulo + 'postNewInversion',
            form: '#'+diccionario.tabs.REINV+'formNewRegInversion',
            data : '&_idPersona='+_private.idSocio,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                           simpleScript.reloadGrid('#'+diccionario.tabs.REINV+'gridSocio');
                           setTimeout(function(){            
                                var _socio = $('#'+diccionario.tabs.REINV+'regInversion-cont').html();
                                regInversion.getGridInversion(_private.idSocio,_socio);                                      
                           }, 1000);                                                            
                           simpleScript.closeModal('#'+diccionario.tabs.REINV+'formNewRegInversion');
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
    
      this.publico.postEditarInversion = function(){
        simpleAjax.send({
            flag: 2,
            element: '#'+diccionario.tabs.REINV+'btnGrRegInversion',
            root: _private.config.modulo + 'postEditInversion',
            form: '#'+diccionario.tabs.REINV+'formEditInversion',
            data: '&_idInversion='+_private.idInversion+'&_idPersona='+_private.idSocio,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            _private.idInversion = 0;
                           simpleScript.reloadGrid('#'+diccionario.tabs.REINV+'gridSocio');   
                           setTimeout(function(){            
                                simpleScript.reloadGrid('#'+diccionario.tabs.REINV+'gridInversion');                               
                           }, 1000);                            
                           simpleScript.closeModal('#'+diccionario.tabs.REINV+'formEditInversion');
                        }
                    });
                }else if(!isNaN(data.result) && parseInt(data.result) === 2){
                    simpleScript.notify.error({
                        content: mensajes.MSG_4
                    });
                }else if(!isNaN(data.result) && parseInt(data.result) === 3){
                    simpleScript.notify.error({
                        content: mensajes.MSG_15
                    });
                    simpleScript.closeModal('#'+diccionario.tabs.REINV+'formEditInversion');
                }
            }
        });
    };     
    
    
    this.publico.postDeleteInversionAll = function(btn){
        simpleScript.validaCheckBox({
            id: "#"+diccionario.tabs.REINV+"gridInversion",
            msn: mensajes.MSG_9,
            fnCallback: function(){
                simpleScript.notify.confirm({
                    content: mensajes.MSG_7,
                    callbackSI: function(){                        
                        simpleAjax.send({
                            element: btn,
                            form: "#"+diccionario.tabs.REINV+"formGridSocio",
                            root: _private.config.modulo + "postDeleteInversionAll",
                            fnCallback: function(data) {
                                if(!isNaN(data.result) && parseInt(data.result) === 1){
                                    simpleScript.notify.ok({
                                        content: mensajes.MSG_8,
                                        callback: function(){
                                            simpleScript.reloadGrid('#'+diccionario.tabs.REINV+'gridSocio');
                                            setTimeout(function(){            
                                                 var _socio = $('#'+diccionario.tabs.REINV+'regInversion-cont').html();
                                                 regInversion.getGridInversion(_private.idSocio,_socio);                                      
                                            }, 1000);         
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
    
    this.publico.postDesactivarSocio = function(btn,id){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postDesactivarSocio',
            data: '&_idPersona='+id,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: 'Socio se desactivo correctamente',
                        callback: function(){
                           simpleScript.reloadGrid('#'+diccionario.tabs.REINV+'gridSocio'); 
                        }
                    });
                }
            }
        });
    };
    
    this.publico.postActivarSocio = function(btn,id){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postActivarSocio',
            data: '&_idPersona='+id,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: 'Socio se activo correctamente',
                        callback: function(){
                            simpleScript.reloadGrid('#'+diccionario.tabs.REINV+'gridSocio'); 
                        }
                    });
                }
            }
        });
    };    
    
    return this.publico;
    
};
var regInversion = new regInversion_();