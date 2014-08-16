var fichaTecnica_ = function(){
    
    var _private = {};
    
    _private.idProducto = 0;
    
    _private.idCaratula = 0;
    
    _private.config = {
        modulo: 'panel/fichaTecnica/'
    };

    this.publico = {};
    
    this.publico.resetKeyProducto = function(){
        _private.idProducto = 0;
    };    
    
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.T102,
            label: $(element).attr('title'),
            fnCallback: function(){
                fichaTecnica.getCont();
            }
        });
    };
    
    this.publico.getCont = function(){
        simpleAjax.send({
            dataType: 'html',
            root: _private.config.modulo,
            fnCallback: function(data){
                $('#'+diccionario.tabs.T102+'_CONTAINER').html(data);                
                fichaTecnica.getGridFichaTecnica();
            }
        });
    };
    
    this.publico.getGridFichaTecnica = function (){
        $('#'+diccionario.tabs.T102+'gridFichaTecnica').dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "<input type='checkbox' id='"+diccionario.tabs.T102+"chk_all' onclick='simpleScript.checkAll(this,\"#"+diccionario.tabs.T102+"gridFichaTecnica\");'>", sWidth: "1%", sClass: "center", bSortable: false},
                {sTitle: "Ubicación", sWidth: "55%"},
                {sTitle: "Area m2", sWidth: "8%",  sClass: "center", bSortable: false},
                {sTitle: "Estado", sWidth: "8%",  sClass: "center", bSortable: false},
                {sTitle: "Acciones", sWidth: "15%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[1, 'asc']],
            sScrollY: "200px",
            sAjaxSource: _private.config.modulo+'getGridFichaTecnica',
            fnDrawCallback: function() {
                $('#'+diccionario.tabs.T102+'gridFichaTecnica_filter').find('input').attr('placeholder','Buscar');
                /*para hacer evento invisible*/
               /* simpleScript.removeAttr.click({
                    container: '#widget_'+diccionario.tabs.T102, //widget del datagrid
                    typeElement: 'button, #'+diccionario.tabs.T102+'chk_all'
                });*/
            }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getListaCaratulas = function(){
         _private.idCaratula = simpleScript.getParam(arguments[0]);
        
        simpleAjax.send({
            dataType: 'html',
            root: _private.config.modulo + 'getListaCaratulas',
            data: '&_idProducto='+_private.idCaratula,
            fnCallback: function(data){
                $('#cont-listaCaratulas').html(data);
            }
        });
        
    }
   
    this.publico.getProvincias = function(obj){
        simpleAjax.send({
            gifProcess: true,
            root: _private.config.modulo + 'getProvincias',
            data: '&_idDepartamento='+obj.idDepartamento,
            fnCallback: function(data){
                simpleScript.listBox({
                    data: data,
                    optionSelec: true,
                    content: obj.content,
                    attr:{
                        id: obj.idElement,
                        name: obj.nameElement
                    },
                    dataView:{
                        etiqueta: 'provincia',
                        value: 'id_provincia'
                    },
                    fnCallback: function(){
                        simpleScript.setEvent.change({
                            element: '#'+obj.idElement,
                            event: function(){
                                registrarVendedor.getUbigeo({
                                    idProvincia: $('#'+obj.idElement).val(),
                                    content: obj.contentUbigeo,
                                    idElement: obj.idUbigeo,
                                    nameElement: obj.idUbigeo
                                });
                            }
                        });
                    }
                });
            }
        });
    };
    
    this.publico.getUbigeo = function(obj){
        simpleAjax.send({
            gifProcess: true,
            root: _private.config.modulo + 'getUbigeo',
            data: '&_idProvincia='+obj.idProvincia,
            fnCallback: function(data){
                simpleScript.listBox({
                    data: data,
                    optionSelec: true,
                    content: obj.content,
                    attr:{
                        id: obj.idElement,
                        name: obj.nameElement
                    },
                    dataView:{
                        etiqueta: 'distrito',
                        value: 'id_ubigeo'
                    }
                });
            }
        });
    };
    
    this.publico.getNuevoFichaTecnica = function(btn){
        simpleAjax.send({
            element: btn,
            dataType: 'html',
            root: _private.config.modulo + 'getNuevoFichaTecnica',
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.T102+'formFichaTecnica').modal('show');
            }
        });
    };
    this.publico.getEditarFichaTecnica = function(id){
        _private.idProducto = id;
        
        simpleAjax.send({
            gifProcess: true,
            dataType: 'html',
            root: _private.config.modulo + 'getEditarFichaTecnica',
            data: '&_idFichaTecnica='+_private.idProducto,
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.T102+'formFichaTecnica').modal('show');
            }
        });
    };
       
    
    
    return this.publico;
    
};
var fichaTecnica = new fichaTecnica_();