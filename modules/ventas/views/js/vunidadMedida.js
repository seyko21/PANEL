/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 06-11-2014 16:11:31 
* Descripcion : vunidadMedida.js
* ---------------------------------------
*/
var vunidadMedida_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idVunidadMedida = 0;
    
    _private.callbackData = null;
    
    _private.config = {
        modulo: "ventas/vunidadMedida/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : VunidadMedida*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.VUNID,
            label: $(element).attr("title"),
            fnCallback: function(){
                vunidadMedida.getContenido();
            }
        });
    };
    
    /*contenido de tab: VunidadMedida*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.VUNID+"_CONTAINER").html(data);
                vunidadMedida.getGridVunidadMedida();
            }
        });
    };
    
    this.publico.getGridVunidadMedida = function (){
        var oTable = $("#"+diccionario.tabs.VUNID+"gridVunidadMedida").dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "<input type='checkbox' id='"+diccionario.tabs.VUNID+"chk_all' onclick='simpleScript.checkAll(this,\"#"+diccionario.tabs.VUNID+"gridVunidadMedida\");'>", sWidth: "1%", sClass: "center", bSortable: false},
                {sTitle: "Descripción", sWidth: "45%"},
                {sTitle: "Sigla", sWidth: "10%"},
                {sTitle: "Estado", sWidth: "10%", sClass: "center"},
                {sTitle: "Acciones", sWidth: "8%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[1, "asc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridVunidadMedida",
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.VUNID+"gridVunidadMedida_filter").find("input").attr("placeholder","Buscar por Descripcion o Sigla").css("width","350px");
                simpleScript.enterSearch("#"+diccionario.tabs.VUNID+"gridVunidadMedida",oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.VUNID,
                    typeElement: "button"
                });
            $('#'+diccionario.tabs.VUNID+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.VUNID+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getFormNewVunidadMedida = function(btn,callbackData){
         /*para cuando se llama formulario desde otro modulo*/
        _private.callbackData = callbackData;  
        
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormNewVunidadMedida",
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.VUNID+"formNewVunidadMedida").modal("show");
            }
        });
    };
    
    this.publico.getFormEditVunidadMedida = function(btn,id){
        _private.idVunidadMedida = id;
            
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormEditVunidadMedida",
            data: "&_idVunidadMedida="+_private.idVunidadMedida,
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.VUNID+"formEditVunidadMedida").modal("show");
            }
        });
    };
    
    this.publico.getAddListUnidadMedida = function(){
        simpleAjax.send({
            root: _private.config.modulo + 'getAddListUnidadMedida ',
            fnCallback: function(data){
                simpleScript.closeModal('#'+diccionario.tabs.VUNID+'formNewVunidadMedida');                
                $(_private.callbackData).append('<option value="'+data.id_unidadmedida+'">'+data.nombre+'</option>');
                _private.callbackData = null;
            }
        });
        
    };
    
    this.publico.postNewVunidadMedida = function(){
        simpleAjax.send({
            element: "#"+diccionario.tabs.VUNID+"btnGrVunidadMedida",
            root: _private.config.modulo + "postNewVunidadMedida",
            form: "#"+diccionario.tabs.VUNID+"formNewVunidadMedida",
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                             /*si se graba desde otro modulo*/                                  
                            if(_private.callbackData.length > 0){                                
                               vunidadMedida.getAddListUnidadMedida();                                
                            }
                            /*se verifica si existe tabb para recargar grid*/
                            if($('#'+diccionario.tabs.VUNID+'_CONTAINER').length > 0){
                               vunidadMedida.getGridVunidadMedida();
                            }                                                        
                        }
                    });
                }else if(!isNaN(data.result) && parseInt(data.result) === 2){
                    simpleScript.notify.error({
                        content: "Unidad Medida ya existe."
                    });
                }
            }
        });
    };    
    
    this.publico.postEditVunidadMedida = function(){
        simpleAjax.send({
            element: "#"+diccionario.tabs.VUNID+"btnEdVunidadMedida",
            root: _private.config.modulo + "postEditVunidadMedida",
            form: "#"+diccionario.tabs.VUNID+"formEditVunidadMedida",
            data: "&_idVunidadMedida="+_private.idVunidadMedida,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_19,
                        callback: function(){
                            _private.idVunidadMedida = 0;
                            simpleScript.closeModal("#"+diccionario.tabs.VUNID+"formEditVunidadMedida");
                            simpleScript.reloadGrid("#"+diccionario.tabs.VUNID+"gridVunidadMedida");
                        }
                    });
                }else if(!isNaN(data.result) && parseInt(data.result) === 2){
                    simpleScript.notify.error({
                        content: "Unidad Medida ya existe."
                    });
                }
            }
        });
    };
           
    this.publico.postDeleteVunidadMedidaAll = function(btn){
        simpleScript.validaCheckBox({
            id: "#"+diccionario.tabs.VUNID+"gridVunidadMedida",
            msn: mensajes.MSG_9,
            fnCallback: function(){
                simpleScript.notify.confirm({
                    content: mensajes.MSG_7,
                    callbackSI: function(){
                        simpleAjax.send({
                            element: btn,
                            form: "#"+diccionario.tabs.VUNID+"formGridVunidadMedida",
                            root: _private.config.modulo + "postDeleteVunidadMedidaAll",
                            fnCallback: function(data) {
                                if(!isNaN(data.result) && parseInt(data.result) === 1){
                                    simpleScript.notify.ok({
                                        content: mensajes.MSG_8,
                                        callback: function(){
                                            vunidadMedida.getGridVunidadMedida();
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
    
    this.publico.postDesactivar = function(btn,id){
           simpleAjax.send({
               element: btn,
               root: _private.config.modulo + 'postDesactivar',
               data: '&_idVunidadMedida='+id,
               clear: true,
               fnCallback: function(data) {
                   if(!isNaN(data.result) && parseInt(data.result) === 1){
                       simpleScript.notify.ok({
                           content: 'Unidad de Medida se desactivo correctamente',
                           callback: function(){
                               simpleScript.reloadGrid("#"+diccionario.tabs.VUNID+"gridVunidadMedida");
                           }
                       });
                   }
               }
           });
       };
    
    this.publico.postActivar = function(btn,id){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postActivar',
            data: '&_idVunidadMedida='+id,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: 'Unidad de Medida se activo correctamente',
                        callback: function(){
                           simpleScript.reloadGrid("#"+diccionario.tabs.VUNID+"gridVunidadMedida");
                        }
                    });
                }
            }
        });
    };         
    
    return this.publico;
    
};
var vunidadMedida = new vunidadMedida_();