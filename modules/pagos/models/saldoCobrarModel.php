<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 27-09-2014 23:09:28 
* Descripcion : saldoCobrarModel.php
* ---------------------------------------
*/ 

class saldoCobrarModel extends Model{

    private $_flag;
    private $_usuario;
    private $_idPersona;
    private $_f1;
    private $_f2;       
    
    /*para el grid*/
    public  $_iDisplayStart;
    private $_iDisplayLength;
    private $_iSortingCols;
    private $_sSearch;
    
    
    public function __construct() {
        parent::__construct();
        $this->_set();
    }
    
    private function _set(){
        $this->_flag        = Formulario::getParam("_flag");
        $this->_usuario     = Session::get("sys_idUsuario");
        $this->_idPersona   = Session::get('sys_idPersona');
        $this->_f1    = Functions::cambiaf_a_mysql(Formulario::getParam("_f1"));
        $this->_f2    = Functions::cambiaf_a_mysql(Formulario::getParam("_f2"));        
                
        $this->_iDisplayStart  = Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength = Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   = Formulario::getParam("iSortingCols");
        $this->_sSearch        = Formulario::getParam("sSearch");
    }
    
    /*data para el grid: SaldoCobrar*/
    public function getSaldoCobrar(){
        $aColumns       =   array("numero_cuota","orden_numero","nombrecompleto","porcentaje_comision","meses_contrato","fecha","comision_venta","comision_asignado","comision_saldo" ); //para la ordenacion y pintado en html
        /*
	 * Ordenando, se verifica por que columna se ordenara
	 */
        
        $sOrder = "";
        for ( $i=0 ; $i<intval( $this->_iSortingCols ) ; $i++ ){
                if ( $this->post( "bSortable_".intval($this->post("iSortCol_".$i)) ) == "true" ){
                        $sOrder .= " ".$aColumns[ intval( $this->post("iSortCol_".$i) ) ]." ".
                                ($this->post("sSortDir_".$i)==="asc" ? "asc" : "desc") .",";
                }
        }
        $sOrder = substr_replace( $sOrder, "", -1 );
  
        $query = "call sp_pagoConsultaSaldoCobrarGrid(:rol,:estado,:idpersona,:f1,:f2,:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        //Validar por ROL:
        if (Session::get('sys_defaultRol') == APP_COD_VEND){
            $rol = 'V';
        }else if( Session::get('sys_defaultRol') ==  APP_COD_SOCIO ){
            $rol = 'S';
        }
        
        $parms = array(
            ":rol"=>$rol,
            ":estado"=>'S',
            ":idpersona"=>$this->_idPersona,
            ":f1" => $this->_f1,
            ":f2" => $this->_f2,            
            ":iDisplayStart" => $this->_iDisplayStart,
            ":iDisplayLength" => $this->_iDisplayLength,
            ":sOrder" => $sOrder,
            ":sSearch" => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }

    public function getIndexSaldoCobrar(){
        $aColumns       =   array("orden_numero","porcentaje_comision","comision_venta","comision_asignado","comision_saldo" ); //para la ordenacion y pintado en html
        /*
	 * Ordenando, se verifica por que columna se ordenara
	 */
        $sOrder = "";
        for ( $i=0 ; $i<intval( $this->_iSortingCols ) ; $i++ ){
                if ( $this->post( "bSortable_".intval($this->post("iSortCol_".$i)) ) == "true" ){
                        $sOrder .= " ".$aColumns[ intval( $this->post("iSortCol_".$i) ) ]." ".
                                ($this->post("sSortDir_".$i)==="asc" ? "asc" : "desc") .",";
                }
        }
        $sOrder = substr_replace( $sOrder, "", -1 );
  
        $query = "call sp_pagoIndexSaldoCobrarGrid(:rol,:idpersona,:iDisplayStart,:iDisplayLength,:sOrder);";
        
        //Validar por ROL:
        if (Session::get('sys_defaultRol') == APP_COD_VEND){
            $rol = 'V';
        }else if( Session::get('sys_defaultRol') ==  APP_COD_SOCIO ){
            $rol = 'S';
        }
        
        $parms = array(
            ":rol"=>$rol,
            ":idpersona"=>$this->_idPersona,
            ":iDisplayStart" => $this->_iDisplayStart,
            ":iDisplayLength" => $this->_iDisplayLength,
            ":sOrder" => $sOrder
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }    
    
}

?>