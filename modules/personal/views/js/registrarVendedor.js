var registrarVendedor_ = function(){
    
    var _private = {};
    
    _private.id = 0;
    
    _private.config = {
        modulo: 'personal/registrarVendedor/'
    };

    this.publico = {};
    
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.T7,
            label: $(element).attr('title'),
            fnCallback: function(){
                registrarVendedor.getCont();
            }
        });
    };
    
    this.publico.getCont = function(){
        simpleAjax.send({
            dataType: 'html',
            root: _private.config.modulo,
            fnCallback: function(data){
                $('#'+diccionario.tabs.T7+'_CONTAINER').html(data);
                registrarVendedor.getGridVendedor();
            }
        });
    };
    
    this.publico.getGridVendedor = function (){
        $('#'+diccionario.tabs.T7+'getGridVendedor').dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "<input type='checkbox' id='"+diccionario.tabs.T7+"chk_all' onclick='simpleScript.checkAll(this,\"#"+diccionario.tabs.T7+"getGridVendedor\");'>", sWidth: "1%", sClass: "center", bSortable: false},
                {sTitle: "Campo 1", sWidth: "55%"},
                {sTitle: "Campo 2", sWidth: "8%",  sClass: "center", bSortable: false},
                {sTitle: "Estado", sWidth: "8%",  sClass: "center", bSortable: false},
                {sTitle: "Acciones", sWidth: "15%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[1, 'asc']],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+'getGridVendedor',
            fnDrawCallback: function() {
                $('#'+diccionario.tabs.T7+'getGridVendedor_filter').find('input').attr('placeholder','Buscar');
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: '#widget_'+diccionario.tabs.T7, //widget del datagrid
                    typeElement: 'button, #'+diccionario.tabs.T7+'chk_all'
                });
            }
        });
        setup_widgets_desktop();
    };
    
    this.publico.getNuevoVendedor = function(btn){
        simpleAjax.send({
            element: btn,
            dataType: 'html',
            root: _private.config.modulo + 'getNuevoVendedor',
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.T7+'formVendedor').modal('show');
            }
        });
    };
    
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
                        name: obj.nameElement,
                        onchange: obj.change
                    },
                    dataView:{
                        etiqueta: 'provincia',
                        value: 'id_provincia'
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

    this.publico.postNuevoVendedor = function(){
        simpleAjax.send({
            flag: 1,
            element: '#'+diccionario.tabs.T7+'btnGvend',
            root: _private.config.modulo + 'postNuevoVendedor',
            form: '#'+diccionario.tabs.T7+'formVendedor',
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            registrarVendedor.getGridVendedor();
                        }
                    });
                }else if(!isNaN(data.result) && parseInt(data.result) === 2){
                    simpleScript.notify.error({
                        content: mensajes.MSG_4
                    });
                }
            }
        });
    };
    
    return this.publico;
    
};
var registrarVendedor = new registrarVendedor_();