<?php
/*
 * Documento   : tipoConceptoModel
 * Creado      : 30-ene-2014, 19:26:46
 * Autor       : RDCC
 * Descripcion :
 */
class tipoConceptoModel extends Model{
    private $_flag;
    private $_key;
    private $_idTipoConcepto;
    private $_descripcion;
    private $_estado;
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
        $this->_key     = Aes::de($this->post('_key'));    /*se decifra*/
        $this->_idTipoConcepto     = Aes::de($this->post('_idTipoConcepto'));    /*se decifra*/
        $this->_descripcion  = $this->post(T5.'txt_descripcion');
        $this->_estado  = $this->post(T5.'chk_activo');
        $this->_chkdel  = $this->post(T5.'chk_delete');
        $this->_usuario = Session::get('sys_idUsuario');
        
        $this->_iDisplayStart  =   $this->post('iDisplayStart'); 
        $this->_iDisplayLength =   $this->post('iDisplayLength'); 
        $this->_iSortingCols   =   $this->post('iSortingCols');
        $this->_sSearch        =   $this->post('sSearch');
    }
    
    public function getTipoConceptos(){
        $aColumns       =   array( 'chk','descripcion' ); //para la ordenacion y pintado en html
        /*
	 * Ordenando, se verifica por que columna se ordenara
	 */
        $sOrder = "";
        for ( $i=0 ; $i<intval( $this->_iSortingCols ) ; $i++ ){
                if ( $this->post( "bSortable_".intval($this->post("iSortCol_".$i)) ) == "true" ){
                        $sOrder .= " ".$aColumns[ intval( $this->post("iSortCol_".$i) ) ]." ".
                                ($this->post("sSortDir_".$i)==="asc" ? "asc" : 'desc') ." ";
                }
        }
        
        $query = "call sp_configTipoConceptoGrid(:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ':iDisplayStart' => $this->_iDisplayStart,
            ':iDisplayLength' => $this->_iDisplayLength,
            ':sOrder' => $sOrder,
            ':sSearch' => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    public function getTipoConcepto(){
        $query = " SELECT * FROM `pub_tipoconcepto` WHERE id_tipo = :id ";
        $parms = array(
            ':id' => $this->_idTipoConcepto,
        );
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
    public function getLastTipoConcepto(){
        $query = " SELECT id_tipo,descripcion FROM `pub_tipoconcepto` WHERE estado = :estado ORDER BY 1 DESC LIMIT 1 ";
        $parms = array(
            ':estado' => 'A',
        );
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
    public function mantenimientoTipoConcepto(){
        $query = "call sp_configTipoConceptoMantenimiento(:flag,:key,:descripcion,:estado,:usuario);";
        $parms = array(
            ':flag' => $this->_flag,
            ':key' => $this->_idTipoConcepto,
            ':descripcion' => $this->_descripcion,
            ':estado' => ($this->_estado == 'A')?'A':'I',
            ':usuario' => $this->_usuario
        );
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
    public function mantenimientoTipoConceptoAll(){
        foreach ($this->_chkdel as $value) {
            $query = "call sp_configTipoConceptoMantenimiento(:flag,:key,:descripcion,:estado,:usuario);";
            $parms = array(
                ':flag' => $this->_flag,
                ':key' => Aes::de($value),
                ':descripcion' => '',
                ':estado' => '',
                ':usuario' => $this->_usuario
            );
            $this->execute($query,$parms);
        }
        $data = array('result'=>1);
        return $data;
    }
    
}
?>
