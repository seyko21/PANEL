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
    private $_usuario;    
    public $_idCaratula;
    private $_tipoPanel;
    
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
        $this->_tipoPanel =   Formulario::getParam("_tipoPanel"); 
        $this->_iDisplayStart  =   Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength =   Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   =   Formulario::getParam("iSortingCols");
        $this->_sSearch        =   Formulario::getParam("sSearch");
    }
    
    public function getGridProducto(){
        $aColumns       =   array( 'codigo','distrito','ubicacion','elemento','dimesion_area','precio','iluminado','estado' ); //para la ordenacion y pintado en html
        /*
	 * Ordenando, se verifica por que columna se ordenara
	 */
        $sOrder = "";
        for ( $i=0 ; $i<intval( $this->_iSortingCols ) ; $i++ ){
                if ( Formulario::getParam( "bSortable_".intval(Formulario::getParam("iSortCol_".$i)) ) == "true" ){
                        $sOrder .= " ".$aColumns[ intval( Formulario::getParam("iSortCol_".$i) ) ]." ".
                                (Formulario::getParam("sSortDir_".$i)==="asc" ? "asc" : 'desc') .",";
                }
        }        
        $sOrder = substr_replace( $sOrder, "", -1 );
        
        $query = "call sp_catalogoPreciosGrid(:tipopanel, :iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ':tipopanel' => $this->_tipoPanel,
            ':iDisplayStart' => $this->_iDisplayStart,
            ':iDisplayLength' => $this->_iDisplayLength,
            ':sOrder' => $sOrder,
            ':sSearch' => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);
     
        return $data; 
       
    }
    public function getTipoPanel(){
        $query = "SELECT t.id_tipopanel, t.descripcion FROM lgk_tipopanel t
                 WHERE exists 
                 (select * from lgk_catalogo c where c.id_tipopanel = t.id_tipopanel and c.estado = 'A'  )
                  and t.estado = :estado; 
                 ";
        
        $parms = array(
            ':estado' => 'A'
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    public function getRptFichaTecnicaCatalogo(){
        $query = " SELECT a.id_producto, 
                          a.`id_caratula`,
                          a.`codigo` , 
                          CONCAT(c.`ubicacion`,' - ',a.`descripcion` ) AS ubicacion,
                          a.`precio`,
                          a.`iluminado`,
                          a.`estado`,                          
                          c.`dimension_alto`, 
                          c.`dimension_ancho`, 
                          c.`dimesion_area`, 
                          c.`observacion`,
                          c.`estado` AS estadoProducto,
                          t.`descripcion` AS tipoPanel, 
                          u.`distrito`, 
                          (SELECT d.`departamento` FROM `ub_departamento` d WHERE d.`id_departamento` = LEFT(c.`id_ubigeo`,2)) AS departamento,
                          (SELECT p.`provincia` FROM `ub_provincia` p WHERE p.`id_provincia` = LEFT(c.`id_ubigeo`,4)) AS provincia,
                          c.google_latitud,
                          c.google_longitud,
                          ( SELECT pm.fecha_inicio FROM lgk_permisomuni pm WHERE pm.id_producto = c.id_producto AND pm.estado = 'A' ORDER BY pm.id_permisomuni DESC LIMIT 1 ) AS fecha_inicio,
                          ( SELECT pm.fecha_final FROM lgk_permisomuni pm WHERE pm.id_producto = c.id_producto AND pm.estado = 'A' ORDER BY pm.id_permisomuni DESC LIMIT 1 ) AS fecha_final,
                          ( SELECT pm.observacion FROM lgk_permisomuni pm WHERE pm.id_producto = c.id_producto AND pm.estado = 'A' ORDER BY pm.id_permisomuni DESC LIMIT 1 ) AS pm_obs,
                          ( SELECT pm.monto_pago FROM lgk_permisomuni pm WHERE pm.id_producto = c.id_producto AND pm.estado = 'A' ORDER BY pm.id_permisomuni DESC LIMIT 1 ) AS pm_precio,
                          ( SELECT `porcentaje_comision` FROM `lgk_asignacioncuenta` ac WHERE ac.`id_caratula` = a.`id_caratula` AND ac.`estado` = 'A' LIMIT 1 ) AS comision_vendedor,
                          ( SELECT (SELECT p.`nombrecompleto` FROM `mae_persona` p WHERE p.`id_persona` = ac.`id_persona` ) FROM `lgk_asignacioncuenta` ac WHERE ac.`id_caratula` = a.`id_caratula` AND ac.`estado` = 'A' LIMIT 1 ) AS vendedor                          
	FROM `lgk_caratula` a
		INNER JOIN `lgk_catalogo` c ON c.`id_producto` = a.`id_producto`
		INNER JOIN `lgk_tipopanel` t ON t.`id_tipopanel` = c.`id_tipopanel`
		INNER JOIN `ub_ubigeo` u ON u.`id_ubigeo` = c.`id_ubigeo`
	WHERE a.`id_caratula` = :idCaratula ; ";
        $parms = array(
            ':idCaratula' => $this->_idCaratula
        );
        $data = $this->queryAll($query,$parms);    
        //print_r($data);
        return $data;
    }        
    
    
}

?>