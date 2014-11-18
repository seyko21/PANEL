/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 16-09-2014 03:09:14 
* Descripcion : persona.js
* ---------------------------------------
*/
var persona_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idPersona = 0;
    
    _private.config = {
        modulo: "Personal/persona/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : Persona*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.REPER,
            label: $(element).attr("title"),
            fnCallback: function(){
                persona.getContenido();
            }
        });
    };
    
    /*contenido de tab: Persona*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.REPER+"_CONTAINER").html(data);
                persona.getGridPersona();
            }
        });
    };
    
    this.publico.getGridPersona = function (){
     var oTable = $('#'+diccionario.tabs.REPER+'gridPersona').dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "<input type='checkbox' id='"+diccionario.tabs.REPER+"chk_all' onclick='simpleScript.checkAll(this,\"#"+diccionario.tabs.REPER+"gridPersona\");'>", sWidth: "1%", sClass: "center", bSortable: false},              
                {sTitle: "Nombres y Apellidos", sWidth: "35%"},
                {sTitle: "Email", sWidth: "20%"},
                {sTitle: "Teléfonos", sWidth: "10%"},  
                {sTitle: "DNI", sClass: "center", sWidth: "10%"},  
                {sTitle: "Ciudad", sWidth: "25%"}, 
                {sTitle: "Estado", sWidth: "9%",  sClass: "center", bSortable: false},                
                {sTitle: "Acciones", sWidth: "15%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[1, 'asc']],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+'getGridPersona',
            fnDrawCallback: function() {
                $('#'+diccionario.tabs.REPER+'gridPersona_filter').find('input').attr('placeholder','Buscar por Nombre o nro documento').css('width','300px');
                simpleScript.enterSearch("#"+diccionario.tabs.REPER+'gridPersona',oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: '#widget_'+diccionario.tabs.REPER, //widget del datagrid
                    typeElement: 'button, #'+diccionario.tabs.REPER+'chk_all'
                });
                 $('#'+diccionario.tabs.REPER+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.REPER+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getFormNewPersona = function(btn){
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormNewPersona",
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.REPER+"formNewPersona").modal("show");
            }
        });
    };
    
    this.publico.getDatosPersonales = function(id){
        simpleAjax.send({
            gifProcess: true,
            dataType: "html",
            root: _private.config.modulo + "getFormDatosPersonales",
            data: '&_idPersona='+id,
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.REPER+"formDatosPersonales").modal("show");
            }
        });
    };
    
    //Ventana Editar Persona
    this.publico.getFormEditPersona = function(btn,id){
        _private.idPersona = id;
       
        simpleAjax.send({
            element: btn,
            dataType: 'html',
            gifProcess: true,
            data: '&_idPersona='+_private.idPersona,
            root: _private.config.modulo + 'getFormEditPersona',
            fnCallback: function(data){
                $('#cont-modal').append(data);
                $('#'+diccionario.tabs.REPER+'formEditPersona').modal('show');
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
                                persona.getUbigeo({
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
    
    this.publico.postNewPersona = function(){
        simpleAjax.send({
            flag: 1,
            element: '#'+diccionario.tabs.REPER+'btnGpersona',
            root: _private.config.modulo + 'postNewPersona',
            form: '#'+diccionario.tabs.REPER+'formNewPersona',
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                           persona.getGridPersona();
                           simpleScript.closeModal('#'+diccionario.tabs.REPER+'formNewPersona');
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
    
 this.publico.postEditarPersona = function(){
        simpleAjax.send({
            flag: 2,
            element: '#'+diccionario.tabs.REPER+'btnEpersona',
            root: _private.config.modulo + 'postEditPersona',
            form: '#'+diccionario.tabs.REPER+'formEditPersona',
            data: '&_idPersona='+_private.idPersona,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            _private.idPersona = 0;
                            simpleScript.reloadGrid('#'+diccionario.tabs.REPER+'gridPersona'); 
                            simpleScript.closeModal('#'+diccionario.tabs.REPER+'formEditPersona');
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
    
    this.publico.postDeletePersonaAll = function(btn){
        simpleScript.validaCheckBox({
            id: "#"+diccionario.tabs.REPER+"gridPersona",
            msn: mensajes.MSG_9,
            fnCallback: function(){
                simpleScript.notify.confirm({
                    content: mensajes.MSG_7,
                    callbackSI: function(){
                        simpleAjax.send({
                            element: btn,
                            form: "#"+diccionario.tabs.REPER+"formGridPersona",
                            root: _private.config.modulo + "postDeletePersonaAll",
                            fnCallback: function(data) {
                                if(!isNaN(data.result) && parseInt(data.result) === 1){
                                    simpleScript.notify.ok({
                                        content: mensajes.MSG_8,
                                        callback: function(){
                                            persona.getGridPersona();
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
    
    this.publico.postDesactivarPersona = function(btn,id){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postDesactivar',
            data: '&_idPersona='+id,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: 'Persona se desactivo correctamente',
                        callback: function(){
                           simpleScript.reloadGrid('#'+diccionario.tabs.REPER+'gridPersona'); 
                        }
                    });
                }
            }
        });
    };
    
    this.publico.postActivarPersona = function(btn,id){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postActivar',
            data: '&_idPersona='+id,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: 'Persona se activo correctamente',
                        callback: function(){
                            simpleScript.reloadGrid('#'+diccionario.tabs.REPER+'gridPersona'); 
                        }
                    });
                }
            }
        });
    };
    
    return this.publico;
    
};
var persona = new persona_();