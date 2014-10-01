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
            var f1, f2;
            f1 = $.datepicker.parseDate('dd/mm/yy', _f1);
            f2 = $.datepicker.parseDate('dd/mm/yy', _f2);        
            if( f1 > f2 ){
               simpleScript.notify.warning({
                      content: 'La fecha inicio no puede ser mayor que la fecha final.'      
                });           
           }        
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
                {sTitle: "N°", sWidth: "5%", bSortable: false},
                {sTitle: "N° OS", sWidth: "10%"},
                {sTitle: "Socio", sWidth: "28%"},
                {sTitle: "Fecha", sWidth: "15%",sClass: "center" },
                {sTitle: "Porcentaje", sWidth: "5%",sClass: "center" },
                {sTitle: "Comision", sWidth: "10%", sClass: "right"},             
                {sTitle: "Pagado", sWidth: "10%", sClass: "right"},             
                {sTitle: "Saldo", sWidth: "10%", sClass: "right"},             
                {sTitle: "Acciones", sWidth: "8%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[2, "asc"]],
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
            }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getGridBoleta = function (){
         $('#'+diccionario.tabs.SASOC+'gridPagoVendedor').dataTable({
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
                {sTitle: "N° Boleta", sWidth: "15%"},
                {sTitle: "Fecha", sWidth: "20%"},
                {sTitle: "Numero", sWidth: "15%"},
                {sTitle: "Serie", sWidth: "15%"},
                {sTitle: "Exonerado", sWidth: "5%", sClass: "center"},
                {sTitle: "Total", sWidth: "15%", sClass: "right"},
                {sTitle: "Retencion", sWidth: "15%", sClass: "right"},
                {sTitle: "Acciones", sWidth: "10%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[0, 'asc']],
            sScrollY: "350px",
            sAjaxSource: _private.config.modulo+'gridPagoVendedor',
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
                $('#'+diccionario.tabs.SASOC+'formPagoVendedor').modal('show');
                setTimeout(function(){                    
                    saldoSocio.getGridBoleta()
                }, 500);
                
            }
        });
    };   
    
    this.publico.postPDF = function(btn,idd){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postPDF',
            data: '&_idBoleta='+idd,
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