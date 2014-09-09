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
        $('#'+diccionario.tabs.REPRO+'gridRegProduccion').dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "<input type='checkbox' id='"+diccionario.tabs.REPRO+"chk_all' onclick='simpleScript.checkAll(this,\"#"+diccionario.tabs.REPRO+"gridFichaTecnica\");'>", sWidth: "1%", sClass: "center", bSortable: false},                
                {sTitle: "Ciudad", sWidth: "15%"},
                {sTitle: "Fecha", sWidth: "10%",  sClass: "center"},
                {sTitle: "Ubicación", sWidth: "25%"},
                {sTitle: "Elemento", sWidth: "10%"},                                
                {sTitle: "Total", sWidth: "10%",  sClass: "right"},
                {sTitle: "Asignado", sWidth: "10%",  sClass: "right"},
                {sTitle: "Saldo", sWidth: "10%",  sClass: "right"},
                {sTitle: "Acciones", sWidth: "15%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[2, 'asc']],
            sScrollY: "350px",
            sAjaxSource: _private.config.modulo+'getGridRegProduccion',
            fnDrawCallback: function() {
                $('#'+diccionario.tabs.REPRO+'gridRegProduccion_filter').find('input').attr('placeholder','Buscar por Ciudad o Ubicación').css('width','350px');               
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: '#widget_'+diccionario.tabs.REPRO, //widget del datagrid
                    typeElement: 'button, #'+diccionario.tabs.REPRO+'chk_all'
                });
            }
        });
        setup_widgets_desktop();                
        
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