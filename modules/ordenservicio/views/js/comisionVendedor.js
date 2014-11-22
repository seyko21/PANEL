/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 21-09-2014 18:09:08 
* Descripcion : comisionVendedor.js
* ---------------------------------------
*/
var comisionVendedor_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idVendedor = 0;
    
    _private.config = {
        modulo: "ordenservicio/comisionVendedor/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : ComisionVendedor*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.COMVE,
            label: $(element).attr("title"),
            fnCallback: function(){
                comisionVendedor.getContenido();
            }
        });
    };
    
    /*contenido de tab: ComisionVendedor*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.COMVE+"_CONTAINER").html(data);
                comisionVendedor.getGridComisionVendedor();
            }
        });
    };
    
    this.publico.getGridComisionVendedor = function (){
        var oTable = $("#"+diccionario.tabs.COMVE+"gridComisionVendedor").dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "Vendedor", sWidth: "25%"},
                {sTitle: "Acciones", sWidth: "8%", sClass: "center", bSortable: false}                
            ],
            aaSorting: [[0, "asc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridComisionVendedor",
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.COMVE+"gridComisionVendedor_filter").find("input").attr("placeholder","Buscar por vendedor").css("width","250px");
                simpleScript.enterSearch("#"+diccionario.tabs.COMVE+'gridComisionVendedor',oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.COMVE,
                    typeElement: "button"
                });
                 $('#'+diccionario.tabs.COMVE+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.COMVE+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getFormComisionVendedor = function(btn,id,vend){
        _private.idVendedor = id;
        
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormComisionVendedor",
            data: '&_idVendedor='+_private.idVendedor+'&_vendedor='+vend,
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.COMVE+"formComisionVendedor").modal("show");
            }
        });
    };
    
    this.publico.postGenerarComisionVendedor = function(btn,idOrden){
        simpleScript.notify.confirm({
            content: '¿Está seguro de generar comisión?',
            callbackSI: function(){
                simpleAjax.send({
                    element: btn,
                    root: _private.config.modulo + "postGenerarComisionVendedor",
                    form: "#"+diccionario.tabs.COMVE+"formComisionVendedor",
                    data: '&_idOrden='+idOrden,
                    fnCallback: function(data) {
                        if(!isNaN(data.result) && parseInt(data.result) === 1){
                            simpleScript.notify.ok({
                                content: 'Comisión se generó correctamente',
                                callback: function(){
                                    simpleScript.closeModal('#'+diccionario.tabs.COMVE+'formComisionVendedor');
                                    simpleScript.reloadGrid("#"+diccionario.tabs.COMVE+"gridComisionVendedor");
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
var comisionVendedor = new comisionVendedor_();