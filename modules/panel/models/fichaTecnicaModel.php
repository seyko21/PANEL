<?php
/*
* --------------------------------------
* fecha: 15-08-2014 02:08:24 
* Descripcion : fichaTecnicaModel.php
* --------------------------------------
*/ 

class fichaTecnicaModel extends Model{

    private $_flag;
    private $_key;
    private $_usuario;
    private $_idProducto;
    private $_idDepartamento;
    private $_idProvincia;
    private $_idtipopanel;
    private $_idubigeo;
    private $_ubicacion;
    private $_alto;
    private $_ancho;
    private $_googlelatitud;
    private $_googlelongitud;
    private $_observacion;
    
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
        $this->_usuario = Session::get('sys_idUsuario');
        $this->_idProducto = Aes::de($this->post('_idProducto'));   
        $this->_idDepartamento = $this->post('_idDepartamento');
        $this->_idProvincia = $this->post('_idProvincia'); 
        
        $this->_idtipopanel  = $this->post(T102.'lst_tipopanel');  
        $this->_idubigeo  = $this->post(T102.'lst_ubigeo');  
        $this->_ubicacion  = $this->post(T102.'txt_ubicacion');  
        $this->_ancho  = $this->post(T102.'txt_ancho');  
        $this->_alto  = $this->post(T102.'txt_alto');  
        $this->_googlelatitud  = $this->post(T102.'txt_latitud');  
        $this->_googlelongitud  = $this->post(T102.'txt_longitud');  
        $this->_observacion  = $this->post(T102.'txt_observacion');                          
        
        $this->_iDisplayStart  =   $this->post('iDisplayStart'); 
        $this->_iDisplayLength =   $this->post('iDisplayLength'); 
        $this->_iSortingCols   =   $this->post('iSortingCols');
        $this->_sSearch        =   $this->post('sSearch');
    }
    
    public function getGridFichaTecnica(){
        $aColumns       =   array( 'chk','id_producto','ubicacion' ); //para la ordenacion y pintado en html
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
        
        $query = "call sp_catalogoFichaTecnicaGrid(:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ':iDisplayStart' => $this->_iDisplayStart,
            ':iDisplayLength' => $this->_iDisplayLength,
            ':sOrder' => $sOrder,
            ':sSearch' => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);
        return $data; 
       
    }
    
     public function getDepartamentos(){
        $query = "SELECT id_departamento,departamento FROM `ub_departamento` ORDER BY departamento ";
        
        $parms = array();
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    public function getProvincias($dep=''){
        $query = "SELECT id_provincia,provincia FROM `ub_provincia` WHERE LEFT(id_provincia,2) = :idDepartamento ORDER BY provincia";
        
        $parms = array(
            ':idDepartamento'=>($dep == '')?$this->_idDepartamento:$dep
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }

    public function getUbigeo($pro=''){
        $query = "SELECT id_ubigeo,distrito FROM `ub_ubigeo` WHERE LEFT(id_ubigeo,4) = :idProvincia ORDER BY distrito;";
        
        $parms = array(
            ':idProvincia'=>($pro == '')?$this->_idProvincia:$pro
        );
                
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    public function getTPanelFichaTecnica(){
        $query = "SELECT id_tipopanel, descripcion FROM lgk_tipopanel WHERE estado = :estado; ";
        
        $parms = array(
            ':estado' => 'A'
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    public function getFichaTecnica(){
        $query = " SELECT * FROM lgk_catalogo WHERE id_producto = :id ";
        $parms = array(
            ':id' => $this->_idProducto,
        );
        $data = $this->queryOne($query,$parms);            
        return $data;
    }
    
    public function getCaratulas(){
        $query = "call sp_catalogoCaratulasConsultas(:idProducto);";
        
        $parms = array(            
            ':idProducto' => $this->_idProducto
        );
        $data = $this->queryAll($query,$parms);
        if(!isset($data['error'])){  
            $xdata = $data;
        }else{
            $xdata = $data['error'];
        }       
        
        return $xdata;
    }    
    public function mantenimientoFichaTecnica(){
        $query = "call sp_catalogoFichaTecnicaMantenimiento(:flag,:key,:id_tipopanel,				
				:id_ubigeo,:ubicacion,:dimension_alto,:dimension_ancho,				
				:google_latitud,:google_longitud,
				:observacion,:usuario);";
        $parms = array(
            ':flag' => $this->_flag,
            ':key' => $this->_idProducto,
            ':id_tipopanel' => $this->_idtipopanel,     
            ':id_ubigeo' => $this->_idubigeo,   
            ':ubicacion' => $this->_ubicacion,   
            ':dimension_alto' => $this->_alto,   
            ':dimension_ancho' => $this->_ancho,   
            ':google_latitud' => $this->_googlelatitud,   
            ':google_longitud' => $this->_googlelongitud,   
            ':observacion' =>($this->_observacion == '')?'Ninguno':$this->_observacion,                           
            ':usuario' => $this->_usuario
        );
        $data = $this->queryOne($query,$parms);        
        return $data;
    }
    
    public function mantenimientoFichaTecnicaAll(){        
        foreach ($this->_chkdel as $value) {
            $query = "call sp_catalogoFichaTecnicaMantenimiento(:flag,:key,:id_tipopanel,				
				:id_ubigeo,:ubicacion,:dimension_alto,:dimension_ancho,				
				:google_latitud,:google_longitud,
				:observacion,:usuario);";
            $parms = array(
                  ':flag' => $this->_flag,
                  ':key' => Aes::de($value),
                  ':id_tipopanel' => '',     
                  ':id_ubigeo' => '',   
                  ':ubicacion' => '',   
                  ':dimension_alto' => '',   
                  ':dimension_ancho' => '',   
                  ':google_latitud' => '',   
                  ':google_longitud' => '',   
                  ':observacion' => '',                                 
                  ':usuario' => $this->_usuario
            );
            $this->execute($query,$parms);
        }
        $data = array('result'=>1);
        return $data;
    }   
    
}

?>