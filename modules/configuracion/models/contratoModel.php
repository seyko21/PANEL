<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 12-09-2014 17:09:12 
* Descripcion : contratoModel.php
* ---------------------------------------
*/ 

class contratoModel extends Model{

    private $_flag;
    private $_idContrato;    
    private $_usuario;
    private $_nombre;
    private $_cuerpo;
    
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
        $this->_idContrato   = Aes::de(Formulario::getParam("_idContrato"));    /*se decifra*/
        $this->_usuario     = Session::get("sys_idUsuario");
        $this->_nombre     = Formulario::getParam(CONTR.'txt_nombre');
        $this->_cuerpo     = Formulario::getParam('_cuerpo');
        
        $this->_iDisplayStart  = Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength = Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   = Formulario::getParam("iSortingCols");
        $this->_sSearch        = Formulario::getParam("sSearch");
    }
    
    /*data para el grid: Contrato*/
    public function getContrato(){
        $aColumns       =   array('chk','nombre','fecha_creacion' ); //para la ordenacion y pintado en html
        /*
	 * Ordenando, se verifica por que columna se ordenara
	 */
        $sOrder = "";
        for ( $i=0 ; $i<intval( $this->_iSortingCols ) ; $i++ ){
                if ( $this->post( "bSortable_".intval($this->post("iSortCol_".$i)) ) == "true" ){
                        $sOrder .= " ".$aColumns[ intval( $this->post("iSortCol_".$i) ) ]." ".
                                ($this->post("sSortDir_".$i)==="asc" ? "asc" : "desc") ." ";
                }
        }
        
        $query = "call sp_configContratoGrid(:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ":iDisplayStart" => $this->_iDisplayStart,
            ":iDisplayLength" => $this->_iDisplayLength,
            ":sOrder" => $sOrder,
            ":sSearch" => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    /*seleccionar registro a editar: Contrato*/
    public function findContrato(){
        $query = "SELECT id_contrato,nombre,cuerpo_contrato,estado FROM lgk_contrato WHERE id_contrato = :id; ";
        
        $parms = array(
            ':id' => $this->_idContrato
        );
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
    public function mantenimientoContrato(){
        $query = "call sp_configContratoMantenimiento(:flag,:key,:nombre,:cuerpo,:usuario);";
        $parms = array(
            ':flag' => $this->_flag,
            ':key' => $this->_idContrato,
            ':nombre' => $this->_nombre,
            ':cuerpo' => $this->_cuerpo,            
            ':usuario' => $this->_usuario
        );
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
    public function mantenimientoContratoAll(){
        foreach ($this->_chkdel as $value) {
            $query = "call sp_configContratoMantenimiento(:flag,:key,:nombre,:cuerpo,:usuario);";
            $parms = array(
                ':flag' => $this->_flag,
                ':key' => Aes::de($value),
                ':nombre' => '',
                ':cuerpo' => '',
                ':usuario' => $this->_usuario
            );
            $this->execute($query,$parms);
        }
        $data = array('result'=>1);
        return $data;
    }
    public function postDesactivar(){
        $query = "UPDATE `lgk_contrato` SET
                    `estado` = 'I'
                WHERE `id_contrato` = :id;";
        $parms = array(
            ':id' => $this->_idContrato
        );
        $this->execute($query,$parms);
        $data = array('result'=>1);    
        
        return $data;
    }
    
    public function postActivar(){
        $query = "UPDATE `lgk_contrato` SET
                    `estado` = 'A'
                WHERE `id_contrato` = :id;";
        $parms = array(
            ':id' => $this->_idContrato
        );
        $this->execute($query,$parms);
        $data = array('result'=>1);
        return $data;
    }       
    
}

?>