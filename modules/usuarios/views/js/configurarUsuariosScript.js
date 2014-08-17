var configurarUsuariosScript_ = function(){
    
   
   this.public = {};
   
   this.public.setEmpleado = function (obj,form){
       for(var i in obj){
           $('#'+i).val(obj[i]);
       }
       simpleScript.closeModal('#'+diccionario.tabs.T4+'formBuscarEmpleado');
   };
   
   return this.public;
    
};
var configurarUsuariosScript = new configurarUsuariosScript_();