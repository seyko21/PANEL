var configurarUsuarios_ = function(){
    
    var _private = {};
    
    _private.tab = 0;
    
    _private.config = {
        modulo: 'usuarios/configurarUsuarios/'
    };
    
    this.public = {};
    
    this.public.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.T4,
            label: $(element).attr('title'),
            fnCallback: function(){
                configurarUsuarios.getCont();
            }
        });
    };
    
    this.public.getCont = function(){
        simpleAjax.send({
            dataType: 'html',
            root: _private.config.modulo,
            fnCallback: function(data){
                $('#'+diccionario.tabs.T4+'_CONTAINER').html(data);
                configurarUsuarios.getGridUsuarios();
            }
        });
    };
    
    this.public.getGridUsuarios = function (){
        var oTable = $('#'+diccionario.tabs.T4+'gridUsuariosx').dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "Usuario",sWidth: "20%"},
                {sTitle: "Nombres", sWidth: "25%"},
                {sTitle: "Roles", sWidth: "20%", bSortable: false},
                {sTitle: "Estado", sWidth: "8%",  sClass: "center", bSortable: false},
                {sTitle: "Ultimo acceso", sWidth: "8%", sClass: "center", bSortable: false},
                {sTitle: "Acciones", sWidth: "15%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[1, 'asc']],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+'getUsuarios',
            fnDrawCallback: function() {
                $('#'+diccionario.tabs.T4+'gridUsuariosx_filter').find('input').attr('placeholder','Buscar por nombres');
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: '#widget_'+diccionario.tabs.T4+'usuarios',
                    typeElement: 'button'
                });
            }
        });
        setup_widgets_desktop();
    };
    
    this.public.getNuevoUsuario = function(btn){
        simpleAjax.send({
            element: btn,
            dataType: 'html',
            root: _private.config.modulo + 'getNuevoUsuario',
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.T4+'formUsuario').modal('show');
            }
        });
    };
    
//    this.public.getEmpleados = function(){
//        return _private.config.modulo + 'getEmpleados/';
//    };
    
    this.public.getFormEmpleado = function(btn,tab){
        _private.tab = tab;
        simpleAjax.send({
            element: btn,
            dataType: 'html',
            root: _private.config.modulo + 'getFormEmpleado',
            fnCallback: function(data){
                $('#cont-modal').append(data);  /*los formularios con append*/
                $('#'+diccionario.tabs.T4+'formBuscarEmpleado').modal('show');
            }
        });
    };
    
    this.public.getEmpleados = function(){
        $('#'+diccionario.tabs.T4+'gridEmpleadosFound_filter').remove();
        $('#'+diccionario.tabs.T4+'gridEmpleadosFound').dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sServerMethod: "POST",
            bPaginate: false,
            aoColumns: [
                {sTitle: "Nro.", sClass: "center",sWidth: "2%"},
                {sTitle: "Nombres y Apellidos", sWidth: "88%"}
            ],
            aaSorting: [[1, 'asc']],
//            sScrollY: "250px",
            sAjaxSource: _private.config.modulo+'getEmpleados',
            fnServerParams: function(aoData) {
                aoData.push({"name": diccionario.tabs.T4+"_term", "value": $('#'+diccionario.tabs.T4+'txt_search').val()});
                aoData.push({"name": "_tab", "value": _private.tab});
            },
            fnDrawCallback: function() {
                $('#'+diccionario.tabs.T4+'gridEmpleadosFound_filter').remove();
                $('#'+diccionario.tabs.T4+'gridEmpleadosFound_wrapper').find('.dt-bottom-row').remove();
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: '#'+diccionario.tabs.T4+'gridEmpleadosFound',
                    typeElement: 'a'
                });
            }
        });
        $('#'+diccionario.tabs.T4+'gridEmpleadosFound_filter').remove();
    };
    
    this.public.postNuevoUsuario = function(){
        simpleAjax.send({
            flag: 1,
            element: '#'+diccionario.tabs.T4+'btnGrabaAccion',
            root: _private.config.modulo + 'postNuevoUsuario',
            form: '#'+diccionario.tabs.T4+'formUsuario',
            clear: true,
            fnCallback: function(data) {
                if(!isNaN(data.result) && parseInt(data.result) === 1){
                    simpleScript.notify.ok({
                        content: mensajes.MSG_3,
                        callback: function(){
                            configurarMenu.getGridUsuarios();
                        }
                    });
                }else if(!isNaN(data.result) && parseInt(data.result) === 2){
                    simpleScript.notify.error({
                        content: 'Empleado ya tiene usuario.'
                    });
                }
            }
        });
    };
    
    return this.public;
    
};
 var configurarUsuarios = new configurarUsuarios_();