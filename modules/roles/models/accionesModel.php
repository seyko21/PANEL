<?php
/*
 * Documento   : accionesModel
 * Creado      : 05-jul-2014
 * Autor       : ..... .....
 * Descripcion : 
 */
class accionesModel extends Model{
    private $_flag;
    private $_key;
    private $_accion;
    private $_alias;
    private $_activo;
    private $_icono;
    private $_theme;
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
        $this->_flag    = $this->post('_flag');
        $this->_key     = Aes::de($this->post('_key'));    /*se decifra*/
        $this->_accion  = $this->post('CRDACtxt_accion');
        $this->_alias   = $this->post('CRDACtxt_alias');
        $this->_icono   = $this->post('CRDACtxt_icono');
        $this->_theme   = $this->post('CRDACtxt_theme');
        $this->_activo  = $this->post('CRDACchk_activo');
        $this->_usuario = Session::get('sys_usuario');
        
        $this->_iDisplayStart  =   $this->post('iDisplayStart'); 
        $this->_iDisplayLength =   $this->post('iDisplayLength'); 
        $this->_iSortingCols   =   $this->post('iSortingCols');
        $this->_sSearch        =   $this->post('sSearch');
    }
    
    public function getAcciones(){
        $aColumns       =   array( 'id_acciones','accion','','alias' ); //para la ordenacion y pintado en html
        /*
	 * Ordenando, se verifica por que columna se ordenara
	 */
        $sOrder = "";
        for ( $i=0 ; $i<intval( $this->_iSortingCols ) ; $i++ ){
                if ( $this->post( 'bSortable_'.intval($this->post('iSortCol_'.$i)) ) == "true" ){
                        $sOrder .= " ".$aColumns[ intval( $this->post('iSortCol_'.$i) ) ]." ".
                                ($this->post('sSortDir_'.$i)==='asc' ? 'asc' : 'desc') ." ";
                }
        }
        
        $query = "call sp_rolesAccionesGrid(:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ':iDisplayStart' => $this->_iDisplayStart,
            ':iDisplayLength' => $this->_iDisplayLength,
            ':sOrder' => $sOrder,
            ':sSearch' => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    public function getAccion($id){
        $query = "call sp_rolesAccionesConsultas(:flag,:criterio);";
        $parms = array(
            ':flag' => 1,
            ':criterio' => Aes::de($id)
        );
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
    public function mantenimientoAccion(){
        $query = "call sp_rolesAccionesMantenimiento(:flag,:key,:accion,:alias,:activo,:icono,:theme,:usuario);";
        $parms = array(
            ':flag' => $this->_flag,
            ':key' => $this->_key,
            ':accion' => $this->_accion,
            ':alias' => $this->_alias,
            ':activo' => ($this->_activo == '1')?1:0,
            ':icono' => $this->_icono,
            ':theme' => $this->_theme,
            ':usuario' => $this->_usuario
        );
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
}
?>
