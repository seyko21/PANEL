var tipoConcepto_ = function(){
    
    var _private = {};
    
    _private.idTipoConcepto = 0;
    
    _private.callbackData = null;
    
    _private.config = {
        modulo: 'configuracion/tipoConcepto/'
    };
    
    this.publico = {};
    
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.T5,
            label: $(element).attr('title'),
            fnCallback: function(){
                tipoConcepto.getCont();
            }
        });
    };
    
    this.publico.getCont = function(){
        simpleAjax.send({
            dataType: 'html',
            root: _private.config.modulo,
            fnCallback: function(data){
                $('#'+diccionario.tabs.T5+'_CONTAINER').html(data);
                tipoConcepto.getTipoConceptos();
            }
        });
    };
    
    this.publico.getTipoConceptos = function (){
        var oTable = $('#'+diccionario.tabs.T5+'gridTipoConceptos').dataTable({             
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
//            "sDom": '<"top"i>rt<"bottom"fp><"clear">',        //"sDom": 'T C lfrtip',
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "<input type='checkbox' id='"+diccionario.tabs.T5+"chk_all' onclick='simpleScript.checkAll(this,\"#"+diccionario.tabs.T5+"gridTipoConceptos\");'>", sWidth: "1%", sClass: "center", bSortable: false},
                {sTitle: "Descripción", sWidth: "55%"},
                {sTitle: "Estado", sWidth: "8%",  sClass: "center", bSortable: false},
                {sTitle: "Acciones", sWidth: "15%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[1, 'asc']],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+'getTipoConceptos',
            fnDrawCallback: function() {
                $('#'+diccionario.tabs.T5+'gridTipoConceptos_filter').find('input').attr('placeholder','Buscar por descripción').css("width","300px");
                simpleScript.enterSearch("#"+diccionario.tabs.T5+"gridTipoConceptos",oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: '#widget_'+diccionario.tabs.T5+'tipoconceptos',
                    typeElement: 'button, #'+diccionario.tabs.T5+'chk_all'
                });
                $('#'+diccionario.tabs.T5+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.T5+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getNuevoTipoConcepto = function(btn,callbackData){
        /*para cuando se llama formulario desde otro modulo*/
        _private.callbackData = callbackData;        
        simpleAjax.send({
            element: btn,
            dataType: 'html',
            root: _private.config.modulo + 'getNuevoTipoConcepto',
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.T5+'formTipoConcepto').modal('show');
            }
        });
    };
    
    this.publico.getEditarTipoConcepto = function(id){
        _private.idTipoConcepto = id;
        
        simpleAjax.send({
            gifProcess: true,
            dataType: 'html',
            root: _private.config.modulo + 'getEditarTipoConcepto',
            data: '&_idTipoConcepto='+_private.idTipoConcepto,
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.T5+'formTipoConcepto').modal('show');
            }
        });
    };
    
    this.publico.getAddListTipoConcepto = function(){
        simpleAjax.send({
            root: _private.config.modulo + 'getAddListTipoConcepto',
            fnCallback: function(data){
                simpleScript.closeModal('#'+diccionario.tabs.T5+'formTipoConcepto');
                $(_private.callbackData).append('<option value="'+data.id_tipo+'">'+data.descripcion+'</option>');
                _private.callbackData = null;
            }
        });
        
    };
    
    this.publico.postNuevoTipoConcepto = function(){
        simpleAjax.send({
            flag: 1,
            element: '#'+diccionario.tabs.T5+'btnGtconc',
            root: _private.config.modulo + 'postNuevoTipoConcepto',
            form: '#'+diccionario.tabs.T5+'formTipoConcepto',
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            /*si se graba desde otro modulo*/                            
                            if(_private.callbackData.length > 0){                                
                               tipoConcepto.getAddListTipoConcepto();                                
                            }
                            /*se verifica si existe tabb para recargar grid*/
                            if($('#'+diccionario.tabs.T5+'_CONTAINER').length > 0){
                               tipoConcepto.getTipoConceptos();
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
    
    this.publico.postEditarTipoConcepto = function(){
        simpleAjax.send({
            flag: 2,
            element: '#'+diccionario.tabs.T5+'btnGtconc',
            root: _private.config.modulo + 'postEditarTipoConcepto',
            form: '#'+diccionario.tabs.T5+'formTipoConcepto',
            data: '&_idTipoConcepto='+_private.idTipoConcepto,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            _private.idTipoConcepto = 0;
                            simpleScript.reloadGrid('#'+diccionario.tabs.T5+'gridTipoConceptos');
                            simpleScript.closeModal('#'+diccionario.tabs.T5+'formTipoConcepto');
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
    
    this.publico.postDeleteTipoConcepto = function(){
        var idTipo = simpleScript.getParam(arguments[0]);
        
        simpleScript.notify.confirm({
            content: mensajes.MSG_5,
            callbackSI: function(){
                simpleAjax.send({
                    flag: 3,
                    gifProcess: true,
                    data: '&_idTipoConcepto='+idTipo,
                    root: _private.config.modulo + 'postDeleteTipoConcepto',
                    fnCallback: function(data) {
                        if(!isNaN(data.result) && parseInt(data.result) === 1){
                            simpleScript.notify.ok({
                                content: mensajes.MSG_6,
                                callback: function(){
                                    tipoConcepto.getTipoConceptos();
                                }
                            });
                        }
                    }
                });
            }
        });
    };
    
    this.publico.postDeleteTipoConceptoAll = function(btn){
        simpleScript.validaCheckBox({
            id: '#'+diccionario.tabs.T5+'gridTipoConceptos',
            msn: mensajes.MSG_9,
            fnCallback: function(){
                simpleScript.notify.confirm({
                    content: mensajes.MSG_7,
                    callbackSI: function(){
                        simpleAjax.send({
                            flag: 4,
                            element: btn,
                            form: '#'+diccionario.tabs.T5+'formGridTipoConcepto',
                            root: _private.config.modulo + 'postDeleteTipoConceptoAll',
                            fnCallback: function(data) {
                                if(!isNaN(data.result) && parseInt(data.result) === 1){
                                    simpleScript.notify.ok({
                                        content: mensajes.MSG_8,
                                        callback: function(){
                                            tipoConcepto.getTipoConceptos();
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
               data: '&_idTipoConcepto='+id,
               clear: true,
               fnCallback: function(data) {
                   if(!isNaN(data.result) && parseInt(data.result) === 1){
                       simpleScript.notify.ok({
                           content: 'Tipo Concepto se desactivo correctamente',
                           callback: function(){
                               simpleScript.reloadGrid('#'+diccionario.tabs.T5+'gridTipoConceptos');
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
            data: '&_idTipoConcepto='+id,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: 'Tipo Concepto se activo correctamente',
                        callback: function(){
                            simpleScript.reloadGrid('#'+diccionario.tabs.T5+'gridTipoConceptos');
                        }
                    });
                }
            }
        });
    };    
    
    return this.publico;
    
};
 var tipoConcepto = new tipoConcepto_();
 
 