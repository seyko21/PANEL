/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 23-08-2014 22:08:31 
* Descripcion : permisoMunicipal.js
* ---------------------------------------
*/
var permisoMunicipal_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idPermisoMunicipal = 0;
    _private.idProducto = 0;
    
    _private.config = {
        modulo: "panel/permisoMunicipal/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : PermisoMunicipal*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.PERMU,
            label: $(element).attr("title"),
            fnCallback: function(){
                permisoMunicipal.getContenido();
            }
        });
    };
    
    /*contenido de tab: PermisoMunicipal*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.PERMU+"_CONTAINER").html(data);
                permisoMunicipal.getGridFichaTecnica();
                setTimeout(function(){            
                    $("#"+diccionario.tabs.PERMU+"btnProducto1").click();
                }, 2000);     
                
            }
        });
    };
    
    this.publico.getGridFichaTecnica = function (){
        var oTable = $('#'+diccionario.tabs.PERMU+'gridFichaTecnica').dataTable({                         
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [     
                {sTitle: "Ciudad", sWidth: "20%"},
                {sTitle: "Ubicación", sWidth: "30%"},                
                {sTitle: "Fecha Inicio", sWidth: "8%",  sClass: "center"},
                {sTitle: "Fecha Final", sWidth: "8%",  sClass: "center"},
                {sTitle: "Area m2", sWidth: "8%",  sClass: "center", bSortable: false},                
                {sTitle: "Estado", sWidth: "8%",  sClass: "center", bSortable: false},
                {sTitle: "Acciones", sWidth: "15%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[1, 'asc']],
            sScrollY: "200px",
            sAjaxSource: _private.config.modulo+'getGridFichaTecnica',
            fnDrawCallback: function() {
                $('#'+diccionario.tabs.PERMU+'gridFichaTecnica_filter').find('input').attr('placeholder','Buscar por Ciudad o Ubicación').css('width','350px');                          
                simpleScript.enterSearch("#"+diccionario.tabs.PERMU+'gridFichaTecnica',oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: '#widget_'+diccionario.tabs.PERMU, //widget del datagrid
                    typeElement: 'button, #'+diccionario.tabs.PERMU+'chk_all'
                });
                $('#'+diccionario.tabs.PERMU+'refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.PERMU+'refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });        
        setup_widgets_desktop();     
    };
        
     this.publico.getGridPermisoMunicipal = function(){        
        _private.idProducto = simpleScript.getParam(arguments[0]);                        
        var oTable = $('#'+diccionario.tabs.PERMU+'gridPermisoMunicipal').dataTable({
            bFilter: false, 
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [     
                {sTitle: "Fecha Inicio", sWidth: "10%", sClass: "center", bSortable: false},
                {sTitle: "Fecha Final", sWidth: "10%", sClass: "center", bSortable: false},
                {sTitle: "Monto Pagado", sWidth: "15%",  sClass: "center", bSortable: false},
                {sTitle: "Observación", sWidth: "40%",  sClass: "center", bSortable: false},                
                {sTitle: "Estado", sWidth: "8%",  sClass: "center", bSortable: false},
                {sTitle: "Acciones", sWidth: "15%", sClass: "center", bSortable: false}
            ],           
            sScrollY: "200px",
            sAjaxSource: _private.config.modulo+'getGridPermisoMunicipal',
            fnServerParams: function(aoData){
                aoData.push({"name": "_idProducto", "value": _private.idProducto});                
            },
            fnDrawCallback: function() {        
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: '#widget_'+diccionario.tabs.PERMU+'pm', //widget del datagrid
                    typeElement: 'button'
                });
                $('#'+diccionario.tabs.PERMU+'pm_refresh').click(function(){
                   oTable.fnReloadAjax(oTable.fnSettings());
                }); 
            },
            fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
               return '<button id="'+diccionario.tabs.PERMU+'pm_refresh" class="btn btn-primary" title="Actualizar"><i class="fa fa-refresh"></i></button> '+iStart +" al "+ iEnd+' de '+iTotal;
           }
        });        
        setup_widgets_desktop();     
        //Ubicacion:
        simpleAjax.send({
            dataType: 'html',
            root: _private.config.modulo + 'getUbicacion',
            data: '&_idProducto='+_private.idProducto,
            typeData: 'html',
            fnCallback: function(data){                                 
                   $('#widget_'+diccionario.tabs.PERMU+'pm header h2').html(data);                                  
            }
        });   
    };
        
    
    this.publico.getFormNewPermisoMunicipal = function(btn, id){
        _private.idProducto  = id;    
        simpleAjax.send({
            element: btn,
            dataType: "html",
            root: _private.config.modulo + "getFormNewPermisoMunicipal",
            fnCallback: function(data){
                $("#cont-modal").append(data);  /*los formularios con append*/
                $("#"+diccionario.tabs.PERMU+"formNewPermisoMunicipal").modal("show");
            }
        });
    };
    
     this.publico.getEditarPermisoMunicipal= function(id, idd){
        _private.idPermisoMunicipal = id;
        _private.idProducto  = idd;         
        simpleAjax.send({
            gifProcess: true,
            dataType: 'html',
            root: _private.config.modulo + 'getFormEditPermisoMunicipal',
            data: '&_idPermisoMuni='+ _private.idPermisoMunicipal,
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.PERMU+'formPermisoMunicipal').modal('show');           
            }
        });
    };       
    
    this.publico.postNewPermisoMunicipal = function(){
       var f1;
       var f2;              
       f1 = $.datepicker.parseDate('dd/mm/yy', $('#'+diccionario.tabs.PERMU+'txt_fi').val());
       f2 = $.datepicker.parseDate('dd/mm/yy', $('#'+diccionario.tabs.PERMU+'txt_ff').val());        
       if( f1 >= f2 ){
           simpleScript.notify.warning({
                 content: 'La fecha Inicio no puede ser mayor que la fecha final.'      
            });         
            return;
       }
                     
      simpleAjax.send({
            flag: 1,
            element: '#'+diccionario.tabs.PERMU+'btnGrPermisoMunicipal',
            root: _private.config.modulo + 'postNuevoPermisoMunicipal',
            form: '#'+diccionario.tabs.PERMU+'formNewPermisoMunicipal',
            data: '&_idProducto='+_private.idProducto ,
            clear: true,
            fnCallback: function(data) {            
               if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            simpleScript.reloadGrid('#'+diccionario.tabs.PERMU+'gridFichaTecnica');                          
                             setTimeout(function(){            
                                   permisoMunicipal.getGridPermisoMunicipal(_private.idProducto);                                   
                             }, 1000);    
                             simpleScript.closeModal('#'+diccionario.tabs.PERMU+'formNewPermisoMunicipal');
                        }
                    });
                }else if(!isNaN(data.result) && parseInt(data.result) === 2){
                    simpleScript.notify.error({
                        content: mensajes.MSG_4
                    });
                }
            }
        });
    };
    
    this.publico.postEditarPermisoMunicipal = function(){    
       var f1;
       var f2;              
       f1 = $.datepicker.parseDate('dd/mm/yy', $('#'+diccionario.tabs.PERMU+'txt_fi').val());
       f2 = $.datepicker.parseDate('dd/mm/yy', $('#'+diccionario.tabs.PERMU+'txt_ff').val());        
       if( f1 >= f2 ){
           simpleScript.notify.warning({
                 content: 'La fecha Inicio no puede ser mayor que la fecha final.'      
            });         
            return;
       }
       
        simpleAjax.send({
            flag: 2,
            element: '#'+diccionario.tabs.PERMU+'btnAPermisoMunicipal',
            root: _private.config.modulo + 'postEditarPermisoMunicipal',
            form: '#'+diccionario.tabs.PERMU+'formPermisoMunicipal',
            data: '&_idPermisoMuni='+_private.idPermisoMunicipal+'&_idProducto='+_private.idProducto,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){                            
                            simpleScript.reloadGrid('#'+diccionario.tabs.PERMU+'gridFichaTecnica');                               
                             setTimeout(function(){            
                                   permisoMunicipal.getGridPermisoMunicipal(_private.idProducto);                                   
                             }, 1000);                                                                  
                            simpleScript.closeModal('#'+diccionario.tabs.PERMU+'formPermisoMunicipal');
                        }
                    });
                }else if(!isNaN(data.result) && parseInt(data.result) === 2){
                    simpleScript.notify.error({
                        content: mensajes.MSG_4
                    });
                }
            }
        });
    };     
    
   this.publico.postDeletePermisoMunicipal  = function(btn,id ){        
        simpleScript.notify.confirm({
            content: mensajes.MSG_5,
            callbackSI: function(){                        
               simpleAjax.send({
                    flag: 3,
                    element: btn,                    
                    root: _private.config.modulo + 'postDeletePermisoMunicipal',                    
                    data: '&_idPermisoMuni='+id,                    
                    fnCallback: function(data) {
                         if(!isNaN(data.result) && parseInt(data.result) === 1){
                            simpleScript.notify.ok({
                                content: mensajes.MSG_6,
                                callback: function(){                                        
                                    simpleScript.reloadGrid('#'+diccionario.tabs.PERMU+'gridFichaTecnica');                                                              
                                    setTimeout(function(){            
                                          permisoMunicipal.getGridPermisoMunicipal(_private.idProducto);                                   
                                    }, 1000);                                         
                                }
                            });
                        } 
                    }
                });
                                
            }
        });
    };       
    
    
    return this.publico;
    
};
var permisoMunicipal = new permisoMunicipal_();