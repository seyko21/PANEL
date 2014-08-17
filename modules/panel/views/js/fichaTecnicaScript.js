var lat = null;
var lng = null;
var map = null;
var geocoder = null;
var marker = null;

var fichaTecnicaScript_ = function(){
    
   this.publico = {};
         
   this.publico.resetFromCaratula = function(){
       $('#name-caratulas, #cont-listaCaratulas').html('');
   };
   
    this.publico.googleMap = function(xzoom,tipo) {	     
        geocoder = new google.maps.Geocoder();	        
        //Si hay valores creamos un objeto punto
        if(lat != null && lng != null){
            var punto = new google.maps.LatLng(lat,lng);
        }else{
            var punto = new google.maps.LatLng(-9.076271308095277, -78.5916839248535);
        }
        //Definimos algunas opciones del mapa a crear
        if(tipo == 1){
                var myOptions = {
                        center: punto,//centro del mapa
                        zoom: xzoom,//zoom del mapa
                        mapTypeId: google.maps.MapTypeId.ROADMAP 
                };
        }else{
                var myOptions = {
                        center: punto,//centro del mapa
                        zoom: xzoom,//zoom del mapa
                        mapTypeId: google.maps.MapTypeId.HYBRID 
                };
             }
         //creamos el mapa con las opciones anteriores y le pasamos el elemento div
         map = new google.maps.Map(document.getElementById(diccionario.tabs.T102+"map_canvas"), myOptions); 
         marker = new google.maps.Marker({
                  map: map,//el mapa creado en el paso anterior
                  position: punto,//objeto con latitud y longitud
                  draggable: true //que el marcador se pueda arrastrar
         });	        	 
         //función que actualiza los input del formulario con las nuevas latitudes
         //Estos campos suelen ser hidden
          fichaTecnicaScript.updatePosition(punto);	    
          //actualize el formulario con las nuevas coordenadas
           google.maps.event.addListener(marker, 'dragend', function(){
                fichaTecnicaScript.updatePosition(marker.getPosition());	     
           });	 
      };   
       //funcion que traduce la direccion en coordenadas
      this.publico.codeAddress = function(){	         
         //obtengo la direccion del formulario
         var address = $("#"+diccionario.tabs.T102+"txt_direccion").val();
         //hago la llamada al geodecoder
         geocoder.geocode( { 'address': address}, function(results, status) {
         //si el estado de la llamado es OK
         if (status == google.maps.GeocoderStatus.OK) {
             //centro el mapa en las coordenadas obtenidas
             map.setCenter(results[0].geometry.location);
             //coloco el marcador en dichas coordenadas
             marker.setPosition(results[0].geometry.location);
             //actualizo el formulario      
             fichaTecnicaScript.updatePosition(results[0].geometry.location);       
             //Añado un listener para cuando el markador se termine de arrastrar
             //actualize el formulario con las nuevas coordenadas
             google.maps.event.addListener(marker, 'dragend', function(){
                 fichaTecnicaScript.updatePosition(marker.getPosition());
             });
          } else {
             //si no es OK devuelvo error             
             simpleScript.notify.warning({
                content: mensajes.MSG_11                        
             });             
          }                    
        });
      };   
      
      
    //funcion que simplemente actualiza los campos del formulario
    this.publico.updatePosition = function (latLng){	       
        $("#"+diccionario.tabs.T102+"txt_latitud").val(latLng.lat());
        $("#"+diccionario.tabs.T102+"txt_longitud").val(latLng.lng());	
        lat = latLng.lat();
        lng = latLng.lng();
     };
          
    //Resetear Mapa
     this.publico.resetMap = function(m){
         x = m.getZoom();
         c = m.getCenter();
         google.maps.event.trigger(m, 'resize');
         m.setZoom(x);
         m.setCenter(c);
    }; 
    
    this.publico.enter = function(evt){	
        var nav4 = window.Event ? true : false;
	var key = nav4 ? evt.which : evt.keyCode;	
	if (key == 13){
            fichaTecnicaScript.codeAddress();
            return false ;
	}	
    };

    
                
   return this.publico;
   
};
var fichaTecnicaScript = new fichaTecnicaScript_();    
