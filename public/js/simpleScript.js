var simpleScript_ = function(){
    /*metodos y variables privadas*/
    var _private = {};
    
    /*variables para los tabs*/
    _private.tabs = $("#cont-general-tabs").tabs();
    
    _private.tabTemplate = "<li style='position:relative;' id='#{idli}'> \n\
                                <span class='air air-top-left delete-tab' style='top:7px; left:7px;'>\n\
                                    <button class='btn btn-xs font-xs btn-default hover-transparent'><i class='fa fa-times'></i></button>\n\
                                </span>\n\
                                <a href='#{href}'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; #{label}</a>\n\
                            </li>";
    
    _private.tabs = $("#cont-general-tabs").tabs();
    
    _private.tabCounter = 0;
    /*fin variables tabs*/
    
    /*metodos y variables publicos*/
    this.public = {};
    
    this.public.removeAttr = {
        click: function(obj){
            var collection = $(obj.container).find(obj.typeElement);
            $.each(collection,function(){
                /*obtener evento*/
                var onclick = $(this).attr('onclick');
                    /*asignar evento*/
                    $(this).click(function(){
                        eval(onclick);
                    });
                $(this).attr('onclick',null);
            });
        }
    };
    
    this.public.notify = {
        
        ok: function(obj){
            $.smallBox({
                    title : (obj.title !== undefined)?obj.title : "Aviso del Sistema:",
                    content : (obj.content !== undefined)?obj.content : "No content",
                    color : (obj.color !== undefined)?obj.color : "#739E73",
                    iconSmall : (obj.icon !== undefined)?obj.icon : "fa fa-check shake animated",
                    timeout : (obj.timeout !== undefined)?obj.timeout : 6000
            });
            if(obj.callback !== undefined){
                obj.callback();
            }
            
            
//            $.bigBox({
//                    title : (obj.title !== undefined)?obj.title : "Aviso del Sistema:",
//                    content : (obj.content !== undefined)?obj.content : "No content",
//                    color : (obj.color !== undefined)?obj.color : "#739E73",
//                    timeout: (obj.timeout !== undefined)?obj.timeout : 6000,
//                    icon : (obj.icon !== undefined)?obj.icon : "fa fa-check",
//                    number : (obj.number !== undefined)?obj.number : "2"
//            });
//            if(obj.callback !== undefined){
//                obj.callback();
//            }
        },
                
        error: function(obj){
            $.smallBox({
                    title : (obj.title !== undefined)?obj.title : "Aviso del Sistema:",
                    content : (obj.content !== undefined)?obj.content : "No content",
                    color : (obj.color !== undefined)?obj.color : "#C46A69",
                    iconSmall : (obj.icon !== undefined)?obj.icon : "fa fa-warning shake animated",
                    timeout : (obj.timeout !== undefined)?obj.timeout : 6000
            });
            if(obj.callback !== undefined){
                obj.callback();
            }
//            $.bigBox({
//                    title : (obj.title !== undefined)?obj.title : "Aviso del Sistema:",
//                    content : (obj.content !== undefined)?obj.content : "No content",
//                    color : (obj.color !== undefined)?obj.color : "#C46A69",
//                    icon : (obj.icon !== undefined)?obj.icon : "fa fa-warning shake animated",
//                    timeout : (obj.timeout !== undefined)?obj.timeout : 6000,
//                    number : (obj.number !== undefined)?obj.number : "1"
//            });
//            if(obj.callback !== undefined){
//                obj.callback();
//            }
        },
              
        info: function(obj){
            $.bigBox({
                    title : (obj.title !== undefined)?obj.title : "Aviso del Sistema:",
                    content : (obj.content !== undefined)?obj.content : "No content",
                    color : (obj.color !== undefined)?obj.color : "#3276B1",
                    timeout : (obj.timeout !== undefined)?obj.timeout : 6000,
                    icon : (obj.icon !== undefined)?obj.icon : "fa fa-bell swing animated",
                    number : (obj.number !== undefined)?obj.number : "1"
            });
            if(obj.callback !== undefined){
                obj.callback();
            }
        },
                
        warning: function(obj){
            $.bigBox({
                    title : (obj.title !== undefined)?obj.title : "Aviso del Sistema:",
                    content : (obj.content !== undefined)?obj.content : "No content",
                    color : (obj.color !== undefined)?obj.color : "#C79121",
                    timeout : (obj.timeout !== undefined)?obj.timeout : 6000,
                    icon : (obj.icon !== undefined)?obj.icon : "fa fa-shield fadeInLeft animated",
                    number : (obj.number !== undefined)?obj.number : "1"
            });
            if(obj.callback !== undefined){
                obj.callback();
            }
        },
                
        msn: function(obj){
            $.smallBox({
                    title : (obj.title !== undefined)?obj.title : "",
                    content : (obj.content !== undefined)?obj.content : "No content",
                    color : (obj.color !== undefined)?obj.color : "#296191",
                    timeout : (obj.timeout !== undefined)?obj.timeout : 6000,
                    icon : (obj.icon !== undefined)?obj.icon : "fa fa-bell swing animated"
            });
            if(obj.callback !== undefined){
                obj.callback();
            }
        },
                
        smallMsn: function(obj){
            $.smallBox({
                    title : (obj.title !== undefined)?obj.title : "",
                    content : (obj.content !== undefined)?obj.content : "No content",
                    color : (obj.color !== undefined)?obj.color : "#296191",
                    iconSmall : (obj.icon !== undefined)?obj.icon : "fa fa-thumbs-up bounce animated",
                    timeout : (obj.timeout !== undefined)?obj.timeout : 6000
            });
            if(obj.callback !== undefined){
                obj.callback();
            }
        },
        
        confirm: function(obj){
            $.SmartMessageBox({
                title : "ERP - Confirmar:",
                content : (obj.content !== undefined)?obj.content : "No content",
                buttons : '[No][Si]'
            }, function(ButtonPressed) {
                    if (ButtonPressed === "Si") {
                        if(obj.callbackSI !== undefined){
                            obj.callbackSI();
                        }
                    }
                    if (ButtonPressed === "No") {
                        if(obj.callbackNO !== undefined){
                            obj.callbackNO();
                        }
                    }
            });
        }        
        
    };
    
    this.public.setEvent = {
        
        click: function(obj){
            $(obj.element).off('click');
            $(obj.element).on({
                click:function(){
                    eval(obj.event);
                }
            });
        },
        
        keypress: function(obj){
            $(obj.element).off('keypress');
            $(obj.element).on({
                keypress:function(){
                    eval(obj.event);
                }
            });
        },
        
        keyup: function(obj){
            $(obj.element).off('keyup');
            $(obj.element).on({
                keypress:function(){
                    eval(obj.event);
                }
            });
        },
        
        change: function(obj){
            $(obj.element).off('change');
            $(obj.element).on({
                keypress:function(){
                    eval(obj.event);
                }
            });
        },
        
        date: function(obj){
            $(obj.element).datepicker({
                showOn: "button",
                buttonImage: "img/date.png",
                buttonImageOnly: true,
                changeMonth: true,
                changeYear: true
            });
            $(obj.element).mask('99/99/9999');
        },
                
        dateRange: function(obj){
            $(obj.ini).datepicker({
                showOn: "button",
                buttonImageOnly: true,
                buttonImage: "img/date.png",
                numberOfMonths: 1,
                onClose: function( selectedDate ) {
                    $(obj.fin).datepicker( "option", obj.opt, selectedDate );
                }
            });
            $(obj.ini).mask('99/99/9999');
        }
    };
    
    this.public.listBox = function(obj){
        var data = obj.data.row,
            optionSelec = obj.optionSelec, /*para mostrar texto seleccionar*/
            content = obj.content,
            deffault = (obj.deffault !== undefined)?obj.deffault:'', /*para seleccionar un registro*/
            fnCallback = (obj.fnCallback !== undefined)?obj.fnCallback:'',
            dataView = obj.dataView,
            attr = '';
      
        if(obj.attr !== undefined && obj.attr !== ''){
            for(var i in obj.attr){
                attr += i+'="'+obj.attr[i]+'" ';
            }
        }
        var cb = '<select '+attr+' type="select" onkeypress="if(gKeyEnter(event)){var ntabindex = parseInt($(this).attr(\'tabindex\')) + 1;$(\'[tabindex=\' + ntabindex + \']\').focus();return false;}">';
        if(optionSelec){
            cb += '<option value="">Seleccionar</option>';
        }
        var sel = '';
        var id = '';
        var value = '';
        for(var i in data){
            id = 'data[i].'+dataView.id;
            value = 'data[i].'+dataView.value;
            if(deffault === eval(id)){
                sel = ' selected = "selected" ';
            }
            cb += '<option value="'+eval(id)+'" '+sel+'>'+eval(value)+'</option>';
        }
        cb +='</select>';
        $('#'+content).html(cb);
        
        if(fnCallback !== ''){
            fnCallback();
        }
    };
    
    this.public.redirect = function(url){
        self.location = url;
    };
    
    /*
     * simpleScript.addTab({
            id : 'xxxxxxx',
            label: 'titulo',
            content: '----',
            fnCallback: function(){
                alert(algo)
            }
        });
     */
    this.public.addTab = function(obj){
        /*verificar si tab existe*/
        if($('#cont-general-tabs').find('#'+obj.id+'_CONTAINER').length === 0){
            _private.tabCounter++;
            var li = $(_private.tabTemplate.replace(/#\{href\}/g, "#" + obj.id+'_CONTAINER').replace(/#\{label\}/g, obj.label).replace(/#\{idli\}/g, 'li-'+obj.id)), 
                tabContentHtml = (obj.content !== undefined)? obj.content : '<h1><i class="fa fa-cog fa-spin"></i> Cargando...</h1>';

            _private.tabs.find("#cont-tabs-sys").append(li);
            _private.tabs.find('#cont-main').append("<div id='" + obj.id + "_CONTAINER'><p>" + tabContentHtml + "</p></div>");
            _private.tabs.tabs("refresh");
            
            if(obj.fnCallback !== undefined){
                obj.fnCallback();
            }
        }
        $('#li-'+obj.id).find('a').click();
    };
    
    this.public.closeTabs = function(){
        $("#cont-general-tabs").on("click", 'span.delete-tab', function() {
		var panelId = $(this).closest("li").remove().attr("aria-controls");
		$("#" + panelId).remove();
		_private.tabs.tabs("refresh");
                _private.tabCounter--;
	});
    };
    
    this.public.getParam = function(param){
        if(param === undefined){
            return false;
        }else{
            return param;
        }
    };
    
    this.public.closeModal = function(obj){
        var search = obj.toString().indexOf('#'), id = '';
        if(search === -1){/*cuando se cierra modal desde botones*/
            id = '#'+$(obj).parent().parent().parent().parent().attr('id');
        }else{/*cuando se cierra modal desde closeModal*/
            id = obj;
        }
        
        $(id).modal('hide');
        setTimeout("$('"+id+"').remove()",1000);
    };
    
    this.public.noSubmit = function(form){
        $(form).find('input').keypress(function(e) { if(e.keyCode === 13)return false; });
    };
    
    this.public.noBlank = function(cadena){
        var c='';
        for(var i in cadena){
            if(cadena[i] !== ' '){
                c += cadena[i]; 
            }
        }
        return c;
    };
    
    this.public.triggerPress = function(el,e){
        if(e.keyCode === 13){
            $(el).click();
        }
    };
    
    this.public.validaCheckBox = function(obj){
        var marca = 0;
        var collection = ($(obj.id).find('ul').find('li').length > 0)?$(obj.id).find('ul').find('li'):$(obj.id).find('tbody tr');
        $.each(collection,function(){
            var chk = ($(this).find('label').find('input:checkbox').length > 0)?$(this).find('label').find('input:checkbox'):$(this).find('input:checkbox');
            if(chk.is(':checked')){
                marca = 1;
            }
        });
        if(marca === 0){
            simpleScript.notify.error({
                content: obj.msn
            });
            return false;
        }
        if(obj.fnCallback !== undefined){
            obj.fnCallback();
        }
    };
    
    this.public.checkAll = function(el,tab){
        var d = $(tab).find('tbody tr');
        if($(el).is(':checked')){
            d.each(function(){       
                $(this).find(':checkbox').prop('checked','checked');    
            });
        }else{
            d.each(function(){    
                $(this).find(':checkbox').prop('checked','');     
            });
        }
    };
    
    return this.public;
    
};

var simpleScript = new simpleScript_();

simpleScript.closeTabs();