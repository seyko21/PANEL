/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 02-10-2014 23:10:16 
* Descripcion : retornoInversion.js
* ---------------------------------------
*/
var retornoInversion_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idProducto = 0;
    _private.idSocio = 0;
    
    _private.config = {
        modulo: "Produccion/retornoInversion/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : RetornoInversion*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.REINV,
            label: $(element).attr("title"),
            fnCallback: function(){
                retornoInversion.getContenido();
            }
        });
    };
    
    /*contenido de tab: RetornoInversion*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.REINV+"_CONTAINER").html(data);
                retornoInversion.getGridRetornoInversion();
            }
        });
    };
    
   this.publico.getConsulta = function(btn, idP,idS ){
        _private.idProducto = idP;               
        _private.idSocio = idS;
        simpleAjax.send({
            element : btn,
            gifProcess: true,
            dataType: 'html',
            root: _private.config.modulo + 'getConsulta',
            data: '&_idProducto='+_private.idProducto+'&id_Socio='+_private.idSocio,
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.REINV+'formRoiOs').modal('show');
                setTimeout(function(){                    
                    retornoInversion.getGridRoiOS();
                }, 500);
                
            }
        });
    };       
        
    
    this.publico.getGridRetornoInversion = function (){
        var oTable = $("#"+diccionario.tabs.REINV+"gridRetornoInversion").dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "N°", sWidth: "1%",bSortable: false},             
                {sTitle: "Socio", sWidth: "20%"},
                {sTitle: "codigos", sWidth: "10%"},
                {sTitle: "Ubicación", sWidth: "20%"},
                {sTitle: "% Ganancia", sWidth: "10%", sClass: "right"},
                {sTitle: "Inversión", sWidth: "10%", sClass: "right"},
                {sTitle: "Ingresos", sWidth: "10%", sClass: "right"},
                {sTitle: "% ROI", sWidth: "10%", sClass: "right"},  
                {sTitle: "Acciones", sWidth: "8%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[1, "asc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridRetornoInversion",
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.REINV+"gridRetornoInversion_filter").find("input").attr("placeholder","Buscar por Socio o Codigos o Ubicacion").css("width","300px");
                simpleScript.enterSearch("#"+diccionario.tabs.REINV+"gridRetornoInversion",oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.REINV,
                    typeElement: "button"
                });
            }
        });
        setup_widgets_desktop();
    };     
    
     this.publico.getGridRoiOS = function (){
         $('#'+diccionario.tabs.REINV+'gridRoiOs').dataTable({
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
                {sTitle: "N° OS", sWidth: "8%"},
                {sTitle: "Codigo", sWidth: "8%"},
                {sTitle: "Fecha", sWidth: "8%",sClass: "center"},
                {sTitle: "Tiempo", sWidth: "8%",sClass: "center"},                
                {sTitle: "Importe", sWidth: "10%", sClass: "right"},
                {sTitle: "Impuesto", sWidth: "10%", sClass: "right"},
                {sTitle: "Total", sWidth: "10%", sClass: "right"},
                {sTitle: "Comision", sWidth: "10%", sClass: "right"},
                {sTitle: "Egresos", sWidth: "10%", sClass: "right"},
                {sTitle: "T. Utilidad", sWidth: "10%", sClass: "right"},
                {sTitle: "Ganancia", sWidth: "10%", sClass: "right"} 
            ],
            aaSorting: [[0, 'asc']],
            sScrollY: "350px",
            sAjaxSource: _private.config.modulo+'getGridRoiOs',
            fnServerParams: function(aoData) {
                aoData.push({"name": "_idSocio", "value": _private.idSocio});
                aoData.push({"name": "_idProducto", "value": _private.idProducto});
            }
        });
        setup_widgets_desktop();
    };    
    
    
    return this.publico;
    
};
var retornoInversion = new retornoInversion_();