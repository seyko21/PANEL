/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 22-11-2014 20:11:18 
* Descripcion : vegresos.js
* ---------------------------------------
*/
var vegresos_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idVegresos = 0;
    
    _private.config = {
        modulo: "ventas/vegresos/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : Vegresos*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.VEGRE,
            label: $(element).attr("title"),
            fnCallback: function(){
                vegresos.getContenido();
            }
        });
    };
    
    /*contenido de tab: Vegresos*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.VEGRE+"_CONTAINER").html(data);
                vegresos.getGridVegresos();
            }
        });
    };
    
    this.publico.getGridVegresos = function (){
        var oTable = $("#"+diccionario.tabs.VEGRE+"gridVegresos").dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "<input type='checkbox' id='"+diccionario.tabs.VEGRE+"chk_all' onclick='simpleScript.checkAll(this,\"#"+diccionario.tabs.VEGRE+"gridVegresos\");'>", sWidth: "1%", sClass: "center", bSortable: false},                                
                {sTitle: "Descripción de Gasto", sWidth: "45%"},
                {sTitle: "Fecha", sWidth: "10%", sClass: "center"},
                {sTitle: "Moneda", sWidth: "10%", sClass: "center"},
                {sTitle: "Monto", sWidth: "15%", sClass: "right"},
                {sTitle: "Estado", sWidth: "10%", sClass: "center"}
            ],
            aaSorting: [[1, "asc"]],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+"getGridVegresos",
            fnServerParams: function(aoData) {
                aoData.push({"name": "_fecha", "value": $("#"+diccionario.tabs.VEGRE+"txt_fechaGrid").val()});                
            },
            fnDrawCallback: function() {
                $("#"+diccionario.tabs.VEGRE+"gridVegresos_filter").find("input").attr("placeholder","Buscar por Descripción").css("width","250px");
                simpleScript.enterSearch("#"+diccionario.tabs.VEGRE+"gridVegresos",oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: "#widget_"+diccionario.tabs.VEGRE,
                    typeElement: "button"
                });
                $('#'+diccionario.tabs.VEGRE+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.VEGRE+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getFormNewVegresos = function(btn){
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormNewVegresos",
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.VEGRE+"formNewVegresos").modal("show");
            }
        });
    };
    
    this.publico.getFormEditVegresos = function(btn,id){
        _private.idVegresos = id;
            
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormEditVegresos",
            data: "&_idVegresos="+_private.idVegresos,
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.VEGRE+"formEditVegresos").modal("show");
            }
        });
    };
    
    this.publico.postNewVegresos = function(){
        simpleAjax.send({
            element: "#"+diccionario.tabs.VEGRE+"btnGrVegresos",
            root: _private.config.modulo + "postNewVegresos",
            form: "#"+diccionario.tabs.VEGRE+"formNewVegresos",
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            var f = new Date();
                            var fecha = f.getDate() + "/" + (f.getMonth() +1) + "/" + f.getFullYear();
                            vegresos.getGridVegresos();
                            setTimeout(function(){
                                $("#"+diccionario.tabs.VEGRE+"lst_moneda").val('SO');
                                $("#"+diccionario.tabs.VEGRE+"txt_fecha").val(fecha);    
                            },100);
                            
                        }
                    });
                }
            }
        });
    };
    
    this.publico.postEditVegresos = function(){
        simpleAjax.send({
            element: "#"+diccionario.tabs.VEGRE+"btnEdVegresos",
            root: _private.config.modulo + "postEditVegresos",
            form: "#"+diccionario.tabs.VEGRE+"formEditVegresos",
            data: "&_idVegresos="+_private.idVegresos,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_19,
                        callback: function(){
                            _private.idVegresos = 0;
                            simpleScript.closeModal("#"+diccionario.tabs.VEGRE+"formEditVegresos");
                            simpleScript.reloadGrid("#"+diccionario.tabs.VEGRE+"gridVegresos");
                        }
                    });
                }
            }
        });
    };
        
    this.publico.postDeleteVegresosAll = function(btn){
        simpleScript.validaCheckBox({
            id: "#"+diccionario.tabs.VEGRE+"gridVegresos",
            msn: mensajes.MSG_9,
            fnCallback: function(){
                simpleScript.notify.confirm({
                    content: mensajes.MSG_7,
                    callbackSI: function(){
                        simpleAjax.send({
                            flag: 3, //si se usa SP usar flag, sino se puede eliminar esta linea
                            element: btn,
                            form: "#"+diccionario.tabs.VEGRE+"formGridVegresos",
                            root: _private.config.modulo + "postDeleteVegresosAll",
                            fnCallback: function(data) {
                                if(!isNaN(data.result) && parseInt(data.result) === 1){
                                    simpleScript.notify.ok({
                                        content: mensajes.MSG_14,
                                        callback: function(){
                                            vegresos.getGridVegresos();
                                        }
                                    });
                                }
                            }
                        });
                    }
                });
            }
        });
    };
    
    return this.publico;
    
};
var vegresos = new vegresos_();