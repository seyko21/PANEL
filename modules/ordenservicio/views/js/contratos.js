/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 28-09-2014 00:09:01 
* Descripcion : contratos.js
* ---------------------------------------
*/
var contratos_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idContratos = 0;
    
    _private.config = {
        modulo: "ordenservicio/contratos/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : Contratos*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.MCON,
            label: $(element).attr("title"),
            fnCallback: function(){
                contratos.getContenido();
            }
        });
    };
    
    /*contenido de tab: Contratos*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.MCON+"_CONTAINER").html(data);
                contratos.getGridContratos();
            }
        });
    };
    
    this.publico.getGridContratos = function (){
        var _f1 = $("#"+diccionario.tabs.MCON+"txt_f1").val();
        var _f2 = $("#"+diccionario.tabs.MCON+"txt_f2").val();        
        var f1, f2;
        f1 = $.datepicker.parseDate('dd/mm/yy', _f1);
        f2 = $.datepicker.parseDate('dd/mm/yy', _f2);        
        if( f1 > f2 ){
           simpleScript.notify.warning({
                  content: 'La fecha inicio no puede ser mayor que la fecha final.'      
            });           
       }
        var oTable = $("#"+diccionario.tabs.MCON+"gridContratos").dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "N° OS", sWidth: "10%",},                
                {sTitle: "Fecha", sWidth: "10%",  sClass: "center"},
                {sTitle: "Cliente", sWidth: "25%"},
                {sTitle: "Creado por", sWidth: "25%"},
                {sTitle: "Total", sWidth: "15%",sClass: "right"},
                {sTitle: "Estado", sWidth: "10%", sClass: "center"},                
                {sTitle: "Acciones", sWidth: "8%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[2, "asc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridContratos",
            fnServerParams: function(aoData) {
                aoData.push({"name": "_f1", "value": _f1});                
                aoData.push({"name": "_f2", "value": _f2}); 
            },            
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.MCON+"gridContratos_filter").find("input").attr("placeholder","Buscar por N° OS").css("width","200px");
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.MCON,
                    typeElement: "button"
                });
            }
        });
        setup_widgets_desktop();
    };      
    
    this.publico.postExportarContratoPDF = function(btn,id){
        simpleAjax.send({
            element: btn,
            root: 'ordenservicio/generarOrden/postExportarContratoPDF',
            data: '&_idOrden='+id,
            fnCallback: function(data) {
                if(parseInt(data.result) === 1){
                    $('#'+diccionario.tabs.MCON+'btnDowPDF').off('onclick');
                    $('#'+diccionario.tabs.MCON+'btnDowPDF').off('click');
                    $('#'+diccionario.tabs.MCON+'btnDowPDF').attr("onclick","window.open('public/files/"+data.archivo+"','_blank');generarCotizacion.deleteArchivo('"+data.archivo+"');");
                    $('#'+diccionario.tabs.MCON+'btnDowPDF').click();
                }
            }
        });
    };     
    
    return this.publico;
    
};
var contratos = new contratos_();