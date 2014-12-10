var vGenerarCotizacionScript_ = function(){
    
    var _private = {};
    
    _private.productoAdd = [];
    
    _private.total = 0;
    
    this.publico = {};
    
    this.publico.addProducto = function(){
        simpleScript.validaCheckBox({
            id: '#'+diccionario.tabs.VCOTI+'gridProductosFound',
            msn: mensajes.MSG_9,
            fnCallback: function(){
                var collection = $('#'+diccionario.tabs.VCOTI+'gridProductosFound').find('tbody').find('tr'),
                    chk,cad,idProducto,descripcion,precio,um,cmulti,tr='',duplicado,incligv,igv,importe=0;
                 
                /*recorriendo productos seleccionados*/
                $.each(collection,function(index,value){
                    chk = $(this).find('td:eq(1)').find('input:checkbox');
                    if(chk.is(':checked')){
                        cad = chk.val().split('~');
                        idProducto = cad[0];
                        descripcion = cad[1];
                        precio = parseFloat(cad[2]).toFixed(2);
                        um = cad[3];
                        cmulti= cad[4];
                        duplicado = 0;   
                        incligv = cad[5];
                        igv  = cad[6];             
                        /*validanco duplicidad*/
                        /*if(_private.productoAdd.length > 0){//hay data
                            for(var x in _private.productoAdd){
                                if(_private.productoAdd[x] === simpleAjax.stringGet(idProducto)){
                                    duplicado = 1;
                                    simpleScript.notify.warning({
                                        content: descripcion+' no se puede agregar dos veces'
                                    });
                                }
                            }
                        }*/
                                                                                                           
                        if(duplicado === 0){//no duplicado, agregar
                            if (incligv=='S')
                                importe = precio;                            
                            else
                               importe = parseFloat(precio) + (parseFloat(precio) * parseFloat(igv)); 
                            /*guardo idProducto decifrado en temporal para validar ducplicidad*/
                            _private.productoAdd.push(simpleAjax.stringGet(idProducto));

                            tr += '<tr id="'+diccionario.tabs.VCOTI+'tr_'+simpleAjax.stringGet(idProducto)+'">\n\
                                <td>\n\
                                    <input type="hidden" id="'+diccionario.tabs.VCOTI+index+'hhddIdProducto" name="'+diccionario.tabs.VCOTI+'hhddIdProducto[]" value="'+idProducto+'">\n\
                                    <input type="hidden" id="'+diccionario.tabs.VCOTI+index+'hhddIGV" name="'+diccionario.tabs.VCOTI+'hhddIGV[]" value="'+igv+'">\n\
                                    <input type="hidden" id="'+diccionario.tabs.VCOTI+index+'hhddIncl" name="'+diccionario.tabs.VCOTI+'hhddIncl[]" value="'+incligv+'">\n\
                                    '+descripcion+'\
                                </td>\n\
                                <td>'+um+'</td>\n\
                                <td>\n\
                                    <label class="input"><input type="text" id="'+diccionario.tabs.VCOTI+index+'txt_cantidad1" name="'+diccionario.tabs.VCOTI+'txt_cantidad1[]" value="1" style="text-align:right" data-index="'+index+'"></label></td>';
                            
                            if (cmulti == '0'){
                                  tr +=    '<td>\n\
                                    <label class="input"><input style="background:#CCC;text-align:right;" type="text" id="'+diccionario.tabs.VCOTI+index+'txt_cantidad2" name="'+diccionario.tabs.VCOTI+'txt_cantidad2[]" value="1" style="text-align:right" data-index="'+index+'" readonly></label>\n\
                                </td>'
                            }else{
                              tr +=    '<td>\n\
                                    <label class="input"><input type="text" id="'+diccionario.tabs.VCOTI+index+'txt_cantidad2" name="'+diccionario.tabs.VCOTI+'txt_cantidad2[]" value="1" style="text-align:right" data-index="'+index+'"></label>\n\
                                </td>'
                            }
                            tr += '<td class="right">1.00</td>\n\
                                <td class="right">\n\
                                    <label class="input"><input type="text" id="'+diccionario.tabs.VCOTI+index+'txt_precio" name="'+diccionario.tabs.VCOTI+'txt_precio[]" value="'+precio+'" data-value="'+precio+'" data-index="'+index+'" style="text-align:right"></label>\n\
                                </td>\n\
                                <td class="right">'+parseFloat(importe).toFixed(2)+'</td>\n\
                                <td>\n\
                                    <button type="button" class="btn btn-danger btn-xs" onclick="vGenerarCotizacionScript.removeItem(\''+idProducto+'\');"><i class="fa fa-trash-o"></i></a>\n\
                                </td>\n\
                            </tr>';
                        }
                    }
                });
                
                if(tr !== ''){
                    /*agrego los registros*/
                    $('#'+diccionario.tabs.VCOTI+'gridProductos').find('tbody').append(tr);
                    
                    /*mensaje de cierre ventana*/
                    simpleScript.notify.ok({
                        content: 'Productos se agregaron correctamente',
                        callback: function(){
                            simpleScript.closeModal('#'+diccionario.tabs.VCOTI+'formBuscarProductos');
                        }
                    });
                    vGenerarCotizacionScript.calculoTotal();
                    vGenerarCotizacionScript.calculoTotalFilaUp();
                }
            }
        });
        simpleScript.removeAttr.click({
            container: '#'+diccionario.tabs.VCOTI+'gridProductos',
            typeElement: 'button'
        });
    };
    
    this.publico.removeItem = function(idConc){
        /*quitar del array*/
        for(var x in _private.productoAdd){
            if(_private.productoAdd[x] === simpleAjax.stringGet(idConc)){
                _private.productoAdd[x] = null;
            }
        }
        $('#'+diccionario.tabs.VCOTI+'tr_'+simpleAjax.stringGet(idConc)).remove();
        vGenerarCotizacionScript.calculoTotal();
    };
    
    this.publico.calculoTotal = function(){
        var collection = $('#'+diccionario.tabs.VCOTI+'gridProductos').find('tbody').find('tr');
        var t = 0;
        $.each(collection,function(){
            var tt = simpleScript.deleteComa($.trim($(this).find('td:eq(6)').text()));
            if(tt.length > 0){
                t += parseFloat(tt);
            }
        });
        _private.total = t;
        $('#'+diccionario.tabs.VCOTI+'txt_total').val(_private.total.toFixed(2));
    };
    
    this.publico.calculoTotalFilaUp = function(){
        var collection = $('#'+diccionario.tabs.VCOTI+'gridProductos').find('tbody').find('tr');
        
                
        $.each(collection,function(){
            var tthis = $(this);
            
            /*keyup para cantidad 1*/
            $(this).find('td:eq(2)').find('input:text').keyup(function(){
                if(isNaN($(this).val()) || $(this).val() < 0 ){
                    $(this).val(1);
                }else{
                    var index = $(this).attr('data-index');
                    var cn = $(this).val();
                    var cn2 =  simpleScript.deleteComa($('#'+diccionario.tabs.VCOTI+index+'txt_cantidad2').val());                    
                    var precio = simpleScript.deleteComa($('#'+diccionario.tabs.VCOTI+index+'txt_precio').val());
                    var igv = $('#'+diccionario.tabs.VCOTI+index+'hhddIGV').val();
                    var incl = $('#'+diccionario.tabs.VCOTI+index+'hhddIncl').val();
                    
                    cn = cn.replace(",","");
                    cn2 = cn2.replace(",","");
                    
                    var ttc = parseFloat(cn) * parseFloat(cn2);                    
                     tthis.find('td:eq(4)').html(ttc.toFixed(2));
                    
                    var total = 0;
                    if (incl == 'S'){
                        total = parseFloat(precio) * parseFloat(ttc); 
                    }else{
                        total = (parseFloat(precio) + parseFloat(precio) * parseFloat(igv)) * parseFloat(ttc);
                    }
                                                                                                  
                    tthis.find('td:eq(6)').html(total.toFixed(2));
                    
                    
                    vGenerarCotizacionScript.calculoTotal();
                }
            });
            
            /*keyup para Cantidad 2*/
            $(this).find('td:eq(3)').find('input:text').keyup(function(){
                if(isNaN($(this).val()) || $(this).val() < 0 ){
                    var d = $(this).attr('data-value');
                    $(this).val(d);
                }else{
                    var index = $(this).attr('data-index');
                    var cn = $(this).val();
                    var cn2 =  simpleScript.deleteComa($('#'+diccionario.tabs.VCOTI+index+'txt_cantidad1').val());                  
                    var precio = simpleScript.deleteComa($('#'+diccionario.tabs.VCOTI+index+'txt_precio').val());
                    var igv = $('#'+diccionario.tabs.VCOTI+index+'hhddIGV').val();
                    var incl = $('#'+diccionario.tabs.VCOTI+index+'hhddIncl').val();
                    
                    cn = cn.replace(",","");
                    cn2 = cn2.replace(",","");
                    
                    var ttc = parseFloat(cn) * parseFloat(cn2);
                    
                    var total = 0;
                    if (incl == 'S'){
                        total = parseFloat(precio) * parseFloat(ttc); 
                    }else{
                        total = (parseFloat(precio) + parseFloat(precio) * parseFloat(igv)) * parseFloat(ttc);
                    }
                    
                    tthis.find('td:eq(4)').html(ttc.toFixed(2));
                    tthis.find('td:eq(6)').html(total.toFixed(2));
                    vGenerarCotizacionScript.calculoTotal();
                }
            });
            
            /*keyup para precio*/
            $(this).find('td:eq(5)').find('input:text').keyup(function(){
                if(isNaN($(this).val()) || $(this).val() <= 0 ){
                    var d = $(this).attr('data-value');
                    $(this).val(d);
                }else{
                    var index = $(this).attr('data-index');
                    var pr = $(this).val();                  
                    var cn = simpleScript.deleteComa($('#'+diccionario.tabs.VCOTI+index+'txt_cantidad1').val());
                    var cn2 = simpleScript.deleteComa($('#'+diccionario.tabs.VCOTI+index+'txt_cantidad2').val());
                    var igv = $('#'+diccionario.tabs.VCOTI+index+'hhddIGV').val();
                    var incl = $('#'+diccionario.tabs.VCOTI+index+'hhddIncl').val();                    
                
                    var ttc = parseFloat(cn) * parseFloat(cn2);
                    
                    pr = pr.replace(",","");
                                                            
                    var total = 0;
                    if (incl == 'S'){
                        total = parseFloat(pr) * parseFloat(ttc); 
                    }else{
                        total = (parseFloat(pr) + parseFloat(pr) * parseFloat(igv)) * parseFloat(ttc);
                    }
                    
                    tthis.find('td:eq(6)').html(total.toFixed(2));
                    vGenerarCotizacionScript.calculoTotal();
                }
            }); 
            
        });
        
    };
    
    this.publico.resetArrayProducto = function(){
        _private.productoAdd = [];
        _private.total = 0;
    };
    
    return this.publico;
    
};
var vGenerarCotizacionScript = new vGenerarCotizacionScript_();