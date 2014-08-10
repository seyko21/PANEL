<?php
/*
 * Documento   : loginModel
 * Creado      : 30-ene-2014, 19:26:46
 * Autor       : RDCC
 * Descripcion :
 */
class configurarUsuariosModel extends Model{
    private $_flag;
    private $_key;
    private $_empleado;
    private $_clave;
    private $_mail;
    private $_activo;
    private $_roles;
    private $_usuario;
    
    /*para el grid*/
    private $_iDisplayStart;
    private $_iDisplayLength;
    private $_iSortingCols;
    private $_sSearch;
    private $_xsearch;
    
    public function __construct() {
        parent::__construct();
        $this->_set();
    }
    
    private function _set(){
        $this->_flag    = $this->post('_flag');
        $this->_key     = Aes::de($this->post('_key'));    /*se decifra*/
        $this->_empleado= Aes::de($this->post(T4.'txt_empleado'));    /*se decifra*/
        $this->_clave  = $this->post(T4.'txt_clave');
        $this->_activo  = $this->post(T4.'chk_activo');
        $this->_roles  = $this->post(T4.'chk_roles');
        $this->_mail  = $this->post(T4.'txt_email');
        $this->_xsearch  = $this->post(T4.'_term');
        $this->_usuario = Session::get('sys_idUsuario');
        
        $this->_iDisplayStart  =   $this->post('iDisplayStart'); 
        $this->_iDisplayLength =   $this->post('iDisplayLength'); 
        $this->_iSortingCols   =   $this->post('iSortingCols');
        $this->_sSearch        =   $this->post('sSearch');
    }
    
    public function getUsuarios(){
        $aColumns       =   array( 'usuario','nombrecompleto' ); //para la ordenacion y pintado en html
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
        
        $query = "call sp_usuariosConfigurarUsuariosGrid(:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ':iDisplayStart' => $this->_iDisplayStart,
            ':iDisplayLength' => $this->_iDisplayLength,
            ':sOrder' => $sOrder,
            ':sSearch' => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    public function getRoles(){
        $query = " SELECT id_rol,rol FROM men_rol WHERE activo = :activo ";
        $parms = array(
            ':activo' => '1',
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    public function getEmpleados(){
        $query = " SELECT persona,nombrecompleto,email FROM `mae_persona` WHERE nombrecompleto LIKE CONCAT('%',:nombre,'%') AND estado = :estado ";
        $parms = array(
            ':nombre'=> $this->_xsearch,
            ':estado' => '1',
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    public function mantenimientoUsuario(){
        $query = "call sp_usuariosConfigurarUsuariosMantenimiento(:flag,:key,:empleado,:usuario,:clave,:activo,:user);";
        $parms = array(
            ':flag' => $this->_flag,
            ':key' => $this->_key,
            ':empleado' => $this->_empleado,
            ':usuario' => $this->_mail,
            ':clave' => md5($this->_clave.APP_PASS_KEY),
            ':activo' => $this->_activo,
            ':user' => $this->_usuario
        );
        $data = $this->queryOne($query,$parms);
       
        if($data['lastIdUser'] != ''){
            foreach ($this->_roles as $rol) {
                $query = "call sp_usuariosConfigurarUsuariosMantenimiento(:flag,:key,:empleado,:usuario,:clave,:activo,:user);";
                $parms = array(
                    ':flag' => 2,
                    ':key' => $rol,
                    ':empleado' => $data['lastIdUser'],
                    ':usuario' => '',
                    ':clave' => '',
                    ':activo' => '',
                    ':user' => $this->_usuario
                );
                $this->execute($query,$parms);
            }
        }
        $res = array('result'=>$data['result'],'duplicado'=>$data['duplicado']);
        return $res;
    }
    
}
?>
