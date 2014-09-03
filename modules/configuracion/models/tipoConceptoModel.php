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
        $this->_flag    = Formulario::getParam('_flag');
        $this->_key     = Aes::de(Formulario::getParam('_key'));    /*se decifra*/
        $this->_idTipoConcepto     = Aes::de(Formulario::getParam('_idTipoConcepto'));    /*se decifra*/
        $this->_descripcion  = Formulario::getParam(T5.'txt_descripcion');
        $this->_estado  = Formulario::getParam(T5.'chk_activo');
        $this->_chkdel  = Formulario::getParam(T5.'chk_delete');
        $this->_usuario = Session::get('sys_idUsuario');
        
        $this->_iDisplayStart  =   Formulario::getParam('iDisplayStart'); 
        $this->_iDisplayLength =   Formulario::getParam('iDisplayLength'); 
        $this->_iSortingCols   =   Formulario::getParam('iSortingCols');
        $this->_sSearch        =   Formulario::getParam('sSearch');
                
    }
    
    public function getTipoConceptos(){
        $aColumns       =   array( 'chk','descripcion' ); //para la ordenacion y pintado en html
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
    
    public function postDesactivar(){
        $query = "UPDATE `pub_tipoconcepto` SET
                    `estado` = 'I'
                WHERE `id_tipo` = :idTConcepto;";
        $parms = array(
            ':idTConcepto' => $this->_idTipoConcepto
        );
        $this->execute($query,$parms);
        $data = array('result'=>1);    
        
        return $data;
    }
    
    public function postActivar(){
        $query = "UPDATE `pub_tipoconcepto` SET
                    `estado` = 'A'
                WHERE `id_tipo` = :idTConcepto;";
        $parms = array(
            ':idTConcepto' => $this->_idTipoConcepto
        );
        $this->execute($query,$parms);
        $data = array('result'=>1);
        return $data;
    }        
    
}
?>
