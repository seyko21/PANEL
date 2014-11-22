/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 11-10-2014 16:10:11 
* Descripcion : asignarPanelSocio.js
* ---------------------------------------
*/
var asignarPanelSocio_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idAsignarPanelSocio = 0;
    
    _private.tab = '';
    
    _private.config = {
        modulo: "produccion/asignarPanelSocio/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : AsignarPanelSocio*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.APASO,
            label: $(element).attr("title"),
            fnCallback: function(){
                asignarPanelSocio.getContenido();
            }
        });
    };
    
    /*contenido de tab: AsignarPanelSocio*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.APASO+"_CONTAINER").html(data);
                asignarPanelSocio.getGridAsignarPanelSocio();
            }
        });
    };
    
    this.publico.getGridAsignarPanelSocio = function (){
        var oTable = $("#"+diccionario.tabs.APASO+"gridAsignarPanelSocio").dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "Socio", sWidth: "25%"},
                {sTitle: "Producto", sWidth: "25%"},
                {sTitle: "Monto Inv.", sWidth: "10%", sClass: "right", bSortable: false},
                {sTitle: "Ganancia %", sWidth: "10%", sClass: "right", bSortable: false},
                {sTitle: "Acciones", sWidth: "8%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[1, "asc"],[0, "asc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridAsignarPanelSocio",
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.APASO+"gridAsignarPanelSocio_filter").find("input").attr("placeholder","Buscar por socio o producto").css("width","250px");
                simpleScript.enterSearch("#"+diccionario.tabs.APASO+"gridAsignarPanelSocio",oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.APASO,
                    typeElement: "button"
                });
                $('#'+diccionario.tabs.APASO+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.APASO+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getGridSocios = function(){
        $('#'+diccionario.tabs.APASO+'gridSociosFound').dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            bFilter: false, 
            sServerMethod: "POST",
            bPaginate: false,
            aoColumns: [
                {sTitle: "Nro.", sClass: "center",sWidth: "2%",  bSortable: false},
                {sTitle: "Socio", sWidth: "50%"},
                {sTitle: "Monto Inv.", sWidth: "10%",sClass: "right",  bSortable: false},
                {sTitle: "Monto Saldo", sWidth: "10%", sClass: "right", bSortable: false}
            ],
            aaSorting: [[1, 'asc']],
            sScrollY: "250px",
            sAjaxSource: _private.config.modulo+'getGridSocios',
            fnServerParams: function(aoData) {
                aoData.push({"name": diccionario.tabs.APASO+"_term", "value": $('#'+diccionario.tabs.APASO+'txt_search').val()});
                aoData.push({"name": "_tab", "value": _private.tab});
            },
            fnDrawCallback: function() {
                $('#'+diccionario.tabs.APASO+'gridSociosFound_wrapper').find('.dt-bottom-row').remove();
                $('#'+diccionario.tabs.APASO+'gridSociosFound_wrapper').find('.dataTables_scrollBody').css('overflow-x','hidden');
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: '#'+diccionario.tabs.APASO+'gridSociosFound',
                    typeElement: 'a'
                });
            }
        });
        $('#'+diccionario.tabs.T8+'gridEmpleadosFound_filter').remove();
    };
    
    this.publico.getProductos = function(){
        $('#'+diccionario.tabs.APASO+'gridProductosFound').dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            bFilter: false, 
            bInfo: false,
            sServerMethod: "POST",
            bPaginate: false,
            aoColumns: [
                {sTitle: "Nro.", sClass: "center",sWidth: "2%",  bSortable: false},
                {sTitle: "Descripción", sWidth: "40%",  bSortable: false},
                {sTitle: "Invertido", sWidth: "10%",sClass: "right",  bSortable: false},
                {sTitle: "Asignado", sWidth: "10%",sClass: "right",  bSortable: false},
                {sTitle: "Saldo", sWidth: "10%", sClass: "right", bSortable: false}
            ],
            aaSorting: [[1, 'asc']],
            sScrollY: "250px",
            sAjaxSource: _private.config.modulo+'getProductos',
            fnServerParams: function(aoData) {
                aoData.push({"name": diccionario.tabs.APASO+"_term", "value": $('#'+diccionario.tabs.APASO+'txt_search').val()});
                aoData.push({"name": "_tab", "value": _private.tab});
                aoData.push({"name": diccionario.tabs.APASO+"txt_idpersona", "value": $('#'+diccionario.tabs.APASO+'txt_idpersona').val()});
            },
            fnDrawCallback: function() {
                $('#'+diccionario.tabs.APASO+'gridProductosFound_wrapper').find('.dataTables_scrollBody').css('overflow-x','hidden');
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: '#'+diccionario.tabs.APASO+'gridProductosFound',
                    typeElement: 'a'
                });
            }
        });
    };
    
    this.publico.getFormNewAsignarPanelSocio = function(btn){
        simpleScript.addTab({
            id : diccionario.tabs.APASO+'new',
            label: 'Nueva Asignación',
            fnCallback: function(){
                asignarPanelSocio.getContNew();
            }
        });
    };
    
    this.publico.getContNew = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo+'getFormNewAsignarPanelSocio',
            fnCallback: function(data){
                $("#"+diccionario.tabs.APASO+"new_CONTAINER").html(data);
            }
        });
    };
    
    this.publico.getFormBuscarSocio = function(btn,tab){
        _private.tab = tab;
        simpleAjax.send({
            element: btn,
            dataType: 'html',
            root: _private.config.modulo + 'getFormBuscarSocio',
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.APASO+'formBuscarSocio').modal('show');
            }
        });
    };
    
    this.publico.formBuscarProductoPanelSocio = function(btn,tab){
        var socio = $('#'+diccionario.tabs.APASO+'txt_idpersona').val();
        if(socio === ''){
            simpleScript.notify.warning({
                content: 'Debe de buscar un socio'
            });
            return false;
        }
        
        _private.tab = tab;
         
        simpleAjax.send({
            element: btn,
            dataType: 'html',
            root: _private.config.modulo + 'formBuscarProductoPanelSocio',
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.APASO+'formBuscarProductoPanelSocio').modal('show');
            }
        });
    };
    
    this.publico.getInversiones = function(){
        simpleAjax.send({
            gifProcess: true,
            dataType: 'html',
            root: _private.config.modulo + 'getTableInversiones',
            data: '&_idPersona='+$('#'+diccionario.tabs.APASO+'txt_idpersona').val(),
            fnCallback: function(data){
                $('#'+diccionario.tabs.APASO+'gridInversiones tbody').html(data);
            }
        });
    };
    
    this.publico.postNewAsignarPanelSocio = function(){
        simpleAjax.send({
            element: "#"+diccionario.tabs.APASO+"btnGpan",
            root: _private.config.modulo + "postNewAsignarPanelSocio",
            form: "#"+diccionario.tabs.APASO+"formNewAsignarPanelSocio",
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            simpleScript.closeTab(diccionario.tabs.APASO+'new');
                            asignarPanelSocio.getGridAsignarPanelSocio();
                        }
                    });
                }
            }
        });
    };
    
    this.publico.postDeleteAsignarPanelSocio = function(btn,id){
        simpleScript.notify.confirm({
            content: mensajes.MSG_5,
            callbackSI: function(){
                simpleAjax.send({
                    element: btn,
                    root: _private.config.modulo + "postDeleteAsignarPanelSocio",
                    data: '&_idAsignacionPanel='+id,
                    fnCallback: function(data) {
                        if(!isNaN(data.result) && parseInt(data.result) === 1){
                            simpleScript.notify.ok({
                                content: mensajes.MSG_6,
                                callback: function(){
                                    //simpleScript.reloadGridDelete("#"+diccionario.tabs.APASO+"gridAsignarPanelSocio");
                                    asignarPanelSocio.getGridAsignarPanelSocio();
                                }
                            });
                        }else if(!isNaN(data.result) && parseInt(data.result) === 2){
                            simpleScript.notify.error({
                                content: 'Registro no puede eliminarse porque esta siendo usado en otras operaciones'
                            });
                        }
                    }
                });
            }
        });
    } 
    
    return this.publico;
    
};
var asignarPanelSocio = new asignarPanelSocio_();