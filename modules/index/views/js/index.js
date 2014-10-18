var index_ = function(){
    
    var _private = {};
    
    _private.config = {
        modulo: 'index'
    };
    
    this.public = {};
    
    this.public.postLogout = function(){
        simpleAjax.send({
            element: '#CRDACbtnEditaAccion',
            root: _private.config.modulo + '/login/logout',
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: 'Cesión terminada'
                    });
                    simpleScript.redirect('index');
                }
            }
        });
    };
    
    this.public.getChangeRol = function(idRol){
        simpleAjax.send({
            gifProcess: true,
            root: _private.config.modulo + '/index/getChangeRol/',
            data: '&_idRol='+idRol,
            fnCallback: function(){
                simpleScript.redirect('index');
            }
        });
    };
    
    this.public.getModulos = function(dominio){
        simpleAjax.send({
            dataType: 'html',
            gifProcess: true,
            root: _private.config.modulo + '/index/getModulos/'+dominio,
            fnCallback: function(data){
                $('#nav_modulos').html(data);
                $('nav ul').jarvismenu({
			accordion : true,
			speed : $.menu_speed,
			closedSign : '<em class="fa fa-expand-o"></em>',
			openedSign : '<em class="fa fa-collapse-o"></em>'
		});                                               
            }
        });
    };
    
    this.public.inactividad = function(){
        simpleAjax.send({
            dataType: 'html',
            gifProcess: true,
            root: _private.config.modulo + '/index/getLock',
            fnCallback: function(data){
                $('#cont-allheader').html('');
                $('#main').html(data);
                $(document).off('mousemove');
            }
        });
    };
    
    this.public.changeFoto = function(){
        simpleAjax.send({
            dataType: 'html',
            gifProcess: true,
            root: _private.config.modulo + '/index/changeFoto',
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.T4+'formChangeFoto').modal('show');
            }
        });
    };
    
    this.public.postAnulaCotizacionesVencidas = function(){
        simpleAjax.send({
            root: _private.config.modulo + '/index/postAnulaCotizacionesVencidas/',
        });
    };
    
    this.public.postGenerarUtilidadSocio = function(){
        simpleAjax.send({
            root: _private.config.modulo + '/index/postGenerarUtilidadSocio/',
        });
    };
    
    return this.public;
    
};
 var index = new index_();
 
 /*anular cotizaciones vencidas*/
 index.postAnulaCotizacionesVencidas();
 
  /*generar utilidades*/
setTimeout(function(){
     index.postGenerarUtilidadSocio();
},3000);