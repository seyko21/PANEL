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
                {sTitle: "N°", sWidth: "1%",bSortable: false},
                {sTitle: "Acciones", sWidth: "8%", sClass: "center", bSortable: false},
                {sTitle: "CAMPO 1", sWidth: "25%"},
                {sTitle: "CAMPO 2", sWidth: "25%", bSortable: false},
                {sTitle: "Estado", sWidth: "10%", sClass: "center", bSortable: false}                
            ],
            aaSorting: [[2, "asc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridInstalacion",
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.ORINS+"gridInstalacion_filter").find("input").attr("placeholder","Buscar por Instalacion").css("width","250px");
                simpleScript.enterSearch("#"+diccionario.tabs.ORINS+"gridInstalacion",oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.ORINS,
                    typeElement: "button"
                });
            }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getFormNewInstalacion = function(btn){
        simpleScript.addTab({
            id : diccionario.tabs.ORINS+'new',
            label: $(btn).attr("title"),
            fnCallback: function(){
                instalacion.getContenidoNew();
            }
        });
    };
    
    this.publico.getContenidoNew = function(){
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
    
    this.publico.getFormBuscarConceptos = function(btn,tab){
        _private.tab = tab;
        simpleAjax.send({
            element: btn,
            dataType: 'html',
            root: _private.config.modulo + 'getFormBuscarConceptos',
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.ORINS+'formBuscarConceptos').modal('show');
            }
        });
    };
    
    
    
    
    
    
    
    
    
    
    
    
    this.publico.postNewInstalacion = function(){
        simpleAjax.send({
            flag: 1,
            element: "#"+diccionario.tabs.ORINS+"btnGrInstalacion",
            root: _private.config.modulo + "postNewInstalacion",
            form: "#"+diccionario.tabs.ORINS+"formNewInstalacion",
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            simpleScript.reloadGrid("#"+diccionario.tabs.ORINS+"gridInstalacion");
                        }
                    });
                }else if(!isNaN(data.result) && parseInt(data.result) === 2){
                    simpleScript.notify.error({
                        content: "Instalacion ya existe."
                    });
                }
            }
        });
    };
    
    this.publico.postDeleteInstalacionAll = function(btn){
        simpleScript.validaCheckBox({
            id: "#"+diccionario.tabs.ORINS+"gridInstalacion",
            msn: mensajes.MSG_9,
            fnCallback: function(){
                simpleScript.notify.confirm({
                    content: mensajes.MSG_7,
                    callbackSI: function(){
                        simpleAjax.send({
                            flag: 3, //si se usa SP usar flag, sino se puede eliminar esta linea
                            element: btn,
                            form: "#"+diccionario.tabs.ORINS+"formGridInstalacion",
                            root: _private.config.modulo + "postDeleteInstalacionAll",
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