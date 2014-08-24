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
    private $_idCaratula; 
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
    
    private $_chkdel; 
    
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
        
        $this->_idCaratula = Aes::de($this->post('_idCaratula'));  /*se decifra*/       
        $this->_codigo = $this->post(T102.'txt_codigo'); 			
        $this->_descripcion = $this->post(T102.'txt_descripcion'); 
        $this->_precio =  str_replace(',','',$this->post(T102.'txt_precio'));  /*quitamos la coma para guardar decimal*/
        $this->_iluminado = $this->post(T102.'chk_iluminado');
        $this->_imagen = $this->post(T102.'txt_imagen');  
        
        $this->_chkdel  = $this->post(T102.'chk_delete'); 
         
        $this->_iDisplayStart  =   $this->post('iDisplayStart'); 
        $this->_iDisplayLength =   $this->post('iDisplayLength'); 
        $this->_iSortingCols   =   $this->post('iSortingCols');
        $this->_sSearch        =   $this->post('sSearch');
        
       }
    
    public function getGridFichaTecnica(){
        $aColumns       =   array( 'chk','u.distrito','ubicacion' ); //para la ordenacion y pintado en html
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
       // print_r($parms);
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
            ':id' => $this->_idCaratula,
        );
        $data = $this->queryOne($query,$parms);            
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
            ':idProducto' => $this->_idProducto,
        );
        $data = $this->queryAll($query,$parms);    
        //print_r($data);
        return $data;
    }    
    public function getUbicacion(){
        $query = " SELECT ubicacion,  FORMAT(dimension_alto,0) as dimension_alto, FORMAT(dimension_ancho,0) as dimension_ancho"
                . "  FROM lgk_catalogo WHERE id_producto = :id ";
        $parms = array(
            ':id' => $this->_idProducto,
        );
        $data = $this->queryOne($query,$parms);           
        return $data;
    }    
    
    public function getGridCaratula(){
        $aColumns       =   array( 'chk' ); //para la ordenacion y pintado en html
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
        
        $query = "call sp_catalogoCaratulasConsultas(:idProducto);";
        
        $parms = array(
            ':idProducto' => $this->_idProducto
        );
        $data = $this->queryAll($query,$parms);        
        return $data; 
       
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