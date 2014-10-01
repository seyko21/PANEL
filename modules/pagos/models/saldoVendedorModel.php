<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 26-09-2014 21:09:55 
* Descripcion : saldoVendedorModel.php
* ---------------------------------------
*/ 

class saldoVendedorModel extends Model{

    private $_flag;
    private $_idComision;
    public  $_idBoleta;
    private $_estadocb;
    private $_usuario;
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
        $this->_idComision   = Aes::de(Formulario::getParam("_idComision"));    /*se decifra*/
        $this->_usuario     = Session::get("sys_idUsuario");
        $this->_estadocb  = Formulario::getParam("_estadocb");  
        $this->_f1    = Functions::cambiaf_a_mysql(Formulario::getParam("_f1"));
        $this->_f2    = Functions::cambiaf_a_mysql(Formulario::getParam("_f2")); 
        
        $this->_idBoleta  = Aes::de(Formulario::getParam("_idBoleta"));
        
        $this->_iDisplayStart  = Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength = Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   = Formulario::getParam("iSortingCols");
        $this->_sSearch        = Formulario::getParam("sSearch");
    }
    
    /*data para el grid: SaldoVendedor*/
    public function getSaldoVendedor(){
        $aColumns       =   array("","orden_numero","nombrecompleto","fecha","porcentaje_comision","comision_venta","comision_asignado","comision_saldo" ); //para la ordenacion y pintado en html
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
  
        $query = "call sp_pagoConsultaSaldoVendedorGrid(:rol,:estado,:f1,:f2,:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ":rol"=>'V',
            ":estado"=>$this->_estadocb,
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

     public function gridPagoVendedor(){
        $aColumns       =   array('boleta_numero','fecha','recibo_numero','recibo_serie','exonerado','monto_total','monto_retencion','monto_neto'); //para la ordenacion y pintado en html
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
        
        $query = "call sp_pagoBoletaGrid(:idComision,:iDisplayStart,:iDisplayLength,:sOrder);";
        
        $parms = array(
            ':idComision' => $this->_idComision,
            ':iDisplayStart' => $this->_iDisplayStart,
            ':iDisplayLength' => $this->_iDisplayLength,
            ':sOrder' => $sOrder
        );
        
        $data = $this->queryAll($query,$parms);
     
        return $data; 
       
    }    
    
    public function getBoleta(){            
        $query = "
        SELECT
            tb.`id_boleta`,
            tb.`boleta_periodo`,
            tb.`boleta_numero`,
            tb.`id_persona`,
            tb.`recibo_numero`,
            tb.`recibo_serie`,
            tb.`monto_total`,
            tb.`monto_retencion`,
            tb.monto_neto,
            CASE tb.`exonerado` WHEN 'S' THEN 'SI' WHEN 'N' THEN 'NO' END AS exonerado,
            tb.`observacion`,
            tb.`estado`,
            DATE_FORMAT(tb.`fecha_creacion`  ,'%d/%m/%Y') AS fecha,
            cv.`comision_venta`,
            cv.`comision_asignado`,
            cv.`comision_saldo`,
            cv.`porcentaje_comision`,
            pp.`nombrecompleto` as benefactor,
            pp.`direccion`,
            pp.`numerodocumento` AS ruc,
            pp.`dni`,
            (SELECT valor FROM `pub_parametro`  WHERE alias = 'IR' ) as impuesto_ir
        FROM `tes_boleta` tb
           INNER JOIN `lgk_comisionvendedor` cv ON cv.`id_comision` = tb.`id_comision`
           INNER JOIN mae_persona pp ON pp.`id_persona` = tb.`id_persona` 
        WHERE tb.`id_comision` = :idd";
        
        $parms = array(
            ':idd' => $this->_idBoleta
        );
        $data = $this->queryOne($query,$parms);            
        return $data;
    
    }    
    
}

?>