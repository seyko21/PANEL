/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 24-09-2014 00:09:39 
* Descripcion : compromisoPagar.js
* ---------------------------------------
*/
var compromisoPagar_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idCompromisoPagar = 0;
    
    _private.config = {
        modulo: "ordenservicio/compromisoPagar/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : CompromisoPagar*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.COPAG,
            label: $(element).attr("title"),
            fnCallback: function(){
                compromisoPagar.getContenido();
            }
        });
    };
    
    /*contenido de tab: CompromisoPagar*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.COPAG+"_CONTAINER").html(data);
                compromisoPagar.getGridCompromisoPagar();
            }
        });
    };
    
    this.publico.getGridCompromisoPagar = function (){
        var _f1 = $("#"+diccionario.tabs.COPAG+"txt_f1").val();
        var _f2 = $("#"+diccionario.tabs.COPAG+"txt_f2").val();              
        
        var oTable = $("#"+diccionario.tabs.COPAG+"gridCompromisoPagar").dataTable({
            bFilter: true,
            sSearch: true,
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "N° OS", sWidth: "8%",},                
                {sTitle: "Cuota", sWidth: "4%",  sClass: "center"},
                {sTitle: "Fecha", sWidth: "10%",  sClass: "center"},
                {sTitle: "Cliente", sWidth: "40%"},
                {sTitle: "Mora", sWidth: "10%",sClass: "right"},
                {sTitle: "Total", sWidth: "15%",sClass: "right"},
                {sTitle: "Estado", sWidth: "8%", sClass: "center"},                
                {sTitle: "Acciones", sWidth: "8%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[0, "desc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridCompromisoPagar",
             fnServerParams: function(aoData){
                aoData.push({"name": "_f1", "value": _f1});                
                aoData.push({"name": "_f2", "value": _f2}); 
                aoData.push({"name": "_estadocb", "value": $("#"+diccionario.tabs.COPAG+"lst_estadosearch").val()}); 
            },            
            fnDrawCallback: function(){
                $("#"+diccionario.tabs.COPAG+"gridCompromisoPagar_filter").find("input").attr("placeholder","Buscar por N° OS").css("width","200px");
                simpleScript.enterSearch("#"+diccionario.tabs.COPAG+'gridCompromisoPagar',oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.COPAG,
                    typeElement: "button"
                });
                simpleScript.removeAttr.change({
                    container: "#widget_"+diccionario.tabs.COPAG,
                    typeElement: "select"
                });
               $('#'+diccionario.tabs.COPAG+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.COPAG+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();
    };
    this.publico.postPDF = function(btn,idCot){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postPDF',
            data: '&_idCompromiso='+idCot,
            fnCallback: function(data) {
                if(parseInt(data.result) === 1){
                    $('#'+diccionario.tabs.COPAG+'btnDowPDF').attr("onclick","window.open('public/files/"+data.archivo+"','_blank');compromisoPagar.deleteArchivo('"+data.archivo+"');");
                    $('#'+diccionario.tabs.COPAG+'btnDowPDF').click();
                }
            }
        });
    };
    
    this.publico.postExcel = function(btn,idCot){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postExcel',
            data: '&_idCompromiso='+idCot,
            fnCallback: function(data) {
                if(parseInt(data.result) === 1){
                    $('#'+diccionario.tabs.COPAG+'btnDowExcel').attr("onclick","window.open('public/files/"+data.archivo+"','_self');compromisoPagar.deleteArchivo('"+data.archivo+"');");
                    $('#'+diccionario.tabs.COPAG+'btnDowExcel').click();
                }
                if(!isNaN(data.result) && parseInt(data.result) === 2){
                    simpleScript.notify.error({
                        content: 'Ocurrió un error al exportar.'
                    });
                }
            }
        });
    };
    
  
    this.publico.deleteArchivo = function(archivo){
        setTimeout(function(){
            simpleAjax.send({
                root: _private.config.modulo + 'deleteArchivo',
                data: '&_archivo='+archivo
            });
        },7000);
    };
    
  
    return this.publico;
    
};
var compromisoPagar = new compromisoPagar_();