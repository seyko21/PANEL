var acciones_ = function(){
    
    var _private = {};
    
    _private.config = {
        modulo: 'roles/acciones/'
    };
    
    _private.idAccion = 0;
    
    this.public = {};
    
    this.public.main = function(){
        simpleScript.addTab({
            id : diccionario.tabs.T2,
            label: 'Configurar acciones',
            fnCallback: function(){
                acciones.getCont();
            }
        });
    };
    
    this.public.getCont = function(){
        simpleAjax.send({
            dataType: 'html',
            root: _private.config.modulo+'acciones',
            fnCallback: function(data){
                $('#'+diccionario.tabs.T2+'_CONTAINER').html(data);
                acciones.getGridAcciones();
            }
        });
    };
    
    this.public.getGridAcciones = function (){        
        var oTable = $('#gridAcciones').dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "Código", sClass: "center",sWidth: "10%"},
                {sTitle: "Acción", sWidth: "25%"},
                {sTitle: "Diseño", sWidth: "15%", sClass: "center", bSortable: false},
                {sTitle: "Alias", sWidth: "10%"},
                {sTitle: "Estado", sWidth: "10%", sClass: "center", bSortable: false},
                {sTitle: "Acciones", sWidth: "15%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[1, 'asc']],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+'getAcciones',
            fnDrawCallback: function() {
                $('#gridAcciones_filter').find('input').attr('placeholder','Buscar por acción');
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: '#widget_'+diccionario.tabs.T2+'acciones',
                    typeElement: 'button'
                });
                $('#'+diccionario.tabs.T2+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.T2+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();
    };
    
    this.public.getNuevaAccion = function(btn){
        var bt = '#CRDACbtnNew';
        if(btn !== undefined){
            bt = btn;
        }
        simpleAjax.send({
            element: bt,
            dataType: 'html',
            root: _private.config.modulo + 'getNuevaAccion',
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#CRDACformNuevaAccion').modal('show');
                accionesScript.validateAccion({
                    form: '#CRDACformNuevaAccion', 
                    evento: 'acciones.postAccion()'
                });
            }
        });
    };
    
    /*extraer rol para editar*/
    this.public.getAccion = function(){
        _private.idAccion = simpleScript.getParam(arguments[0]);
       
        simpleAjax.send({
            dataType: 'html',
            gifProcess: true,
            data: '&_key='+_private.idAccion,
            root: _private.config.modulo + 'getEditAccion',
            fnCallback: function(data){
                $('#cont-modal').append(data);
                $('#CRDACformEditAccion').modal('show');
                accionesScript.validateAccion({
                    form: '#CRDACformEditAccion', 
                    evento: 'acciones.postEditAccion()'
                });
            }
        });
    };
    
    this.public.postAccion = function(){
        simpleAjax.send({
            flag: 1,
            element: '#CRDACbtnGrabaAccion',
            root: _private.config.modulo + 'postAccion',
            form: '#CRDACformNuevaAccion',
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            acciones.getGridAcciones();
                        }
                    });
                }else if(!isNaN(data.result) && parseInt(data.result) === 2){
                    simpleScript.notify.error({
                        content: 'Acción ya existe'
                    });
                }else if(!isNaN(data.result) && parseInt(data.result) === 3){
                    simpleScript.notify.error({
                        content: 'Alias ya existe'
                    });
                }
            }
        });
    };
    
    this.public.postEditAccion = function(){
        simpleAjax.send({
            flag: 2,
            element: '#CRDACbtnEditaAccion',
            root: _private.config.modulo + 'postAccion',
            form: '#CRDACformEditAccion',
            data: '&_key='+_private.idAccion,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                           simpleScript.reloadGrid('#gridAcciones');
                           simpleScript.closeModal('#CRDACformEditAccion');
                        }
                    });
                }else if(!isNaN(data.result) && parseInt(data.result) === 2){
                    simpleScript.notify.error({
                        content: 'Acción ya existe'
                    });
                }else if(!isNaN(data.result) && parseInt(data.result) === 3){
                    simpleScript.notify.error({
                        content: 'Alias ya existe'
                    });
                }
            }
        });
    };
    
    this.public.postDeleteAccion = function(){
        _private.idAccion = simpleScript.getParam(arguments[0]);
        
        simpleScript.notify.confirm({
            content: mensajes.MSG_5,
            callbackSI: function(){
                simpleAjax.send({
                    flag: 3,
                    gifProcess: true,
                    data: '&_key='+_private.idAccion,
                    root: _private.config.modulo + 'postDeleteAccion',
                    fnCallback: function(data) {
                        if(!isNaN(data.result) && parseInt(data.result) === 1){
                            simpleScript.notify.ok({
                                content: mensajes.MSG_6,
                                callback: function(){
                                    acciones.getGridAcciones();
                                }
                            });
                        }
                    }
                });
            }
        });
    };
    
    return this.public;
    
};
var acciones = new acciones_();