var generarCotizacionScript_ = function(){
    
    var _private = {};
    
    _private.prodAdd = [];
    
    _private.total = 0;
    
    this.publico = {};
    
    this.publico.addProducto = function(){
        simpleScript.validaCheckBox({
            id: '#'+diccionario.tabs.T8+'gridProductosFound',
            msn: mensajes.MSG_9,
            fnCallback: function(){
                var collection = $('#'+diccionario.tabs.T8+'gridProductosFound').find('tbody').find('tr'),
                    chk,cad,idProducto,descripcion,precio,tr='',produccion,total,allTr,duplicado;
                    
                    
                /*recorriendo productos seleccionados*/
                $.each(collection,function(index,value){
                    chk = $(this).find('td:eq(1)').find('input:checkbox');
                    if(chk.is(':checked')){
                        cad = chk.val().split('~');
                        idProducto = cad[0];
                        descripcion = cad[1];
                        precio = parseFloat(cad[2]).toFixed(2);
                        produccion = 0;
                        total = parseFloat(precio + produccion).toFixed(2);
                        duplicado = 0;
                        
                        /*validanco duplicidad*/
                        if(_private.prodAdd.length > 0){//hay data
                            for(var x in _private.prodAdd){
                                if(_private.prodAdd[x] === simpleAjax.stringGet(idProducto)){
                                    duplicado = 1;
                                    simpleScript.notify.warning({
                                        content: descripcion+' no se puede agregar dos veces'
                                    });
                                }
                            }
                        }
                        
                        if(duplicado === 0){//no duplicado, agregar
                            /*guardo idProducto decifrado en temporal para validar ducplicidad*/
                            _private.prodAdd.push(simpleAjax.stringGet(idProducto));

                            tr += '<tr id="'+diccionario.tabs.T8+'tr_'+idProducto+'">\n\
                                <td>\n\
                                    <input type="hidden" id="'+diccionario.tabs.T8+'hhddIdProducto" name="'+diccionario.tabs.T8+'hhddIdProducto[]" value="'+idProducto+'">\n\
                                    <input type="hidden" id="'+diccionario.tabs.T8+'hhddPrecio" name="'+diccionario.tabs.T8+'hhddPrecio[]" value="'+precio+'">\n\
                                    <input type="hidden" id="'+diccionario.tabs.T8+'hhddProduccion" name="'+diccionario.tabs.T8+'hhddProduccion[]" value="'+produccion+'">\n\
                                    '+descripcion+'\
                                </td>\n\
                                <td class="right">'+precio+'</td>\n\
                                <td class="right">'+produccion+'</td>\n\
                                <td class="right">1</td>\n\
                                <td class="right">'+total+'</td>\n\
                                <td>\n\
                                    <button type="button" class="btn btn-danger btn-xs" onclick="generarCotizacionScript.removeProducto(\''+idProducto+'\',\''+precio+'\');"><i class="fa-trash-o"></i></a>\n\
                                </td>\n\
                            </tr>';
                            
                            _private.total += (precio + produccion);
                        }
                    }
                });
                
                if(tr !== ''){
                    /*agrego los registros*/
                    $('#'+diccionario.tabs.T8+'gridProductos').find('tbody').html(tr);
                    /*total de registros*/
                    allTr = $('#'+diccionario.tabs.T8+'gridProductos').find('tbody').find('tr').length;
                    var diff = 0;
                    if(allTr < 5){
                        diff = 5 - allTr;
                        tr = simpleScript.createCell({
                            rows: diff,
                            cols: 6
                        });
                        $('#'+diccionario.tabs.T8+'gridProductos').find('tbody').append(tr);
                    }
                    /*cargando total*/
                    $('#'+diccionario.tabs.T8+'txt_total').val(parseFloat(_private.total).toFixed(2));
                    /*mensaje u cierro ventana*/
                    simpleScript.notify.ok({
                        content: 'Productos se agregaron correctamente'
                    });
                }
            }
        });
    };
    
    this.publico.removeProducto = function(idProd,precio){
        /*quitar del array*/
        for(var x in _private.prodAdd){
            if(_private.prodAdd[x] === simpleAjax.stringGet(idProd)){
                _private.prodAdd[x] = null;
            }
        }
        $('#'+diccionario.tabs.T8+'tr_'+idProd).remove();
        _private.total -= precio;
        $('#'+diccionario.tabs.T8+'txt_total').val(parseFloat(_private.total).toFixed(2));
    };
    
    this.publico.resetArrayProducto = function(){
        _private.prodAdd = [];
    };
    
    return this.publico;
    
};
var generarCotizacionScript = new generarCotizacionScript_();