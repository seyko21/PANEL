/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 30-09-2014 00:09:45 
* Descripcion : misInversiones.js
* ---------------------------------------
*/
var misInversiones_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idMisInversiones = 0;
    
    _private.config = {
        modulo: "Produccion/misInversiones/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : MisInversiones*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.MIINV,
            label: $(element).attr("title"),
            fnCallback: function(){
                misInversiones.getContenido();
            }
        });
    };
    
    /*contenido de tab: MisInversiones*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.MIINV+"_CONTAINER").html(data);
                misInversiones.getGridMisInversiones();
            }
        });
    };
    
    this.publico.getGridMisInversiones = function (){
        var oTable = $("#"+diccionario.tabs.MIINV+"gridMisInversiones").dataTable({
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
                {sTitle: "N°", sWidth: "2%",bSortable: false},
                {sTitle: "Socio", sWidth: "40%", bSortable: false},
                {sTitle: "Fecha Inversion", sWidth: "5%", sClass: "center"},
                {sTitle: "Monto", sWidth: "15%",  sClass: "right"},
                {sTitle: "Invertido", sWidth: "15%", sClass: "right"},
                {sTitle: "Saldo", sWidth: "15%", sClass: "right"},
                {sTitle: "Acciones", sWidth: "5%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[2, "asc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridMisInversiones",
            fnDrawCallback: function() {                
                simpleScript.enterSearch("#"+diccionario.tabs.MIINV+"gridMisInversiones",oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.MIINV,
                    typeElement: "button"
                });
                 simpleScript.removeAttr.change({
                    container: "#widget_"+diccionario.tabs.MIINV,
                    typeElement: "select"
                });
            }
        });
        setup_widgets_desktop();
    };
    
    
    return this.publico;
    
};
var misInversiones = new misInversiones_();