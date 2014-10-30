var renovacionScript_ = function(){
    
    var _private = {};
    
    _private.prodAdd = [];
    
    _private.total = 0;
    
    this.publico = {};
    
    this.publico.addProducto = function(){
        simpleScript.validaCheckBox({
            id: '#'+diccionario.tabs.GENRE+'gridProductosFound',
            msn: mensajes.MSG_9,
            fnCallback: function(){
                var collection = $('#'+diccionario.tabs.GENRE+'gridProductosFound').find('tbody').find('tr'),
                    chk,cad,idProducto,descripcion,precio,tr='',produccion,total,allTr,duplicado,ubigeo,area;
                 
                var meses = $('#'+diccionario.tabs.GENRE+'txt_meses').val();
                var vprod = $('#'+diccionario.tabs.GENRE+'txt_produccion').val();
                
                /*recorriendo productos seleccionados*/
                $.each(collection,function(index,value){
                    chk = $(this).find('td:eq(1)').find('input:checkbox');
                    if(chk.is(':checked')){
                        cad = chk.val().split('~');
                        idProducto = cad[0];
                        descripcion = cad[1];
                        precio = parseFloat(cad[2]).toFixed(2);
                        area = cad[3];
                        //produccion = parseFloat(cad[3]).toFixed(2);
                        produccion = parseFloat(vprod) * parseFloat(area);
                        ubigeo = cad[4];
                        total = parseFloat(precio) * parseFloat(meses) + parseFloat(produccion);
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

                            tr += '<tr id="'+diccionario.tabs.GENRE+'tr_'+simpleAjax.stringGet(idProducto)+'">\n\
                                <td>\n\
                                    <input type="hidden" class="'+diccionario.tabs.GENRE+'hhddIdProducto" id="'+diccionario.tabs.GENRE+index+'hhddIdProducto" name="'+diccionario.tabs.GENRE+'hhddIdProducto[]" value="'+idProducto+'">\n\
                                    <input type="hidden" class="'+diccionario.tabs.GENRE+'hhddProduccion" id="'+diccionario.tabs.GENRE+index+'hhddProduccion" name="'+diccionario.tabs.GENRE+'hhddProduccion[]" value="'+produccion+'">\n\
                                    '+descripcion+'\
                                </td>\n\
                                <td class="right">'+area+'</td>\n\
                                <td class="right">\n\
                                    <label class="input"><input type="text" id="'+diccionario.tabs.GENRE+'hhddPrecio" name="'+diccionario.tabs.GENRE+'hhddPrecio[]" value="'+precio+'" data-value="'+precio+'" style="text-align:right"></label>\n\
                                </td>\n\
                                <td class="right">'+meses+'</td>\n\
                                <td class="center">\n\
                                    <input type="checkbox" id="'+diccionario.tabs.GENRE+index+'chk_disenio" name="chk_disenio[]" onclick="renovacionScript.setDisenio(this,'+index+',\''+idProducto+'\');" checked="checked">\n\
                                </td>\n\
                                <td class="right">'+produccion.toFixed(2)+'</td>\n\
                                <td class="right">'+total.toFixed(2)+'</td>\n\ </tr>';
//                              <td>\n\
//                                    <button type="button" class="btn btn-danger btn-xs" onclick="renovacionScript.removeProducto(\''+idProducto+'\',\''+precio+'\');"><i class="fa fa-trash-o"></i></a>\n\
//                                </td>\n\
                            _private.total += total;
                        }
                    }
                });
                
                if(tr !== ''){
                    renovacionScript.resetArrayProducto();
                    /*agrego los registros*/
                    $('#'+diccionario.tabs.GENRE+'gridProductos').find('tbody').html(tr);
                    /*total de registros*/
                    allTr = $('#'+diccionario.tabs.GENRE+'gridProductos').find('tbody').find('tr').length;
                    var diff = 0;
                    if(allTr < 5){
                        diff = 5 - allTr;
                        tr = simpleScript.createCell({
                            rows: diff,
                            cols: 8
                        });
                        $('#'+diccionario.tabs.GENRE+'gridProductos').find('tbody').append(tr);
                    }
                    /*cargando total*/
                    $('#'+diccionario.tabs.GENRE+'txt_total').val(parseFloat(_private.total).toFixed(2));
                    /*mensaje u cierro ventana*/
                    simpleScript.notify.ok({
                        content: 'Productos se agregaron correctamente',
                        callback: function(){
                            simpleScript.closeModal('#'+diccionario.tabs.GENRE+'formBuscarProducto');
                        }
                    });
                    renovacionScript.calculaPrecio();   
                    renovacionScript.calculoTotal();
                }
            }
        });
        simpleScript.removeAttr.click({
            container: '#'+diccionario.tabs.GENRE+'gridProductos',
            typeElement: 'button'
        });
    };
    
    this.publico.setDisenio = function(radio,index,idProducto){
        var vprod = $('#'+diccionario.tabs.GENRE+'txt_produccion').val();
        var area  = $.trim($('#'+diccionario.tabs.GENRE+'tr_'+simpleAjax.stringGet(idProducto)).find('td:eq(1)').html());
        
        if($(radio).is(':checked')){
            var m = parseFloat(vprod) * parseFloat(area);
            $('#'+diccionario.tabs.GENRE+index+'hhddProduccion').val(parseFloat(m));
            $('#'+diccionario.tabs.GENRE+'tr_'+simpleAjax.stringGet(idProducto)).find('td:eq(5)').html(parseFloat(m).toFixed(2));
        }else{
            $('#'+diccionario.tabs.GENRE+index+'hhddProduccion').val(0);
            $('#'+diccionario.tabs.GENRE+'tr_'+simpleAjax.stringGet(idProducto)).find('td:eq(5)').html(0);
        }
        renovacionScript.reCalcular();
    };
    
    this.publico.calculaPrecio = function(){
        var collection = $('#'+diccionario.tabs.GENRE+'gridProductos').find('tbody').find('tr');
        var total = 0;
        var precio = 0;
        $.each(collection,function(){
            var tthis = $(this);
            var produccion = $(this).find('td:eq(0)').find('.'+diccionario.tabs.GENRE+'hhddProduccion').val();
            var meses = $('#'+diccionario.tabs.GENRE+'txt_meses').val();
            var chk  = $(this).find('td:eq(4)').find('input:checkbox');
            
            $(this).find('td:eq(2)').find('input:text').keyup(function(){
                if(isNaN($(this).val()) || $(this).val() == '' || $(this).val() <= 0  ){
                    var precio = $(this).attr('data-value');
                    $(this).val(precio);                                                            
                }else{
                   precio = $(this).val();
                   precio = precio.replace(",","");                                        
                }
                
                if(chk.is(':checked')){
                    total = (parseFloat(precio) * parseFloat(meses)) + parseFloat(produccion);
                }else{
                    total = (parseFloat(precio) * parseFloat(meses)) ;
                }
                                        
                tthis.find('td:eq(6)').html(total.toFixed(2));
                renovacionScript.calculoTotal();                  
                
            });
        });
    };
    
    this.publico.calculoTotal = function(){
        var collection = $('#'+diccionario.tabs.GENRE+'gridProductos').find('tbody').find('tr');
        var t = 0;
        $.each(collection,function(){
            var tt = simpleScript.deleteComa($.trim($(this).find('td:eq(6)').text()));
            if(tt.length > 0){
                t += parseFloat(tt);
            }
        });
        _private.total = t;
        $('#'+diccionario.tabs.GENRE+'txt_total').val(t.toFixed(2));
    };
    
    this.publico.changeProduccion = function(el){
        var prod = el.value;
        var collection = $('#'+diccionario.tabs.GENRE+'gridProductos').find('tbody').find('tr');
        $.each(collection,function(){
            var area = $.trim($(this).find('td:eq(1)').text());
            var chk  = $(this).find('td:eq(4)').find('input:checkbox');
            var costoprod = parseFloat(prod) * parseFloat(area);
            if(area !== '' && chk.is(':checked')){
                $(this).find('td:eq(0)').find('.'+diccionario.tabs.GENRE+'hhddProduccion').val(costoprod);
                $(this).find('td:eq(5)').html(costoprod.toFixed(2));
            }
        });
        renovacionScript.reCalcular();
    };
    
    this.publico.reCalcular = function(y){
        var collection = $('#'+diccionario.tabs.GENRE+'gridProductos').find('tbody').find('tr');
        $.each(collection,function(){
            var tthis = $(this);
            var produccion = $(this).find('td:eq(0)').find('.'+diccionario.tabs.GENRE+'hhddProduccion').val();
            if(produccion >= 0){
                var meses = $('#'+diccionario.tabs.GENRE+'txt_meses').val();
                var precio = $(this).find('td:eq(2)').find('input:text').val();
                precio = precio.replace(",","");

                var total = (parseFloat(precio) * parseFloat(meses)) + parseFloat(produccion);

                tthis.find('td:eq(6)').html(total.toFixed(2));
                renovacionScript.calculoTotal();
                if(y === 1){
                    tthis.find('td:eq(3)').html(meses);
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
        $('#'+diccionario.tabs.GENRE+'tr_'+simpleAjax.stringGet(idProd)).remove();
        //_private.total -= precio;
//        $('#'+diccionario.tabs.GENRE+'txt_total').val(parseFloat(_private.total).toFixed(2));
        renovacionScript.calculoTotal();
    };
    
    this.publico.resetArrayProducto = function(){
        _private.prodAdd = [];
        _private.total = 0;
    };
    
    return this.publico;
    
};
var renovacionScript = new renovacionScript_();