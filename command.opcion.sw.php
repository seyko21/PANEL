<?php
$ruta = 'modules/';
$directorio = opendir($ruta);

if(isset($_POST['lst_modulo']) && isset($_POST['txt_opcion'])){
    $modulo = $_POST['lst_modulo'];
    $opcion = trim($_POST['txt_opcion']);
    if($modulo != '' && $modulo != '.' && $modulo != '..'){
        if(strlen($opcion) > 0){
            // verificar que opcion no existe
            if(!file_exists($ruta.$modulo.'/controllers/'.$opcion.'Controller.php')){
                //crear controlador
$contenido='<?php
/*
* --------------------------------------
* fecha: '.date('d-m-Y H:m:s').' 
* Descripcion : '.$opcion.'Controller.php
* --------------------------------------
*/    

class '.$opcion.'Controller extends Controller{

    public function __construct() {
        $this->loadModel(\''.$opcion.'\');
    }
    
    public function index(){ 
        Obj::run()->View->render(\'index\');
    }
    
}

?>';
                $fp=fopen($ruta.$modulo.'/controllers/'.$opcion.'Controller.php',"x");
                fwrite($fp,$contenido);
                fclose($fp) ;
                
                //creando el modelo
$contenido = '<?php
/*
* --------------------------------------
* fecha: '.date('d-m-Y H:m:s').' 
* Descripcion : '.$opcion.'Model.php
* --------------------------------------
*/ 

class '.$opcion.'Model extends Model{

    private $_flag;
    private $_key;
    private $_usuario;
    
    /*para el grid*/
    private $_iDisplayStart;
    private $_iDisplayLength;
    private $_iSortingCols;
    private $_sSearch;
    
    public function __construct() {
        parent::__construct();
        $this->_set();
    }
    
    private function _set(){
        $this->_flag    = $this->post(\'_flag\');
        $this->_key     = Aes::de($this->post(\'_key\'));    /*se decifra*/
        $this->_usuario = Session::get(\'sys_idUsuario\');
        
        $this->_iDisplayStart  =   $this->post(\'iDisplayStart\'); 
        $this->_iDisplayLength =   $this->post(\'iDisplayLength\'); 
        $this->_iSortingCols   =   $this->post(\'iSortingCols\');
        $this->_sSearch        =   $this->post(\'sSearch\');
    }
    
}

?>';
                $fp=fopen($ruta.$modulo.'/models/'.$opcion.'Model.php',"x");
                fwrite($fp,$contenido);
                fclose($fp) ;
                
                
                //creando el js
$contenido = 'var '.$opcion.'_ = function(){
    
    var _private = {};
    
    _private.id = 0;
    
    _private.config = {
        modulo: \''.$modulo.'/'.$opcion.'/\'
    };

    this.publico = {};
    
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.[NUMERO_DE_LABEL],
            label: $(element).attr(\'title\'),
            fnCallback: function(){
                '.$opcion.'.getCont();
            }
        });
    };
    
    this.publico.getCont = function(){
        simpleAjax.send({
            dataType: \'html\',
            root: _private.config.modulo,
            fnCallback: function(data){
                $(\'#\'+diccionario.tabs.[NUMERO_DE_LABEL]+\'_CONTAINER\').html(data);
                '.$opcion.'.[METODO_PARA_GRID]();
            }
        });
    };
    
this.publico.[METODO_PARA_GRID] = function (){
        $(\'#\'+diccionario.tabs.[NUMERO_DE_LABEL]+\'grid[NOMBRE_TABLA]\').dataTable({
            bProcessing: true,
            bServerSide: true,
            bDestroy: true,
            sPaginationType: "bootstrap_full", //two_button
            sServerMethod: "POST",
            bPaginate: true,
            iDisplayLength: 10,            
            aoColumns: [
                {sTitle: "<input type=\'checkbox\' id=\'"+diccionario.tabs.[NUMERO_DE_LABEL]+"chk_all\' onclick=\'simpleScript.checkAll(this,\"#"+diccionario.tabs.[NUMERO_DE_LABEL]+"grid[NOMBRE_TABLA]\");\'>", sWidth: "1%", sClass: "center", bSortable: false},
                {sTitle: "Campo 1", sWidth: "55%"},
                {sTitle: "Campo 2", sWidth: "8%",  sClass: "center", bSortable: false},
                {sTitle: "Estado", sWidth: "8%",  sClass: "center", bSortable: false},
                {sTitle: "Acciones", sWidth: "15%", sClass: "center", bSortable: false}
            ],
            aaSorting: [[1, \'asc\']],
            sScrollY: "300px",
            sAjaxSource: _private.config.modulo+\'[ACCION_DE_CONTROLLER_PHP]\',
            fnDrawCallback: function() {
                $(\'#\'+diccionario.tabs.[NUMERO_DE_LABEL]+\'grid[NOMBRE_TABLA]_filter\').find(\'input\').attr(\'placeholder\',\'Buscar\');
                /*para hacer evento invisible*/
                simpleScript.removeAttr.click({
                    container: \'#widget_\'+diccionario.tabs.[NUMERO_DE_LABEL], //widget del datagrid
                    typeElement: \'button, #\'+diccionario.tabs.[NUMERO_DE_LABEL]+\'chk_all\'
                });
            }
        });
        setup_widgets_desktop();
    };
    
    return this.publico;
    
};
var '.$opcion.' = new '.$opcion.'_();';

                $fp=fopen($ruta.$modulo.'/views/js/'.$opcion.'.js',"x");
                fwrite($fp,$contenido);
                fclose($fp) ;
                
                
            }  else {
                echo 'Opcion => <b>'.$opcion.'</b> ya esta creada.';
            }
        }else{
            echo 'Ingrese opcion.';
        }
    }else{
        echo 'seleccione modulo.';
    }
}
?>
<form id="formModulo" name="formModulo" action="" method="post">
    <table width="200" border="1">
        <tr>
            <td>Modulo:</td>
            <td>
                <select name="lst_modulo" id="lst_modulo">
                    <option value="">Seleccionar</option>
                    <?php while ($archivo = readdir($directorio)): ?>
                        <option value="<?php echo $archivo; ?>"><?php echo $archivo; ?></option>
                    <?php endwhile; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Opcion:</td>
            <td>
            <input type="text" name="txt_opcion" id="txt_opcion" /></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td><button type="submit">Crear modulo</button></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
    </table>
</form>
