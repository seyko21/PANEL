/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 18-11-2014 22:11:42 
* Descripcion : vConsultaSaldo.js
* ---------------------------------------
*/
var vConsultaSaldo_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idVConsultaSaldo = 0;
    
    _private.config = {
        modulo: "ventas/vConsultaSaldo/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : VConsultaSaldo*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.VCSCL,
            label: $(element).attr("title"),
            fnCallback: function(){
                vConsultaSaldo.getContenido();
            }
        });
    };
    
    /*contenido de tab: VConsultaSaldo*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.VCSCL+"_CONTAINER").html(data);
                vConsultaSaldo.getGridVConsultaSaldo();
            }
        });
    };
    
    this.publico.getGridVConsultaSaldo = function (){
        var _f1 = $("#"+diccionario.tabs.VCSCL+"txt_f1").val();
        var _f2 = $("#"+diccionario.tabs.VCSCL+"txt_f2").val();        
        
        var oTable = $("#"+diccionario.tabs.VCSCL+"gridVConsultaSaldo").dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "N°", sWidth: "1%",bSortable: false},
                {sTitle: "Código", sWidth: "7%"},
                {sTitle: "Cliente", sWidth: "20%"},
                {sTitle: "Fecha", sWidth: "10%",  sClass: "center"},
                {sTitle: "Moneda", sWidth: "7%"},                                
                {sTitle: "Total", sWidth: "11%",  sClass: "right"},  
                {sTitle: "Pagado", sWidth: "11%",  sClass: "right"},
                {sTitle: "Saldo", sWidth: "11%",  sClass: "right"},
                {sTitle: "Estado", sWidth: "8%",  sClass: "center"},
                {sTitle: "Acciones", sWidth: "10%", sClass: "center", bSortable: false} 
            ],
            aaSorting: [[2, "asc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridVConsultaSaldo",
            fnServerParams: function(aoData) {
                aoData.push({"name": "_f1", "value": _f1});                
                aoData.push({"name": "_f2", "value": _f2});
                aoData.push({"name": "_tipocb", "value": $("#"+diccionario.tabs.VCSCL+"lst_tiposearch").val()}); 
            },
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.VCSCL+"gridVConsultaSaldo_filter").find("input").attr("placeholder","Buscar por Cliente").css("width","200px");
                simpleScript.enterSearch("#"+diccionario.tabs.VCSCL+"gridVConsultaSaldo",oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.VCSCL,
                    typeElement: "button"
                });
                $('#'+diccionario.tabs.VCSCL+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.VCSCL+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getGridIndexVConsultaSaldo = function (){
        var oTable = $('#'+diccionario.tabs.PANP+'gridConsultaSaldo').dataTable({
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
                {sTitle: "Código", sWidth: "7%"},
                {sTitle: "Cliente", sWidth: "20%"},
                {sTitle: "Fecha", sWidth: "10%",  sClass: "center"},
                {sTitle: "Moneda", sWidth: "7%"},                                
                {sTitle: "Total", sWidth: "11%",  sClass: "right"},  
                {sTitle: "Saldo", sWidth: "11%",  sClass: "right"}
            ],
            aaSorting: [[2, 'desc']],
            sScrollY: "125px",
            sAjaxSource: _private.config.modulo+'getGridIndexConsultaSaldo',
            fnDrawCallback: function() {
              $('#'+diccionario.tabs.PAAL+'gridConsultaSaldo_refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.PAAL+'gridConsultaSaldo_refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });

    };     
    
    this.publico.postPDF = function(btn,id,cod){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postPDF',
            data: '&_idVenta='+id+'&_cod='+cod,
            fnCallback: function(data) {
                if(parseInt(data.result) === 1){
                    $('#'+diccionario.tabs.VCSCL+'btnDowPDF').off('click');
                    $('#'+diccionario.tabs.VCSCL+'btnDowPDF').attr("onclick","window.open('public/files/"+data.archivo+"','_blank');generarCotizacion.deleteArchivo('"+data.archivo+"');");
                    $('#'+diccionario.tabs.VCSCL+'btnDowPDF').click();
                }
            }
        });
    };    
    
    return this.publico;
    
};
var vConsultaSaldo = new vConsultaSaldo_();