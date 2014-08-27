<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 27-08-2014 02:08:12 
* Descripcion : catalogoPrecioModel.php
* ---------------------------------------
*/ 

class catalogoPreciosModel extends Model{

    private $_flag;
    private $_idCaratula;
    private $_usuario;
    private $_codigo;				
    private $_descripcion; 
    private $_precio;
    private $_iluminado;
    private $_imagen;
    
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
        $this->_flag                    = Formulario::getParam("_flag");        
        $this->_usuario                 = Session::get("sys_idUsuario");
        
        $this->_idCaratula = Aes::de(Formulario::getParam('_idCaratula'));  /*se decifra*/       
        $this->_codigo = Formulario::getParam(TAB_CATPRE.'txt_codigo'); 			
        $this->_descripcion = Formulario::getParam(TAB_CATPRE.'txt_descripcion'); 
        $this->_precio =  str_replace(',','',Formulario::getParam(TAB_CATPRE.'txt_precio'));  /*quitamos la coma para guardar decimal*/
        $this->_iluminado = Formulario::getParam(TAB_CATPRE.'chk_iluminado');
        $this->_imagen = Formulario::getParam(TAB_CATPRE.'txt_imagen');  
        
        $this->_iDisplayStart  =   Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength =   Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   =   Formulario::getParam("iSortingCols");
        $this->_sSearch        =   Formulario::getParam("sSearch");
    }
    
    public function getGridProducto(){
        $aColumns       =   array( 'codigo','distrito','ubicacion' ); //para la ordenacion y pintado en html
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
        
        $query = "call sp_catalogoPreciosGrid(:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ':iDisplayStart' => $this->_iDisplayStart,
            ':iDisplayLength' => $this->_iDisplayLength,
            ':sOrder' => $sOrder,
            ':sSearch' => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);
              
        return $data; 
       
    }
    
    public function getCaratula(){
        $query = " SELECT
                `id_caratula`,
                `id_producto`,
                `codigo`,
                `descripcion`,
                FORMAT(`precio`,2) AS precio,
                `iluminado`,
                `estado`,  
                `imagen`
              FROM `lgk_caratula` 
              WHERE id_caratula = :id ";
        $parms = array(
            ':id' => $this->_idCaratula
        );
        $data = $this->queryOne($query,$parms);            
        return $data;
    }    
    
    public function mantenimientoCaratula(){
        $query = "call sp_catalogoCaratulaMantenimiento(:flag,:key,:idproducto,				
				:codigo, :descripcion, :precio, :iluminado, :imagen, :usuario);";
        $parms = array(
            ':flag' => $this->_flag,
            ':key' => $this->_idCaratula,
            ':idproducto' => $this->_idProducto,     
            ':codigo' => $this->_codigo,   
            ':descripcion' => $this->_descripcion,   
            ':precio' => $this->_precio,   
            ':iluminado' => $this->_iluminado,   
            ':imagen' => $this->_imagen,               
            ':usuario' => $this->_usuario
        );
        $data = $this->queryOne($query,$parms);            
        return $data;
    }        
    
}

?>