<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 21-09-2014 05:09:42 
* Descripcion : confirmarInstalacionModel.php
* ---------------------------------------
*/ 

class confirmarInstalacionModel extends Model{

    private $_flag;
    public  $_idOrdenDetalle;
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
        $this->_idOrdenDetalle   = Aes::de(Formulario::getParam("_idOrdenDetalle"));    /*se decifra*/
        $this->_usuario     = Session::get("sys_idUsuario");
        
        $this->_iDisplayStart  = Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength = Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   = Formulario::getParam("iSortingCols");
        $this->_sSearch        = Formulario::getParam("sSearch");
    }
    
    /*data para el grid: ConfirmarInstalacion*/
    public function getConfirmarInstalacion(){
        $aColumns       =   array("orden_numero","ordenin_numero","codigo","ubicacion","fecha_inicio"); //para la ordenacion y pintado en html
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
        
        $query = "call sp_ordseConfirmarInstalacionGrid(:acceso,:usuario,:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ":acceso" => Session::get('sys_all'),
            ":usuario" => $this->_usuario,
            ":iDisplayStart" => $this->_iDisplayStart,
            ":iDisplayLength" => $this->_iDisplayLength,
            ":sOrder" => $sOrder,
            ":sSearch" => $this->_sSearch
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    public function getImagen(){
        $query = " SELECT `imagen` FROM `lgk_ordenserviciod` WHERE `id_ordenserviciod` = :idOrdenDdetalle;";
        $parms = array(
            ":idOrdenDdetalle" => $this->_idOrdenDetalle
        );
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
    public function insertImagen($doc){
        $query = "UPDATE `lgk_ordenserviciod` SET
                    `imagen` = :doc,
                    fecha_imagen = NOW()
                WHERE `id_ordenserviciod` = :idOrdenDetalle;";
        $parms = array(
            ':idOrdenDetalle' => $this->_idOrdenDetalle,
            ':doc' => $doc
        );
        $this->execute($query,$parms);
        $data = array('result'=>1);
        return $data;
    }
    
    public function deleteImagen(){
        $query = "UPDATE  lgk_ordenserviciod SET
                    imagen = ''
                WHERE id_ordenserviciod = :idOrdenDetalle;";
        
        $parms = array(
            ':idOrdenDetalle'=>$this->_idOrdenDetalle
        );
        
        $this->execute($query,$parms);
        
        $data = array('result'=>1);
        return $data;
    }  
    
}

?>