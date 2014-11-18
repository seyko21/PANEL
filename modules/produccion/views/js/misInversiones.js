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
    
    _private.idInversion = 0;
    
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
    
   this.publico.getConsulta = function(btn, id){
        _private.idInversion = id;               
        simpleAjax.send({
            element : btn,
            gifProcess: true,
            dataType: 'html',
            root: _private.config.modulo + 'getConsulta',
            data: '&_idInversion='+_private.idInversion,
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.MIINV+'formDetalleInversion').modal('show');
                setTimeout(function(){                    
                    misInversiones.getGridDetalle();
                }, 500);
                
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
                $('#'+diccionario.tabs.MIINV+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.MIINV+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getGridDetalle = function (){
         $('#'+diccionario.tabs.MIINV+'gridDetalleInversion').dataTable({
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
                {sTitle: "Caratulas", sWidth: "15%"},
                {sTitle: "Ubicacion", sWidth: "25%"},
                {sTitle: "Area", sWidth: "10%",sClass: "center"},
                {sTitle: "Fecha", sWidth: "18%",sClass: "center"},
                {sTitle: "Invertido", sWidth: "15%", sClass: "right"},
                {sTitle: "T. produccion", sWidth: "15%", sClass: "right"}                                
            ],
            aaSorting: [[0, 'asc']],
            sScrollY: "150px",
            sAjaxSource: _private.config.modulo+'getGridMisInversionesDet',
            fnServerParams: function(aoData) {
                aoData.push({"name": "_idInversion", "value": _private.idInversion});
            }
        });
        setup_widgets_desktop();
    };    
    
    return this.publico;
    
};
var misInversiones = new misInversiones_();