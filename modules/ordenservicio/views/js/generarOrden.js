/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 09-09-2014 06:09:13 
* Descripcion : generarOrden.js
* ---------------------------------------
*/
var generarOrden_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idOrden = 0;
    
    _private.montoTotal = 0;
    
    _private.config = {
        modulo: "ordenservicio/generarOrden/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : GenerarOrden*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.GNOSE,
            label: $(element).attr("title"),
            fnCallback: function(){
                generarOrden.getContenido();
            }
        });
    };
    
    /*contenido de tab: GenerarOrden*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.GNOSE+"_CONTAINER").html(data);
                generarOrden.getGridGenerarOrden();
            }
        });
    };
    
    this.publico.getGridGenerarOrden = function (){
        var oTable = $("#"+diccionario.tabs.GNOSE+"gridGenerarOrden").dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "N°", sWidth: "1%",bSortable: false},
                {sTitle: "Código", sWidth: "10%",sClass: "center"},
                {sTitle: "Nro. Cotización", sWidth: "10%",sClass: "center"},
                {sTitle: "Representante", sWidth: "20%"},
                {sTitle: "Descuento", sWidth: "10%", sClass: "right"},
                {sTitle: "Fecha", sWidth: "10%"},
                {sTitle: "Monto", sWidth: "8%", sClass: "right", bSortable: false},
                {sTitle: "Estado", sWidth: "8%", sClass: "center", bSortable: false},
                {sTitle: "Acciones", sWidth: "15%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[1, "desc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridGenerarOrden",
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.GNOSE+"gridGenerarOrden_filter").find("input").attr("placeholder","Buscar por código, cotización o representante").css("width","340px");
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.GNOSE,
                    typeElement: "button"
                });
            }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getGridCuotas = function (){
        var oTable = $("#"+diccionario.tabs.GNOSE+"gridCuotas").dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            bFilter: false,
            bLengthChange: false,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 50,            
            aoColumns: [
                {sTitle: "N°", sWidth: "1%"},
                {sTitle: "Monto", sWidth: "10%",sClass: "right",bSortable: false},
                {sTitle: "Fecha Pago", sWidth: "20%",sClass: "center",bSortable: false},
                {sTitle: "Estado", sWidth: "10%",sClass: "center",bSortable: false},
                {sTitle: "Acciones", sWidth: "8%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[0, "asc"]],
            sScrollY: "190px",
            sAjaxSource: _private.config.modulo+"getGridCuotas",
            fnServerParams: function(aoData) {
                aoData.push({"name": "_idOrden", "value": _private.idOrden});
            },
            fnDrawCallback: function() {
                $('#'+diccionario.tabs.GNOSE+'gridCuotas_info').css('padding-left','10px');
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#"+diccionario.tabs.GNOSE+'gridCuotas',
                    typeElement: "button"
                });
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
                var m=0;
                $("#"+diccionario.tabs.GNOSE+"gridCuotas").find('tbody').find('tr').each(function(){
                    m += parseFloat(simpleScript.deleteComa($(this).find('td:eq(1)').html()));
                });
                
                var saldo = _private.montoTotal - m;
                
                if(isNaN(m)){
                    m = 0;
                }
                if(isNaN(saldo)){
                    saldo = 0;
                }
                return 'Monto programado: <b>'+m.toFixed(2)+'</b><br> Saldo: <b>'+parseFloat(saldo).toFixed(2)+'</b><br>Monto total: <b>'+parseFloat(_private.montoTotal).toFixed(2)+'</br>';
            }                  
        });
        setup_widgets_desktop();
    };
    
    this.publico.getFormCronograma = function(btn,id,monto,norden){
        _private.idOrden = id;
        _private.montoTotal = monto;
        
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormCronograma",
            data: '&_norden='+norden,
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.GNOSE+"formCronograma").modal("show");
            }
        });
    };
    
    this.publico.getFormEditOrden = function(btn,id,estado){
        _private.idOrden = id;
        
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormEditOrden",
            data: '&_idOrden='+_private.idOrden+'&_estado='+estado,
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.GNOSE+"formEditOrden").modal("show");
            }
        });
    };
    
    this.publico.postCuota = function(){
        if(!generarOrdenScript.validaCuota(_private.montoTotal)){
            simpleAjax.send({
                element: '#'+diccionario.tabs.GNOSE+'btnGrCro',
                root: _private.config.modulo + 'postCuota',
                form: '#'+diccionario.tabs.GNOSE+'formCronograma',
                data: '&_idOrden='+_private.idOrden,
                clear: true,
                fnCallback: function(data) {
                    if(!isNaN(data.result) && parseInt(data.result) === 1){
                        simpleScript.notify.ok({
                            content: mensajes.MSG_3,
                            callback: function(){
                                generarOrden.getGridCuotas();
                                setTimeout(function(){generarOrden.getGridGenerarOrden();},2000);
                            }
                        });
                    }else if(!isNaN(data.result) && parseInt(data.result) === 2){
                        simpleScript.notify.error({
                            content: mensajes.MSG_4
                        });
                    }
                }
            });
        }
    };
    
    this.publico.postEditOrden = function(){
        simpleAjax.send({
            element: '#'+diccionario.tabs.GNOSE+'btnEdOrd',
            root: _private.config.modulo + 'postEditOrden',
            form: '#'+diccionario.tabs.GNOSE+'formEditOrden',
            data: '&_idOrden='+_private.idOrden,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            simpleScript.closeModal('#'+diccionario.tabs.GNOSE+'formEditOrden');
                        }
                    });
                }
            }
        });
    };
    
    this.publico.postDeleteCuota = function(btn,id){
        simpleScript.notify.confirm({
            content: mensajes.MSG_7,
            callbackSI: function(){
                simpleAjax.send({
                    element: btn,
                    root: _private.config.modulo + "postDeleteCuota",
                    data: '&_idCuota='+id,
                    fnCallback: function(data) {
                        if(!isNaN(data.result) && parseInt(data.result) === 1){
                            simpleScript.notify.ok({
                                content: mensajes.MSG_8,
                                callback: function(){
                                    generarOrden.getGridCuotas();
                                }
                            });
                        }
                    }
                });
            }
        });
    };
    
    this.publico.postDeleteCuotaNo = function(){
        simpleScript.notify.warning({
            content: 'Cuota no puede ser eliminada'
        });
    };
    
    this.publico.postExportarContratoPDF = function(btn,id){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postExportarContratoPDF',
            data: '&_idOrden='+id,
            fnCallback: function(data) {
                if(parseInt(data.result) === 1){
                    $('#'+diccionario.tabs.GNOSE+'btnDowPDF').off('onclick');
                    $('#'+diccionario.tabs.GNOSE+'btnDowPDF').off('click');
                    $('#'+diccionario.tabs.GNOSE+'btnDowPDF').attr("onclick","window.open('public/files/"+data.archivo+"','_blank');generarCotizacion.deleteArchivo('"+data.archivo+"');");
                    $('#'+diccionario.tabs.GNOSE+'btnDowPDF').click();
                }
            }
        });
    };
    
    return this.publico;
    
};
var generarOrden = new generarOrden_();