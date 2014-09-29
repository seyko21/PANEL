/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 20-09-2014 23:09:13 
* Descripcion : misCotizaciones.js
* ---------------------------------------
*/
var misCotizaciones_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idMisCotizaciones = 0;
    
    _private.config = {
        modulo: "Cotizacion/misCotizaciones/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : MisCotizaciones*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.MISCO,
            label: $(element).attr("title"),
            fnCallback: function(){
                misCotizaciones.getContenido();
            }
        });
    };
    
    /*contenido de tab: MisCotizaciones*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.MISCO+"_CONTAINER").html(data);
                misCotizaciones.getGridMisCotizaciones();
            }
        });
    };
    
    this.publico.getGridMisCotizaciones = function (){
         var oTable = $("#"+diccionario.tabs.MISCO+"gridMisCotizaciones").dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "Código", sClass: "center",sWidth: "12%"},
                {sTitle: "Prospecto", sWidth: "35%"},                
                {sTitle: "Fecha", sWidth: "10%",sClass: "center"},
                {sTitle: "Meses", sWidth: "10%",sClass: "center"},
                {sTitle: "Fec. Venc.", sWidth: "10%", sClass: "center"},
                {sTitle: "Total", sWidth: "12%", sClass: "right"},
                {sTitle: "Estado", sWidth: "15%", sClass: "center", bSortable: false},
                {sTitle: "Acciones", sWidth: "10%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[0, "desc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridMisCotizaciones",
            fnServerParams: function(aoData) {
                aoData.push({"name": "_estadocb", "value": $("#"+diccionario.tabs.MISCO+"lst_estadosearch").val()});
            },
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.MISCO+"gridMisCotizaciones_filter").find("input").attr("placeholder","Buscar por código o prospecto").css("width","300px");                                        
                simpleScript.enterSearch("#"+diccionario.tabs.MISCO+"gridMisCotizaciones",oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.change({
                    container: "#widget_"+diccionario.tabs.MISCO,
                    typeElement: "select"
                });
            }
        });

        setup_widgets_desktop();
    };
    
    this.publico.getFormNewMisCotizaciones = function(btn){
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormNewMisCotizaciones",
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.MISCO+"formNewMisCotizaciones").modal("show");
            }
        });
    };
    
    this.publico.getFormEditMisCotizaciones = function(btn,id){
        _private.idMisCotizaciones = id;
            
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormEditMisCotizaciones",
            data: "&_idMisCotizaciones="+_private.idMisCotizaciones,
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.MISCO+"formEditMisCotizaciones").modal("show");
            }
        });
    };
    
    this.publico.postPDF = function(btn,idCot){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postPDF',
            data: '&_idCotizacion='+idCot,
            fnCallback: function(data) {
                if(parseInt(data.result) === 1){
                    $('#'+diccionario.tabs.MISCO+'btnDowPDF').attr("onclick","window.open('public/files/"+data.archivo+"','_blank');generarCotizacion.deleteArchivo('"+data.archivo+"');");
                    $('#'+diccionario.tabs.MISCO+'btnDowPDF').click();
                }
            }
        });
    }; 
    
    return this.publico;
    
};
var misCotizaciones = new misCotizaciones_();