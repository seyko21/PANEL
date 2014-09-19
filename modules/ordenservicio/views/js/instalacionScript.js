var instalacionScript_ = function(){
    
    var _private = {};
    
    _private.conceptoAdd = [];
    
    _private.total = 0;
    
    this.publico = {};
    
    this.publico.addConcepto = function(){
        simpleScript.validaCheckBox({
            id: '#'+diccionario.tabs.ORINS+'gridConceptosFound',
            msn: mensajes.MSG_9,
            fnCallback: function(){
                var collection = $('#'+diccionario.tabs.ORINS+'gridConceptosFound').find('tbody').find('tr'),
                    chk,cad,idConcepto,descripcion,precio,tr='',total,allTr,duplicado;
                 
                /*recorriendo productos seleccionados*/
                $.each(collection,function(index,value){
                    chk = $(this).find('td:eq(1)').find('input:checkbox');
                    if(chk.is(':checked')){
                        cad = chk.val().split('~');
                        idConcepto = cad[0];
                        descripcion = cad[1];
                        precio = parseFloat(cad[2]).toFixed(2);
                        
                        total = parseFloat(precio);
                        duplicado = 0;                        
                        
                        /*validanco duplicidad*/
                        if(_private.conceptoAdd.length > 0){//hay data
                            for(var x in _private.conceptoAdd){
                                if(_private.conceptoAdd[x] === simpleAjax.stringGet(idConcepto)){
                                    duplicado = 1;
                                    simpleScript.notify.warning({
                                        content: descripcion+' no se puede agregar dos veces'
                                    });
                                }
                            }
                        }
                        
                        if(duplicado === 0){//no duplicado, agregar
                            /*guardo idConcepto decifrado en temporal para validar ducplicidad*/
                            _private.conceptoAdd.push(simpleAjax.stringGet(idConcepto));

                            tr += '<tr id="'+diccionario.tabs.ORINS+'tr_'+idConcepto+'">\n\
                                <td>\n\
                                    <input type="hidden" id="'+diccionario.tabs.ORINS+'hhddIdConcepto" name="'+diccionario.tabs.ORINS+'hhddIdConcepto[]" value="'+idConcepto+'">\n\
                                    '+descripcion+'\
                                </td>\n\
                                <td class="right">\n\
                                    <label class="input"><input type="text" id="'+diccionario.tabs.ORINS+'txt_precio" name="'+diccionario.tabs.ORINS+'txt_precio[]" value="'+precio+'" data-value="'+precio+'" style="text-align:right"></label>\n\
                                </td>\n\
                                <td>\n\
                                    <button type="button" class="btn btn-danger btn-xs" onclick="instalacionScript.removeCaratula(\''+idConcepto+'\');"><i class="fa fa-trash-o"></i></a>\n\
                                </td>\n\
                            </tr>';
                            
                            _private.total += total;
                        }
                    }
                });
                
                if(tr !== ''){
                    /*agrego los registros*/
                    $('#'+diccionario.tabs.ORINS+'gridConceptos').find('tbody').html(tr);
                    /*total de registros*/
                    allTr = $('#'+diccionario.tabs.ORINS+'gridConceptos').find('tbody').find('tr').length;
                    var diff = 0;
                    if(allTr < 5){
                        diff = 5 - allTr;
                        tr = simpleScript.createCell({
                            rows: diff,
                            cols: 3
                        });
                        $('#'+diccionario.tabs.ORINS+'gridConceptos').find('tbody').append(tr);
                    }
                    /*cargando total*/
                    $('#'+diccionario.tabs.ORINS+'txt_total').val(parseFloat(_private.total).toFixed(2));
                    /*mensaje u cierro ventana*/
                    simpleScript.notify.ok({
                        content: 'Conceptos se agregaron correctamente',
                        callback: function(){
                            simpleScript.closeModal('#'+diccionario.tabs.ORINS+'formBuscarConceptos');
                        }
                    });
//                    generarCotizacionScript.calculaPrecio();
                }
            }
        });
    };
    
    this.publico.removeCaratula = function(idConc){
        /*quitar del array*/
        for(var x in _private.conceptoAdd){
            if(_private.conceptoAdd[x] === simpleAjax.stringGet(idConc)){
                _private.conceptoAdd[x] = null;
            }
        }
        $('#'+diccionario.tabs.ORINS+'tr_'+idConc).remove();
//        generarCotizacionScript.calculoTotal();
    };
    
    return this.publico;
    
};
var instalacionScript = new instalacionScript_();