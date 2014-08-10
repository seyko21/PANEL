var tipoPanel_ = function(){
    
    var _private = {};
    
    _private.idTipoPanel = 0;
    
    _private.config = {
        modulo: 'catalogo/tipoPanel/'
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
        $('#'+diccionario.tabs.T101+'gridTipoPanel').dataTable({
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
                $('#'+diccionario.tabs.T101+'gridTipoPanel_filter').find('input').attr('placeholder','Buscar');
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: '#widget_'+diccionario.tabs.T101, //widget del datagrid
                    typeElement: 'button, #'+diccionario.tabs.T101+'chk_all'
                });
            }
        });
        setup_widgets_desktop();
    };
    
   this.publico.getNuevoTipoPanel = function(btn){
        simpleAjax.send({
            element: btn,
            dataType: 'html',
            root: _private.config.modulo + 'getNuevoTipoPanel',
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.T101+'formTipoPanel').modal('show');
            }
        });
    };
    
    this.publico.getEditarTipoPanel = function(id){
        _private.idTipoPanel = id;
        
        simpleAjax.send({
            gifProcess: true,
            dataType: 'html',
            root: _private.config.modulo + 'getEditarTipoPanel',
            data: '&_idTipoPanel='+_private.idTipoPanel,
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.T101+'formTipoPanel').modal('show');
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
                            tipoPanel.getGridTipoPanel();
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
            element: '#'+diccionario.tabs.T101+'btnGtipa',
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
                            tipoPanel.getGridTipoPanel();
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
        
    
    
    return this.publico;
    
};
var tipoPanel = new tipoPanel_();