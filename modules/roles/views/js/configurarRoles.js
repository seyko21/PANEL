var configurarRoles_ = function(){
    
    var _private = {};
    
    _private.config = {
        modulo: 'roles/configurarRoles/'
    };
    
    _private.idRol = 0;
    
    this.public = {};
    
    this.public.main = function(){
        simpleScript.addTab({
            id : diccionario.tabs.T1,
            label: 'Configurar roles',
            fnCallback: function(){
                configurarRoles.getCont();
            }
        });
    };
    
    this.public.getCont = function(){
        simpleAjax.send({
            dataType: 'html',
            root: _private.config.modulo,
            fnCallback: function(data){
                $('#'+diccionario.tabs.T1+'_CONTAINER').html(data);
                configurarRoles.getGridRoles();
            }
        });
    };
    
    this.public.getGridRoles = function (){
        var oTable = $('#gridRoles').dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "Código", sClass: "center",sWidth: "10%"},
                {sTitle: "Rol", sWidth: "50%"},
                {sTitle: "Estado", sWidth: "10%", sClass: "center", bSortable: false},
                {sTitle: "Acciones", sWidth: "15%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[1, 'asc']],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+'getRoles',
            fnDrawCallback: function() {
                $('#gridRoles_filter').find('input').attr('placeholder','Buscar por rol');
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: '#widget_'+diccionario.tabs.T1+'roles',
                    typeElement: 'button'
                });
                $(window).resize(function(){
                    //oTable.fnAdjustColumnSizing();
                });
            }
        });
        setup_widgets_desktop();
    };
    
    this.public.getNuevoRol = function(){
        simpleAjax.send({
            element: '#CRDCRbtnNew',
            dataType: 'html',
            root: _private.config.modulo + 'getNuevoRol',
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#CRDCRformNuevoRol').modal('show');
                configurarRolesScript.validateRol({
                    form: '#CRDCRformNuevoRol', 
                    evento: 'configurarRoles.postRol()'
                });
            }
        });
    };
    
    /*extraer rol para editar*/
    this.public.getRol = function(){
        _private.idRol = simpleScript.getParam(arguments[0]);
       
        simpleAjax.send({
            dataType: 'html',
            gifProcess: true,
            data: '&_key='+_private.idRol,
            root: _private.config.modulo + 'getEditRol',
            fnCallback: function(data){
                $('#cont-modal').append(data);
                $('#CRDCRformEditRol').modal('show');
                configurarRolesScript.validateRol({
                    form: '#CRDCRformEditRol', 
                    evento: 'configurarRoles.postEditRol()'
                });
            }
        });
    };
    
    /*los accesos del rol*/
    this.public.getAccesos = function(){
        _private.idRol = simpleScript.getParam(arguments[0]);
        var rol = simpleScript.getParam(arguments[1]);
        
        simpleAjax.send({
            dataType: 'html',
            gifProcess: true,
            data: '&_key='+_private.idRol,
            root: _private.config.modulo + 'getAccesos',
            fnCallback: function(data){
                $('#cont-modal').append(data);
                $('#CRDCRformAccesos').modal('show');
                $('#cont-rol').html(rol);
            }
        });
    };
    
    /*los acciones del rol opcion*/
    this.public.getAccionesRolOpcion = function(){
        var contAccion  = simpleScript.getParam(arguments[0]);
        var idRolOpcion = simpleScript.getParam(arguments[1]);
        simpleAjax.send({
            dataType: 'html',
            element: '#btn_'+contAccion,
            abort: false,
            data: '&_rolOpcion='+idRolOpcion,
            root: _private.config.modulo + 'getOpcionRolAxions',
            fnCallback: function(data){
                $('.accionesOpcion-cont').fadeOut();
                $('#cont-acciones'+contAccion).html(data);
                $('#cont-acciones'+contAccion).fadeIn();
            }
        });
    };
    
    this.public.postRol = function(){
        simpleAjax.send({
            flag: 1,
            element: '#CRDCRbtnGrabaRol',
            root: _private.config.modulo + 'postRol',
            form: '#CRDCRformNuevoRol',
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            configurarRoles.getGridRoles();
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
    
    this.public.postEditRol = function(){
        simpleAjax.send({
            flag: 3,
            element: '#CRDCRbtnEditaRol',
            root: _private.config.modulo + 'postRol',
            form: '#CRDCRformEditRol',
            data: '&_key='+_private.idRol,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            configurarRoles.getGridRoles();
                            simpleScript.reloadGrid('#gridRoles');
                            simpleScript.closeModal('#CRDCRformEditRol');
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
    
    /*eliminar rol*/
    this.public.postDeleteRol = function(){
        _private.idRol = simpleScript.getParam(arguments[0]);
        
        simpleScript.notify.confirm({
            content: mensajes.MSG_5,
            callbackSI: function(){
                simpleAjax.send({
                    flag: 2,
                    gifProcess: true,
                    data: '&_key='+_private.idRol,
                    root: _private.config.modulo + 'postDeleteRol',
                    fnCallback: function(data) {
                        if(!isNaN(data.result) && parseInt(data.result) === 1){
                            simpleScript.notify.ok({
                                content: mensajes.MSG_6,
                                callback: function(){
                                    configurarRoles.getGridRoles();
                                }
                            });
                        }
                    }
                });
            }
        });
    };
    
    /*agregar opcion a rol*/
    this.public.postOpcion = function(){
        var radio    = simpleScript.getParam(arguments[0]);
        var flag     = simpleScript.getParam(arguments[1]);
        var idRol    = simpleScript.getParam(arguments[2]);
        var idOpcion = simpleScript.getParam(arguments[3]);
        
        simpleAjax.send({
            flag: flag,
            gifProcess: true,
            root: _private.config.modulo + 'postOpcion',
            data: '&_key='+idRol+'&_opcion='+idOpcion,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    /*se activa boton acciones y se agrega evento*/
//                    alert(simpleAjax.stringGet(idRol)+simpleAjax.stringGet(idOpcion)+'---'+idOpcion)
                    $('#btn_'+simpleAjax.stringGet(idRol)+simpleAjax.stringGet(idOpcion)+'').attr('disabled',false);
                    simpleScript.setEvent.click({
                        element: '#btn_'+simpleAjax.stringGet(idRol)+simpleAjax.stringGet(idOpcion),
                        event: 'configurarRoles.getAccionesRolOpcion(\''+simpleAjax.stringGet(idRol)+simpleAjax.stringGet(idOpcion)+'\',simpleAjax.stringPost(\''+data.id_rolopciones+'\'));'
                    });
                    /*se cambia ebento para que elimine*/
                    $(radio).attr("onclick","");
                    simpleScript.setEvent.click({
                        element: radio,
                        event: "configurarRoles.postOpcion(this,5,'"+idRol+"','"+idOpcion+"')"
                    });
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3
                    });
                }else if(!isNaN(data.result) && parseInt(data.result) === 2){
                    simpleScript.notify.error({
                        content: mensajes.MSG_4
                    });
                }else if(!isNaN(data.result) && parseInt(data.result) === 3){
//                    alert(simpleAjax.stringGet(idRol)+simpleAjax.stringGet(idOpcion)+'---'+idOpcion)
                    /*se desactiva boton acciones y quita evento*/
                    $('#btn_'+simpleAjax.stringGet(idRol)+simpleAjax.stringGet(idOpcion)+'').attr('disabled',true).removeAttr('onclick');
                    $('#btn_'+simpleAjax.stringGet(idRol)+simpleAjax.stringGet(idOpcion)+'').off('click');
                    /*se cambia evento para que agregue*/
                    $(radio).attr("onclick","");
                    simpleScript.setEvent.click({
                        element: radio,
                        event: "configurarRoles.postOpcion(this,4,'"+idRol+"','"+idOpcion+"')"
                    });
                    simpleScript.notify.ok({
                        content: mensajes.MSG_6
                    });
                    /*se limpia contenedor de acciones*/
                    $('#cont-acciones'+simpleAjax.stringGet(idRol)+simpleAjax.stringGet(idOpcion)+'').html('');
                }
            }
        });
    };
    
    this.public.postAccionOpcionRol = function(){
        var flag     = simpleScript.getParam(arguments[0]);
        var rolOpcion= simpleScript.getParam(arguments[1]);
        var accion   = simpleScript.getParam(arguments[2]);
        
        simpleAjax.send({
            flag: flag,
            gifProcess: true,
            abort: false,
            root: _private.config.modulo + 'postAccionOpcionRol',
            data: '&_accion='+accion+'&_opcion='+rolOpcion,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3
                    });
                }else if(!isNaN(data.result) && parseInt(data.result) === 3){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_6
                    });
                }
            }
        });
        
    };
    
    this.public.postDuplicarRol = function(btn,id){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postDuplicarRol',
            data: '&_key='+id,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: 'Rol se duplicó correctamente',
                        callback: function(){
                            configurarRoles.getGridRoles();
                        }
                    });
                }
            }
        });
    };
    
    return this.public;
    
};
 var configurarRoles = new configurarRoles_();