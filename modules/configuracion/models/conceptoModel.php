<?php
/*
* --------------------------------------
* fecha: 06-08-2014 03:08:36 
* Descripcion : conceptoModel.php
* --------------------------------------
*/ 

class conceptoModel extends Model{

    private $_flag;
    private $_idConcepto;
    private $_idTipoConcepto;
    private $_descripcion;
    private $_importe;
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
        $this->_idConcepto     = Aes::de($this->post('_idConcepto'));    /*se decifra*/
        $this->_idTipoConcepto     = $this->post(T6.'lst_tipoconcento');
        $this->_descripcion     = $this->post(T6.'txt_descripcion');
        $this->_importe     = $this->post(T6.'txt_importe');
        $this->_estado     = $this->post(T6.'chk_activo');
        $this->_chkdel  = $this->post(T6.'chk_delete');
        $this->_usuario = Session::get('sys_idUsuario');
        
        $this->_iDisplayStart  =   $this->post('iDisplayStart'); 
        $this->_iDisplayLength =   $this->post('iDisplayLength'); 
        $this->_iSortingCols   =   $this->post('iSortingCols');
        $this->_sSearch        =   $this->post('sSearch');
    }
    
    public function getGridConceptos(){
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
        
        $query = "call sp_configConceptoGrid(:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ':iDisplayStart' => $this->_iDisplayStart,
            ':iDisplayLength' => $this->_iDisplayLength,
            ':sOrder' => $sOrder,
            ':sSearch' => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    public function getTipoConceptos(){
        $query = "SELECT id_tipo,descripcion FROM pub_tipoconcepto WHERE estado = :estado; ";
        
        $parms = array(
            ':estado' => 'A'
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    public function getConcepto(){
        $query = "SELECT id_tipo,descripcion,precio,estado FROM pub_concepto WHERE id_concepto = :idConcepto; ";
        
        $parms = array(
            ':idConcepto' => $this->_idConcepto
        );
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
    public function mantenimientoConcepto(){
        $query = "call sp_configConceptoMantenimiento(:flag,:key,:idTipoConcepto,:descripcion,:importe,:estado,:usuario);";
        $parms = array(
            ':flag' => $this->_flag,
            ':key' => $this->_idConcepto,
            ':idTipoConcepto' => $this->_idTipoConcepto,
            ':descripcion' => $this->_descripcion,
            ':importe' => $this->_importe,
            ':estado' => ($this->_estado == 'A')?'A':'I',
            ':usuario' => $this->_usuario
        );
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
    public function mantenimientoConceptoAll(){
//                print_r($this->_chkdel);
        foreach ($this->_chkdel as $value) {
            $query = "call sp_configConceptoMantenimiento(:flag,:key,:idTipoConcepto,:descripcion,:importe,:estado,:usuario);";
            $parms = array(
                ':flag' => $this->_flag,
                ':key' => Aes::de($value),
                ':idTipoConcepto' => '',
                ':descripcion' => '',
                ':importe' => '',
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