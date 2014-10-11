var httpR;
var simpleAjax_ = function(){
    /*metodos y variables privadas*/
    var _private = {};
    
    _private.clear = function(form){
        $(form+" :text").val('');
        $(form+" :hidden").val('');
        $(form+" :password").val('');
        $(form+" textarea").val('');
        $(form+" select").val(''); // 
        $(form+" :checked").attr("checked",false);
        $(form+" :radio").attr("checked",false);
    };
    
    _private.processIn = function(){
        $('#process-general').fadeIn();
    };
    
    _private.btnString = [];
    
    _private.processObjetoIn = function(el){
        /*guardo texto de boton*/
        _private.btnString.push({
            objeto: el,
            xhtml: $(el).html()
        });
        $(el).html('<i class="fa fa-gear fa-lg fa-spin"></i>');
        $(el).attr('disabled',true);
    };
      
    _private.processObjetoOut = function(el){
        var txt = '', xobj = '';
        for(var i in _private.btnString){
            if(el === _private.btnString[i].objeto){
                xobj= _private.btnString[i].objeto;
                txt =  _private.btnString[i].xhtml;
                $(xobj).html(txt);
                $(xobj).attr('disabled',false);
                break;
            }
        }
    };
            
    _private.processOut = function(){
        $('#process-general').fadeOut();
    };
    
    /*metodos y variables publicas*/
    this.public = {};
    
    this.public.cadena = function(){
        return String.fromCharCode(97,100,65,66,75,67,68,76,90,69,70,88,71,72,73,74);
    };
    
    this.public.send = function(obj){ //form, obj, ruta, evt,data, datatype, processImg
        if(obj.element !== undefined){
            _private.processObjetoIn(obj.element);
        }
        /*se activa gif loadinf*/
        if(obj.gifProcess !== undefined && obj.gifProcess !== false){
            _private.processIn();
        }
        var myRand   = parseInt(Math.random()*999999999999999);
        var typeData = (obj.dataType !== undefined)?obj.dataType:'json';
        var datos    = '_keypassw='+myRand;
        
        datos += (obj.form !== undefined)?'&'+$(obj.form).serialize():'';
        datos += (obj.flag !== undefined)?'&_flag='+obj.flag.toString():'';
        datos += (obj.data !== undefined)?obj.data:'';/*&_flag=ALGO&_flag=ALGO*/
      
        $.ajax({
            type: "POST",
            data: datos,
            url: obj.root,
            dataType: typeData,
            beforeSend: function(data2){
                if(obj.abort !== undefined && obj.abort !== false){
                    if (httpR) {
                        httpR.abort();
                    }
                    httpR = data2;
                }
            },
            success: function(data){
                /*validar error del SP*/
                if(typeData === 'json' && data.length>0 || data.error !== undefined){
                    /*no es un array, servidor devuelve cadena, y el unico q devuelve cadena es el ERROR del SP*/
                    if(data instanceof Object === false || data.error !== undefined){
                        var msn = data;
                        if(data.error !== undefined){
                            msn = data.error;
                        }
                        simpleScript.notify.error({
                            content: msn
                        });
                    }
                }
                if(obj.fnCallback !== undefined){//si existe callback
                    var callBback = obj.fnCallback;
                    callBback(data);
                }
                if(obj.element !== undefined){
                    _private.processObjetoOut(obj.element);//respuesta de servidor finalizada
                } 
                if(obj.clear !== undefined && obj.clear !== false && parseInt(data.duplicado) !== 1){
                    _private.clear(obj.form);
                }
                /*se desactiva gif loadinf*/
                if(obj.gifProcess !== undefined && obj.gifProcess !== false){
                    _private.processOut();//respuesta de servidor finalizada
                }
            }
        });
    };
    
    this.public.sendFile = function(obj){//form, obj, ruta, evt,data, datatype, processImg
        if(obj.element !== undefined){
            _private.processObjetoIn(obj.element);
        }
        /*se activa gif loadinf*/
        if(obj.gifProcess !== undefined && obj.gifProcess !== false){
            _private.processIn();
        }
        var httpR;
        var myRand   = parseInt(Math.random()*999999999999999);
        var typeData = (obj.dataType !== undefined)?obj.dataType:'json';
        
        /*añadiendo file*/
        if(obj.dataFile !== undefined){
            /*---------------upload file----------*/
            var inputFileImage = document.getElementById(obj.dataFile.file);
            var file = inputFileImage.files[0];
            var datos = new FormData($(obj.form)[0]);
            datos.append(obj.dataFile.newname,file);
            datos.append('_keypassw',myRand);
            datos.append('_accion',obj.accion);
            datos.append('_flag',obj.flag);
            datos.append('keyData',obj.keyData);
        }
       
        $.ajax({
            type: "POST",
            data: datos,
            url: obj.root,
            dataType: typeData,
            contentType:false,
            processData:false,
            cache:false,
            beforeSend: function(data2){
                if (httpR) {
                    httpR.abort();
                }
                httpR = data2;
            },
            success: function(data){
                if(obj.fnCallback !== undefined){//si existe callback
                    var callBback = obj.fnCallback;
                    callBback(data);
                }
                if(obj.element !== undefined){
                    _private.processObjetoOut(obj.element);//respuesta de servidor finalizada
                }
                if(obj.clear !== undefined && obj.clear !== false){
                    _private.clear(obj.form);
                }
                /*se desactiva gif loadinf*/
                if(obj.gifProcess !== undefined && obj.gifProcess !== false){
                    _private.processOut();//respuesta de servidor finalizada
                }
            }
        });
    };
    
    this.public.stringPost = function(c){
        return Aes.Ctr.post(c, 256);
    };
            
    this.public.stringGet = function(c){
        return Aes.Ctr.get(c, 256);
    };
    
    
    
    return this.public;
};
  
var simpleAjax = new simpleAjax_();
