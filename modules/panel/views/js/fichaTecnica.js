
var fichaTecnica_ = function(){
    
    var _private = {};
    
    _private.idProducto = 0;
    
    _private.idCaratula = 0;
    
    _private.config = {
        modulo: 'panel/fichaTecnica/'
    };

    this.publico = {};
    
    this.publico.resetKeyProducto = function(){
        _private.idProducto = 0;
    };    
    
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.T102,
            label: $(element).attr('title'),
            fnCallback: function(){
                fichaTecnica.getCont();
            }
        });
    };
    
    this.publico.getCont = function(){
        simpleAjax.send({
            dataType: 'html',
            root: _private.config.modulo,
            fnCallback: function(data){
                $('#'+diccionario.tabs.T102+'_CONTAINER').html(data);                
                fichaTecnica.getGridFichaTecnica();
            }
        });
    };
    
    this.publico.getGridFichaTecnica = function (){
        $('#'+diccionario.tabs.T102+'gridFichaTecnica').dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "<input type='checkbox' id='"+diccionario.tabs.T102+"chk_all' onclick='simpleScript.checkAll(this,\"#"+diccionario.tabs.T102+"gridFichaTecnica\");'>", sWidth: "1%", sClass: "center", bSortable: false},
                {sTitle: "ID", sWidth: "8%"},
                {sTitle: "Ubicación", sWidth: "40%"},
                {sTitle: "Area m2", sWidth: "8%",  sClass: "center", bSortable: false},
                {sTitle: "Nro Caratulas", sWidth: "8%",  sClass: "center", bSortable: false},
                {sTitle: "Estado", sWidth: "8%",  sClass: "center", bSortable: false},
                {sTitle: "Acciones", sWidth: "15%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[1, 'asc']],
            sScrollY: "200px",
            sAjaxSource: _private.config.modulo+'getGridFichaTecnica',
            fnDrawCallback: function() {
                $('#'+diccionario.tabs.T102+'gridFichaTecnica_filter').find('input').attr('placeholder','Buscar');
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: '#widget_'+diccionario.tabs.T102, //widget del datagrid
                    typeElement: 'button, #'+diccionario.tabs.T102+'chk_all'
                });
            }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getListaCaratulas = function(){
         _private.idCaratula = simpleScript.getParam(arguments[0]);
        
        simpleAjax.send({
            dataType: 'html',
            root: _private.config.modulo + 'getListaCaratulas',
            data: '&_idProducto='+_private.idCaratula,
            fnCallback: function(data){
                $('#cont-listaCaratulas').html(data);
            }
        });
        
    }
   
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
                                fichaTecnica.getUbigeo({
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
          
    this.publico.getNuevoFichaTecnica = function(btn){        
        simpleAjax.send({
            element: btn,
            dataType: 'html',
            root: _private.config.modulo + 'getNuevoFichaTecnica',
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.T102+'formFichaTecnica').modal('show');                       
            }
        });        
        
    };
    this.publico.getEditarFichaTecnica = function(id){
        _private.idProducto = id;
        
        simpleAjax.send({
            gifProcess: true,
            dataType: 'html',
            root: _private.config.modulo + 'getEditarFichaTecnica',
            data: '&_idFichaTecnica='+_private.idProducto,
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.T102+'formFichaTecnica').modal('show');
            }
        });
    };
       
    this.publico.postNuevoFichaTecnica = function(){        
        //Validar Manualmente:
        if( $('#'+diccionario.tabs.T102+'txt_ubicacion').val() == '' ||
            $('#'+diccionario.tabs.T102+'txt_ancho').val() == '' ||
            $('#'+diccionario.tabs.T102+'txt_alto').val() == '' ||
            $('#'+diccionario.tabs.T102+'lst_departamento').val() == '' ||
            $('#'+diccionario.tabs.T102+'lst_provincia').val() == '' ||
            $('#'+diccionario.tabs.T102+'lst_ubigeo').val() == '' ||
            $('#'+diccionario.tabs.T102+'lst_tipopanel').val() == '' ||
            $('#'+diccionario.tabs.T102+'txt_latitud').val() == '' ||
            $('#'+diccionario.tabs.T102+'txt_longitud').val() == ''){
                simpleScript.notify.error({
                    content: mensajes.MSG_12        
                 });
                return;
        }
        
        simpleAjax.send({
            flag: 1,
            element: '#'+diccionario.tabs.T102+'btnGfitec',
            root: _private.config.modulo + 'postNuevoFichaTecnica',
            form: '#'+diccionario.tabs.T102+'formFichaTecnica',
            clear: true,
            fnCallback: function(data) {            
               if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            fichaTecnica.getGridFichaTecnica();     
                            //simpleScript.closeModal('#'+diccionario.tabs.T102+'formFichaTecnica');
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
    
    this.publico.postEditarFichaTecnica = function(){
        
        simpleAjax.send({
            flag: 2,
            element: '#'+diccionario.tabs.T102+'btnGfitec',
            root: _private.config.modulo + 'postEditarFichaTecnica',
            form: '#'+diccionario.tabs.T102+'formFichaTecnica',
            data: '&_idProducto='+_private.idProducto,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            _private.idProducto = 0;
                            fichaTecnica.getGridFichaTecnica();
                            simpleScript.closeModal('#'+diccionario.tabs.T102+'formFichaTecnica');
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
    this.publico.postDeleteFichaTecnicaAll = function(btn){
        simpleScript.validaCheckBox({
            id: '#'+diccionario.tabs.T102+'gridFichaTecnica',
            msn: mensajes.MSG_9,
            fnCallback: function(){
                simpleScript.notify.confirm({
                    content: mensajes.MSG_7,
                    callbackSI: function(){
                        simpleAjax.send({
                            flag: 4,
                            element: btn,
                            form: '#'+diccionario.tabs.T102+'formGridFichaTecnica',
                            root: _private.config.modulo + 'postDeleteFichaTecnicaAll',
                            fnCallback: function(data) {
                                if(!isNaN(data.result) && parseInt(data.result) === 1){
                                    simpleScript.notify.ok({
                                        content: mensajes.MSG_8,
                                        callback: function(){
                                            fichaTecnica.getGridFichaTecnica();
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
var fichaTecnica = new fichaTecnica_();