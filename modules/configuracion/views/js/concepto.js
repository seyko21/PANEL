var concepto_ = function(){
    
    var _private = {};
    
    _private.idConcepto = 0;
    
    _private.config = {
        modulo: 'configuracion/concepto/'
    };

    this.publico = {};
    
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.T6,
            label: $(element).attr('title'),
            fnCallback: function(){
                concepto.getCont();
            }
        });
    };
    
    this.publico.getCont = function(){
        simpleAjax.send({
            dataType: 'html',
            root: _private.config.modulo,
            fnCallback: function(data){
                $('#'+diccionario.tabs.T6+'_CONTAINER').html(data);
                concepto.getGridConceptos();
            }
        });
    };
    
    this.publico.getGridConceptos = function (){
      var oTable = $('#'+diccionario.tabs.T6+'gridConceptos').dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "<input type='checkbox' id='"+diccionario.tabs.T6+"chk_all' onclick='simpleScript.checkAll(this,\"#"+diccionario.tabs.T6+"gridConceptos\");'>", sWidth: "1%", sClass: "center", bSortable: false},
                {sTitle: "Descripción", sWidth: "45%"},
                {sTitle: "Tipo de Concepto", sWidth: "20%" },
                {sTitle: "Destino", sWidth: "10%"},
                {sTitle: "Precio", sWidth: "10%",sClass: "right"},
                {sTitle: "Estado", sWidth: "10%",  sClass: "center", bSortable: false},
                {sTitle: "Acciones", sWidth: "5%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[1, 'asc']],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+'getGridConceptos',
            fnDrawCallback: function() {
                $('#'+diccionario.tabs.T6+'gridConceptos_filter').find('input').attr('placeholder','Buscar por Descripción').css("width","300px");
                simpleScript.enterSearch("#"+diccionario.tabs.T6+"gridConceptos",oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: '#widget_'+diccionario.tabs.T6,
                    typeElement: 'button, #'+diccionario.tabs.T6+'chk_all'
                });
                $('#'+diccionario.tabs.T6+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.T6+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getNuevoConcepto = function(btn){
        simpleAjax.send({
            element: btn,
            dataType: 'html',
            root: _private.config.modulo + 'getNuevoConcepto',
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.T6+'formConcepto').modal('show');
            }
        });
    };
    
    this.publico.getEditarConcepto = function(){
        _private.idConcepto = simpleScript.getParam(arguments[0]);
        
        simpleAjax.send({
            gifProcess: true,
            dataType: 'html',
            root: _private.config.modulo + 'getEditarConcepto',
            data: '&_idConcepto='+_private.idConcepto,
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.T6+'formConcepto').modal('show');
            }
        });
    };
    
    this.publico.postNuevoConcepto = function(){
        simpleAjax.send({
            flag: 1,
            element: '#'+diccionario.tabs.T6+'btnGconc',
            root: _private.config.modulo + 'postNuevoConcepto',
            form: '#'+diccionario.tabs.T6+'formConcepto',
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            concepto.getGridConceptos();                            
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
    
    this.publico.postEditarConcepto = function(){
        simpleAjax.send({
            flag: 2,
            element: '#'+diccionario.tabs.T6+'btnEconc',
            root: _private.config.modulo + 'postEditarConcepto',
            form: '#'+diccionario.tabs.T6+'formConcepto',
            data: '&_idConcepto='+_private.idConcepto,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            _private.idConcepto = 0;
                           simpleScript.reloadGrid('#'+diccionario.tabs.T6+'gridConceptos');
                           simpleScript.closeModal('#'+diccionario.tabs.T6+'formConcepto');
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
    
    this.publico.postDeleteConceptoAll = function(btn){
        simpleScript.validaCheckBox({
            id: '#'+diccionario.tabs.T6+'gridConceptos',
            msn: mensajes.MSG_9,
            fnCallback: function(){
                simpleScript.notify.confirm({
                    content: mensajes.MSG_7,
                    callbackSI: function(){
                        simpleAjax.send({
                            flag: 3,
                            element: btn,
                            form: '#'+diccionario.tabs.T6+'formGridConcepto',
                            root: _private.config.modulo + 'postDeleteConceptoAll',
                            fnCallback: function(data) {
                                if(!isNaN(data.result) && parseInt(data.result) === 1){
                                    simpleScript.notify.ok({
                                        content: mensajes.MSG_8,
                                        callback: function(){
                                            concepto.getGridConceptos();
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
               data: '&_idConcepto='+id,
               clear: true,
               fnCallback: function(data) {
                   if(!isNaN(data.result) && parseInt(data.result) === 1){
                       simpleScript.notify.ok({
                           content: 'Concepto se desactivo correctamente',
                           callback: function(){
                               simpleScript.reloadGrid('#'+diccionario.tabs.T6+'gridConceptos');
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
            data: '&_idConcepto='+id,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: 'Concepto se activo correctamente',
                        callback: function(){
                            simpleScript.reloadGrid('#'+diccionario.tabs.T6+'gridConceptos');
                        }
                    });
                }
            }
        });
    };     
    
    return this.publico;
    
};
var concepto = new concepto_();