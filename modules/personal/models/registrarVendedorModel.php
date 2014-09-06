<?php
/*
* --------------------------------------
* fecha: 10-08-2014 06:08:26 
* Descripcion : registrarVendedorModel.php
* --------------------------------------
*/ 

class registrarVendedorModel extends Model{

    private $_flag;
    private $_idPersona;
    private $_idDepartamento;
    private $_idProvincia;
    private $_apellidoPaterno;
    private $_apellidoMaterno;
    private $_idVendedor;
    private $_nombres;
    private $_sexo;
    private $_direccion;
    private $_email;
    private $_telefono;
    private $_numeroDoc;
    private $_dni;
    private $_pass;
    private $_ubigeo;
    private $_chkdel;
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
        $this->_idPersona     = Aes::de($this->post('_idPersona'));    /*se decifra*/
        $this->_idVendedor     = Aes::de($this->post('_idVendedor'));    /*se decifra*/
        $this->_pass     = Aes::de($this->post('_pass'));    /*se decifra*/
        
        $this->_idDepartamento = $this->post('_idDepartamento');
        $this->_idProvincia = $this->post('_idProvincia');
        $this->_apellidoPaterno = $this->post(T7.'txt_apellidopaterno');
        $this->_apellidoMaterno = $this->post(T7.'txt_apellidomaterno');
        $this->_nombres = $this->post(T7.'txt_nombres');
        $this->_sexo = $this->post(T7.'rd_sexo');
        $this->_direccion = $this->post(T7.'txt_direccion');
        $this->_email = $this->post(T7.'txt_email');
        $this->_telefono = $this->post(T7.'txt_telefonos');
        $this->_numeroDoc = $this->post(T7.'txt_nrodocumento');
        $this->_dni = $this->post(T7.'txt_dni');
        $this->_ubigeo = $this->post(T7.'lst_ubigeo');
        $this->_usuario = Session::get('sys_idUsuario');
        $this->_chkdel  = $this->post(T7.'chk_delete');
        
        $this->_iDisplayStart  =   $this->post('iDisplayStart'); 
        $this->_iDisplayLength =   $this->post('iDisplayLength'); 
        $this->_iSortingCols   =   $this->post('iSortingCols');
        $this->_sSearch        =   $this->post('sSearch');
    }
    
    public function getGridVendedor() {
        $aColumns       =   array( 'chk','numerodocumento','dni','numerodocumento','dni','nombrecompleto','email','telefono' ); //para la ordenacion y pintado en html
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
        
        $query = "call sp_perVendedorGrid(:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ':iDisplayStart' => $this->_iDisplayStart,
            ':iDisplayLength' => $this->_iDisplayLength,
            ':sOrder' => $sOrder,
            ':sSearch' => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    public function findVendedor(){
        $query = "SELECT 
                    nombres,
                    apellidopaterno,
                    apellidomaterno,
                    numerodocumento,
                    id_ubigeo,
                    direccion,
                    dni,
                    email,
                    sexo,
                    telefono 
                FROM mae_persona WHERE id_persona = :idPersona;";
        
        $parms = array(
            ':idPersona'=>$this->_idPersona
        );
        
        $data = $this->queryOne($query,$parms);
        return $data;
    }


    public function getDepartamentos(){
        $query = "SELECT id_departamento,departamento FROM `ub_departamento` ORDER BY departamento ";
        
        $parms = array();
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    public function getProvincias($dep=''){
        $query = "SELECT id_provincia,provincia FROM `ub_provincia` WHERE LEFT(id_provincia,2) = :idDepartamento ORDER BY provincia";
        
        $parms = array(
            ':idDepartamento'=>($dep == '')?$this->_idDepartamento:$dep
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }

    public function getUbigeo($pro=''){
        $query = "SELECT id_ubigeo,distrito FROM `ub_ubigeo` WHERE LEFT(id_ubigeo,4) = :idProvincia ORDER BY distrito;";
        
        $parms = array(
            ':idProvincia'=>($pro == '')?$this->_idProvincia:$pro
        );
                
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    public function mantenimientoVendedor(){
        $query = "call sp_perVendedorMantenimiento(
                    :flag,
                    :idPersona,
                    :apellidoPaterno,
                    :apellidoMaterno,
                    :nombres,
                    :sexo,
                    :direccion,
                    :email,
                    :telefono,
                    :numeroDoc,
                    :dni,
                    :ubigeo,
                    :usuario
                );";
        $parms = array(
            ':flag' => $this->_flag,
            ':idPersona' => $this->_idPersona,
            ':apellidoPaterno' => $this->_apellidoPaterno,
            ':apellidoMaterno' => $this->_apellidoMaterno,
            ':nombres' => $this->_nombres,
            ':sexo' => $this->_sexo,
            ':direccion' => $this->_direccion,
            ':email' => $this->_email,
            ':telefono' => $this->_telefono,
            ':numeroDoc' => $this->_numeroDoc,
            ':dni' => $this->_dni,
            ':ubigeo' => $this->_ubigeo,
            ':usuario' => $this->_usuario
        );
        $data = $this->queryOne($query,$parms);  
        return $data;
    }
    
    public function mantenimientoVendedorAll(){
        foreach ($this->_chkdel as $value) {
            $query = "UPDATE `mae_persona` SET
			`estado` = '0'
                    WHERE `id_persona` = :idPersona;";
            $parms = array(
                ':idPersona' => Aes::de($value)
            );
            $this->execute($query,$parms);
        }
        $data = array('result'=>1);
        return $data;
    }
    
    public function postDesactivarVendedor(){
        $query = "UPDATE `mae_persona` SET
                    `estado` = 'I'
                WHERE `id_persona` = :idPersona;";
        $parms = array(
            ':idPersona' => $this->_idPersona
        );
        $this->execute($query,$parms);
        $data = array('result'=>1);
        return $data;
    }
    
    public function postActivarVendedor(){
        $query = "UPDATE `mae_persona` SET
                    `estado` = 'A'
                WHERE `id_persona` = :idPersona;";
        $parms = array(
            ':idPersona' => $this->_idPersona
        );
        $this->execute($query,$parms);
        $data = array('result'=>1);
        return $data;
    }
    
    public function postPassVendedor(){
        $query = "UPDATE `mae_usuario` SET
                    `clave` = :clave
                WHERE `id_usuario` = :idPersona;";
        $parms = array(
            ':idPersona' => $this->_idPersona,
            ':clave' => md5($this->_pass.APP_PASS_KEY)
        );
        $this->execute($query,$parms);
        $data = array('result'=>1);
        return $data;
    }
    
}

?>