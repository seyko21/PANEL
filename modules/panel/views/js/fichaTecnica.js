var fichaTecnica_ = function(){
    
    var _private = {};
    
    _private.idProducto = 0;    
    _private.idCaratula = 0;    
    
    _private.config = {
        modulo: 'panel/fichaTecnica/'
    };

    this.publico = {};
    
    this.publico.resetKeyProducto = function(){
        _private.idProducto = 0;
    };    
    
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.T102,
            label: $(element).attr('title'),
            fnCallback: function(){
                fichaTecnica.getCont();
            }
        });
    };
    
    this.publico.getCont = function(){
        simpleAjax.send({
            dataType: 'html',
            root: _private.config.modulo,
            fnCallback: function(data){
                $('#'+diccionario.tabs.T102+'_CONTAINER').html(data);                
                fichaTecnica.getGridFichaTecnica();
                setTimeout(function(){         
                    $("#"+diccionario.tabs.T102+"btnProducto1").click();                                    
                }, 2000);     
            }
        });
    };
    
    this.publico.getGridFichaTecnica = function (){
        var oTable = $('#'+diccionario.tabs.T102+'gridFichaTecnica').dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "<input type='checkbox' id='"+diccionario.tabs.T102+"chk_all' onclick='simpleScript.checkAll(this,\"#"+diccionario.tabs.T102+"gridFichaTecnica\");'>", sWidth: "1%", sClass: "center", bSortable: false},                
                {sTitle: "Ciudad", sWidth: "15%"},
                {sTitle: "Ubicación", sWidth: "25%"},
                {sTitle: "Elemento", sWidth: "10%"},                
                {sTitle: "Precio", sWidth: "8%",  sClass: "right"},
                {sTitle: "N° Caras", sWidth: "5%",  sClass: "center", bSortable: false},
                {sTitle: "Estado", sWidth: "8%",  sClass: "center", bSortable: false},
                {sTitle: "Acciones", sWidth: "15%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[2, 'asc']],
            sScrollY: "200px",
            sAjaxSource: _private.config.modulo+'getGridFichaTecnica',
            fnDrawCallback: function() {
                $('#'+diccionario.tabs.T102+'gridFichaTecnica_filter').find('input').attr('placeholder','Buscar por Ciudad o Ubicación').css('width','350px');               
                simpleScript.enterSearch("#"+diccionario.tabs.T102+'gridFichaTecnica',oTable);
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: '#widget_'+diccionario.tabs.T102, //widget del datagrid
                    typeElement: 'button, #'+diccionario.tabs.T102+'chk_all'
                });
            }
        });
        setup_widgets_desktop();                
        
    };
    
    this.publico.getGridCaratula = function(){        
        _private.idProducto = simpleScript.getParam(arguments[0]);                        
        $('#'+diccionario.tabs.T102+'gridCaratula').dataTable({
            bFilter: false, 
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "full_numbers", //two_button
            sServerMethod: "POST",
            bPaginate: false,
            iDisplayLength: 10, 
            sSearch: false,
            aoColumns: [                
                {sTitle: "Código", sWidth: "10%",bSortable: false},
                {sTitle: "Descripción", sWidth: "30%",bSortable: false},
                {sTitle: "Precio", sWidth: "8%",  sClass: "right", bSortable: false},
                {sTitle: "Imagen", sWidth: "8%",  sClass: "center", bSortable: false},
                {sTitle: "Iluminado", sWidth: "8%",  sClass: "center", bSortable: false},
                {sTitle: "Estado", sWidth: "8%",  sClass: "center", bSortable: false},
                {sTitle: "Acciones", sWidth: "15%", sClass: "center", bSortable: false}
            ],                     
            sAjaxSource: _private.config.modulo+'getGridCaratula',
            fnServerParams: function(aoData) {
                aoData.push({"name": "_idProducto", "value": _private.idProducto});                
            },
            fnDrawCallback: function() {       
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: '#widget_'+diccionario.tabs.T102+'Caratula', //widget del datagrid
                    typeElement: 'img, button, #'+diccionario.tabs.T102+'chk_all'
                });
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
                   $('#widget_'+diccionario.tabs.T102+'Caratula header h2').html(data);                                  
            }
        });        
        
    }
   
    this.publico.getProvincias = function(obj){
        simpleAjax.send({
            gifProcess: true,
            root: _private.config.modulo + 'getProvincias',
            data: '&_idDepartamento='+obj.idDepartamento,
            fnCallback: function(data){
                simpleScript.listBox({
                    data: data,
                    optionSelec: true,
                    content: obj.content,
                    attr:{
                        id: obj.idElement,
                        name: obj.nameElement
                    },
                    dataView:{
                        etiqueta: 'provincia',
                        value: 'id_provincia'
                    },
                    fnCallback: function(){
                        simpleScript.setEvent.change({
                            element: '#'+obj.idElement,
                            event: function(){
                                fichaTecnica.getUbigeo({
                                    idProvincia: $('#'+obj.idElement).val(),
                                    content: obj.contentUbigeo,
                                    idElement: obj.idUbigeo,
                                    nameElement: obj.idUbigeo
                                });
                            }
                        });
                    }
                });
            }
        });
    };
    
    this.publico.getUbigeo = function(obj){
        simpleAjax.send({
            gifProcess: true,
            root: _private.config.modulo + 'getUbigeo',
            data: '&_idProvincia='+obj.idProvincia,
            fnCallback: function(data){
                simpleScript.listBox({
                    data: data,
                    optionSelec: true,
                    content: obj.content,
                    attr:{
                        id: obj.idElement,
                        name: obj.nameElement
                    },
                    dataView:{
                        etiqueta: 'distrito',
                        value: 'id_ubigeo'
                    }
                });
            }
        });
    };
          
    this.publico.getNuevoFichaTecnica = function(btn){        
        simpleAjax.send({
            element: btn,
            dataType: 'html',
            root: _private.config.modulo + 'getNuevoFichaTecnica',
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.T102+'formFichaTecnica').modal('show');                       
            }
        });        
        
    };
    this.publico.getEditarFichaTecnica = function(btn,id){
        _private.idProducto = id;
        
        simpleAjax.send({
            element: btn,
            dataType: 'html',
            root: _private.config.modulo + 'getEditarFichaTecnica',
            data: '&_idProducto='+_private.idProducto,
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.T102+'formFichaTecnica').modal('show');
            }
        });
    };
    this.publico.getNuevoCaratula = function(btn, id){
        _private.idProducto  = id;         
        simpleAjax.send({
            element: btn,
            dataType: 'html',
            root: _private.config.modulo + 'getNuevoCaratula',
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.T102+'formCaratula').modal('show');
                
            }
        });        
        
    };    
     this.publico.getEditarCaratula = function(btn,id, idd){
        _private.idCaratula = id;
        _private.idProducto  = idd;         
        simpleAjax.send({
            element: btn,
            dataType: 'html',
            root: _private.config.modulo + 'getEditarCaratula',
            data: '&_idCaratula='+_private.idCaratula,
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.T102+'formCaratula').modal('show');
            }
        });
    };   
       
    this.publico.getFormAdjuntar = function(btn,id){
        _private.idCaratula = id;

        simpleAjax.send({
            element: btn,
            dataType: 'html',
            gifProcess: true,
            data: '&_idCaratula='+id,
            root: _private.config.modulo + 'getFormAdjuntar',
            fnCallback: function(data){
                $('#cont-modal').append(data);
                $('#'+diccionario.tabs.T102+'formAdjuntar').modal('show');
            }
        });
    };           
       
    this.publico.postNuevoFichaTecnica = function(){        
        //Validar Manualmente:
        if( $('#'+diccionario.tabs.T102+'txt_ubicacion').val() == '' ||
            $('#'+diccionario.tabs.T102+'txt_ancho').val() == '' ||
            $('#'+diccionario.tabs.T102+'txt_alto').val() == '' ||
            $('#'+diccionario.tabs.T102+'lst_departamento').val() == '' ||
            $('#'+diccionario.tabs.T102+'lst_provincia').val() == '' ||
            $('#'+diccionario.tabs.T102+'lst_ubigeo').val() == '' ||
            $('#'+diccionario.tabs.T102+'lst_tipopanel').val() == '' ||
            $('#'+diccionario.tabs.T102+'txt_latitud').val() == '' ||
            $('#'+diccionario.tabs.T102+'txt_longitud').val() == ''){
                simpleScript.notify.error({
                    content: mensajes.MSG_12        
                 });
                return;
        }
        
        simpleAjax.send({
            flag: 1,
            element: '#'+diccionario.tabs.T102+'btnGfitec',
            root: _private.config.modulo + 'postNuevoFichaTecnica',
            form: '#'+diccionario.tabs.T102+'formFichaTecnica',
            clear: true,
            fnCallback: function(data) {            
               if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){                            
                            fichaTecnica.getGridCaratula();
                            simpleScript.closeModal('#'+diccionario.tabs.T102+'formFichaTecnica');
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
    
    this.publico.postEditarFichaTecnica = function(){
          //Validar Manualmente:
        if( $('#'+diccionario.tabs.T102+'txt_ubicacion').val() == '' ||
            $('#'+diccionario.tabs.T102+'txt_ancho').val() == '' ||
            $('#'+diccionario.tabs.T102+'txt_alto').val() == '' ||
            $('#'+diccionario.tabs.T102+'lst_departamento').val() == '' ||
            $('#'+diccionario.tabs.T102+'lst_provincia').val() == '' ||
            $('#'+diccionario.tabs.T102+'lst_ubigeo').val() == '' ||
            $('#'+diccionario.tabs.T102+'lst_tipopanel').val() == '' ||
            $('#'+diccionario.tabs.T102+'txt_latitud').val() == '' ||
            $('#'+diccionario.tabs.T102+'txt_longitud').val() == ''){
                simpleScript.notify.error({
                    content: mensajes.MSG_12        
                 });
                return;
        }
        simpleAjax.send({
            flag: 2,
            element: '#'+diccionario.tabs.T102+'btnGfitec',
            root: _private.config.modulo + 'postEditarFichaTecnica',
            form: '#'+diccionario.tabs.T102+'formFichaTecnica',
            data: '&_idProducto='+_private.idProducto,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            _private.idProducto = 0;                            
                            simpleScript.reloadGrid('#'+diccionario.tabs.T102+'gridFichaTecnica');
                            simpleScript.closeModal('#'+diccionario.tabs.T102+'formFichaTecnica');
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
    this.publico.postDeleteFichaTecnicaAll = function(btn){
        simpleScript.validaCheckBox({
            id: '#'+diccionario.tabs.T102+'gridFichaTecnica',
            msn: mensajes.MSG_9,
            fnCallback: function(){
                simpleScript.notify.confirm({
                    content: mensajes.MSG_7,
                    callbackSI: function(){
                        simpleAjax.send({
                            flag: 4,
                            element: btn,
                            form: '#'+diccionario.tabs.T102+'fromGridFichaTecnica',
                            root: _private.config.modulo + 'postDeleteFichaTecnicaAll',
                            fnCallback: function(data) {
                                if(!isNaN(data.result) && parseInt(data.result) === 1){
                                    simpleScript.notify.ok({
                                        content: mensajes.MSG_8,
                                        callback: function(){
                                            fichaTecnica.getGridFichaTecnica();
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
         
     this.publico.postNuevoCaratula = function(){            
        simpleAjax.send({
            flag: 1,
            element: '#'+diccionario.tabs.T102+'btnGcara',
            root: _private.config.modulo + 'postNuevoCaratula',
            form: '#'+diccionario.tabs.T102+'formCaratula',
            data: '&_idProducto='+_private.idProducto ,
            clear: true,
            fnCallback: function(data) {            
               if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            simpleScript.reloadGrid('#'+diccionario.tabs.T102+'gridFichaTecnica');                                                                                 
                            setTimeout(function(){            
                                  fichaTecnica.getGridCaratula(_private.idProducto);                                   
                            }, 1000);                                                                                      
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
    
    this.publico.postEditarCaratula = function(){       
        simpleAjax.send({
            flag: 2,
            element: '#'+diccionario.tabs.T102+'btnGcara',
            root: _private.config.modulo + 'postEditarCaratula',
            form: '#'+diccionario.tabs.T102+'formCaratula',
            data: '&_idCaratula='+_private.idCaratula,
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){                            
                            simpleScript.reloadGrid('#'+diccionario.tabs.T102+'gridFichaTecnica');                                                                                     
                             setTimeout(function(){            
                                   fichaTecnica.getGridCaratula(_private.idProducto);                                   
                             }, 1000);                                                                  
                            simpleScript.closeModal('#'+diccionario.tabs.T102+'formCaratula');
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
    
   this.publico.postDeleteCaratula = function(btn,idCaratula ){        
        simpleScript.notify.confirm({
            content: mensajes.MSG_5,
            callbackSI: function(){                        
               simpleAjax.send({
                    flag: 3,
                    element: btn,                    
                    root: _private.config.modulo + 'postDeleteCaratula',                    
                    data: '&_idCaratula='+idCaratula,                    
                    fnCallback: function(data) {
                         if(!isNaN(data.result) && parseInt(data.result) === 1){
                            simpleScript.notify.ok({
                                content: mensajes.MSG_6,
                                callback: function(){                                        
                                    simpleScript.reloadGrid('#'+diccionario.tabs.T102+'gridFichaTecnica');                                                           
                                    setTimeout(function(){            
                                          fichaTecnica.getGridCaratula(_private.idProducto);                                   
                                    }, 1000);                                         
                                }
                            });
                        } else if(!isNaN(data.result) && parseInt(data.result) === 3){
                            simpleScript.notify.error({
                                content: 'La caratula esta siendo usada en el sistema, no se puede eliminar.'
                            });
                        }
                    }
                });
                                
            }
        });
    };       
    
    this.publico.postPDF = function(btn,id){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postPDF',
            data: '&_idProducto='+id,
            fnCallback: function(data) {
                if(parseInt(data.result) === 1){
                    $('#'+diccionario.tabs.T102+'btnDowPDF').attr("onclick","window.open('public/files/"+data.archivo+"','_blank');fichaTecnica.deleteArchivo('"+data.archivo+"');");
                    $('#'+diccionario.tabs.T102+'btnDowPDF').click();
                }                
            }
        });
    };
    
    this.publico.postExcel = function(btn,id){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postExcel',
            data: '&_idProducto='+id,
            fnCallback: function(data) {
                if(parseInt(data.result) === 1){
                   $('#'+diccionario.tabs.T102+'btnDowExcel').attr("onclick","window.open('public/files/"+data.archivo+"','_self');fichaTecnica.deleteArchivo('"+data.archivo+"');");
                   $('#'+diccionario.tabs.T102+'btnDowExcel').click();
                }
                if(!isNaN(data.result) && parseInt(data.result) === 2){
                    simpleScript.notify.error({
                        content: 'Ocurrió un error al exportar Ficha Tecnica.'
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
    
    this.publico.deleteAdjuntar = function(btn,id,img){
        simpleScript.notify.confirm({
            content: mensajes.MSG_7,
            callbackSI: function(){
                simpleAjax.send({
                    element: btn,
                    root: _private.config.modulo + 'deleteAdjuntar',
                    data: '&_idCaratula='+id+'&_img='+img,
                    fnCallback: function(data){
                        if(!isNaN(data.result) && parseInt(data.result) === 1){
                            simpleScript.notify.ok({
                                content: mensajes.MSG_8,
                                callback: function(){
                                    $('#'+diccionario.tabs.T102+'dow').attr('onclick','');
                                    $('#'+diccionario.tabs.T102+'dow').html(''); 
                                    $('#'+diccionario.tabs.T102+'btndow').css('display','none');
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
var fichaTecnica = new fichaTecnica_();

