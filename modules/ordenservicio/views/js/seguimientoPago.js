/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 16-09-2014 22:09:43 
* Descripcion : seguimientoPago.js
* ---------------------------------------
*/
var seguimientoPago_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idSeguimientoPago = 0;
    
    _private.nOrden = 0;
    
    _private.idCompromiso = 0;
    
    _private.fila = 0;
    
    _private.boton = 0;
    
    _private.config = {
        modulo: "ordenservicio/seguimientoPago/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : SeguimientoPago*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.SEGPA,
            label: $(element).attr("title"),
            fnCallback: function(){
                seguimientoPago.getContenido();
            }
        });
    };
    
    /*contenido de tab: SeguimientoPago*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.SEGPA+"_CONTAINER").html(data);
                seguimientoPago.getGridSeguimientoPago();
            }
        });
    };
    
    this.publico.getGridSeguimientoPago = function (){ 
        var oTable = $("#"+diccionario.tabs.SEGPA+"gridSeguimientoPago").dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "N°", sWidth: "2%",bSortable: false},
                {sTitle: "N° OS", sWidth: "10%",sClass: "center"},
                {sTitle: "Cliente", sWidth: "30%"},
                {sTitle: "Creado por", sWidth: "20%"},
                {sTitle: "Estado", sWidth: "10%"},
                {sTitle: "Fecha", sWidth: "10%"},
                {sTitle: "Total", sWidth: "10%", sClass: "right", bSortable: false},
                {sTitle: "Acciones", sWidth: "8%", sClass: "center", bSortable: false}            
            ],
            aaSorting: [[1, "desc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridSeguimientoPago",
            fnServerParams: function(aoData) {
                aoData.push({"name": "_estadocb", "value": $("#"+diccionario.tabs.SEGPA+"lst_estadosearch").val()});
            },
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.SEGPA+"gridSeguimientoPago_filter").find("input").attr("placeholder","Buscar por N° OS o Cliente").css("width","350px");
                simpleScript.enterSearch("#"+diccionario.tabs.SEGPA+"gridSeguimientoPago",oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.SEGPA,
                    typeElement: "button"
                });
                $('#'+diccionario.tabs.SEGPA+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.SEGPA+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();
    };
    this.publico.getGridHIOR= function (){
         $('#'+diccionario.tabs.SEGPA+'gridHIOR').dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,   
            sSearch: false,
            bFilter: false,
            aoColumns: [                
                {sTitle: "N°", sWidth: "5%", sClass: "center",bSortable: false},
                {sTitle: "Fecha", sWidth: "23%", sClass: "center",bSortable: false},
                {sTitle: "Observacion", sWidth: "50%", sClass: "left",bSortable: false},
                {sTitle: "Estado", sWidth: "10%", bSortable: false}
            ],
            aaSorting: [[1, 'asc']],
            sScrollY: "350px",
            sAjaxSource: _private.config.modulo+'getGridTiempoOrden',
            fnServerParams: function(aoData) {
                aoData.push({"name": "_idOrden", "value": _private.nOrden });
            }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getFormPagarOrden = function(btn,id,norden){
        _private.nOrden = norden;
        
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormPagarOrden",
            data: "&_idOrden="+id+'&_norden='+_private.nOrden,
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.SEGPA+"formPagarOrden").modal("show");
            }
        });
    };
    
    
    this.publico.getFormPagarOrdenParametros = function(btn,fila,idCompromiso,cuota){
        _private.idCompromiso = idCompromiso;
        _private.fila = fila;
        _private.boton = btn;
        
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormPagarOrdenParametros",
            data: '&_norden='+_private.nOrden+'&_ncuota='+cuota,
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.SEGPA+"formPagarOrdenParametros").modal("show");
            }
        });
    };
    
    
    this.publico.postPagarOrden = function(){
        
//        if(!$.validator.prototype.dateValid(fech)){
//            simpleScript.notify.warning({
//                content: 'Fecha es incorrecta'
//            });
//            return false;
//        }
        simpleAjax.send({
            element: "#"+diccionario.tabs.SEGPA+"btnGpr",
            root: _private.config.modulo + "postPagarOrden",
            form: "#"+diccionario.tabs.SEGPA+"formPagarOrdenParametros",
            data: "&_idCompromiso="+_private.idCompromiso,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            seguimientoPago.getGridSeguimientoPago();
                            simpleScript.closeModal('#'+diccionario.tabs.SEGPA+'formPagarOrdenParametros');
                            $("#"+_private.fila+diccionario.tabs.SEGPA+"dfecha").html(data.fecha);
                            $(_private.boton).off('click');
                            $(_private.boton).addClass('disabled');
                            _private.idCompromiso = 0;
                            _private.boton = 0;
                        }
                    });
                }
            }
        });
    };   
    
    this.publico.getConsulta = function(id, cod){
        _private.nOrden = id;               
        simpleAjax.send({
            gifProcess: true,
            dataType: 'html',
            root: _private.config.modulo + 'getConsulta',
            data: '&_idOrden='+_private.nOrden+'&_numeroOrden='+cod,
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.SEGPA+'formHIOR').modal('show');
                setTimeout(function(){                    
                    seguimientoPago.getGridHIOR()
                }, 500);
                
            }
        });
    };       
    
    return this.publico;
    
};
var seguimientoPago = new seguimientoPago_();