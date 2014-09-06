/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 05-09-2014 23:09:58 
* Descripcion : regProduccion.js
* ---------------------------------------
*/
var regProduccion_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idRegProduccion = 0;
    
    _private.config = {
        modulo: "produccion/regProduccion/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : RegProduccion*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.REPRO,
            label: $(element).attr("title"),
            fnCallback: function(){
                regProduccion.getContenido();
            }
        });
    };
    
    /*contenido de tab: RegProduccion*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.REPRO+"_CONTAINER").html(data);
                regProduccion.getGridRegProduccion();
            }
        });
    };
    
    this.publico.getGridRegProduccion = function (){
        /*------------------LOGICA PARA DATAGRID------------------------*/
    };
    
    this.publico.getFormNewRegProduccion = function(btn){
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormNewRegProduccion",
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.REPRO+"formNewRegProduccion").modal("show");
            }
        });
    };
    
    this.publico.postNewRegProduccion = function(){
        /*-----LOGICA PARA ENVIO DE FORMULARIO-----*/
    };
    
    this.publico.postDeleteRegProduccionAll = function(btn){
        simpleScript.validaCheckBox({
            id: "#"+diccionario.tabs.REPRO+"gridRegProduccion",
            msn: mensajes.MSG_9,
            fnCallback: function(){
                simpleScript.notify.confirm({
                    content: mensajes.MSG_7,
                    callbackSI: function(){
                        simpleAjax.send({
                            flag: 3, //si se usa SP usar flag, sino se puede eliminar esta linea
                            element: btn,
                            form: "#"+diccionario.tabs.REPRO+"formGridRegProduccion",
                            root: _private.config.modulo + "postDeleteRegProduccionAll",
                            fnCallback: function(data) {
                                if(!isNaN(data.result) && parseInt(data.result) === 1){
                                    simpleScript.notify.ok({
                                        content: mensajes.MSG_8,
                                        callback: function(){
                                            regProduccion.getGridRegProduccion();
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
var regProduccion = new regProduccion_();