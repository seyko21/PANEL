<?php
/*
* --------------------------------------
* fecha: 10-08-2014 06:08:26 
* Descripcion : registrarVendedorModel.php
* --------------------------------------
*/ 

class registrarVendedorModel extends Model{

    private $_flag;
    public  $_idPersona;
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
        $this->_flag    = Formulario::getParam('_flag');
        $this->_idPersona     = Aes::de(Formulario::getParam('_idPersona'));    /*se decifra*/
        $this->_idVendedor     = Aes::de(Formulario::getParam('_idVendedor'));    /*se decifra*/
        $this->_pass     = Aes::de(Formulario::getParam('_pass'));    /*se decifra*/
        
        $this->_idDepartamento = Formulario::getParam('_idDepartamento');
        $this->_idProvincia = Formulario::getParam('_idProvincia');
        $this->_apellidoPaterno = Formulario::getParam(T7.'txt_apellidopaterno');
        $this->_apellidoMaterno = Formulario::getParam(T7.'txt_apellidomaterno');
        $this->_nombres = Formulario::getParam(T7.'txt_nombres');
        $this->_sexo = Formulario::getParam(T7.'rd_sexo');
        $this->_direccion = Formulario::getParam(T7.'txt_direccion');
        $this->_email = Formulario::getParam(T7.'txt_email');
        $this->_telefono = Formulario::getParam(T7.'txt_telefonos');
        $this->_numeroDoc = Formulario::getParam(T7.'txt_nrodocumento');
        $this->_dni = Formulario::getParam(T7.'txt_dni');
        $this->_ubigeo = Formulario::getParam(T7.'lst_ubigeo');
        $this->_usuario = Session::get('sys_idUsuario');
        $this->_chkdel  = Formulario::getParam(T7.'chk_delete');
        
        $this->_iDisplayStart  =   Formulario::getParam('iDisplayStart'); 
        $this->_iDisplayLength =   Formulario::getParam('iDisplayLength'); 
        $this->_iSortingCols   =   Formulario::getParam('iSortingCols');
        $this->_sSearch        =   Formulario::getParam('sSearch');
    }
    
    public function getGridVendedor() {
        $aColumns       =   array( 'chk','','numerodocumento','dni','numerodocumento','dni','nombrecompleto','email','telefono' ); //para la ordenacion y pintado en html
        /*
	 * Ordenando, se verifica por que columna se ordenara
	 */
        $sOrder = "";
        for ( $i=0 ; $i<intval( $this->_iSortingCols ) ; $i++ ){
                if ( Formulario::getParam( 'bSortable_'.intval(Formulario::getParam('iSortCol_'.$i)) ) == "true" ){
                        $sOrder .= " ".$aColumns[ intval( Formulario::getParam('iSortCol_'.$i) ) ]." ".
                                (Formulario::getParam('sSortDir_'.$i)==='asc' ? 'asc' : 'desc') ." ";
                }
        }
        
        $query = "call sp_perVendedorGrid(:rolV,:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ':rolV' => APP_COD_VEND,
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
                    `clave` = :clave,
                    clave_comun = :comun
                WHERE `id_usuario` = :idPersona;";
        $parms = array(
            ':idPersona' => $this->_idPersona,
            ':clave' => md5($this->_pass.APP_PASS_KEY),
            ':comun' => $this->_pass
        );
        $this->execute($query,$parms);
        $data = array('result'=>1);
        return $data;
    }
    
    public function adjuntarDocumento($doc){
        $query = "UPDATE `mae_persona` SET
                    `antecedentes` = :doc
                WHERE `id_persona` = :idPersona;";
        $parms = array(
            ':idPersona' => $this->_idPersona,
            ':doc' => $doc
        );
        $this->execute($query,$parms);
        $data = array('result'=>1);
        return $data;
    }
    
    public function getAdjuntado(){
        $query = "SELECT 
                    antecedentes
                FROM mae_persona WHERE id_persona = :idPersona;";
        
        $parms = array(
            ':idPersona'=>$this->_idPersona
        );
        
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
    public function deleteAdjuntar(){
        $query = "UPDATE  mae_persona SET
                    antecedentes = ''
                WHERE id_persona = :idPersona;";
        
        $parms = array(
            ':idPersona'=>$this->_idPersona
        );
        
        $this->execute($query,$parms);
        
        $data = array('result'=>1);
        return $data;
    }      
    
}

?>