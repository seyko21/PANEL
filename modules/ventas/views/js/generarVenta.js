/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 05-09-2014 23:09:58 
* Descripcion : generarVenta.js
* ---------------------------------------
*/
var generarVenta_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idVenta = 0;
    
    _private.tab = '';
    
    _private.config = {
        modulo: "ventas/generarVenta/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : GenerarVenta*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.VGEVE,
            label: $(element).attr("title"),
            fnCallback: function(){
                generarVenta.getContenido();
            }
        });
    };
    
    /*contenido de tab: GenerarVenta*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.VGEVE+"_CONTAINER").html(data);
                generarVenta.getGridGenerarVenta();
            }
        });
    };
    
    this.publico.getGridGenerarVenta = function (){
       var oTable = $('#'+diccionario.tabs.VGEVE+'gridGenerarVenta').dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "<input type='checkbox' id='"+diccionario.tabs.VGEVE+"chk_all' onclick='simpleScript.checkAll(this,\"#"+diccionario.tabs.VGEVE+"gridGenerarVenta\");'>", sWidth: "1%", sClass: "center", bSortable: false},                
                {sTitle: "Código", sWidth: "10%"},
                {sTitle: "Descripción", sWidth: "20%"},
                {sTitle: "Tipo Doc", sWidth: "8%"},
                {sTitle: "Fecha", sWidth: "10%",  sClass: "center"},
                {sTitle: "Moneda", sWidth: "7%"},                                
                {sTitle: "Total", sWidth: "15%",  sClass: "right"},           
                {sTitle: "Estado", sWidth: "10%",  sClass: "center"},
                {sTitle: "Acciones", sWidth: "15%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[1, 'desc']],
            sScrollY: "350px",
            sAjaxSource: _private.config.modulo+'getGridGenerarVenta',
            fnDrawCallback: function() {
                $('#'+diccionario.tabs.VGEVE+'gridGenerarVenta_filter').find('input').attr('placeholder','Buscar por Código o Descripción').css('width','400px');               
                simpleScript.enterSearch("#"+diccionario.tabs.VGEVE+"gridGenerarVenta",oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: '#widget_'+diccionario.tabs.VGEVE, //widget del datagrid
                    typeElement: 'button, #'+diccionario.tabs.VGEVE+'chk_all'
                });
            }
        });
        setup_widgets_desktop();                
        
    };    
    
    this.publico.getFormBuscarProductos = function(btn,tab,idMoneda){
        _private.tab = tab;
        simpleAjax.send({
            element: btn,
            dataType: 'html',
            root: _private.config.modulo + 'getFormBuscarProductos',
            data: '&_idMoneda='+idMoneda,
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.VGEVE+'formBuscarProductos').modal('show');
            }
        });
    };
    
    this.publico.getFormNewGenerarVenta = function(btn){
        generarVentaScript.resetArrayProducto();
        simpleScript.addTab({
            id : diccionario.tabs.VGEVE+'new',
            label: 'Nueva Venta',
            fnCallback: function(){
                generarVenta.getContProd();
            }
        });
    };
    
    this.publico.getContProd = function(){
        simpleAjax.send({
            dataType: 'html',
            root: _private.config.modulo+'getFormNewGenerarVenta',
            fnCallback: function(data){
                $('#'+diccionario.tabs.VGEVE+'new_CONTAINER').html(data);
            }
        });
    };
    
    this.publico.getFormEditarGenerarVenta = function(btn,id){
        _private.idVenta = id;
        
        generarVentaScript.resetArrayProducto();
        simpleScript.addTab({
            id : diccionario.tabs.VGEVE+'edit',
            label: 'Editar Venta',
            fnCallback: function(){
                generarVenta.getContEditProd();
            }
        });
    };
    
    this.publico.getContEditProd = function(){
        simpleAjax.send({
            dataType: 'html',
            root: _private.config.modulo+'getFormEditGenerarVenta',
            data: '&_idVenta='+_private.idVenta,
            fnCallback: function(data){
                $('#'+diccionario.tabs.VGEVE+'edit_CONTAINER').html(data);
            }
        });
    };           
    
    this.publico.postNewGenerarVenta = function(f){
        simpleAjax.send({
            element: "#"+diccionario.tabs.VGEVE+"btnGrVenta",
            root: _private.config.modulo + "postNewGenerarVenta",
            form: "#"+diccionario.tabs.VGEVE+"formNewGenerarVenta",
            data: '&_flag='+f+'&_idVenta='+_private.idVenta,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            simpleScript.closeTab(diccionario.tabs.VGEVE+'new');
                            simpleScript.closeTab(diccionario.tabs.VGEVE+'edit');
                            generarVenta.getGridGenerarVenta();
                            _private.idVenta = 0;
                        }
                    });
                }
            }
        });
    };
    
    this.publico.postPDF = function(btn,id,cod){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postPDF',
            data: '&_idVenta='+id+'&_cod='+cod,
            fnCallback: function(data) {
                if(parseInt(data.result) === 1){
                    $('#'+diccionario.tabs.VGEVE+'btnDowPDF').off('click');
                    $('#'+diccionario.tabs.VGEVE+'btnDowPDF').attr("onclick","window.open('public/files/"+data.archivo+"','_blank');generarCotizacion.deleteArchivo('"+data.archivo+"');");
                    $('#'+diccionario.tabs.VGEVE+'btnDowPDF').click();
                }
            }
        });
    };
    
    this.publico.postExcel = function(btn,id,cod){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postExcel',
            data: '&_idVenta='+id+'&_cod='+cod,
            fnCallback: function(data) {
                if(parseInt(data.result) === 1){
                    $('#'+diccionario.tabs.VGEVE+'btnDowExcel').off('click');
                    $('#'+diccionario.tabs.VGEVE+'btnDowExcel').attr("onclick","window.open('public/files/"+data.archivo+"','_self');generarCotizacion.deleteArchivo('"+data.archivo+"');");
                    $('#'+diccionario.tabs.VGEVE+'btnDowExcel').click();
                }
                if(!isNaN(data.result) && parseInt(data.result) === 2){
                    simpleScript.notify.error({
                        content: 'Ocurrió un error al exportar Venta.'
                    });
                }
            }
        });
    };
    
    this.publico.postAnularGenerarVentaAll = function(btn){
        simpleScript.validaCheckBox({
            id: "#"+diccionario.tabs.VGEVE+"gridGenerarVenta",
            msn: mensajes.MSG_9,
            fnCallback: function(){
                simpleScript.notify.confirm({
                    content: mensajes.MSG_13,
                    callbackSI: function(){
                        simpleAjax.send({
                            element: btn,
                            form: "#"+diccionario.tabs.VGEVE+"formGridGenerarVenta",
                            root: _private.config.modulo + "postAnularGenerarVentaAll",
                            fnCallback: function(data) {
                                if(!isNaN(data.result) && parseInt(data.result) === 1){
                                    simpleScript.notify.ok({
                                        content: mensajes.MSG_8,
                                        callback: function(){
                                            generarVenta.getGridGenerarVenta();
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
var generarVenta = new generarVenta_();