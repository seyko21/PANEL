<?php
/*
 * Documento   : loginModel
 * Creado      : 30-ene-2014, 19:26:46
 * Autor       : RDCC
 * Descripcion :
 */
class loginModel extends Model{
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getValidar(){
        $flag = $this->post('_flag');
        $user = Aes::de($this->post('_user'));
        $pass = Aes::de($this->post('_clave'));
        
        $query = "call sp_confUsuarioConsultas(:flag,:user,:pass);";
        $parms = array(
            ':flag' => $flag,
            ':user' => $user,
            ':pass' => md5($pass.APP_PASS_KEY)
        );
        $data = $this->queryOne($query,$parms);
        return $data;
        
    }
    
    public function getRoles() {
        $query = "call sp_confUsuarioConsultas(:flag,:criterio1,:criterio2);";
        $parms = array(
            ':flag' => 2,
            ':criterio1' => Session::get('sys_idUsuario'),
            ':criterio2' => ''
        );
        $data = $this->queryAll($query, $parms);
        return $data;
    }
    
    public function getMenu() {
        $query = "call sp_confUsuarioConsultas(:flag,:criterio1,:criterio2);";
        $parms = array(
            ':flag' => 3,
            ':criterio1' => Session::get('sys_defaultRol'),
            ':criterio2' => ''
        );
        $data = $this->queryAll($query, $parms);
        return $data;
    }
    
    public function getAccionesOpcion($opcion) {
        $query = "call sp_confUsuarioConsultas(:flag,:criterio1,:criterio2);";
        $parms = array(
            ':flag' => 4,
            ':criterio1' => $opcion,
            ':criterio2' => ''
        );
        $data = $this->queryAll($query, $parms);
        return $data;
    }
    
    public function postLastLogin() {
        $query = "UPDATE mae_usuario SET ultimo_acceso = :fecha where id_usuario = :usuario;";
        $parms = array(
            ':fecha'=> date('Y-m-d H:m:s'),
            ':usuario' => Session::get('sys_idUsuario')
        );
        $data = $this->queryAll($query, $parms);
        return $data;
    }
    
}
?>
