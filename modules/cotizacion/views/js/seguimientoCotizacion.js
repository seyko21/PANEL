/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 04-09-2014 07:09:53 
* Descripcion : seguimientoCotizacion.js
* ---------------------------------------
*/
var seguimientoCotizacion_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idCotizacion = 0;
    
    _private.newEstado = '';
        
    _private.actEstado = '';
    
    _private.combo = '';
    
    _private.config = {
        modulo: "cotizacion/seguimientoCotizacion/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : SeguimientoCotizacion*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.SEGCO,
            label: $(element).attr("title"),
            fnCallback: function(){
                seguimientoCotizacion.getContenido();
            }
        });
    };
    
    /*contenido de tab: SeguimientoCotizacion*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.SEGCO+"_CONTAINER").html(data);
                seguimientoCotizacion.getGridSeguimientoCotizacion();
            }
        });
    };
    
    this.publico.getGridSeguimientoCotizacion = function (){
        var oTable = $("#"+diccionario.tabs.SEGCO+"gridSeguimientoCotizacion").dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "Código", sClass: "center",sWidth: "15%"},
                {sTitle: "Prospecto", sWidth: "25%"},
                {sTitle: "Fecha", sWidth: "10%",sClass: "center"},
                {sTitle: "Meses", sWidth: "10%",sClass: "center"},
                {sTitle: "Fec. Venc.", sWidth: "10%", sClass: "center"},
                {sTitle: "Total", sWidth: "10%", sClass: "right"},
                {sTitle: "Estado", sWidth: "15%", sClass: "center", bSortable: false},
                {sTitle: "Acciones", sWidth: "10%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[0, "desc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridSeguimientoCotizacion",
            fnServerParams: function(aoData) {
                aoData.push({"name": "_estadocb", "value": $("#"+diccionario.tabs.SEGCO+"lst_estadosearch").val()});
            },
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.SEGCO+"gridSeguimientoCotizacion_filter").find("input").attr("placeholder","Buscar por código o prospecto").css("width","300px");
                
                $('#'+diccionario.tabs.SEGCO+'gridSeguimientoCotizacion_filter').find('input').unbind();
                $('#'+diccionario.tabs.SEGCO+'gridSeguimientoCotizacion_filter').find('input').bind('keyup', function(e) {
                    $("#"+diccionario.tabs.SEGCO+"lst_estadosearch").val('A');
                    oTable.fnFilter(this.value);   
                });  
        
        
                /*para hacer evento invisible*/
                simpleScript.removeAttr.change({
                    container: "#widget_"+diccionario.tabs.SEGCO,
                    typeElement: "select"
                });
            }
        });
//        $(window).resize(function(){
//            oTable.fnAdjustColumnSizing();
//        });
        setup_widgets_desktop();
    };
    
    this.publico.getFormObservacion = function(id,newest,actest,cb,ncoti){
        _private.idCotizacion = id;
    
        _private.newEstado = newest;
        
        _private.actEstado = actest;
    
        _private.combo = cb;
    
        simpleAjax.send({
            gifProcess: true,
            dataType: "html",
            root: _private.config.modulo + "getFormObservacion",
            data: '&_ncoti='+ncoti,
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.SEGCO+"formObservacion").modal("show");
            }
        });
    };
    
    this.publico.postObservacion = function(){
        simpleAjax.send({
            flag: 1,
            element: "#"+diccionario.tabs.SEGCO+"btnGrSeguimientoCotizacion",
            root: _private.config.modulo + "postObservacion",
            form: "#"+diccionario.tabs.SEGCO+"formObservacion",
            data: '&_estado='+_private.newEstado+'&_idCotizacion='+_private.idCotizacion,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            simpleScript.closeModal('#'+diccionario.tabs.SEGCO+'formObservacion');
                            simpleScript.reloadGrid("#"+diccionario.tabs.SEGCO+"gridSeguimientoCotizacion");
                        }
                    });
                }
            }
        });
    };
    
    this.publico.cancelEstado = function(){
        $('#'+_private.combo).val(_private.actEstado);
        _private.newEstado = '';
    };
    
    this.publico.postPDF = function(btn,idCot){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postPDF',
            data: '&_idCotizacion='+idCot,
            fnCallback: function(data) {
                if(parseInt(data.result) === 1){
                    $('#'+diccionario.tabs.SEGCO+'btnDowPDF').attr("onclick","window.open('public/files/"+data.archivo+"','_blank');generarCotizacion.deleteArchivo('"+data.archivo+"');");
                    $('#'+diccionario.tabs.SEGCO+'btnDowPDF').click();
                }
            }
        });
    };            
    return this.publico;
    
};
var seguimientoCotizacion = new seguimientoCotizacion_();