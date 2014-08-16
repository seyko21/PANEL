var fichaTecnicaScript_ = function(){
    
   this.public = {};
         
   this.public.resetFromCaratula = function(){
       $('#name-caratulas, #cont-listaCaratulas').html('');
   };
   
   return this.public;
   
};
var fichaTecnicaScript = new fichaTecnicaScript_();    
