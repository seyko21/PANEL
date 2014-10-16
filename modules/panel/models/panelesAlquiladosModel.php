<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 10-09-2014 14:09:04 
* Descripcion : panelesAlquiladosModel.php
* ---------------------------------------
*/ 

class panelesAlquiladosModel extends Model{

    private $_flag;    
    private $_usuario;
    private $_idPersona;
    private $_idCaratula;
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
        $this->_idPersona               = Session::get("sys_idPersona");
        $this->_tipoPanel =   Formulario::getParam("_tipoPanel"); 
        $this->_idCaratula = Aes::de(Formulario::getParam('_idCaratula'));  /*se decifra*/ 
        
        $this->_iDisplayStart  =   Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength =   Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   =   Formulario::getParam("iSortingCols");
        $this->_sSearch        =   Formulario::getParam("sSearch");
    }
    
    public function getTipoPanelCuenta(){
        $query = "call sp_catalogoTipoPanelConsultas(:acceso, :usuario,:estado); ";        
        $parms = array(
            ':acceso' => Session::get('sys_all'),
            ':usuario' => $this->_idPersona,
            ':estado' => 'A' 
        );
        $data = $this->queryAll($query,$parms);
        return $data;
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
        
        $query = "call sp_catalogoMisCuentasGrid(:acceso,:idPersona,:estado,:tipopanel,:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ':acceso' => Session::get('sys_all'),
            ':idPersona' => $this->_idPersona, 
            ':estado' => 'A', 
            ':tipopanel' => $this->_tipoPanel,
            ':iDisplayStart' => $this->_iDisplayStart,
            ':iDisplayLength' => $this->_iDisplayLength,
            ':sOrder' => $sOrder,
            ':sSearch' => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);
     
        return $data; 
       
    }
    
    public function getGridOrdenServicio(){
        $aColumns       =   array('orden_numero','cliente','precio','fecha_inicio','fecha_termino'); //para la ordenacion y pintado en html
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
        
        $query = "call sp_catalogoConsultaOSGrid(:acceso,:usuario,:idCaratula,:iDisplayStart,:iDisplayLength,:sOrder);";
        
        $parms = array(
            ':acceso' => Session::get('sys_all'),
            ':usuario' => $this->_usuario, 
            ':idCaratula' => $this->_idCaratula,
            ':iDisplayStart' => $this->_iDisplayStart,
            ':iDisplayLength' => $this->_iDisplayLength,
            ':sOrder' => $sOrder
        );
        
        $data = $this->queryAll($query,$parms);
     
        return $data; 
       
    }    
    public function getIndexPanelAlquilado(){
        $aColumns       =   array('codigo','orden_numero','fecha_inicio','fecha_termino'); //para la ordenacion y pintado en html
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
        
        $query = "call sp_catalogoIndexPanelAlquiladoGrid(:acceso,:idPersona,:iDisplayStart,:iDisplayLength,:sOrder);";
        
        $parms = array(   
            ':acceso' => Session::get('sys_all'),
            ':idPersona' => $this->_idPersona,
            ':iDisplayStart' => $this->_iDisplayStart,
            ':iDisplayLength' => $this->_iDisplayLength,
            ':sOrder' => $sOrder
        );
        
        $data = $this->queryAll($query,$parms);
     
        return $data; 
    }
}

?>