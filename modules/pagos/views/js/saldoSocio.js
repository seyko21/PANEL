/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 26-09-2014 23:09:38 
* Descripcion : saldoSocio.js
* ---------------------------------------
*/
var saldoSocio_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idComision = 0;
        
    _private.config = {
        modulo: "pagos/saldoSocio/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : SaldoSocio*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.SASOC,
            label: $(element).attr("title"),
            fnCallback: function(){
                saldoSocio.getContenido();
            }
        });
    };
    
    /*contenido de tab: SaldoSocio*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.SASOC+"_CONTAINER").html(data);
                saldoSocio.getGridSaldoSocio();
            }
        });
    };
    
    this.publico.getGridSaldoSocio = function (){
        
        var _cb = $("#"+diccionario.tabs.SASOC+"lst_estadosearch").val();
        if (_cb == 'T'){
            $("#"+diccionario.tabs.SASOC+"txt_f1").prop('disabled',true);
            $("#"+diccionario.tabs.SASOC+"txt_f2").prop('disabled',true);
        }else{                
            $("#"+diccionario.tabs.SASOC+"txt_f1").prop('disabled',false);
            $("#"+diccionario.tabs.SASOC+"txt_f2").prop('disabled',false);
            var _f1 = $("#"+diccionario.tabs.SASOC+"txt_f1").val();
            var _f2 = $("#"+diccionario.tabs.SASOC+"txt_f2").val();               
        }
        
        var oTable = $("#"+diccionario.tabs.SASOC+"gridSaldoSocio").dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "Cuota", sWidth: "5%"},
                {sTitle: "N° OS", sWidth: "5%"},
                {sTitle: "Socio", sWidth: "20%"},
                {sTitle: "Fecha", sWidth: "15%",sClass: "center" },
                {sTitle: "Porcentaje", sWidth: "5%",sClass: "center" },
                {sTitle: "Alquiler", sWidth: "10%"},                
                {sTitle: "Comision", sWidth: "10%", sClass: "right"},             
                {sTitle: "Pagado", sWidth: "10%", sClass: "right"},             
                {sTitle: "Saldo", sWidth: "10%", sClass: "right"},             
                {sTitle: "Acciones", sWidth: "8%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[1, "asc"],[0, "asc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridSaldoSocio",
            fnServerParams: function(aoData){
                aoData.push({"name": "_f1", "value": _f1});                
                aoData.push({"name": "_f2", "value": _f2}); 
                aoData.push({"name": "_estadocb", "value": _cb});
            },
            fnDrawCallback: function(){
                $("#"+diccionario.tabs.SASOC+"gridSaldoSocio_filter").find("input").attr("placeholder","Buscar por N° OS o Socio").css("width","200px");
                simpleScript.enterSearch("#"+diccionario.tabs.SASOC+"gridSaldoSocio",oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.SASOC,
                    typeElement: "button"
                });
                simpleScript.removeAttr.change({
                    container: "#widget_"+diccionario.tabs.SASOC,
                    typeElement: "select"
                });
                $('#'+diccionario.tabs.SASOC+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.SASOC+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getGridBoleta = function (){
         $('#'+diccionario.tabs.SASOC+'gridPagoSocio').dataTable({
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
                {sTitle: "N° Recibo", sWidth: "15%"},
                {sTitle: "Fecha", sWidth: "20%"},
                {sTitle: "Numero", sWidth: "15%"},
                {sTitle: "Serie", sWidth: "15%" },                
                {sTitle: "Total", sWidth: "15%", sClass: "right"},
                {sTitle: "Acciones", sWidth: "10%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[0, 'asc']],
            sScrollY: "150px",
            sAjaxSource: _private.config.modulo+'gridPagoSocio',
            fnServerParams: function(aoData){
                aoData.push({"name": "_idComision", "value": _private.idComision});
            }
        });
        setup_widgets_desktop();
    };    
        
    this.publico.getConsulta = function(btn, id, nom){
        _private.idComision = id;               
        simpleAjax.send({
            element : btn,
            gifProcess: true,
            dataType: 'html',
            root: _private.config.modulo + 'getConsulta',
            data: '&_idComision='+_private.idComision+'&_persona='+nom,
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.SASOC+'formPagoSocio').modal('show');
                setTimeout(function(){                    
                    saldoSocio.getGridBoleta()
                }, 500);
                
            }
        });
    };   
    
    this.publico.postPDF = function(btn,idd,nb){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postPDF',
            data: '&_idBoleta='+idd+'&_numBoleta='+nb,
            fnCallback: function(data) {
                if(parseInt(data.result) === 1){
                    $('#'+diccionario.tabs.SASOC+'btnDowPDF').attr("onclick","window.open('public/files/"+data.archivo+"','_blank');compromisoPagar.deleteArchivo('"+data.archivo+"');");
                    $('#'+diccionario.tabs.SASOC+'btnDowPDF').click();
                }
            }
        });
    };  
    
    return this.publico;
    
};
var saldoSocio = new saldoSocio_();