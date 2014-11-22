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
    private $_idUsuario;
    private $_pass;
    private $_clave = '1234567';
    private $_mail;
    private $_activo;
    private $_roles;
    private $_usuario;
    private $_rol;
     public  $_idPersona;
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
        $this->_empleado = Aes::de($this->post(T4.'txt_empleado'));    /*se decifra*/
        $this->_idUsuario = Aes::de($this->post('_idUsuario'));    /*se decifra*/
        $this->_activo  = $this->post(T4.'chk_activo');
        $this->_roles  = $this->post(T4.'chk_roles');
        $this->_mail  = $this->post(T4.'txt_email');
        $this->_xsearch  = $this->post(T4.'_term');
        $this->_usuario = Session::get('sys_idUsuario');
        $this->_rol   = $this->post('_rol');
        $this->_iDisplayStart  =   $this->post('iDisplayStart');
        $this->_iDisplayLength =   $this->post('iDisplayLength');
        $this->_iSortingCols   =   $this->post('iSortingCols');
        $this->_sSearch        =   $this->post('sSearch');
        
        $this->_pass     = Aes::de($this->post('_pass'));    /*se decifra*/
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
        
        if (Session::get('sys_defaultRol') == APP_COD_SADM){
            $query = " SELECT id_rol,rol FROM men_rol WHERE activo = :activo ";    
        }else{
            $query = " SELECT id_rol,rol FROM men_rol WHERE activo = :activo and not id_rol ='".APP_COD_SADM."' ";
        }
                
        $parms = array(
            ':activo' => '1',
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    public function getRolesUser(){
        
        if (Session::get('sys_defaultRol') == APP_COD_SADM){
            $query = " SELECT 
                r.id_rol,
                r.rol,
                (SELECT COUNT(*) FROM `men_usuariorol` xx WHERE xx.`id_rol`=r.id_rol AND xx.`id_usuario`=:idUsuario) AS chk
        FROM men_rol r WHERE activo = :activo  ";  
        }else{
            $query = " SELECT 
                r.id_rol,
                r.rol,
                (SELECT COUNT(*) FROM `men_usuariorol` xx WHERE xx.`id_rol`=r.id_rol AND xx.`id_usuario`=:idUsuario) AS chk
                FROM men_rol r WHERE activo = :activo and not id_rol ='".APP_COD_SADM."' ";
        }
               
        $parms = array(
            ':idUsuario'=> $this->_idUsuario,
            ':activo' => '1',
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    public function getEmpleados(){
        
        $query = 'call sp_perBuscarPersona(:flag, :nombre, :estado);';
        
        $parms = array(
            ':flag' => $this->_rol,
            ':nombre'=> $this->_xsearch,
            ':estado' => 'A',
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    public function getUsuario(){
        $query = "SELECT 
                u.`persona`,
                p.`nombrecompleto`,
                u.`usuario`,
                u.estado
        FROM `mae_usuario` u 
        INNER JOIN mae_persona p ON p.`persona`=u.`persona`
        WHERE u.`id_usuario` = :idUsuario";
        
        $parms = array(
            ':idUsuario'=> $this->_idUsuario
        );
        $data = $this->queryOne($query,$parms);
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
                    ':key' => AesCtr::de($rol),
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
    
    public function editarUsuario(){
//        $query = "UPDATE mae_usuario SET usuario = :usuario, estado=:activo WHERE id_usuario=:idUsuario";
//        $parms = array(
//            ':usuario' => $this->_mail,
//            ':activo' => $this->_activo,
//            ':idUsuario' => $this->_idUsuario
//        );
//        $this->execute($query,$parms);
        $query = "call sp_usuariosConfigurarUsuariosMantenimiento(:flag,:key,:empleado,:usuario,:clave,:activo,:user);";
        $parms = array(
            ':flag' => 3,
            ':key' => $this->_idUsuario,
            ':empleado' => '',
            ':usuario' => $this->_mail,
            ':clave' => '',
            ':activo' => $this->_activo,
            ':user' => $this->_usuario
        );
        $data = $this->queryOne($query,$parms);
        
        $res = array('result'=>$data['result'],'duplicado'=>$data['duplicado']);
        
        if($data['result'] != 3){
            /*se borra roles*/
            if (Session::get('sys_defaultRol') == APP_COD_SADM){
                $query = "DELETE FROM men_usuariorol WHERE id_usuario = :idUsuario";
            }else{
                 $query = "DELETE FROM men_usuariorol WHERE id_usuario = :idUsuario and not id_rol ='".APP_COD_SADM."'  ";
            }
           
            $parms = array(
                ':idUsuario' => $this->_idUsuario
            );
            $this->execute($query,$parms);

            /*se graba nuevos roles*/
            foreach ($this->_roles as $rol) {
                $query = "call sp_usuariosConfigurarUsuariosMantenimiento(:flag,:key,:empleado,:usuario,:clave,:activo,:user);";
                $parms = array(
                    ':flag' => 2,
                    ':key' => AesCtr::de($rol),
                    ':empleado' => $this->_idUsuario,
                    ':usuario' => '',
                    ':clave' => '',
                    ':activo' => '',
                    ':user' => $this->_usuario
                );
                $this->execute($query,$parms);
            }

            $res = array('result'=>1,'duplicado'=>0);
        }
        return $res;
    }
    
    public function deleteUsuario(){
        
        $query = "UPDATE mae_usuario SET estado=:estado WHERE id_usuario=:idUsuario";
        $parms = array(
            ':idUsuario' => $this->_key,
            ':estado' => '0'
        );
        $this->execute($query,$parms);
        
        $res = array('result'=>1);
        return $res;
    }
    
    public function postPass(){
        $query = "UPDATE `mae_usuario` SET
                    `clave` = :clave,
                    clave_comun = :comun
                WHERE `id_usuario` = :idUsuario;";
        $parms = array(
            ':idUsuario' => $this->_idUsuario,
            ':clave' => md5($this->_pass.APP_PASS_KEY),
            ':comun' => $this->_pass
        );
        $this->execute($query,$parms);
        $data = array('result'=>1);
        return $data;
    } 
    
   public function getParametros($alias){
        $query = "SELECT
            `valor`, `alias`
          FROM `pub_parametro`
          WHERE estado = :estado AND alias = :alias; ";
        
        $parms = array(
            ':estado'=>'A',
            ':alias'=>$alias
        );
        
        $data = $this->queryOne($query,$parms);
        return $data;
    }
}
?>
