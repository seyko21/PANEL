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
    
    _private.tab = '';
    
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
                {sTitle: "<input type='checkbox' id='"+diccionario.tabs.REPRO+"chk_all' onclick='simpleScript.checkAll(this,\"#"+diccionario.tabs.REPRO+"gridRegProduccion\");'>", sWidth: "1%", sClass: "center", bSortable: false},                
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
    
    this.publico.getProductos = function(){
        $('#'+diccionario.tabs.REPRO+'gridProductosFound').dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            bFilter: false, 
            bInfo: false,
            sServerMethod: "POST",
            bPaginate: false,
            aoColumns: [
                {sTitle: "Nro.", sClass: "center",sWidth: "2%",  bSortable: false},
                {sTitle: "Descripción", sWidth: "40%",  bSortable: false}
            ],
            aaSorting: [[1, 'asc']],
            sScrollY: "250px",
            sAjaxSource: _private.config.modulo+'getProductos',
            fnServerParams: function(aoData) {
                aoData.push({"name": diccionario.tabs.REPRO+"_term", "value": $('#'+diccionario.tabs.REPRO+'txt_search').val()});
                aoData.push({"name": "_tab", "value": _private.tab});
            },
            fnDrawCallback: function() {
                $('#'+diccionario.tabs.REPRO+'gridProductosFound_wrapper').find('.dataTables_scrollBody').css('overflow-x','hidden');
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: '#'+diccionario.tabs.REPRO+'gridProductosFound',
                    typeElement: 'a'
                });
            }
        });
    };
    
    this.publico.getFormNewRegProduccion = function(btn){
        instalacionScript.resetArrayConcepto();
        simpleScript.addTab({
            id : diccionario.tabs.REPRO+'new',
            label: 'Nueva Producción',
            fnCallback: function(){
                regProduccion.getContProd();
            }
        });
    };
    
    this.publico.getContProd = function(){
        simpleAjax.send({
            dataType: 'html',
            root: _private.config.modulo+'getFormNewProduccion',
            fnCallback: function(data){
                $('#'+diccionario.tabs.REPRO+'new_CONTAINER').html(data);
            }
        });
    };
    
    this.publico.getFormBuscarProducto = function(btn,tab){
        _private.tab = tab;
        simpleAjax.send({
            element: btn,
            dataType: 'html',
            root: _private.config.modulo + 'getFormBuscarProducto',
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.REPRO+'formBuscarProducto').modal('show');
            }
        });
    };
    
    this.publico.postNewRegProduccion = function(){
        simpleAjax.send({
            element: "#"+diccionario.tabs.REPRO+"btnGrprod",
            root: _private.config.modulo + "postNewRegProduccion",
            form: "#"+diccionario.tabs.REPRO+"formNewRegProduccion",
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            simpleScript.closeTab(diccionario.tabs.REPRO+'new');
                            regProduccion.getGridRegProduccion();
                        }
                    });
                }
            }
        });
    };
    
    this.publico.postPDF = function(btn,id){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postPDF',
            data: '&_idProduccion='+id,
            fnCallback: function(data) {
                if(parseInt(data.result) === 1){
                    $('#'+diccionario.tabs.REPRO+'btnDowPDF').off('click');
                    $('#'+diccionario.tabs.REPRO+'btnDowPDF').attr("onclick","window.open('public/files/"+data.archivo+"','_blank');generarCotizacion.deleteArchivo('"+data.archivo+"');");
                    $('#'+diccionario.tabs.REPRO+'btnDowPDF').click();
                }
            }
        });
    };
    
    this.publico.postExcel = function(btn,id){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postExcel',
            data: '&_idProduccion='+id,
            fnCallback: function(data) {
                if(parseInt(data.result) === 1){
                    $('#'+diccionario.tabs.REPRO+'btnDowExcel').off('click');
                    $('#'+diccionario.tabs.REPRO+'btnDowExcel').attr("onclick","window.open('public/files/"+data.archivo+"','_self');generarCotizacion.deleteArchivo('"+data.archivo+"');");
                    $('#'+diccionario.tabs.REPRO+'btnDowExcel').click();
                }
                if(!isNaN(data.result) && parseInt(data.result) === 2){
                    simpleScript.notify.error({
                        content: 'Ocurrió un error al exportar cotización.'
                    });
                }
            }
        });
    };
    
    this.publico.postAnularRegProduccionAll = function(btn){
        simpleScript.validaCheckBox({
            id: "#"+diccionario.tabs.REPRO+"gridRegProduccion",
            msn: mensajes.MSG_9,
            fnCallback: function(){
                simpleScript.notify.confirm({
                    content: mensajes.MSG_7,
                    callbackSI: function(){
                        simpleAjax.send({
                            element: btn,
                            form: "#"+diccionario.tabs.REPRO+"formGridRegProduccion",
                            root: _private.config.modulo + "postAnularRegProduccionAll",
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