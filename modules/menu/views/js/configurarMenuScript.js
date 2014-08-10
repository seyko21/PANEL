var configurarMenuScript_ = function(){
    
   this.public = {};
   
   /*limpia keys y contenedores de modulo, menupri, opcion, al eliminar o ver modulos en dominio*/
   this.public.resetFromDominio = function(){
       $('#name-dominio, #cont-listaModulos').html('');
       $('#name-modulo, #cont-listaMenuPrincipal').html('');
       $('#name-menuprincipal, #cont-listaOpciones').html('');
   };
   
   /*limpia keys y contenedores de menupri, opcion, al eliminar o ver menus en modulo*/
   this.public.resetFromModulo = function(){
       $('#name-modulo, #cont-listaMenuPrincipal').html('');
       $('#name-menuprincipal, #cont-listaOpciones').html('');
   };
   
   /*limpia keys y contenedores de opcion, al eliminar o ver opciones en menupri*/
   this.public.resetFromOpcion = function(){
       $('#name-menuprincipal, #cont-listaOpciones').html('');
   };
   
   return this.public;
   
};
var configurarMenuScript = new configurarMenuScript_();