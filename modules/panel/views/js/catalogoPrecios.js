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
         $('#'+diccionario.tabs.TAB_CATPRE+'gridCatalogoPrecio').dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [                
                {sTitle: "Código", sWidth: "8%"},
                {sTitle: "Ciudad", sWidth: "18%"},
                {sTitle: "Ubicación", sWidth: "35%"},
                {sTitle: "Area m2", sWidth: "5%",  sClass: "center", bSortable: false},
                {sTitle: "Precio", sWidth: "8%",  sClass: "right", bSortable: false},                
                {sTitle: "Iluminado", sWidth: "8%",  sClass: "center", bSortable: false},
                {sTitle: "Estado", sWidth: "8%",  sClass: "center", bSortable: false},
                {sTitle: "Acciones", sWidth: "15%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[2, 'asc']],
            sScrollY: "350px",
            sAjaxSource: _private.config.modulo+'getGridProducto',
            fnDrawCallback: function() {
                $('#'+diccionario.tabs.TAB_CATPRE+'gridCatalogoPrecio_filter').find('input').attr('placeholder','Buscar por Ciudad o Ubicación').css('width','350px');;                
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: '#widget_'+diccionario.tabs.TAB_CATPRE, //widget del datagrid
                    typeElement: 'button'
                });
            }
        });
        setup_widgets_desktop();       
    };
           
    this.publico.getEditarCaratula = function(id, idd){
        _private.idCaratula = id;
        _private.idProducto  = idd;         
        simpleAjax.send({
            gifProcess: true,
            dataType: 'html',
            root: _private.config.modulo + 'getEditarCaratula',
            data: '&_idCaratula='+_private.idCaratula,
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.TAB_CATPRE+'formCaratula').modal('show');
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
                            catalogoPrecios.getGridCatalogoPrecios();        
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
    
    
    return this.publico;
    
};
var catalogoPrecios = new catalogoPrecios_();