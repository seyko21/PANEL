/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 03-09-2014 02:09:26 
* Descripcion : socio.js
* ---------------------------------------
*/
var socio_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idSocio = 0;
    
    _private.config = {
        modulo: "personal/socio/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : Socio*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.TAB_SOCIO,
            label: $(element).attr("title"),
            fnCallback: function(){
                socio.getContenido();
            }
        });
    };
    
    /*contenido de tab: Socio*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.TAB_SOCIO+"_CONTAINER").html(data);
                socio.getGridSocio();
            }
        });
    };
    
    this.publico.getGridSocio = function (){
       var oTable = $('#'+diccionario.tabs.TAB_SOCIO+'gridSocio').dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "<input type='checkbox' id='"+diccionario.tabs.TAB_SOCIO+"chk_all' onclick='simpleScript.checkAll(this,\"#"+diccionario.tabs.TAB_SOCIO+"gridSocio\");'>", sWidth: "1%", sClass: "center", bSortable: false},
                {sTitle: "RUC", sClass: "center", sWidth: "10%"},               
                {sTitle: "Nombres y Apellidos", sWidth: "20%"},
                {sTitle: "Email", sWidth: "20%"},
                {sTitle: "Teléfonos", sWidth: "5%"},
                {sTitle: "Invertido", sWidth: "10%",  sClass: "right"},
                {sTitle: "Asignado", sWidth: "10%",  sClass: "right"},
                {sTitle: "Saldo", sWidth: "10%",  sClass: "right"},
                {sTitle: "Estado", sWidth: "8%",  sClass: "center", bSortable: false},                
                {sTitle: "Acciones", sWidth: "15%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[1, 'asc']],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+'getGridSocio',
            fnDrawCallback: function() {
                $('#'+diccionario.tabs.TAB_SOCIO+'gridSocio_filter').find('input').attr('placeholder','Buscar por nombre o nro documento').css('width','300px');;
                simpleScript.enterSearch("#"+diccionario.tabs.TAB_SOCIO+'gridSocio',oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: '#widget_'+diccionario.tabs.TAB_SOCIO, //widget del datagrid
                    typeElement: 'button, #'+diccionario.tabs.TAB_SOCIO+'chk_all'
                });
                $('#'+diccionario.tabs.TAB_SOCIO+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.TAB_SOCIO+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();
    };
    
    //Ventana Nuevo Socio
    this.publico.getFormNewSocio = function(btn){
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormNewSocio",
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.TAB_SOCIO+"formNewSocio").modal("show");
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
                $('#'+diccionario.tabs.TAB_SOCIO+'formEditSocio').modal('show');
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
            element: '#'+diccionario.tabs.TAB_SOCIO+'btnGsocio',
            root: _private.config.modulo + 'postNewSocio',
            form: '#'+diccionario.tabs.TAB_SOCIO+'formNewSocio',
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                           socio.getGridSocio();
                           simpleScript.closeModal('#'+diccionario.tabs.TAB_SOCIO+'formNewSocio');
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
    
 this.publico.postEditarSocio = function(){
        simpleAjax.send({
            flag: 2,
            element: '#'+diccionario.tabs.TAB_SOCIO+'btnGsocio',
            root: _private.config.modulo + 'postEditSocio',
            form: '#'+diccionario.tabs.TAB_SOCIO+'formEditSocio',
            data: '&_idPersona='+_private.idSocio,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            _private.idSocio = 0;
                            simpleScript.reloadGrid('#'+diccionario.tabs.TAB_SOCIO+'gridSocio'); 
                            simpleScript.closeModal('#'+diccionario.tabs.TAB_SOCIO+'formEditSocio');
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
    
    this.publico.postDeleteSocioAll = function(btn){
        simpleScript.validaCheckBox({
            id: "#"+diccionario.tabs.TAB_SOCIO+"gridSocio",
            msn: mensajes.MSG_9,
            fnCallback: function(){
                simpleScript.notify.confirm({
                    content: mensajes.MSG_7,
                    callbackSI: function(){
                        simpleAjax.send({
                            element: btn,
                            form: "#"+diccionario.tabs.TAB_SOCIO+"formGridSocio",
                            root: _private.config.modulo + "postDeleteSocioAll",
                            fnCallback: function(data) {
                                if(!isNaN(data.result) && parseInt(data.result) === 1){
                                    simpleScript.notify.ok({
                                        content: mensajes.MSG_8,
                                        callback: function(){
                                            socio.getGridSocio();
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
                           simpleScript.reloadGrid('#'+diccionario.tabs.TAB_SOCIO+'gridSocio'); 
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
                            simpleScript.reloadGrid('#'+diccionario.tabs.TAB_SOCIO+'gridSocio'); 
                        }
                    });
                }
            }
        });
    };
        
    this.publico.postAcceso = function(btn,id,vend,mail){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postAcceso',
            data: '&_nombres='+vend+'&_id='+id+'&_mail='+mail,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: 'Acceso se envió correctamente'
                    });
                }
            }
        });
    };
    
    return this.publico;
    
};
var socio = new socio_();