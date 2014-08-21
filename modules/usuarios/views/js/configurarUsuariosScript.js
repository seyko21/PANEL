var configurarUsuariosScript_ = function(){
    
   
   this.public = {};
   
   this.public.setEmpleado = function (obj,form){
       for(var i in obj){
           $('#'+i).val(obj[i]);
       }
       simpleScript.closeModal('#'+diccionario.tabs.T4+'formBuscarEmpleado');
   };
   
   this.public.validaFormUser = function(flag){
       var empleado = $('#'+diccionario.tabs.T4+'txt_empleado').val();
       var mail = $('#'+diccionario.tabs.T4+'txt_email').val();
       
       if(empleado === ''){
            simpleScript.notify.warning({
                content: 'Tiene que buscar y seleccionar un empleado.'
            });
       }else if(mail.indexOf('@', 0) === -1 || mail.indexOf('.', 0) === -1) {
            simpleScript.notify.warning({
                content: 'Ingrese un correo válido.'
            });
       }else{
            simpleScript.validaCheckBox({
                id: '#s2',
                msn: 'Seleccione al menos un rol.',
                fnCallback: function(){
                    if(flag === 1){
                        configurarUsuarios.postNuevoUsuario();
                    }else{
                        configurarUsuarios.postEditarUsuario();
                    }
                }
            });
       }
   };
   
   return this.public;
    
};
var configurarUsuariosScript = new configurarUsuariosScript_();