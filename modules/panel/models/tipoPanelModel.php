<?php
/*
* --------------------------------------
* fecha: 08-08-2014 03:08:55 
* Descripcion : tipoPanelModel.php
* --------------------------------------
*/ 

class tipoPanelModel extends Model{

    private $_flag;
    private $_key;
    private $_idTipoPanel;
    private $_descripcion;    
    private $_estado;
    private $_usuario;
    private $_chkdel; 
    
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
        $this->_idTipoPanel   = Aes::de($this->post('_idTipoPanel'));    /*se decifra*/
        $this->_descripcion  = $this->post(T101.'txt_descripcion');                
        $this->_estado  = $this->post(T101.'chk_activo');
        
        $this->_usuario = Session::get('sys_idUsuario');
        $this->_chkdel  = $this->post(T101.'chk_delete');
        
        $this->_iDisplayStart  =   $this->post('iDisplayStart'); 
        $this->_iDisplayLength =   $this->post('iDisplayLength'); 
        $this->_iSortingCols   =   $this->post('iSortingCols');
        $this->_sSearch        =   $this->post('sSearch');
    }
    
    public function getGridTipoPanel(){
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
        
        $query = "call sp_catalogoTipoPanelGrid(:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ':iDisplayStart' => $this->_iDisplayStart,
            ':iDisplayLength' => $this->_iDisplayLength,
            ':sOrder' => $sOrder,
            ':sSearch' => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);
        return $data; 
       
    }
    
     public function getTipoPanel(){
        $query = " SELECT * FROM lgk_tipopanel WHERE id_tipopanel = :id ;";
        $parms = array(
            ':id' => $this->_idTipoPanel
        );
        $data = $this->queryOne($query,$parms);            
        return $data;
    }
    
    public function getLastTipoPanel(){
        $query = " SELECT `id_tipopanel`,`descripcion` FROM lgk_tipopanel WHERE estado = :estado ORDER BY 1 DESC LIMIT 1 ";
        $parms = array(
            ':estado' => 'A',
        );
        $data = $this->queryOne($query,$parms);
        return $data;
    }
        
    public function mantenimientoTipoPanel(){
        $query = "call sp_catalogoTipoPanelMantenimiento(:flag,:key,:descripcion,:estado,:usuario);";
        $parms = array(
            ':flag' => $this->_flag,
            ':key' => $this->_idTipoPanel,
            ':descripcion' => $this->_descripcion,            
            ':estado' => ($this->_estado == 'A')?'A':'I',
            ':usuario' => $this->_usuario
        );
        $data = $this->queryOne($query,$parms);        
        return $data;
    }
    
    public function mantenimientoTipoPanelAll(){        
        foreach ($this->_chkdel as $value) {
            $query = "call sp_catalogoTipoPanelMantenimiento(:flag,:key,:descripcion,:estado,:usuario);";
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
        $query = "UPDATE `lgk_tipopanel` SET
                    `estado` = 'I'
                WHERE `id_tipopanel` = :id;";
        $parms = array(
            ':id' => $this->_idTipoPanel
        );
        $this->execute($query,$parms);
        $data = array('result'=>1);    
        
        return $data;
    }
    
    public function postActivar(){
        $query = "UPDATE `lgk_tipopanel` SET
                    `estado` = 'A'
                WHERE `id_tipopanel` = :id;";
        $parms = array(
            ':id' => $this->_idTipoPanel
        );
        $this->execute($query,$parms);
        $data = array('result'=>1);
        return $data;
    }        
        
    
}

?>