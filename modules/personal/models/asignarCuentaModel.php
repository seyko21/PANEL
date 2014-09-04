<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 19-08-2014 06:08:51 
* Descripcion : asignarCuentaModel.php
* ---------------------------------------
*/ 

class asignarCuentaModel extends Model{

    private $_flag;
    private $_idAsignarCuenta;
    private $_idPersona;
    private $_productos;
    private $_comision;
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
        $this->_flag            = Formulario::getParam("_flag");
        $this->_idAsignarCuenta = Aes::de(Formulario::getParam("_idAsignarCuenta"));    /*se decifra*/
        $this->_idPersona       = Aes::de(Formulario::getParam(ASCU."txt_idpersona"));    /*se decifra*/;
        $this->_productos       = Formulario::getParam(ASCU."chk_prod");
        $this->_comision        = Formulario::getParam(ASCU."txt_comision");
        $this->_usuario         = Session::get("sys_idUsuario");
        $this->_chkdel          = $this->post(ASCU.'chk_delete');
        
        $this->_iDisplayStart  =   Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength =   Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   =   Formulario::getParam("iSortingCols");
        $this->_sSearch        =   Formulario::getParam("sSearch");
    }
    
    public function gridAsignarCuenta(){
        $aColumns       =   array( 'chk','codigo','fecha_creacion','ubicacion','nombrecompleto','porcentaje_comision','estado' ); //para la ordenacion y pintado en html
        /*
	 * Ordenando, se verifica por que columna se ordenara
	 */
        $sOrder = "";
        for ( $i=0 ; $i<intval( $this->_iSortingCols ) ; $i++ ){
                if ( $this->post( 'bSortable_'.intval($this->post('iSortCol_'.$i)) ) == "true" ){
                        $sOrder .= " ".$aColumns[ intval( $this->post('iSortCol_'.$i) ) ]." ".
                                ($this->post('sSortDir_'.$i)==='asc' ? 'asc' : 'desc') ." ";
                }
        }
        
        $query = "call sp_perAsignarCuentaGrid(:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ':iDisplayStart' => $this->_iDisplayStart,
            ':iDisplayLength' => $this->_iDisplayLength,
            ':sOrder' => $sOrder,
            ':sSearch' => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    /*grabar nuevo registro: AsignarCuenta*/
    public function newAsignarCuenta(){
        $datax = array('duplicado'=>0);
        foreach ($this->_productos as $cad) {
            $prod = explode('-', $cad);
            $query = "CALL sp_perAsignarCuentaMantenimiento(:flag,:idAsignacion,:idProducto,:idPersona,:comision,:usuario);";

            $parms = array(
                ':flag'=>  $this->_flag,
                ':idAsignacion'=>  $this->_idAsignarCuenta,
                ':idProducto'=> AesCtr::de($prod[0]),
                ':idPersona'=>  $this->_idPersona,
                ':comision'=>  $this->_comision,
                ':usuario'=>  $this->_usuario
            );

            $data = $this->queryOne($query,$parms);
            if($data['duplica'] > 0){
                $datax = array('duplicado'=>1,'codigo'=>$prod[1]);
                break;
            }
        }
        
        if($datax['duplicado'] == 0){
            foreach ($this->_productos as $cad) {
                $prod = explode('-', $cad);
                $query = "CALL sp_perAsignarCuentaMantenimiento(:flag,:idAsignacion,:idProducto,:idPersona,:comision,:usuario);";

                $parms = array(
                    ':flag'=>  2,
                    ':idAsignacion'=>  $this->_idAsignarCuenta,
                    ':idProducto'=> AesCtr::de($prod[0]),
                    ':idPersona'=>  $this->_idPersona,
                    ':comision'=>  $this->_comision,
                    ':usuario'=>  $this->_usuario
                );

                $this->execute($query,$parms);
            }
            $datax = array('duplicado'=>0,'result'=>1);
        }
        
        return $datax;
    }
    
    /*seleccionar registro a editar: AsignarCuenta*/
    public function findAsignarCuenta(){
        /*-----------------LOGICA PARA SELECT REGISTRO A EDITAR-----------------*/
    }
    
    /*editar registro: AsignarCuenta*/
    public function editAsignarCuenta(){
        /*-------------------------LOGICA PARA EL UPDATE------------------------*/
    }
    
    /*eliminar varios registros: AsignarCuenta*/
    public function anularAsignarCuentaAll(){
        foreach ($this->_chkdel as $value) {
            $query = "
            UPDATE lgk_asignacioncuenta SET estado = 'A' WHERE id_asignacion = :idAsignarCuenta";
            $parms = array(
                ':idAsignarCuenta' => Aes::de($value)
            );
            $this->execute($query,$parms);
        }
        $data = array('result'=>1);
        return $data;
    }
    
    /*productos sin asignar y activos*/
    public function getProductos(){
        $query = "
        SELECT
                c.`id_caratula`,
                c.`codigo`,
                c.`descripcion`,
                c.`precio`,
                c.`iluminado`,
                k.`ubicacion`,
                k.`dimension_alto` AS alto,
                k.`dimension_ancho` AS ancho,
                k.`dimesion_area` AS aarea
        FROM `lgk_caratula` c 
        INNER JOIN `lgk_catalogo` k ON k.`id_producto`=c.`id_producto`
        WHERE c.`estado`='D' AND k.`estado`=:estado
        and not exists (select * from lgk_asignacioncuenta ac where ac.id_caratula = c.id_caratula and ac.estado='R');";
        
        $parms = array(
            ':estado'=>'A'
        );
        
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    
}

?>