<?php
/*
* --------------------------------------
* fecha: 07-08-2014 02:08:17 
* Descripcion : parametroModel.php
* --------------------------------------
*/ 

class parametroModel extends Model{

    private $_flag;
    private $_key;
    private $_idParametro;
    private $_nombre;
    private $_valor;
    private $_estado;
    private $_alias;
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
        $this->_flag    = Formulario::getParam('_flag');
        $this->_key     = Aes::de(Formulario::getParam('_key'));    /*se decifra*/
        $this->_idParametro   = Aes::de(Formulario::getParam('_idParametro'));    /*se decifra*/
        $this->_nombre  = Formulario::getParam(T100.'txt_nombre');
        $this->_valor  = Formulario::getParam(T100.'txt_valor');        
        $this->_alias  = Formulario::getParam(T100.'txt_alias'); 
        $this->_estado  = Formulario::getParam(T100.'chk_activo');
        
        $this->_usuario = Session::get('sys_idUsuario');
        $this->_chkdel  = Formulario::getParam(T100.'chk_delete');
        
        $this->_iDisplayStart  =   Formulario::getParam('iDisplayStart'); 
        $this->_iDisplayLength =   Formulario::getParam('iDisplayLength'); 
        $this->_iSortingCols   =   Formulario::getParam('iSortingCols');
        $this->_sSearch        =   Formulario::getParam('sSearch');
    }
    
    public function getGridParametro(){
        $aColumns       =   array( 'chk','nombre' ); //para la ordenacion y pintado en html
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
        
        $query = "call sp_configParametroGrid(:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ':iDisplayStart' => $this->_iDisplayStart,
            ':iDisplayLength' => $this->_iDisplayLength,
            ':sOrder' => $sOrder,
            ':sSearch' => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);
        return $data; 
       
    }
    
     public function getParametro(){
        $query = " SELECT * FROM pub_parametro WHERE id_parametro = :id ";
        $parms = array(
            ':id' => $this->_idParametro,
        );
        $data = $this->queryOne($query,$parms);        
        return $data;
    }
    
    public function mantenimientoParametro(){
        $query = "call sp_configParametroMantenimiento(:flag,:key,:nombre,:valor,:alias,:estado,:usuario);";
        $parms = array(
            ':flag' => $this->_flag,
            ':key' => $this->_idParametro,
            ':nombre' => $this->_nombre,
            ':valor' => $this->_valor,
            ':alias' => $this->_alias,
            ':estado' => ($this->_estado == 'A')?'A':'I',
            ':usuario' => $this->_usuario
        );
        $data = $this->queryOne($query,$parms);  
        return $data;
    }
    
    public function mantenimientoParametroAll(){        
        foreach ($this->_chkdel as $value) {
            $query = "call sp_configParametroMantenimiento(:flag,:key,:nombre,:valor,:alias,:estado,:usuario);";
            $parms = array(
                ':flag' => $this->_flag,
                ':key' => Aes::de($value),
                ':nombre' => '',
                ':valor' => '', 
                ':alias' => '',
                ':estado' => '',
                ':usuario' => $this->_usuario
            );
            $this->execute($query,$parms);
        }
        $data = array('result'=>1);
        return $data;
    }    
    
    public function postDesactivar(){
        $query = "UPDATE `pub_parametro` SET
                    `estado` = 'I'
                WHERE `id_parametro` = :id;";
        $parms = array(
            ':id' => $this->_idParametro
        );
        $this->execute($query,$parms);
        $data = array('result'=>1);    
        
        return $data;
    }
    
    public function postActivar(){
        $query = "UPDATE `pub_parametro` SET
                    `estado` = 'A'
                WHERE `id_parametro` = :id;";
        $parms = array(
            ':id' => $this->_idParametro
        );
        $this->execute($query,$parms);
        $data = array('result'=>1);
        return $data;
    }      
    
}

?>