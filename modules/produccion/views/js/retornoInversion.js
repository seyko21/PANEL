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
            id : diccionario.tabs.CREIN,
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
                $("#"+diccionario.tabs.CREIN+"_CONTAINER").html(data);
                retornoInversion.getGridRetornoInversion();
            }
        });
    };
    
   this.publico.getConsulta = function(btn, idP,idS, nom ){
        _private.idProducto = idP;               
        _private.idSocio = idS;
        simpleAjax.send({
            element : btn,
            gifProcess: true,
            dataType: 'html',
            root: _private.config.modulo + 'getConsulta',
            data: '&_idProducto='+_private.idProducto+'&id_Socio='+_private.idSocio+'&_nom='+nom,
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.CREIN+'formRoiOs').modal('show');
                setTimeout(function(){                    
                    retornoInversion.getGridRoiOS();
                }, 500);
                
            }
        });
    };       
        
    
    this.publico.getGridRetornoInversion = function (){
        var oTable = $("#"+diccionario.tabs.CREIN+"gridRetornoInversion").dataTable({
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
            aaSorting: [[1, "asc"],[2, "asc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridRetornoInversion",
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.CREIN+"gridRetornoInversion_filter").find("input").attr("placeholder","Buscar por Socio o Codigos o Ubicacion").css("width","300px");
                simpleScript.enterSearch("#"+diccionario.tabs.CREIN+"gridRetornoInversion",oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.CREIN,
                    typeElement: "button"
                });
                $('#'+diccionario.tabs.CREIN+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.CREIN+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();
    };     
    
     this.publico.getGridRoiOS = function (){
         $('#'+diccionario.tabs.CREIN+'gridRoiOs').dataTable({
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
                {sTitle: "Fecha", sWidth: "10%",sClass: "center"},
                {sTitle: "Tiempo", sWidth: "12%",sClass: "center"},                
                {sTitle: "Total ", sWidth: "10%", sClass: "right"},
                {sTitle: "E. Impuesto", sWidth: "8%", sClass: "right"},                               
                {sTitle: "E. Comisión", sWidth: "10%", sClass: "right"},
                {sTitle: "E. Gastos", sWidth: "8%", sClass: "right"},
                {sTitle: "T. Utilidad", sWidth: "10%", sClass: "right"},
                {sTitle: "T. Ganancia", sWidth: "10%", sClass: "right"},
                {sTitle: "Ingresos", sWidth: "10%", sClass: "right", bSortable: false},
                {sTitle: "Estado", sWidth: "10%", sClass: "center", bSortable: false} 
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