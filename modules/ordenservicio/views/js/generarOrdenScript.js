var generarOrdenScript_ = function(){
    
    var _private = {};
    
    this.publico = {};
    
    this.publico.validaCuota = function(mtotal){
        var retorna = 0;
        /*validar q no sobrepase total de orden*/
        var error = 0;
        var c = 0,n=0,f1;
        var f2 = $('#'+diccionario.tabs.GNOSE+'txt_fechapago').val();
        var m = parseFloat($('#'+diccionario.tabs.GNOSE+'txt_monto').val());
        
        var f = new Date();
        var fechaActual = f.getDate() + "/" + (f.getMonth() +1) + "/" + f.getFullYear();
        
        
        /*fecha ingresada debe ser mayor a fecha actual*/
        if(simpleScript.dateDiff(fechaActual,f2) < 0){
            simpleScript.notify.warning({
                content: 'Fecha debe ser mayor o igual a la fecha actual: '+fechaActual
            });
            retorna = 1;
        }
        
        $('#'+diccionario.tabs.GNOSE+'gridCuotas').find('tbody').find('tr').each(function(){
            n = simpleScript.deleteComa($.trim($(this).find('td:eq(1)').html()));
            c += parseFloat(n);
            
            /*validar que fechas sean correlativas*/
            f1 = $.trim($(this).find('td:eq(2)').html());
            var resta = simpleScript.dateDiff(f1,f2);
            if(resta < 1){
                error = 1;
            }
        });
        
        if(error === 1){
            simpleScript.notify.warning({
                content: 'Fecha debe ser mayor a las que ya están programadas'
            });
            retorna = 1;
        }
        
        if( (c + m) > mtotal){
            simpleScript.notify.warning({
                content: 'Cuotas superan monto total ('+parseFloat(mtotal).toFixed(2)+') de orden de servicio'
            });
            retorna = 1;
        }
        return retorna;
    };    
    
    
    return this.publico;
    
};
var generarOrdenScript = new generarOrdenScript_();