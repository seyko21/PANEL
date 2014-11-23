<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 21-08-2014 07:08:16 
* Descripcion : clienteModel.php
* ---------------------------------------
*/ 

class clienteModel extends Model{

    private $_flag;
    private $_idPersona;
    private $_ancestro;
    private $_idDepartamento;
    private $_idProvincia;
    private $_apellidoPaterno;
    private $_apellidoMaterno;
    private $_nombres;
    private $_sexo;
    private $_direccion;
    private $_tipoDoc;
    private $_email;
    private $_telefono;
    private $_numeroDoc;
    private $_ubigeo;
    private $_chkdel;
    private $_chkdelrp;
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
        $this->_ancestro     = Aes::de($this->post('_ancestro'));    /*se decifra*/
        $this->_idDepartamento = $this->post('_idDepartamento');
        $this->_idProvincia = $this->post('_idProvincia');
        $this->_apellidoPaterno = $this->post(REGCL.'txt_apellidopaterno');
        $this->_apellidoMaterno = $this->post(REGCL.'txt_apellidomaterno');
        $this->_nombres = $this->post(REGCL.'txt_nombres');
        $this->_sexo = $this->post(REGCL.'rd_sexo');
        $this->_direccion = $this->post(REGCL.'txt_direccion');
        $this->_email = $this->post(REGCL.'txt_email');
        $this->_telefono = $this->post(REGCL.'txt_telefonos');
        $this->_numeroDoc = $this->post(REGCL.'txt_nrodocumento');
        $this->_ubigeo = $this->post(REGCL.'lst_ubigeo');
        $this->_tipoDoc = $this->post(REGCL.'lst_tipodoc');
        $this->_chkdel  = $this->post(REGCL.'chk_delete');
        $this->_chkdelrp  = $this->post(REGCL.'chk_deleterp');
        $this->_usuario = Session::get("sys_idUsuario");
        
        $this->_xsearch  = $this->post(REGCL.'_term');
        
        $this->_iDisplayStart  =   Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength =   Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   =   Formulario::getParam("iSortingCols");
        $this->_sSearch        =   Formulario::getParam("sSearch");
    }
    
    public function getClientes() {
        $aColumns       =   array( 'chk','numerodocumento','nombrecompleto' ); //para la ordenacion y pintado en html
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
        
        $query = "call sp_ordseClienteGrid(:acceso,:usuario,:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ':acceso' => Session::get('sys_all'),
            ':usuario' => $this->_usuario,
            ':iDisplayStart' => $this->_iDisplayStart,
            ':iDisplayLength' => $this->_iDisplayLength,
            ':sOrder' => $sOrder,
            ':sSearch' => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    public function getRepresentantes() {
        $aColumns       =   array( 'chk','numerodocumento','nombrecompleto' ); //para la ordenacion y pintado en html
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
        
        $query = "call sp_perClienteRepresentanteGrid(:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ':iDisplayStart' => $this->_iDisplayStart,
            ':iDisplayLength' => $this->_iDisplayLength,
            ':sOrder' => $sOrder,
            ':sSearch' => $this->_idPersona,
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    public function getDepartamentos(){
        $query = "SELECT id_departamento,departamento FROM `ub_departamento` ORDER BY departamento ";
        
        $parms = array();
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    /*grabar nuevo registro: Cliente*/
    public function newCliente(){
        $query = "call sp_ordseClienteMantenimiento(
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
                    :ubigeo,
                    :tipoDoc,
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
            ':ubigeo' => $this->_ubigeo,
            ':tipoDoc' => $this->_tipoDoc,
            ':usuario' => $this->_usuario
        );
        $data = $this->queryOne($query,$parms);  
        return $data;
    }
    
    public function newRepresentante(){
        $query = "call sp_perClienteRepresentanteMantenimiento(
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
                    :ubigeo,
                    :tipoDoc,
                    :usuario,
                    :ancestro
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
            ':ubigeo' => $this->_ubigeo,
            ':tipoDoc' => $this->_tipoDoc,
            ':usuario' => $this->_usuario,
            ':ancestro' => $this->_ancestro
        );
        $data = $this->queryOne($query,$parms);  
        return $data;
    }
    
    /*seleccionar registro a editar: Cliente*/
    public function findCliente(){
        $query = "SELECT 
                    nombrecompleto,
                    nombres,
                    apellidopaterno,
                    apellidomaterno,
                    numerodocumento,
                    id_ubigeo,
                    direccion,
                    tipodocumento,
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
    
    /*eliminar varios registros: Cliente*/
    public function deleteClienteAll(){
        foreach ($this->_chkdel as $value) {
            $query = "call sp_perAnularCliente(:flag, :idPersona);";
            $parms = array(
                'flag'=>1,
                ':idPersona' => Aes::de($value)
            );
            $this->execute($query,$parms);
        }
        $data = array('result'=>1);
        return $data;
    }
    
    public function postDesactivarCliente(){
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
    
    public function postActivarCliente(){
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
    
    public function deleteClienteAllRp(){
        foreach ($this->_chkdelrp as $value) {
            $query = "call sp_perAnularCliente(:flag, :idPersona);";
            $parms = array(
                'flag'=>2,
                ':idPersona' => Aes::de($value)
            );
            $this->execute($query,$parms);
        }
        $data = array('result'=>1);
        return $data;
    }
    public function getPersonas(){
        
        $query = 'call sp_perBuscarPersona(:flag, :nombre, :estado);';
        
        $parms = array(
            ':flag' => 3,
            ':nombre'=> $this->_xsearch,
            ':estado' => 'A',
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
     public function actualizarRepresentante(){
        $query = "UPDATE `mae_persona` SET
                    `id_personapadre` = :ancestro
                WHERE `id_persona` = :idPersona;";
        $parms = array(
            ':ancestro' => $this->_ancestro,
            ':idPersona' => $this->_idPersona
        );
        $this->execute($query,$parms);
        
        $data = array('result'=>1);
        return $data;
    }
    
}

?>