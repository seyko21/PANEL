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
    private $_destino;
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
        $this->_idConcepto     = Aes::de(Formulario::getParam('_idConcepto'));    /*se decifra*/
        $this->_idTipoConcepto     = Formulario::getParam(T6.'lst_tipoconcento');
        $this->_descripcion     = Formulario::getParam(T6.'txt_descripcion');
        $this->_importe  =  str_replace(',','',Formulario::getParam(T6.'txt_importe')); //Quitar coma del importe
        $this->_estado     = Formulario::getParam(T6.'chk_activo');
        $this->_chkdel  = Formulario::getParam(T6.'chk_delete');
        $this->_usuario = Session::get('sys_idUsuario');
        $this->_destino     = Formulario::getParam(T6.'lst_destino');
        
        $this->_iDisplayStart  =   Formulario::getParam('iDisplayStart'); 
        $this->_iDisplayLength =   Formulario::getParam('iDisplayLength'); 
        $this->_iSortingCols   =   Formulario::getParam('iSortingCols');
        $this->_sSearch        =   Formulario::getParam('sSearch');
    }
    
    public function getGridConceptos(){
        $aColumns       =   array( 'chk','c.descripcion','t.descripcion','6','c.precio' ); //para la ordenacion y pintado en html
        /*
	 * Ordenando, se verifica por que columna se ordenara
	 */
        $sOrder = "";
        for ( $i=0 ; $i<intval( $this->_iSortingCols ) ; $i++ ){
                if ( Formulario::getParam( "bSortable_".intval(Formulario::getParam("iSortCol_".$i)) ) == "true" ){
                        $sOrder .= " ".$aColumns[ intval( Formulario::getParam("iSortCol_".$i) ) ]." ".
                                (Formulario::getParam("sSortDir_".$i)==="asc" ? "asc" : 'desc') ." ";
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
        $query = "SELECT id_tipo,descripcion,precio,estado, destino FROM pub_concepto WHERE id_concepto = :idConcepto; ";
        
        $parms = array(
            ':idConcepto' => $this->_idConcepto
        );
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
    public function mantenimientoConcepto(){
        $query = "call sp_configConceptoMantenimiento(:flag,:key,:idTipoConcepto,:descripcion,:importe,:destino,:usuario);";
        $parms = array(
            ':flag' => $this->_flag,
            ':key' => $this->_idConcepto,
            ':idTipoConcepto' => $this->_idTipoConcepto,
            ':descripcion' => $this->_descripcion,
            ':importe' => $this->_importe,
            ':destino' => $this->_destino,
            ':usuario' => $this->_usuario
        );
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
    public function mantenimientoConceptoAll(){
//                print_r($this->_chkdel);
        foreach ($this->_chkdel as $value) {
            $query = "call sp_configConceptoMantenimiento(:flag,:key,:idTipoConcepto,:descripcion,:importe,:destino,:usuario);";
            $parms = array(
                ':flag' => $this->_flag,
                ':key' => Aes::de($value),
                ':idTipoConcepto' => '',
                ':descripcion' => '',
                ':importe' => '',
                ':destino' => '',
                ':usuario' => $this->_usuario
            );
            $this->execute($query,$parms);
        }
        $data = array('result'=>1);
        return $data;
    }
    public function postDesactivar(){
        $query = "UPDATE `pub_concepto` SET
                    `estado` = 'I'
                WHERE `id_concepto` = :id;";
        $parms = array(
            ':id' => $this->_idConcepto
        );
        $this->execute($query,$parms);
        $data = array('result'=>1);    
        
        return $data;
    }
    
    public function postActivar(){
        $query = "UPDATE `pub_concepto` SET
                    `estado` = 'A'
                WHERE `id_concepto` = :id;";
        $parms = array(
            ':id' => $this->_idConcepto
        );
        $this->execute($query,$parms);
        $data = array('result'=>1);
        return $data;
    }            
    
}

?>