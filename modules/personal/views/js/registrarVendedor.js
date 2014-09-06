var registrarVendedor_ = function(){
    
    var _private = {};
    
    _private.idVendedor = 0;
    
    _private.tab = 0;
    
    _private.config = {
        modulo: 'personal/registrarVendedor/'
    };

    this.publico = {};
    
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.T7,
            label: $(element).attr('title'),
            fnCallback: function(){
                registrarVendedor.getCont();
            }
        });
    };
    
    this.publico.getCont = function(){
        simpleAjax.send({
            dataType: 'html',
            root: _private.config.modulo,
            fnCallback: function(data){
                $('#'+diccionario.tabs.T7+'_CONTAINER').html(data);
                registrarVendedor.getGridVendedor();
            }
        });
    };
    
    this.publico.getGridVendedor = function (){
        $('#'+diccionario.tabs.T7+'getGridVendedor').dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "<input type='checkbox' id='"+diccionario.tabs.T7+"chk_all' onclick='simpleScript.checkAll(this,\"#"+diccionario.tabs.T7+"getGridVendedor\");'>", sWidth: "1%", sClass: "center", bSortable: false},
                {sTitle: "RUC", sClass: "center", sWidth: "10%", bSortable: false},
                {sTitle: "DNI", sClass: "center", sWidth: "10%", bSortable: false},
                {sTitle: "Nombres y Apellidos", sWidth: "25%"},
                {sTitle: "Email", sWidth: "20%", bSortable: false},
                {sTitle: "Teléfonos", sWidth: "15%", bSortable: false},
                {sTitle: "Estado", sWidth: "8%",  sClass: "center", bSortable: false},
                {sTitle: "Acciones", sWidth: "15%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[1, 'asc']],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+'getGridVendedor',
            fnDrawCallback: function() {
                $('#'+diccionario.tabs.T7+'getGridVendedor_filter').find('input').attr('placeholder','Buscar');
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: '#widget_'+diccionario.tabs.T7, //widget del datagrid
                    typeElement: 'button, #'+diccionario.tabs.T7+'chk_all'
                });
            }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getNuevoVendedor = function(btn){
        simpleAjax.send({
            element: btn,
            dataType: 'html',
            root: _private.config.modulo + 'getNuevoVendedor',
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.T7+'formVendedor').modal('show');
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
                                registrarVendedor.getUbigeo({
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

    this.publico.getEditarVendedor = function(btn,id){
        _private.idVendedor = id;
       
        simpleAjax.send({
            element: btn,
            dataType: 'html',
            gifProcess: true,
            data: '&_idPersona='+_private.idVendedor,
            root: _private.config.modulo + 'getEditarVendedor',
            fnCallback: function(data){
                $('#cont-modal').append(data);
                $('#'+diccionario.tabs.T7+'formVendedor').modal('show');
            }
        });
    };
    
    this.publico.postEditarVendedor = function(){
        simpleAjax.send({
            flag: 2,
            element: '#'+diccionario.tabs.T7+'btnEvend',
            root: _private.config.modulo + 'postNuevoVendedor',
            form: '#'+diccionario.tabs.T7+'formVendedor',
            data: '&_idPersona='+_private.idVendedor,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            _private.idVendedor = 0;
                            registrarVendedor.getGridVendedor();
                            simpleScript.closeModal('#'+diccionario.tabs.T7+'formVendedor');
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
    
    this.publico.postDeleteVendedorAll = function(btn){
        simpleScript.validaCheckBox({
            id: '#'+diccionario.tabs.T7+'getGridVendedor',
            msn: mensajes.MSG_9,
            fnCallback: function(){
                simpleScript.notify.confirm({
                    content: mensajes.MSG_7,
                    callbackSI: function(){
                        simpleAjax.send({
                            flag: 3,
                            element: btn,
                            form: '#'+diccionario.tabs.T7+'formGridVendedor',
                            root: _private.config.modulo + 'postDeleteVendedorAll',
                            fnCallback: function(data) {
                                if(!isNaN(data.result) && parseInt(data.result) === 1){
                                    simpleScript.notify.ok({
                                        content: mensajes.MSG_8,
                                        callback: function(){
                                            registrarVendedor.getGridVendedor();
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
    
    this.publico.postDesactivarVendedor = function(btn,id){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postDesactivarVendedor',
            data: '&_idPersona='+id,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: 'Vendedor se desactivo correctamente',
                        callback: function(){
                            registrarVendedor.getGridVendedor();
                        }
                    });
                }
            }
        });
    };
    
    this.publico.postActivarVendedor = function(btn,id){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postActivarVendedor',
            data: '&_idPersona='+id,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: 'Vendedor se activo correctamente',
                        callback: function(){
                            registrarVendedor.getGridVendedor();
                        }
                    });
                }
            }
        });
    };
    
    this.publico.postAccesoVendedor = function(btn,id,vend,mail){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postAccesoVendedor',
            data: '&_vendedor='+vend+'&_idVendedor='+id+'&_mail='+mail,
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
    
    this.publico.postNuevoVendedor = function(){
        simpleAjax.send({
            flag: 1,
            element: '#'+diccionario.tabs.T7+'btnGvend',
            root: _private.config.modulo + 'postNuevoVendedor',
            form: '#'+diccionario.tabs.T7+'formVendedor',
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            registrarVendedor.getGridVendedor();
                            simpleScript.closeModal('#'+diccionario.tabs.T7+'formVendedor');
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
    
    this.publico.postPassVendedor = function(){
        simpleAjax.send({
            flag: 1,
            element: '#'+diccionario.tabs.T7+'btnGvend',
            root: _private.config.modulo + 'postPassVendedor',
            form: '#'+diccionario.tabs.T7+'formVendedor',
            data: '&_idVendedor='+$('#txtUser').val()+'&_pass='+simpleAjax.stringPost(('#txtNewClave').val()),
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3
                    });
                }
            }
        });
    };
    
    return this.publico;
    
};
var registrarVendedor = new registrarVendedor_();