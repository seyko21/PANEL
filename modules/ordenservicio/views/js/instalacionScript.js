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
                    chk,cad,idConcepto,descripcion,precio,tr='',duplicado;
                 
                /*recorriendo productos seleccionados*/
                $.each(collection,function(index,value){
                    chk = $(this).find('td:eq(1)').find('input:checkbox');
                    if(chk.is(':checked')){
                        cad = chk.val().split('~');
                        idConcepto = cad[0];
                        descripcion = cad[1];
                        precio = parseFloat(cad[2]).toFixed(2);
                        
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

                            tr += '<tr id="'+diccionario.tabs.ORINS+'tr_'+simpleAjax.stringGet(idConcepto)+'">\n\
                                <td>\n\
                                    <input type="hidden" id="'+diccionario.tabs.ORINS+index+'hhddIdConcepto" name="'+diccionario.tabs.ORINS+'hhddIdConcepto[]" value="'+idConcepto+'">\n\
                                    '+descripcion+'\
                                </td>\n\
                                <td>\n\
                                    <label class="input"><input type="text" id="'+diccionario.tabs.ORINS+index+'txt_cantidad" name="'+diccionario.tabs.ORINS+'txt_cantidad[]" value="1" style="text-align:right" data-index="'+index+'"></label>\n\
                                </td>\n\
                                <td class="right">\n\
                                    <label class="input"><input type="text" id="'+diccionario.tabs.ORINS+index+'txt_precio" name="'+diccionario.tabs.ORINS+'txt_precio[]" value="'+precio+'" data-value="'+precio+'" data-index="'+index+'" style="text-align:right"></label>\n\
                                </td>\n\
                                <td class="right">'+precio+'</td>\n\
                                <td>\n\
                                    <button type="button" class="btn btn-danger btn-xs" onclick="instalacionScript.removeCaratula(\''+idConcepto+'\');"><i class="fa fa-trash-o"></i></a>\n\
                                </td>\n\
                            </tr>';
                        }
                    }
                });
                
                if(tr !== ''){
                    /*agrego los registros*/
                    $('#'+diccionario.tabs.ORINS+'gridConceptos').find('tbody').append(tr);
                    
                    /*mensaje de cierre ventana*/
                    simpleScript.notify.ok({
                        content: 'Conceptos se agregaron correctamente',
                        callback: function(){
                            simpleScript.closeModal('#'+diccionario.tabs.ORINS+'formBuscarConceptos');
                        }
                    });
                    instalacionScript.calculoTotal();
                    instalacionScript.calculoTotalFilaUp();
                }
            }
        });
        simpleScript.removeAttr.click({
            container: '#'+diccionario.tabs.ORINS+'gridConceptos',
            typeElement: 'button'
        });
    };
    
    this.publico.removeCaratula = function(idConc){
        /*quitar del array*/
        for(var x in _private.conceptoAdd){
            if(_private.conceptoAdd[x] === simpleAjax.stringGet(idConc)){
                _private.conceptoAdd[x] = null;
            }
        }
        $('#'+diccionario.tabs.ORINS+'tr_'+simpleAjax.stringGet(idConc)).remove();
        instalacionScript.calculoTotal();
    };
    
    this.publico.calculoTotal = function(){
        var collection = $('#'+diccionario.tabs.ORINS+'gridConceptos').find('tbody').find('tr');
        var t = 0;
        $.each(collection,function(){
            var tt = simpleScript.deleteComa($.trim($(this).find('td:eq(3)').text()));
            if(tt.length > 0){
                t += parseFloat(tt);
            }
        });
        _private.total = t;
        $('#'+diccionario.tabs.ORINS+'txt_total').val(_private.total.toFixed(2));
    };
    
    this.publico.calculoTotalFilaUp = function(){
        var collection = $('#'+diccionario.tabs.ORINS+'gridConceptos').find('tbody').find('tr');
        
        
        
        $.each(collection,function(){
            var tthis = $(this);
            
            /*keyup para cantidad*/
            $(this).find('td:eq(1)').find('input:text').keyup(function(){
                if(isNaN($(this).val())){
                    $(this).val(1);
                }else{
                    var cn = $(this).val();
                    var index = $(this).attr('data-index');
                    var precio = simpleScript.deleteComa($('#'+diccionario.tabs.ORINS+index+'txt_precio').val());
                    
                    cn = cn.replace(",","");
                  
                    var total = parseFloat(precio) * parseFloat(cn);
                    
                    tthis.find('td:eq(3)').html(total.toFixed(2));
                    instalacionScript.calculoTotal();
                }
            });
            
            /*keyup para precio*/
            $(this).find('td:eq(2)').find('input:text').keyup(function(){
                if(isNaN($(this).val())){
                    var d = $(this).attr('data-value');
                    $(this).val(d);
                }else{
                    var pr = $(this).val();
                    var index = $(this).attr('data-index');
                    var cantidad = simpleScript.deleteComa($('#'+diccionario.tabs.ORINS+index+'txt_cantidad').val());
                    
                    pr = pr.replace(",","");
                    
                    var total = parseFloat(pr) * parseFloat(cantidad);
                    
                    tthis.find('td:eq(3)').html(total.toFixed(2));
                    instalacionScript.calculoTotal();
                }
            });
            
        });
        
    };
    
    this.publico.resetArrayConcepto = function(){
        _private.conceptoAdd = [];
        _private.total = 0;
    };
    
    return this.publico;
    
};
var instalacionScript = new instalacionScript_();