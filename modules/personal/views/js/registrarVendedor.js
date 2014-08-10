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
    
    return this.publico;
    
};
var registrarVendedor = new registrarVendedor_();