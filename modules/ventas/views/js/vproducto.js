/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 07-11-2014 00:11:47 
* Descripcion : vproducto.js
* ---------------------------------------
*/
var vproducto_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idVproducto = 0;
    
    _private.config = {
        modulo: "ventas/vproducto/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : Vproducto*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.VPROD,
            label: $(element).attr("title"),
            fnCallback: function(){
                vproducto.getContenido();
            }
        });
    };
    
    /*contenido de tab: Vproducto*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.VPROD+"_CONTAINER").html(data);
                vproducto.getGridVproducto();
            }
        });
    };
    
    this.publico.getGridVproducto = function (){
        var oTable = $("#"+diccionario.tabs.VPROD+"gridVproducto").dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "<input type='checkbox' id='"+diccionario.tabs.VPROD+"chk_all' onclick='simpleScript.checkAll(this,\"#"+diccionario.tabs.VPROD+"gridVproducto\");'>", sWidth: "1%", sClass: "center", bSortable: false},
                {sTitle: "Descripción", sWidth: "35%"},
                {sTitle: "Unidad Medida", sWidth: "20%"},
                {sTitle: "Incl. IGV", sWidth: "10%"},
                {sTitle: "Moneda", sWidth: "10%"},
                {sTitle: "Precio Unit.", sWidth: "15%", sClass: "right"},
                {sTitle: "Estado", sWidth: "10%", sClass: "center"},
                {sTitle: "Acciones", sWidth: "8%", sClass: "center", bSortable: false}
                
            ],
            aaSorting: [[1, "asc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridVproducto",
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.VPROD+"gridVproducto_filter").find("input").attr("placeholder","Buscar por Descripción de producto").css("width","350px");
                simpleScript.enterSearch("#"+diccionario.tabs.VPROD+"gridVproducto",oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.VPROD,
                    typeElement: "button"
                });
                $('#'+diccionario.tabs.VPROD+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.VPROD+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getFormNewVproducto = function(btn){
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormNewVproducto",
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.VPROD+"formNewVproducto").modal("show");
            }
        });
    };
    
    this.publico.getFormEditVproducto = function(btn,id){
        _private.idVproducto = id;
            
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormEditVproducto",
            data: "&_idVproducto="+_private.idVproducto,
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.VPROD+"formEditVproducto").modal("show");
            }
        });
    };
    
    this.publico.postNewVproducto = function(){
        simpleAjax.send({             
            element: "#"+diccionario.tabs.VPROD+"btnGrVproducto",
            root: _private.config.modulo + "postNewVproducto",
            form: "#"+diccionario.tabs.VPROD+"formNewVproducto",
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            vproducto.getGridVproducto();
                            _private.idVproducto = 0;
                            setTimeout(function(){
                                $("#"+diccionario.tabs.VPROD+"lst_moneda").val('SO');
                                $("#"+diccionario.tabs.VPROD+"lst_unidadMedida").val('');    
                            },100);
                            
                        }
                    });
                }else if(!isNaN(data.result) && parseInt(data.result) === 2){
                    simpleScript.notify.error({
                        content: "Producto ya existe."
                    });
                }
            }
        });
    };
    
    this.publico.postEditVproducto = function(){
        simpleAjax.send({             
            element: "#"+diccionario.tabs.VPROD+"btnEdVproducto",
            root: _private.config.modulo + "postEditVproducto",
            form: "#"+diccionario.tabs.VPROD+"formEditVproducto",
            data: "&_idVproducto="+_private.idVproducto,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_19,
                        callback: function(){
                            _private.idVproducto = 0;
                            simpleScript.closeModal("#"+diccionario.tabs.VPROD+"formEditVproducto");
                            simpleScript.reloadGrid("#"+diccionario.tabs.VPROD+"gridVproducto");
                        }
                    });
                }else if(!isNaN(data.result) && parseInt(data.result) === 2){
                    simpleScript.notify.error({
                        content: "Producto ya existe."
                    });
                }
            }
        });
    };       
    
    this.publico.postDeleteVproductoAll = function(btn){
        simpleScript.validaCheckBox({
            id: "#"+diccionario.tabs.VPROD+"gridVproducto",
            msn: mensajes.MSG_9,
            fnCallback: function(){
                simpleScript.notify.confirm({
                    content: mensajes.MSG_7,
                    callbackSI: function(){
                        simpleAjax.send({
                            flag: 3, //si se usa SP usar flag, sino se puede eliminar esta linea
                            element: btn,
                            form: "#"+diccionario.tabs.VPROD+"formGridVproducto",
                            root: _private.config.modulo + "postDeleteVproductoAll",
                            fnCallback: function(data) {
                                if(!isNaN(data.result) && parseInt(data.result) === 1){
                                    simpleScript.notify.ok({
                                        content: mensajes.MSG_8,
                                        callback: function(){
                                            vproducto.getGridVproducto();
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
                  data: '&_idVproducto='+id,
                  clear: true,
                  fnCallback: function(data) {
                      if(!isNaN(data.result) && parseInt(data.result) === 1){
                          simpleScript.notify.ok({
                              content: 'Producto se desactivo correctamente',
                              callback: function(){
                                   simpleScript.reloadGrid("#"+diccionario.tabs.VPROD+"gridVproducto");
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
               data: '&_idVproducto='+id,
               clear: true,
               fnCallback: function(data) {
                   if(!isNaN(data.result) && parseInt(data.result) === 1){
                       simpleScript.notify.ok({
                           content: 'Producto se activo correctamente',
                           callback: function(){
                               simpleScript.reloadGrid("#"+diccionario.tabs.VPROD+"gridVproducto");
                           }
                       });
                   }
               }
           });
       };         
        
    
    return this.publico;
    
};
var vproducto = new vproducto_();