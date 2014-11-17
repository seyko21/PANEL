<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 16-09-2014 03:09:14 
* Descripcion : personaModel.php
* ---------------------------------------
*/ 

class personaModel extends Model{

    private $_flag;
    private $_idPersona;
    private $_idDepartamento;
    private $_idProvincia;
    private $_apellidoPaterno;
    private $_apellidoMaterno;
    private $_nombres;
    private $_sexo;
    private $_direccion;
    private $_email;
    private $_telefono;
    private $_numeroDoc;
    private $_dni;
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
        $this->_idDepartamento = Formulario::getParam('_idDepartamento');
        $this->_idProvincia = Formulario::getParam('_idProvincia');
        $this->_apellidoPaterno = Formulario::getParam(REPER.'txt_apellidopaterno');
        $this->_apellidoMaterno = Formulario::getParam(REPER.'txt_apellidomaterno');
        $this->_nombres = Formulario::getParam(REPER.'txt_nombres');
        $this->_sexo = Formulario::getParam(REPER.'rd_sexo');
        $this->_direccion = Formulario::getParam(REPER.'txt_direccion');
        $this->_email = Formulario::getParam(REPER.'txt_email');
        $this->_telefono = Formulario::getParam(REPER.'txt_telefonos');
        $this->_numeroDoc = Formulario::getParam(REPER.'txt_nrodocumento');
        $this->_dni = Formulario::getParam(REPER.'txt_dni');
        $this->_ubigeo = Formulario::getParam(REPER.'lst_ubigeo');
                
        $this->_usuario = Session::get('sys_idUsuario');
        $this->_chkdel  = Formulario::getParam(REPER.'chk_delete');
        
        $this->_iDisplayStart  =   Formulario::getParam('iDisplayStart'); 
        $this->_iDisplayLength =   Formulario::getParam('iDisplayLength'); 
        $this->_iSortingCols   =   Formulario::getParam('iSortingCols');
        $this->_sSearch        =   Formulario::getParam('sSearch');
    }
    
    public function getGridPersona() {
        $aColumns = array( 'chk','nombrecompleto','email','telefono','dni','u.distrito' ); //para la ordenacion y pintado en html

        /*
	 * Ordenando, se verifica por que columna se ordenara
	 */
        $sOrder = "";
        for ( $i=0 ; $i<intval( $this->_iSortingCols ) ; $i++ ){
                if ( Formulario::getParam( 'bSortable_'.intval(Formulario::getParam('iSortCol_'.$i)) ) == "true" ){
                        $sOrder .= " ".$aColumns[ intval( Formulario::getParam('iSortCol_'.$i) ) ]." ".
                                (Formulario::getParam('sSortDir_'.$i)==='asc' ? 'asc' : 'desc') .",";
                }
        }
        $sOrder = substr_replace( $sOrder, "", -1 );
        $query = "call sp_perPersonaGrid(:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ':iDisplayStart' => $this->_iDisplayStart,
            ':iDisplayLength' => $this->_iDisplayLength,
            ':sOrder' => $sOrder,
            ':sSearch' => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);
      
        return $data;
    }    
    
    public function mantenimientoPersona(){
         $query = "call sp_perPersonaMantenimiento(
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
    
    /*seleccionar registro a editar: Persona*/
    public function findPersona(){
        $query = "SELECT 
                        p.nombres,
                        p.apellidopaterno,
                        p.apellidomaterno,
                        p.numerodocumento,
                        p.nombrecompleto,
                        p.id_ubigeo,
                        p.direccion,
                        p.dni,
                        p.email,
                        p.sexo,
                        p.telefono,
                        (select u.foto
                            from mae_usuario u where 
                                u.persona=p.persona
                         ) as foto
                    FROM mae_persona p                    
                    WHERE p.id_persona = :idPersona;";

            $parms = array(
                ':idPersona'=>$this->_idPersona
            );

            $data = $this->queryOne($query,$parms);
            return $data;
    }
    
      
    /*eliminar varios registros*/
   public function deletePersonaAll(){        
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
   
    public function postDesactivar(){
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
    
    public function postActivar(){
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
    
}

?>