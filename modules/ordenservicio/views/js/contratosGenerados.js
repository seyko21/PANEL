/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 23-09-2014 16:09:05 
* Descripcion : contratosGenerados.js
* ---------------------------------------
*/
var contratosGenerados_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idContratosGenerados = 0;
    
    _private.config = {
        modulo: "ordenservicio/contratosGenerados/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : ContratosGenerados*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.COGEN,
            label: $(element).attr("title"),
            fnCallback: function(){
                contratosGenerados.getContenido();
            }
        });
    };
    
    /*contenido de tab: ContratosGenerados*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.COGEN+"_CONTAINER").html(data);
                contratosGenerados.getGridContratosGenerados();
            }
        });
    };
    
    this.publico.getGridContratosGenerados = function (){
        
        var _f1 = $("#"+diccionario.tabs.COGEN+"txt_f1").val();
        var _f2 = $("#"+diccionario.tabs.COGEN+"txt_f2").val();        
        
        var oTable = $("#"+diccionario.tabs.COGEN+"gridContratosGenerados").dataTable({
            bFilter: false,
            sSearch: false,
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
                {sTitle: "Cliente", sWidth: "40%"},
                {sTitle: "Total", sWidth: "15%",sClass: "right"},
                {sTitle: "Estado", sWidth: "10%", sClass: "center"},                
                {sTitle: "Acciones", sWidth: "8%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[0, "desc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridContratosGenerados",
             fnServerParams: function(aoData) {
                aoData.push({"name": "_f1", "value": _f1});                
                aoData.push({"name": "_f2", "value": _f2}); 
            },
            fnDrawCallback: function() {                
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.COGEN,
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
                    $('#'+diccionario.tabs.COGEN+'btnDowPDF').off('onclick');
                    $('#'+diccionario.tabs.COGEN+'btnDowPDF').off('click');
                    $('#'+diccionario.tabs.COGEN+'btnDowPDF').attr("onclick","window.open('public/files/"+data.archivo+"','_blank');generarCotizacion.deleteArchivo('"+data.archivo+"');");
                    $('#'+diccionario.tabs.COGEN+'btnDowPDF').click();
                }
            }
        });
    }; 
   
    
    return this.publico;
    
};
var contratosGenerados = new contratosGenerados_();