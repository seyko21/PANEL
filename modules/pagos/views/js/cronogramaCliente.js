/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 29-09-2014 17:09:58 
* Descripcion : cronogramaCliente.js
* ---------------------------------------
*/
var cronogramaCliente_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idOS = 0;
    
    _private.config = {
        modulo: "pagos/cronogramaCliente/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : CronogramaCliente*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.CRPG,
            label: $(element).attr("title"),
            fnCallback: function(){
                cronogramaCliente.getContenido();
            }
        });
    };
    
    /*contenido de tab: CronogramaCliente*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.CRPG+"_CONTAINER").html(data);
                cronogramaCliente.getGridCronogramaCliente();
            }
        });
    };
    
    this.publico.getGridCronogramaCliente = function (){
        var _f1 = $("#"+diccionario.tabs.CRPG+"txt_f1").val();
        var _f2 = $("#"+diccionario.tabs.CRPG+"txt_f2").val();        
        
        var oTable = $("#"+diccionario.tabs.CRPG+"gridCronogramaCliente").dataTable({
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
                {sTitle: "Cliente", sWidth: "30%"},                
                {sTitle: "Fecha", sWidth: "10%"},
                {sTitle: "Mora", sWidth: "10%", sClass: "right"},
                {sTitle: "Monto", sWidth: "10%", sClass: "right"},
                {sTitle: "Estado", sWidth: "10%"},
                {sTitle: "Acciones", sWidth: "8%", sClass: "center", bSortable: false}            
            ],
            aaSorting: [[1, "desc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridCronogramaCliente",
            fnServerParams: function(aoData) {
                aoData.push({"name": "_f1", "value": _f1});                
                aoData.push({"name": "_f2", "value": _f2}); 
            },
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.CRPG+"gridCronogramaCliente_filter").find("input").attr("placeholder","Buscar por N° OS").css("width","200px");
                simpleScript.enterSearch("#"+diccionario.tabs.CRPG+"gridCronogramaCliente",oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.CRPG,
                    typeElement: "button"
                });
            $('#'+diccionario.tabs.CRPG+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.CRPG+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getGridCuotas = function (){
        var oTable = $("#"+diccionario.tabs.CRPG+"gridCROPC").dataTable({         
            bFilter: false,
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,                      
            aoColumns: [
                {sTitle: "N° Cuota", sWidth: "6%"},
                {sTitle: "Monto S/.", sWidth: "8%",sClass: "right"},                
                {sTitle: "F. Pago", sWidth: "10%",sClass: "center"},                
                {sTitle: "Mora", sWidth: "8%",sClass: "right"},
                {sTitle: "Estado", sWidth: "10%",sClass: "center"}
            ],
            aaSorting: [[0, "asc"]],
            sScrollY: "190px",
            sAjaxSource:  _private.config.modulo+"getGridCuotas",
            fnServerParams: function(aoData) {
                aoData.push({"name": "_idOrden", "value": _private.idOS});
            }        
        });                       
       
        setup_widgets_desktop();
    };   
    
    
    this.publico.getConsulta = function(id, cod){
        _private.idOS = id;               
        simpleAjax.send({
            gifProcess: true,
            dataType: 'html',
            root: _private.config.modulo + 'getConsulta',
            data: '&_idOrden='+_private.idOS+'&_numeroOrden='+cod,
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.CRPG+'formCROPC').modal('show');
                setTimeout(function(){                    
                    cronogramaCliente.getGridCuotas()
                }, 500);
                
            }
        });
    };   
        
    
 
    return this.publico;
    
};
var cronogramaCliente = new cronogramaCliente_();