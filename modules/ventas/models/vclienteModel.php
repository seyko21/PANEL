<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 17-11-2014 17:11:18 
* Descripcion : vclienteModel.php
* ---------------------------------------
*/ 

class vclienteModel extends Model{

    private $_flag;
    private $_idVcliente;
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
    private $_tipoPersona;
    private $_ubigeo;
    private $_chkdel;
    private $_usuario;
    
    /*para el grid*/
    public  $_iDisplayStart;
    private $_iDisplayLength;
    private $_iSortingCols;
    private $_sSearch;
    
    public function __construct() {
        parent::__construct();
        $this->_set();
    }
    
    private function _set(){
        $this->_flag        = Formulario::getParam("_flag");
        $this->_idVcliente   = Aes::de(Formulario::getParam("_idVcliente"));    /*se decifra*/
        $this->_usuario     = Session::get("sys_idUsuario");
              
        $this->_idDepartamento = Formulario::getParam('_idDepartamento');
        $this->_idProvincia = Formulario::getParam('_idProvincia');
        $this->_apellidoPaterno = Formulario::getParam(VRECL.'txt_apellidopaterno');
        $this->_apellidoMaterno = Formulario::getParam(VRECL.'txt_apellidomaterno');
        $this->_nombres = Formulario::getParam(VRECL.'txt_nombres');
        $this->_sexo = Formulario::getParam(VRECL.'rd_sexo');
        $this->_direccion = Formulario::getParam(VRECL.'txt_direccion');
        $this->_email = Formulario::getParam(VRECL.'txt_email');
        $this->_telefono = Formulario::getParam(VRECL.'txt_telefonos');
        $this->_numeroDoc = Formulario::getParam(VRECL.'txt_nrodocumento');
        $this->_ubigeo = Formulario::getParam(VRECL.'lst_ubigeo');
        $this->_tipoPersona = Formulario::getParam(VRECL.'lst_tipoPersona');
        
        $this->_usuario = Session::get('sys_idUsuario');
        $this->_chkdel  = Formulario::getParam(VRECL.'chk_delete');
        
        $this->_iDisplayStart  = Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength = Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   = Formulario::getParam("iSortingCols");
        $this->_sSearch        = Formulario::getParam("sSearch");
    }
    
    /*data para el grid: Vcliente*/
    public function getVcliente(){
        $aColumns = array( 'chk','nombrecompleto','tipo_persona','telefono','numerodocumento','7' ); //para la ordenacion y pintado en html

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
        $query = "call sp_ventaClienteGrid(:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ':iDisplayStart' => $this->_iDisplayStart,
            ':iDisplayLength' => $this->_iDisplayLength,
            ':sOrder' => $sOrder,
            ':sSearch' => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);
      
        return $data;
    }
    
    /*grabar nuevo registro: Vcliente*/
    public function newVcliente(){
       $query = "call sp_ventaClienteMantenimiento(
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
                    :tipoPersona,
                    :usuario
                );";
        $parms = array(
            ':flag' => 1,
            ':idPersona' => $this->_idVcliente,
            ':apellidoPaterno' => $this->_apellidoPaterno,
            ':apellidoMaterno' => $this->_apellidoMaterno,
            ':nombres' => $this->_nombres,
            ':sexo' => $this->_sexo,
            ':direccion' => $this->_direccion,
            ':email' => $this->_email,
            ':telefono' => $this->_telefono,
            ':numeroDoc' => $this->_numeroDoc,
            ':ubigeo' => $this->_ubigeo,
            ':tipoPersona' => $this->_tipoPersona,
            ':usuario' => $this->_usuario
        );
        $data = $this->queryOne($query,$parms);     
        return $data;
    }
    
    /*seleccionar registro a editar: Vcliente*/
    public function findVcliente(){
      $query = "SELECT 
                        p.nombres,
                        p.apellidopaterno as apellidoPaterno,
                        p.apellidomaterno as apellidoMaterno,
                        p.numerodocumento,
                        p.nombrecompleto,
                        p.id_ubigeo,
                        p.direccion,
                        p.email,
                        p.sexo,
                        p.telefono,
                        p.tipo_persona,
                        p.tipodocumento                    
                    FROM mae_persona p                    
                    WHERE p.id_persona = :idPersona;";

            $parms = array(
                ':idPersona'=>$this->_idVcliente
            );

            $data = $this->queryOne($query,$parms);
            return $data;
    }
    
    /*editar registro: Vcliente*/
    public function editVcliente(){
        $query = "call sp_ventaClienteMantenimiento(
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
                    :tipoPersona,
                    :usuario
                );";
        $parms = array(
            ':flag' => 2,
            ':idPersona' => $this->_idVcliente,
            ':apellidoPaterno' => $this->_apellidoPaterno,
            ':apellidoMaterno' => $this->_apellidoMaterno,
            ':nombres' => $this->_nombres,
            ':sexo' => $this->_sexo,
            ':direccion' => $this->_direccion,
            ':email' => $this->_email,
            ':telefono' => $this->_telefono,
            ':numeroDoc' => $this->_numeroDoc,
            ':ubigeo' => $this->_ubigeo,
            ':tipoPersona' => $this->_tipoPersona,
            ':usuario' => $this->_usuario
        );
        $data = $this->queryOne($query,$parms);     
        return $data;
    }
    
    /*eliminar varios registros: Vcliente*/
    public function deleteVclienteAll(){
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
    public function deleteVcliente(){
      
        $query = "UPDATE `mae_persona` SET
                    `estado` = '0'
                WHERE `id_persona` = :idPersona;";
        $parms = array(
            ':idPersona' => $this->_idVcliente
        );
        $this->execute($query,$parms);
        
        $data = array('result'=>1);
        return $data;
    }    
    
    public function postDesactivar(){
        $query = "UPDATE `mae_persona` SET
                    `estado` = 'I'
                WHERE `id_persona` = :idPersona;";
        $parms = array(
            ':idPersona' => $this->_idVcliente
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
            ':idPersona' => $this->_idVcliente
        );
        $this->execute($query,$parms);
        $data = array('result'=>1);
        return $data;
    }   
    
}

?>