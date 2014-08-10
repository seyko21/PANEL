var configurarUsuariosScript_ = function(){
    
   
   this.public = {};
   
   this.public.setEmpleado = function (el,empleado){
       $('#'+diccionario.tabs.T4+'txt_empleado').val(empleado);
       $('#'+diccionario.tabs.T4+'txt_email').val($.trim($(el).attr('data-email')));
       $('#'+diccionario.tabs.T4+'txt_empleadodesc').val($.trim($(el).attr('data-nom')));
       simpleScript.closeModal('#'+diccionario.tabs.T4+'formBuscarEmpleado');
   };
   
   return this.public;
    
};
var configurarUsuariosScript = new configurarUsuariosScript_();