/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 19-08-2014 06:08:51 
* Descripcion : asignarCuenta.js
* ---------------------------------------
*/
var asignarCuenta_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idAsignarCuenta = 0;
    
    _private.config = {
        modulo: "personal/asignarCuenta/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : AsignarCuenta*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.ASCU,
            label: $(element).attr("title"),
            fnCallback: function(){
                asignarCuenta.getContenido();
            }
        });
    };
    
    /*contenido de tab: AsignarCuenta*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.ASCU+"_CONTAINER").html(data);
                asignarCuenta.getGridAsignarCuenta();
            }
        });
    };
    
    this.publico.getGridAsignarCuenta = function (){
        var oTable = $('#'+diccionario.tabs.ASCU+'gridAsignarCuenta').dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "<input type='checkbox' id='"+diccionario.tabs.ASCU+"chk_all' onclick='simpleScript.checkAll(this,\"#"+diccionario.tabs.ASCU+"gridAsignarCuenta\");'>", sWidth: "1%", sClass: "center", bSortable: false},
                {sTitle: "Código", sClass: "center",sWidth: "8%"},
                {sTitle: "Fecha", sWidth: "8%", sClass: "center"},
                {sTitle: "Producto", sWidth: "30%"},
                {sTitle: "Vendedor", sWidth: "20%"},
                {sTitle: "Comisión", sWidth: "10%", sClass: "center"},
                {sTitle: "Estado", sWidth: "8%", sClass: "center", bSortable: false},
                {sTitle: "Acciones", sWidth: "5%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[1, 'desc']],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+'getGridAsignarCuenta',
            fnDrawCallback: function() {
                $('#'+diccionario.tabs.ASCU+'gridAsignarCuenta_filter').find('input').attr('placeholder','Buscar por producto o vendedor').css('width','280px');
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: '#widget_'+diccionario.tabs.ASCU,
                    typeElement: 'button'
                });
            }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getFormNewAsignarCuenta = function(btn){
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormNewAsignarCuenta",
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.ASCU+"formNewAsignarCuenta").modal("show");
            }
        });
    };
    
    this.publico.postNewAsignarCuenta = function(){
        simpleScript.validaCheckBox({
            id: '#'+diccionario.tabs.ASCU+'gridProductos',
            msn: mensajes.MSG_9,
            fnCallback: function(){
                simpleAjax.send({
                    flag: 1,
                    element: "#"+diccionario.tabs.ASCU+"btnGrAsignarCuenta",
                    root: _private.config.modulo + "postNewAsignarCuenta",
                    form: "#"+diccionario.tabs.ASCU+"formNewAsignarCuenta",
                    fnCallback: function(data){
                        if(!isNaN(data.result) && parseInt(data.result) === 1){
                            simpleScript.notify.ok({
                                content: mensajes.MSG_3,
                                callback: function(){
                                    asignarCuenta.getGridAsignarCuenta();
                                    simpleScript.closeModal('#'+diccionario.tabs.ASCU+'formNewAsignarCuenta');
                                }
                            });
                        }
                        if(!isNaN(data.duplicado) && parseInt(data.duplicado) === 1){
                            simpleScript.notify.error({
                                content: 'Producto: '+data.codigo+' ya se asignó. Intente con otros productos.'
                            });
                        }
                    }
                });
            }
        });
    };
    
    this.publico.postAnularAsignarCuentaAll = function(btn){
        simpleScript.validaCheckBox({
            id: "#"+diccionario.tabs.ASCU+"gridAsignarCuenta",
            msn: mensajes.MSG_9,
            fnCallback: function(){
                simpleScript.notify.confirm({
                    content: mensajes.MSG_7,
                    callbackSI: function(){
                        simpleAjax.send({
                            flag: 3, //si se usa SP usar flag, sino se puede eliminar esta linea
                            element: btn,
                            form: "#"+diccionario.tabs.ASCU+"formGridAsignarCuenta",
                            root: _private.config.modulo + "postAnularAsignarCuentaAll",
                            fnCallback: function(data) {
                                if(!isNaN(data.result) && parseInt(data.result) === 1){
                                    simpleScript.notify.ok({
                                        content: mensajes.MSG_8,
                                        callback: function(){
                                            asignarCuenta.getGridAsignarCuenta();
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
var asignarCuenta = new asignarCuenta_();