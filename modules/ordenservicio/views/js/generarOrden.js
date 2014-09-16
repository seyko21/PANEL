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
                {sTitle: "Representante", sWidth: "25%"},
                {sTitle: "Cliente", sWidth: "25%"},
                {sTitle: "Monto", sWidth: "10%", sClass: "right", bSortable: false},
                {sTitle: "Acciones", sWidth: "8%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[2, "asc"]],
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
                {sTitle: "N°", sWidth: "1%",bSortable: false},
                {sTitle: "Monto", sWidth: "10%",sClass: "right",bSortable: false},
                {sTitle: "Fecha Pago", sWidth: "20%",sClass: "center",bSortable: false},
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
    
    this.publico.getFormEditOrden = function(btn,id){
        _private.idOrden = id;
        
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormEditOrden",
            data: '&_idOrden='+_private.idOrden,
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
    
    return this.publico;
    
};
var generarOrden = new generarOrden_();