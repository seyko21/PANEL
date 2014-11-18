/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 18-09-2014 22:09:09 
* Descripcion : instalacion.js
* ---------------------------------------
*/
var instalacion_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idInstalacion = 0;
    
    _private.tab = null;
    
    _private.config = {
        modulo: "ordenservicio/instalacion/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : Instalacion*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.ORINS,
            label: $(element).attr("title"),
            fnCallback: function(){
                instalacion.getContenido();
            }
        });
    };
    
    /*contenido de tab: Instalacion*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.ORINS+"_CONTAINER").html(data);
                instalacion.getGridInstalacion();
            }
        });
    };
    
    this.publico.getGridInstalacion = function (){
        var oTable = $("#"+diccionario.tabs.ORINS+"gridInstalacion").dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "<input type='checkbox' id='"+diccionario.tabs.ORINS+"chk_all' onclick='simpleScript.checkAll(this,\"#"+diccionario.tabs.ORINS+"gridInstalacion\");'>", sWidth: "1%", sClass: "center", bSortable: false},
                {sTitle: "N° OS", sWidth: "10%", sClass: "center"},
                {sTitle: "Instalación", sWidth: "10%", sClass: "center"},                
                {sTitle: "Código", sWidth: "10%", sClass: "center"},
                {sTitle: "Ubicación", sWidth: "35%"},
                {sTitle: "Fecha", sWidth: "10%", sClass: "center"},
                {sTitle: "Total", sWidth: "10%", sClass: "right"},
                {sTitle: "Estado", sWidth: "8%", sClass: "center", bSortable: false},    
                {sTitle: "Acciones", sWidth: "10%", sClass: "center", bSortable: false}                
            ],
            aaSorting: [[1, "desc"],[2, "asc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridInstalacion",
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.ORINS+"gridInstalacion_filter").find("input").attr("placeholder","Buscar por N° OS o Instalación o Codigo o Ubicación").css("width","450px");
                 simpleScript.enterSearch("#"+diccionario.tabs.ORINS+"gridInstalacion",oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.ORINS,
                    typeElement: "button"
                });
                $('#'+diccionario.tabs.ORINS+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.ORINS+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getFormNewInstalacion = function(btn){
        //cerrartab clon
        simpleScript.closeTab(diccionario.tabs.ORINS+'clon');
        
        simpleScript.addTab({
            id : diccionario.tabs.ORINS+'new',
            label: $(btn).attr("title"),
            fnCallback: function(){
                instalacion.getContenidoNew();
            }
        });
    };
    
    this.publico.getContenidoNew = function(){
        instalacionScript.resetArrayConcepto();
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo+'getFormNewInstalacion',
            fnCallback: function(data){
                $("#"+diccionario.tabs.ORINS+"new_CONTAINER").html(data);
                setup_widgets_desktop();
            }
        });
    };
    
    this.publico.getFormBuscarCaratula = function(btn,tab){
        _private.tab = tab;
        simpleAjax.send({
            element: btn,
            dataType: 'html',
            root: _private.config.modulo + 'getFormBuscarCaratulta',
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.ORINS+'formBuscarCaratula').modal('show');
            }
        });
    };
    
    this.publico.getCaratulas = function(){
        $('#'+diccionario.tabs.ORINS+'gridCaratulasFound').dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            bFilter: false, 
            bInfo: false,
            sServerMethod: "POST",
            bPaginate: false,
            aoColumns: [
                {sTitle: "Nro.", sClass: "center",sWidth: "2%",  bSortable: false},
                {sTitle: "Código", sWidth: "10%",  bSortable: false},
                {sTitle: "Descripción", sWidth: "40%",  bSortable: false},
                {sTitle: "OS", sWidth: "10%",  bSortable: false}
            ],
            aaSorting: [[1, 'asc']],
            sScrollY: "250px",
            sAjaxSource: _private.config.modulo+'getCaratulas',
            fnServerParams: function(aoData) {
                aoData.push({"name": diccionario.tabs.ORINS+"_term", "value": $('#'+diccionario.tabs.ORINS+'txt_search').val()});
                aoData.push({"name": "_tab", "value": _private.tab});
            },
            fnDrawCallback: function() {
                $('#'+diccionario.tabs.ORINS+'gridCaratulasFound_wrapper').find('.dataTables_scrollBody').css('overflow-x','hidden');
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: '#'+diccionario.tabs.ORINS+'gridCaratulasFound',
                    typeElement: 'a'
                });
            }
        });
    };
    
    this.publico.getFormBuscarConceptos = function(btn,tab,tipo){
        _private.tab = tab;
        simpleAjax.send({
            element: btn,
            dataType: 'html',
            root: _private.config.modulo + 'getFormBuscarConceptos',
            data: '&_tipo='+tipo,
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.ORINS+'formBuscarConceptos').modal('show');
            }
        });
    };
    
    this.publico.getClonar = function(idCot){
        //cerrartab nuevo
        simpleScript.closeTab(diccionario.tabs.ORINS+'new');
        
        _private.idInstalacion = idCot;
        
        simpleScript.addTab({
            id : diccionario.tabs.ORINS+'clon',
            label: 'Clonar Orden de Instalación',
            fnCallback: function(){
                instalacion.getContClonar();
                instalacionScript.resetArrayConcepto();
            }
        });
    };
    
    this.publico.getContClonar = function(){
        simpleAjax.send({
            dataType: 'html',
            root: _private.config.modulo+'getFormClonarInstalacion',
            data: '&_idInstalacion='+_private.idInstalacion,
            fnCallback: function(data){
                $('#'+diccionario.tabs.ORINS+'clon_CONTAINER').html(data);
                _private.idInstalacion = 0;
            }
        });
    };
    
    this.publico.postNewInstalacion = function(){
        simpleAjax.send({
            element: "#"+diccionario.tabs.ORINS+"btnGinst",
            root: _private.config.modulo + "postNewInstalacion",
            form: "#"+diccionario.tabs.ORINS+"formNewInstalacion",
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            simpleScript.closeTab(diccionario.tabs.ORINS+'new');
                            simpleScript.closeTab(diccionario.tabs.ORINS+'clon');
                            instalacion.getGridInstalacion();
                        }
                    });
                }
            }
        });
    };
    
    this.publico.postPDF = function(btn,id,cod){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postPDF',
            data: '&_idInstalacion='+id+'&_cod='+cod,
            fnCallback: function(data) {
                if(parseInt(data.result) === 1){
                    $('#'+diccionario.tabs.ORINS+'btnDowPDF').off('click');
                    $('#'+diccionario.tabs.ORINS+'btnDowPDF').attr("onclick","window.open('public/files/"+data.archivo+"','_blank');generarCotizacion.deleteArchivo('"+data.archivo+"');");
                    $('#'+diccionario.tabs.ORINS+'btnDowPDF').click();
                }
            }
        });
    };
    
    this.publico.postAnularInstalacionAll = function(btn){
        simpleScript.validaCheckBox({
            id: "#"+diccionario.tabs.ORINS+"gridInstalacion",
            msn: mensajes.MSG_9,
            fnCallback: function(){
                simpleScript.notify.confirm({
                    content: mensajes.MSG_16,
                    callbackSI: function(){
                        simpleAjax.send({
                            flag: 3, //si se usa SP usar flag, sino se puede eliminar esta linea
                            element: btn,
                            form: "#"+diccionario.tabs.ORINS+"formGridInstalacion",
                            root: _private.config.modulo + "postAnularInstalacionAll",
                            fnCallback: function(data) {
                                if(!isNaN(data.result) && parseInt(data.result) === 1){
                                    simpleScript.notify.ok({
                                        content: mensajes.MSG_8,
                                        callback: function(){
                                            instalacion.getGridInstalacion();
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
var instalacion = new instalacion_();