var vclienteScript_ = function(){
    
    var _private = {};
    
    _private.contador = 0;
       
    this.publico = {};
    
    this.publico.validarTipoPersona = function(obj){        
        if( obj.value == 'J'){
            $('.frmpn ').css('display','none');
        }else{
            $('.frmpn ').css('display','block');
        }
    };
    
   
    return this.publico;
    
};
var vclienteScript = new vclienteScript_();