<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 03-09-2014 02:09:26 
* Descripcion : socioModel.php
* ---------------------------------------
*/ 

class socioModel extends Model{

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
    private $_tiposocio;
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
        $this->_apellidoPaterno = Formulario::getParam(TAB_SOCIO.'txt_apellidopaterno');
        $this->_apellidoMaterno = Formulario::getParam(TAB_SOCIO.'txt_apellidomaterno');
        $this->_nombres = Formulario::getParam(TAB_SOCIO.'txt_nombres');
        $this->_sexo = Formulario::getParam(TAB_SOCIO.'rd_sexo');
        $this->_direccion = Formulario::getParam(TAB_SOCIO.'txt_direccion');
        $this->_email = Formulario::getParam(TAB_SOCIO.'txt_email');
        $this->_telefono = Formulario::getParam(TAB_SOCIO.'txt_telefonos');
        $this->_numeroDoc = Formulario::getParam(TAB_SOCIO.'txt_nrodocumento');
        $this->_dni = Formulario::getParam(TAB_SOCIO.'txt_dni');
        $this->_ubigeo = Formulario::getParam(TAB_SOCIO.'lst_ubigeo');
        $this->_tiposocio = Formulario::getParam(TAB_SOCIO.'lst_tiposocio');
                
        $this->_usuario = Session::get('sys_idUsuario');
        $this->_chkdel  = Formulario::getParam(TAB_SOCIO.'chk_delete');
        
        $this->_iDisplayStart  =   Formulario::getParam('iDisplayStart'); 
        $this->_iDisplayLength =   Formulario::getParam('iDisplayLength'); 
        $this->_iSortingCols   =   Formulario::getParam('iSortingCols');
        $this->_sSearch        =   Formulario::getParam('sSearch');
    }
    
    public function getGridSocio() {
        $aColumns = array( 'chk','numerodocumento','nombrecompleto','email','telefono','monto_invertido','monto_asignado','monto_saldo' ); //para la ordenacion y pintado en html

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
        $query = "call sp_perSocioGrid(:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ':iDisplayStart' => $this->_iDisplayStart,
            ':iDisplayLength' => $this->_iDisplayLength,
            ':sOrder' => $sOrder,
            ':sSearch' => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);
      
        return $data;
    }    
    
    public function mantenimientoSocio(){
         $query = "call sp_perSocioMantenimiento(
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
                    :tiposocio,
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
            ':tiposocio' => $this->_tiposocio,
            ':usuario' => $this->_usuario
        );
        $data = $this->queryOne($query,$parms);     
        return $data;
    }
    
    /*seleccionar registro a editar: Socio*/
    public function findSocio(){
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
                        telefono,
                        tipo_socio
                    FROM mae_persona WHERE id_persona = :idPersona;";

            $parms = array(
                ':idPersona'=>$this->_idPersona
            );

            $data = $this->queryOne($query,$parms);
            return $data;
    }
    
      
    /*eliminar varios registros: Socio*/
   public function deleteSocioAll(){        
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
   
    public function postDesactivarSocio(){
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
    
    public function postActivarSocio(){
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