<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 29-08-2014 02:08:11 
* Descripcion : consultaPermisosModel.php
* ---------------------------------------
*/ 

class consultaPermisosModel extends Model{

    private $_flag;
    private $_idProducto;
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
        $this->_flag                    = Formulario::getParam("_flag");
        $this->_idProducto   = Aes::de(Formulario::getParam("_idProducto"));    /*se decifra*/
        $this->_usuario                 = Session::get("sys_idUsuario");
        
        $this->_iDisplayStart  =   Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength =   Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   =   Formulario::getParam("iSortingCols");
        $this->_sSearch        =   Formulario::getParam("sSearch");
    }
    
    public function getGridConsultaPermiso(){
        $aColumns       =   array('distrito','ubicacion','fecha_inicio','fecha_final','dimesion_area'); //para la ordenacion y pintado en html
        /*
	 * Ordenando, se verifica por que columna se ordenara
	 */
        $sOrder = "";
        for ( $i=0 ; $i<intval( $this->_iSortingCols ) ; $i++ ){
                if ( $this->post( "bSortable_".intval($this->post("iSortCol_".$i)) ) == "true" ){
                        $sOrder .= " ".$aColumns[ intval( $this->post("iSortCol_".$i) ) ]." ".
                                ($this->post("sSortDir_".$i)==="asc" ? "asc" : 'desc') .",";
                }
        }
        $sOrder = substr_replace( $sOrder, "", -1 );
        $query = "call sp_catalogoConsultaPMGrid(:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ':iDisplayStart' => $this->_iDisplayStart,
            ':iDisplayLength' => $this->_iDisplayLength,
            ':sOrder' => $sOrder,
            ':sSearch' => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);   
        //print_r($parms);
        return $data; 
       
    }
    
    public function getRptFichaTecnica(){
        $query = " SELECT a.id_producto, 
                          a.`id_caratula`,
                          a.`codigo` , 
                          a.`descripcion`,
                          a.`precio`,
                          a.`iluminado`,
                          a.`estado`,
                          c.`ubicacion`, 
                          c.`dimension_alto`, 
                          c.`dimension_ancho`, 
                          c.`dimesion_area`, 
                          c.`observacion`,
                          c.`estado` as estadoProducto,
                          t.`descripcion` AS tipoPanel, 
                          u.`distrito`, 
                          (SELECT d.`departamento` FROM `ub_departamento` d WHERE d.`id_departamento` = LEFT(c.`id_ubigeo`,2)) AS departamento,
                          (SELECT p.`provincia` FROM `ub_provincia` p WHERE p.`id_provincia` = LEFT(c.`id_ubigeo`,4)) AS provincia		
	FROM `lgk_caratula` a
		INNER JOIN `lgk_catalogo` c ON c.`id_producto` = a.`id_producto`
		INNER JOIN `lgk_tipopanel` t ON t.`id_tipopanel` = c.`id_tipopanel`
		INNER JOIN `ub_ubigeo` u ON u.`id_ubigeo` = c.`id_ubigeo`
	WHERE c.`id_producto` = :idProducto ; ";
        $parms = array(
            ':idProducto' => $this->_idProducto
        );
        $data = $this->queryAll($query,$parms);    
        //print_r($data);
        return $data;
    }        

    public function getGridIndexConsultaPermiso(){
        $aColumns       =   array('distrito','ubicacion','fecha_final'); //para la ordenacion y pintado en html
        /*
	 * Ordenando, se verifica por que columna se ordenara
	 */
        $sOrder = "";
        for ( $i=0 ; $i<intval( $this->_iSortingCols ) ; $i++ ){
                if ( $this->post( "bSortable_".intval($this->post("iSortCol_".$i)) ) == "true" ){
                        $sOrder .= " ".$aColumns[ intval( $this->post("iSortCol_".$i) ) ]." ".
                                ($this->post("sSortDir_".$i)==="asc" ? "asc" : 'desc') .",";
                }
        }
        $sOrder = substr_replace( $sOrder, "", -1 );
        $query = "call sp_catalogoIndexPermisoMunicipalGrid(:iDisplayStart,:iDisplayLength,:sOrder);";
        
        $parms = array(
            ':iDisplayStart' => $this->_iDisplayStart,
            ':iDisplayLength' => $this->_iDisplayLength,
            ':sOrder' => $sOrder
        );
        $data = $this->queryAll($query,$parms);   
        return $data; 
       
    }    
    
    
}

?>