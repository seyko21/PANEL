/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 20-08-2014 08:08:50 
* Descripcion : cliente.js
* ---------------------------------------
*/
var cliente_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idCliente = 0;
    
    _private.config = {
        modulo: "cotizacion/cliente/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : Cliente*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.CLIT,
            label: $(element).attr("title"),
            fnCallback: function(){
                cliente.getContenido();
            }
        });
    };
    
    /*contenido de tab: Cliente*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.CLIT+"_CONTAINER").html(data);
                cliente.getGridCliente();
            }
        });
    };
    
    this.publico.getGridCliente = function (){
        /*------------------LOGICA PARA DATAGRID------------------------*/
    };
    
    this.publico.getFormNewCliente = function(btn){
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormNewCliente",
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.CLIT+"formNewCliente").modal("show");
            }
        });
    };
    
    this.publico.postNewCliente = function(){
        /*-----LOGICA PARA ENVIO DE FORMULARIO-----*/
    };
    
    this.publico.postDeleteClienteAll = function(btn){
        simpleScript.validaCheckBox({
            id: "#"+diccionario.tabs.CLIT+"gridCliente",
            msn: mensajes.MSG_9,
            fnCallback: function(){
                simpleScript.notify.confirm({
                    content: mensajes.MSG_7,
                    callbackSI: function(){
                        simpleAjax.send({
                            flag: 3, //si se usa SP usar flag, sino se puede eliminar esta linea
                            element: btn,
                            form: "#"+diccionario.tabs.CLIT+"formGridCliente",
                            root: _private.config.modulo + "postDeleteClienteAll",
                            fnCallback: function(data) {
                                if(!isNaN(data.result) && parseInt(data.result) === 1){
                                    simpleScript.notify.ok({
                                        content: mensajes.MSG_8,
                                        callback: function(){
                                            cliente.getGridCliente();
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
var cliente = new cliente_();