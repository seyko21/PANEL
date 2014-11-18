var parametro_ = function(){
    
    var _private = {};
    
    _private.idParametro = 0;    
    
    _private.config = {
        modulo: 'configuracion/parametro/'
    };

    this.publico = {};
    
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.T100,
            label: $(element).attr('title'),
            fnCallback: function(){
                parametro.getCont();
            }
        });
    };
    
    this.publico.getCont = function(){
        simpleAjax.send({
            dataType: 'html',
            root: _private.config.modulo,
            fnCallback: function(data){
                $('#'+diccionario.tabs.T100+'_CONTAINER').html(data);
                parametro.getGridParametro();
            }
        });
    };
    
    this.publico.getGridParametro = function (){
        var oTable = $('#'+diccionario.tabs.T100+'gridParametro').dataTable({           
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "<input type='checkbox' id='"+diccionario.tabs.T100+"chk_all' onclick='simpleScript.checkAll(this,\"#"+diccionario.tabs.T100+"gridParametro\");'>", sWidth: "1%", sClass: "center", bSortable: false},
                {sTitle: "Nombre", sWidth: "30%"},                
                {sTitle: "Valor", sWidth: "30%"},
                {sTitle: "Alias", sWidth: "10%",  sClass: "center", bSortable: false},
                {sTitle: "Estado", sWidth: "8%",  sClass: "center", bSortable: false},
                {sTitle: "Acciones", sWidth: "8%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[1, 'asc']],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+'getGridParametro',
            fnDrawCallback: function() {
                $('#'+diccionario.tabs.T100+'gridParametro_filter').find('input:text').attr('placeholder','Buscar por nombre').css("width","300px");
                simpleScript.enterSearch("#"+diccionario.tabs.T100+"gridParametro",oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: '#widget_'+diccionario.tabs.T100, //widget del datagrid
                    typeElement: 'button, #'+diccionario.tabs.T100+'chk_all'
                });
                $('#'+diccionario.tabs.T100+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.T100+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getNuevoParametro = function(btn){
        simpleAjax.send({
            element: btn,
            dataType: 'html',
            root: _private.config.modulo + 'getNuevoParametro',
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.T100+'formParametro').modal('show');
            }
        });
    };
    
    this.publico.getEditarParametro = function(id){
        _private.idParametro = id;
        
        simpleAjax.send({
            gifProcess: true,
            dataType: 'html',
            root: _private.config.modulo + 'getEditarParametro',
            data: '&_idParametro='+_private.idParametro,
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.T100+'formParametro').modal('show');
            }
        });
    };
    
    this.publico.postNuevoParametro = function(){
        simpleAjax.send({
            flag: 1,
            element: '#'+diccionario.tabs.T100+'btnGparm',
            root: _private.config.modulo + 'postNuevoParametro',
            form: '#'+diccionario.tabs.T100+'formParametro',
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            parametro.getGridParametro();
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
    
    this.publico.postEditarParametro = function(){
        
        simpleAjax.send({
            flag: 2,
            element: '#'+diccionario.tabs.T100+'btnGparm',
            root: _private.config.modulo + 'postEditarParametro',
            form: '#'+diccionario.tabs.T100+'formParametro',
            data: '&_idParametro='+_private.idParametro,
            clear: true,
            fnCallback: function(data) {                
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            _private.idParametro = 0;
                            simpleScript.reloadGrid('#'+diccionario.tabs.T100+'gridParametro');
                            simpleScript.closeModal('#'+diccionario.tabs.T100+'formParametro');
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
    this.publico.postDeleteParametroAll = function(btn){
        simpleScript.validaCheckBox({
            id: '#'+diccionario.tabs.T100+'gridParametro',
            msn: mensajes.MSG_9,
            fnCallback: function(){
                simpleScript.notify.confirm({
                    content: mensajes.MSG_7,
                    callbackSI: function(){
                        simpleAjax.send({
                            flag: 3,
                            element: btn,
                            form: '#'+diccionario.tabs.T100+'formGridParametro',
                            root: _private.config.modulo + 'postDeleteParametroAll',
                            fnCallback: function(data) {
                                if(!isNaN(data.result) && parseInt(data.result) === 1){
                                    simpleScript.notify.ok({
                                        content: mensajes.MSG_8,
                                        callback: function(){
                                            parametro.getGridParametro();
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
               data: '&_idParametro='+id,
               clear: true,
               fnCallback: function(data) {
                   if(!isNaN(data.result) && parseInt(data.result) === 1){
                       simpleScript.notify.ok({
                           content: 'Parametro se desactivo correctamente',
                           callback: function(){
                               simpleScript.reloadGrid('#'+diccionario.tabs.T100+'gridParametro');
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
            data: '&_idParametro='+id,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: 'Parametro se activo correctamente',
                        callback: function(){
                            simpleScript.reloadGrid('#'+diccionario.tabs.T100+'gridParametro');
                        }
                    });
                }
            }
        });
    };      
    
    return this.publico;
    
};
var parametro = new parametro_();