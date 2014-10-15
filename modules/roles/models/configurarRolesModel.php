<?php
/*
 * Documento   : configurarRolesModel
 * Creado      : 30-ene-2014, 19:26:46
 * Autor       : ...
 * Descripcion :
 */
class configurarRolesModel extends Model{
    
    private $_flag;
    private $_key;
    private $_rol;
    private $_opcion;
    private $_activo;
    private $_accion;
    private $_accionName;
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
        $this->_flag = $this->post('_flag');
        $this->_key  = Aes::de($this->post('_key'));    /*se decifra*/
        $this->_rol  = $this->post('CRDCRtxt_rol');
        $this->_opcion  = Aes::de($this->post('_opcion'));    /*se decifra*/
        $this->_accion  = Aes::de($this->post('_accion'));    /*se decifra*/
        $this->_activo  = $this->post('CRDCRchk_activo');
        $this->_accionName  = $this->post('_accionName');
        $this->_usuario = Session::get('sys_usuario');
        
        $this->_iDisplayStart  =   $this->post('iDisplayStart'); 
        $this->_iDisplayLength =   $this->post('iDisplayLength'); 
        $this->_iSortingCols   =   $this->post('iSortingCols');
        $this->_sSearch        =   $this->post('sSearch'); 
    }


    public function getRoles(){
        $aColumns       =   array( 'id_rol','rol' ); //para la ordenacion y pintado en html
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
        
        $query = "call sp_rolesConfigurarRolesGrid(:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ':iDisplayStart' => $this->_iDisplayStart,
            ':iDisplayLength' => $this->_iDisplayLength,
            ':sOrder' => $sOrder,
            ':sSearch' => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    public function getRol($idRol){
        $query = "call sp_rolesConfigurarRolesConsultas(:flag,:criterio);";
        $parms = array(
            ':flag' => 1,
            ':criterio' => Aes::de($idRol)
        );
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
    public function mantenimientoRol(){
        $query = "call sp_rolesConfigurarRolesMantenimiento(:flag,:key,:rol,:activo,:usuario);";
        $parms = array(
            ':flag' => $this->_flag,
            ':key' => $this->_key,
            ':rol' => $this->_rol,
            ':activo' => $this->_activo,
            ':usuario' => $this->_usuario
        );
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
    public function mantenimientoRolOpcion(){
        $query = "call sp_rolesConfigurarRolesMantenimiento(:flag,:opcion,:rol,:activo,:usuario);";
        $parms = array(
            ':flag' => $this->_flag,
            ':opcion' => $this->_opcion,
            ':rol' => $this->_key,
            ':activo' => '',
            ':usuario' => $this->_usuario
        );
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
    public function mantenimientoRolOpcionAccion(){
        $query = "call sp_rolesConfigurarRolesMantenimiento(:flag,:accion,:rolOpcion,:activo,:usuario);";
        $parms = array(
            ':flag' => $this->_flag,
            ':accion' => $this->_accion,
            ':rolOpcion' => $this->_opcion,
            ':activo' => '',
            ':usuario' => $this->_usuario
        );
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
    public function consultasRol($flag,$criterio=''){
        $query = "call sp_rolesConfigurarRolesConsultas(:flag,:criterio);";
        $parms = array(
            ':flag' => $flag,
            ':criterio' => Aes::de($criterio)
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    public function consultarMenuOpciones($flag,$idRol,$idMenuPrincipal){
        $criterio = Aes::de($idRol).'-'.Aes::de($idMenuPrincipal);
        
        $query = "call sp_rolesConfigurarRolesConsultas(:flag,:criterio);";
        $parms = array(
            ':flag' => $flag,
            ':criterio' => $criterio
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    public function postDuplicarRol(){
        $query = "call sp_rolesConfigurarRolesMantenimiento(:flag,:key,:rolOpcion,:activo,:usuario);";
        $parms = array(
            ':flag' => 8,
            ':key' => $this->_key,
            ':rolOpcion' => '',
            ':activo' => '',
            ':usuario' => $this->_usuario
        );
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
}
?>
