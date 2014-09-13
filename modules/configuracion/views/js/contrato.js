/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 12-09-2014 17:09:13 
* Descripcion : contrato.js
* ---------------------------------------
*/
var contrato_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idContrato = 0;
    
    _private.config = {
        modulo: "Configuracion/contrato/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : Contrato*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.CONTR,
            label: $(element).attr("title"),
            fnCallback: function(){
                contrato.getContenido();
            }
        });
    };
    
    /*contenido de tab: Contrato*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.CONTR+"_CONTAINER").html(data);
                contrato.getGridContrato();
            }
        });
    };
    
    this.publico.getGridContrato = function (){
        var oTable = $("#"+diccionario.tabs.CONTR+"gridContrato").dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "<input type='checkbox' id='"+diccionario.tabs.CONTR+"chk_all' onclick='simpleScript.checkAll(this,\"#"+diccionario.tabs.CONTR+"gridContrato\");'>", sWidth: "1%", sClass: "center", bSortable: false},
                {sTitle: "Nombre", sWidth: "35%"},
                {sTitle: "Fecha Creado", sClass: "center", sWidth: "25%"},
                {sTitle: "Estado", sWidth: "10%", sClass: "center", bSortable: false},                
                {sTitle: "Acciones", sWidth: "8%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[2, "asc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridContrato",
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.CONTR+"gridContrato_filter").find("input").attr("placeholder","Buscar por Contrato").css("width","250px");
                simpleScript.enterSearch("#"+diccionario.tabs.CONTR+"gridContrato",oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.CONTR,
                    typeElement: "button"
                });
            }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getFormNewContrato = function(){        
        simpleScript.addTab({
            id : diccionario.tabs.CONTR+'new',
            label: 'Nuevo Contrato',
            fnCallback: function(){
                contrato.getContratoNew();
            }
        });               
    };
        
    this.publico.getContratoNew = function(){
        simpleAjax.send({
            dataType: 'html',
            root: _private.config.modulo+'getFormNewContrato',
            fnCallback: function(data){
                $('#'+diccionario.tabs.CONTR+'new_CONTAINER').html(data);
            }
        });
    };    
    
    this.publico.closeTabNew = function(){
        simpleScript.closeTab(diccionario.tabs.CONTR+'new');  
    };
    
    this.publico.getFormEditContrato = function(btn,id){
        _private.idContrato = id;
            
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormEditContrato",
            data: "&_idContrato="+_private.idContrato,
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.CONTR+"formEditContrato").modal("show");
            }
        });
    };
    
    this.publico.postNewContrato = function(){
        simpleAjax.send({
            flag: 1,
            element: "#"+diccionario.tabs.CONTR+"btnGrContrato",
            root: _private.config.modulo + "postNewContrato",
            form: "#"+diccionario.tabs.CONTR+"formNewContrato",
            data: "&_cuerpo="+$('#'+diccionario.tabs.CONTR+'formNewContrato .summernote').code(),
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            contrato.closeTabNew()
                            contrato.getGridContrato();                                                        
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
    
//    this.publico.postEditContrato = function(){
//        simpleAjax.send({
//            flag: AQUI FLAG,
//            element: "#"+diccionario.tabs.CONTR+"btnEdContrato",
//            root: _private.config.modulo + "postEditContrato",
//            form: "#"+diccionario.tabs.CONTR+"formEditContrato",
//            data: "&_idContrato="+_private.idContrato,
//            clear: true,
//            fnCallback: function(data) {
//                if(!isNaN(data.result) && parseInt(data.result) === 1){
//                    simpleScript.notify.ok({
//                        content: mensajes.MSG_10,
//                        callback: function(){
//                            _private.idContrato = 0;
//                            simpleScript.closeModal("#"+diccionario.tabs.CONTR+"formEditContrato");
//                            simpleScript.reloadGrid("#"+diccionario.tabs.CONTR+"gridContrato");
//                        }
//                    });
//                }else if(!isNaN(data.result) && parseInt(data.result) === 2){
//                    simpleScript.notify.error({
//                        content: "Contrato ya existe."
//                    });
//                }
//            }
//        });
//    };
//    
//    this.publico.postDeleteContrato = function(btn,id){
//        simpleScript.notify.confirm({
//            content: mensajes.MSG_5,
//            callbackSI: function(){
//                simpleAjax.send({
//                    flag: AQUI FLAG,
//                    element: btn,
//                    gifProcess: true,
//                    data: "&_idContrato="+id,
//                    root: _private.config.modulo + "postDeleteContrato",
//                    fnCallback: function(data) {
//                        if(!isNaN(data.result) && parseInt(data.result) === 1){
//                            simpleScript.notify.ok({
//                                content: mensajes.MSG_6,
//                                callback: function(){
//                                    simpleScript.reloadGrid("#"+diccionario.tabs.CONTR+"gridContrato");
//                                }
//                            });
//                        }
//                    }
//                });
//            }
//        });
//    };
//    
//    this.publico.postDeleteContratoAll = function(btn){
//        simpleScript.validaCheckBox({
//            id: "#"+diccionario.tabs.CONTR+"gridContrato",
//            msn: mensajes.MSG_9,
//            fnCallback: function(){
//                simpleScript.notify.confirm({
//                    content: mensajes.MSG_7,
//                    callbackSI: function(){
//                        simpleAjax.send({
//                            flag: 3, //si se usa SP usar flag, sino se puede eliminar esta linea
//                            element: btn,
//                            form: "#"+diccionario.tabs.CONTR+"formGridContrato",
//                            root: _private.config.modulo + "postDeleteContratoAll",
//                            fnCallback: function(data) {
//                                if(!isNaN(data.result) && parseInt(data.result) === 1){
//                                    simpleScript.notify.ok({
//                                        content: mensajes.MSG_8,
//                                        callback: function(){
//                                            contrato.getGridContrato();
//                                        }
//                                    });
//                                }
//                            }
//                        });
//                    }
//                });
//            }
//        });
//    };
    
    return this.publico;
    
};
var contrato = new contrato_();