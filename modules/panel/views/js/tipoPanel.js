var tipoPanel_ = function(){
    
    var _private = {};
    
    _private.idTipoPanel = 0;
    _private.callbackData = null;
    
    _private.config = {
        modulo: 'panel/tipoPanel/'
    };

    this.publico = {};
    
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.T101,
            label: $(element).attr('title'),
            fnCallback: function(){
                tipoPanel.getCont();
            }
        });
    };
    
    this.publico.getCont = function(){
        simpleAjax.send({
            dataType: 'html',
            root: _private.config.modulo,
            fnCallback: function(data){
                $('#'+diccionario.tabs.T101+'_CONTAINER').html(data);
                tipoPanel.getGridTipoPanel();
            }
        });
    };
    
    this.publico.getGridTipoPanel = function (){
        var oTable = $('#'+diccionario.tabs.T101+'gridTipoPanel').dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "<input type='checkbox' id='"+diccionario.tabs.T101+"chk_all' onclick='simpleScript.checkAll(this,\"#"+diccionario.tabs.T101+"gridTipoPanel\");'>", sWidth: "1%", sClass: "center", bSortable: false},
                {sTitle: "Descripción", sWidth: "60%"},                
                {sTitle: "Estado", sWidth: "8%",  sClass: "center", bSortable: false},
                {sTitle: "Acciones", sWidth: "15%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[1, 'asc']],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+'getGridTipoPanel',            
            fnDrawCallback: function() {
                $('#'+diccionario.tabs.T101+'gridTipoPanel_filter').find('input').attr('placeholder','Buscar descripción').css('width','350px');
                simpleScript.enterSearch("#"+diccionario.tabs.T101+'gridTipoPanel',oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: '#widget_'+diccionario.tabs.T101, //widget del datagrid
                    typeElement: 'button, #'+diccionario.tabs.T101+'chk_all'
                });
                $('#'+diccionario.tabs.T101+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.T101+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();
    };
    
   this.publico.getNuevoTipoElemento = function(btn, callbackData){
       /*para cuando se llama formulario desde otro modulo*/
        _private.callbackData = callbackData;  
        simpleAjax.send({
            element: btn,
            dataType: 'html',
            root: _private.config.modulo + 'getNuevoTipoElemento',
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.T101+'formTipoPanel').modal('show');
            }
        });
    };
    
    this.publico.getEditarTipoElemento = function(id){
        _private.idTipoPanel = id;
        
        simpleAjax.send({
            gifProcess: true,
            dataType: 'html',
            root: _private.config.modulo + 'getEditarTipoElemento',
            data: '&_idTipoPanel='+_private.idTipoPanel,
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.T101+'formTipoPanel').modal('show');
            }
        });
    };
    
    this.publico.getAddListTipoPanel = function(){
        simpleAjax.send({
            root: _private.config.modulo + 'getAddListTipoPanel',
            fnCallback: function(data){
                simpleScript.closeModal('#'+diccionario.tabs.T101+'formTipoPanel');
                $(_private.callbackData).append('<option value="'+data.id_tipo+'">'+data.descripcion+'</option>');
                _private.callbackData = null;
            }
        });
        
    };
    
    this.publico.postNuevoTipoPanel = function(){
        simpleAjax.send({
            flag: 1,
            element: '#'+diccionario.tabs.T101+'btnGtipa',
            root: _private.config.modulo + 'postNuevoTipoPanel',
            form: '#'+diccionario.tabs.T101+'formTipoPanel',
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            
                             /*si se graba desde otro modulo*/                            
                            if(_private.callbackData.length > 0){                                
                               tipoPanel.getAddListTipoPanel();                                
                            }
                            /*se verifica si existe tabb para recargar grid*/
                            if($('#'+diccionario.tabs.T101+'_CONTAINER').length > 0){
                              tipoPanel.getGridTipoPanel();
                            }
                                                        
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
    
    this.publico.postEditarTipoPanel = function(){
        
        simpleAjax.send({
            flag: 2,
            element: '#'+diccionario.tabs.T101+'btnACTtipa',
            root: _private.config.modulo + 'postEditarTipoPanel',
            form: '#'+diccionario.tabs.T101+'formTipoPanel',
            data: '&_idTipoPanel='+_private.idTipoPanel,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            _private.idTipoPanel = 0;
                            simpleScript.reloadGrid('#'+diccionario.tabs.T101+'gridTipoPanel'); 
                            simpleScript.closeModal('#'+diccionario.tabs.T101+'formTipoPanel');
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
    this.publico.postDeleteTipoPanelAll = function(btn){
        simpleScript.validaCheckBox({
            id: '#'+diccionario.tabs.T101+'gridTipoPanel',
            msn: mensajes.MSG_9,
            fnCallback: function(){
                simpleScript.notify.confirm({
                    content: mensajes.MSG_7,
                    callbackSI: function(){
                        simpleAjax.send({
                            flag: 4,
                            element: btn,
                            form: '#'+diccionario.tabs.T101+'formGridTipoPanel',
                            root: _private.config.modulo + 'postDeleteTipoPanelAll',
                            fnCallback: function(data) {
                                if(!isNaN(data.result) && parseInt(data.result) === 1){
                                    simpleScript.notify.ok({
                                        content: mensajes.MSG_8,
                                        callback: function(){
                                            tipoPanel.getGridTipoPanel();
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
               data: '&_idTipoPanel='+id,
               clear: true,
               fnCallback: function(data) {
                   if(!isNaN(data.result) && parseInt(data.result) === 1){
                       simpleScript.notify.ok({
                           content: 'Tipo Panel se desactivo correctamente',
                           callback: function(){
                               simpleScript.reloadGrid('#'+diccionario.tabs.T101+'gridTipoPanel');
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
            data: '&_idTipoPanel='+id,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: 'Tipo Panel se activo correctamente',
                        callback: function(){
                            simpleScript.reloadGrid('#'+diccionario.tabs.T101+'gridTipoPanel');
                        }
                    });
                }
            }
        });
    };            
    
    
    return this.publico;
    
};
var tipoPanel = new tipoPanel_();