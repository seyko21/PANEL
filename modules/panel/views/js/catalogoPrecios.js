/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 27-08-2014 02:08:12 
* Descripcion : catalogoPrecios.js
* ---------------------------------------
*/
var catalogoPrecios_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idCaratula = 0;
    _private.idProducto = 0;
        
    _private.config = {
        modulo: "panel/catalogoPrecios/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : CatalogoPrecios*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.TAB_CATPRE,
            label: $(element).attr("title"),
            fnCallback: function(){
                catalogoPrecios.getContenido();
            }
        });
    };
    
    /*contenido de tab: CatalogoPrecios*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.TAB_CATPRE+"_CONTAINER").html(data);
                catalogoPrecios.getGridCatalogoPrecios();
            }
        });
    };
    
    this.publico.getGridCatalogoPrecios = function (){
         var oTable = $('#'+diccionario.tabs.TAB_CATPRE+'gridCatalogoPrecio').dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [                
                {sTitle: "Código", sWidth: "8%"},
                {sTitle: "Ciudad", sWidth: "10%"},
                {sTitle: "Ubicación", sWidth: "22%"},
                {sTitle: "Elemento", sWidth: "10%"},
                {sTitle: "Area m2", sWidth: "3%",  sClass: "center"},
                {sTitle: "Precio", sWidth: "5%",  sClass: "right"},                
                {sTitle: "Iluminado", sWidth: "5%",  sClass: "center"},                
                {sTitle: "Estado", sWidth: "5%",  sClass: "center"},
                {sTitle: "Imagen", sWidth: "8%",  sClass: "center", bSortable: false},
                {sTitle: "Acciones", sWidth: "13%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[2, 'asc']],
            sScrollY: "350px",
            sAjaxSource: _private.config.modulo+'getGridProducto',
            fnServerParams: function(aoData) {
                aoData.push({"name": "_tipoPanel", "value": $("#"+diccionario.tabs.TAB_CATPRE+"lst_tipopanelsearch").val()});
            },
            fnDrawCallback: function() {
                $('#'+diccionario.tabs.TAB_CATPRE+'gridCatalogoPrecio_filter').find('input').attr('placeholder','Buscar por Código o Ciudad o Ubicación').css('width','350px');                
                simpleScript.enterSearch("#"+diccionario.tabs.TAB_CATPRE+'gridCatalogoPrecio',oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: '#widget_'+diccionario.tabs.TAB_CATPRE, //widget del datagrid
                    typeElement: 'button, img'
                });
            }
        });
        setup_widgets_desktop();       
    };
           
    this.publico.getEditarCaratula = function(btn,id, idd){
        _private.idCaratula = id;
        _private.idProducto  = idd;         
        simpleAjax.send({
            element: btn,
            dataType: 'html',
            root: _private.config.modulo + 'getEditarCaratula',
            data: '&_idCaratula='+_private.idCaratula,
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.TAB_CATPRE+'formCaratula').modal('show');
            }
        });
    };     
    
    this.publico.getFormAdjuntar = function(btn,id){
        _private.idCaratula = id;
        
        simpleAjax.send({
            element: btn,
            dataType: 'html',
            gifProcess: true,
            data: '&_idCaratula='+id,
            root: _private.config.modulo + 'getFormAdjuntar',
            fnCallback: function(data){
                $('#cont-modal').append(data);
                $('#'+diccionario.tabs.TAB_CATPRE+'formAdjuntar').modal('show');
            }
        });
    };       
    
    this.publico.postEditarCaratula = function(){       
        simpleAjax.send({
            flag: 2,
            element: '#'+diccionario.tabs.TAB_CATPRE+'btnGcara',
            root: _private.config.modulo+'postEditarCaratula',
            form: '#'+diccionario.tabs.TAB_CATPRE+'formCaratula',
            data: '&_idCaratula='+_private.idCaratula,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){                                                              
                            simpleScript.reloadGrid('#'+diccionario.tabs.TAB_CATPRE+'gridCatalogoPrecio');
                            simpleScript.closeModal('#'+diccionario.tabs.TAB_CATPRE+'formCaratula');
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
    this.publico.postPDF = function(btn,id, num){
          simpleAjax.send({
              element: btn,
              root: _private.config.modulo + 'postPDF',
              data: '&_idCaratula='+id+'&_codigo='+num,
              fnCallback: function(data) {
                  if(parseInt(data.result) === 1){
                      $('#'+diccionario.tabs.TAB_CATPRE+'btnDowPDF').attr("onclick","window.open('public/files/"+data.archivo+"','_blank');fichaTecnica.deleteArchivo('"+data.archivo+"');");
                      $('#'+diccionario.tabs.TAB_CATPRE+'btnDowPDF').click();
                  }                
              }
          });
      };

      this.publico.postExcel = function(btn,id, num){
          simpleAjax.send({
              element: btn,
              root: _private.config.modulo + 'postExcel',
              data: '&_idCaratula='+id+'&_codigo='+num,
              fnCallback: function(data) {
                  if(parseInt(data.result) === 1){
                     $('#'+diccionario.tabs.TAB_CATPRE+'btnDowExcel').attr("onclick","window.open('public/files/"+data.archivo+"','_self');fichaTecnica.deleteArchivo('"+data.archivo+"');");
                     $('#'+diccionario.tabs.TAB_CATPRE+'btnDowExcel').click();
                  }
                  if(!isNaN(data.result) && parseInt(data.result) === 2){
                      simpleScript.notify.error({
                          content: 'Ocurrió un error al exportar Ficha Tecnica.'
                      });
                  }
              }
          });
      };  
      
    this.publico.deleteAdjuntar = function(btn,id,img){
        simpleScript.notify.confirm({
            content: mensajes.MSG_7,
            callbackSI: function(){
                simpleAjax.send({
                    element: btn,
                    root: _private.config.modulo + 'deleteAdjuntar',
                    data: '&_idCaratula='+id+'&_img='+img,
                    fnCallback: function(data){
                        if(!isNaN(data.result) && parseInt(data.result) === 1){
                            simpleScript.notify.ok({
                                content: mensajes.MSG_8,
                                callback: function(){
                                    $('#'+diccionario.tabs.TAB_CATPRE+'dow').attr('onclick','');
                                    $('#'+diccionario.tabs.TAB_CATPRE+'dow').html(''); 
                                    $('#'+diccionario.tabs.TAB_CATPRE+'btndow').css('display','none');
                                }
                            });
                        }
                    }
                });
            }
        });
    };         
    
    return this.publico;
    
};
var catalogoPrecios = new catalogoPrecios_();