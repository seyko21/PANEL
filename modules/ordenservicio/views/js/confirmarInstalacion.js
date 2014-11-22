/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 21-09-2014 05:09:42 
* Descripcion : confirmarInstalacion.js
* ---------------------------------------
*/
var confirmarInstalacion_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idOrdenDetalle = 0;
    
    _private.config = {
        modulo: "ordenservicio/confirmarInstalacion/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : ConfirmarInstalacion*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.COINS,
            label: $(element).attr("title"),
            fnCallback: function(){
                confirmarInstalacion.getContenido();
            }
        });
    };
    
    /*contenido de tab: ConfirmarInstalacion*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.COINS+"_CONTAINER").html(data);
                confirmarInstalacion.getGridConfirmarInstalacion();
            }
        });
    };
    
    this.publico.getGridConfirmarInstalacion = function (){
        var oTable = $("#"+diccionario.tabs.COINS+"gridConfirmarInstalacion").dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "Código OS", sWidth: "10%",sClass: "center"},
                {sTitle: "Código OI", sWidth: "10%",sClass: "center"},
                {sTitle: "Código Prod.", sWidth: "8%", sClass: "center"},
                {sTitle: "Producto", sWidth: "25%"},
                {sTitle: "Fecha Inst.", sWidth: "10%", sClass: "center"},
                {sTitle: "Confirmado", sWidth: "7%", sClass: "center", bSortable: false},
                {sTitle: "Acciones", sWidth: "7%", sClass: "center", bSortable: false}                
            ],
            aaSorting: [[0, "desc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridConfirmarInstalacion",
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.COINS+"gridConfirmarInstalacion_filter").find("input").attr("placeholder","Buscar por N° OS o Codigo o producto").css("width","350px");
                simpleScript.enterSearch("#"+diccionario.tabs.COINS+'gridConfirmarInstalacion',oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.COINS,
                    typeElement: "button"
                });
                $('#'+diccionario.tabs.COINS+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.COINS+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getFormConfirmarInstalacion = function(btn,id,codpro){
        _private.idOrdenDetalle = id;
        
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormConfirmarInstalacion",
            data: '&_codigo='+codpro+'&_idOrdenDetalle='+id,
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.COINS+"formConfirmarInstalacion").modal("show");
            }
        });
    };
    
    this.publico.postConfirmarInstalacion = function(){
        simpleAjax.send({
            element: "#"+diccionario.tabs.COINS+"btnGrConfirmarInstalacion",
            root: _private.config.modulo + "postConfirmarInstalacion",
            form: "#"+diccionario.tabs.COINS+"formConfirmarInstalacion",
            data: '&_idOrdenDetalle='+_private.idOrdenDetalle,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3
                    });
                }
            }
        });
    };
    
    this.publico.deleteImagen = function(btn,id,doc){
        simpleScript.notify.confirm({
            content: mensajes.MSG_7,
            callbackSI: function(){
                simpleAjax.send({
                    element: btn,
                    root: _private.config.modulo + 'deleteImagen',
                    data: '&_idOrdenDetalle='+id+'&_doc='+doc,
                    fnCallback: function(data) {
                        if(!isNaN(data.result) && parseInt(data.result) === 1){
                            simpleScript.notify.ok({
                                content: mensajes.MSG_8,
                                callback: function(){
                                    $('#'+diccionario.tabs.COINS+'dow').attr('onclick','');
                                    $('#'+diccionario.tabs.COINS+'dow').html(''); 
                                    $('#'+diccionario.tabs.COINS+'btndow').css('display','none');
                                    simpleScript.reloadGrid('#'+diccionario.tabs.COINS+'gridConfirmarInstalacion');
                                }
                            });
                        }
                    }
                });
            }
        });
    };
    
    this.publico.resetKey = function(){
        _private.idOrdenDetalle = 0;
    };
    
    return this.publico;
    
};
var confirmarInstalacion = new confirmarInstalacion_();