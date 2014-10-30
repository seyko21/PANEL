<?php

class pagoVendedorModel extends Model{
    
    private $_flag;
    private $_idComision;
    private $_idPersona;
    private $_usuario;
    private $_estadocb;    
    private $_serie;
    private $_numero;
    private $_monto;
    private $_exonerar;

    private $_idBoleta;
    private $_chkdel;
    private $_idOS;

    public function __construct() {
        parent::__construct();
        $this->_set();
    }
    
    private function _set(){
        $this->_flag        = Formulario::getParam("_flag");
        $this->_idComision   = Aes::de(Formulario::getParam("_idComision"));    /*se decifra*/
        $this->_usuario     = Session::get("sys_idUsuario");

        $this->_estadocb  = Formulario::getParam("_estadocb");   
        $this->_idBoleta  = Aes::de(Formulario::getParam("_idBoleta"));  
        $this->_idOS  = Formulario::getParam("_idOrdenServicio");  
        $this->_serie        = Formulario::getParam(GPAVE."txt_serie");
        $this->_numero       = Formulario::getParam(GPAVE."txt_numero");
        $this->_monto        = Formulario::getParam(GPAVE."txt_monto");
        $this->_exonerar     = Formulario::getParam(GPAVE."rd_exonerar");
        $this->_idPersona    = Formulario::getParam("_idPersona");
        $this->_chkdel       = Formulario::getParam(GPAVE.'chk_delete');
        $this->_iDisplayStart  = Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength = Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   = Formulario::getParam("iSortingCols");
        $this->_sSearch        = Formulario::getParam("sSearch");
    }
    
    public function getPagosVendedor(){
        $aColumns       =   array("orden_numero","nombrecompleto","fecha","porcentaje_comision","comision_venta","comision_asignado","comision_saldo" ); //para la ordenacion y pintado en html
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
  
        $query = "call sp_pagoProcesoSaldoVendedorGrid(:rol,:estado,:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ":rol"=>'V',
            ":estado"=>$this->_estadocb,
            ":iDisplayStart" => $this->_iDisplayStart,
            ":iDisplayLength" => $this->_iDisplayLength,
            ":sOrder" => $sOrder,
            ":sSearch" => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    public function pagarComision(){
        $query = "call sp_pagoPagosVendedorMantenimiento(:flag,:idComision,:idPersona,:serie,:numero,:monto,:exonerar,:usuario);";
        
        $parms = array(
            ":flag"=>1,
            ":idComision" => $this->_idComision,
            ":idPersona" => $this->_idPersona,              
            ":serie" => $this->_serie,
            ":numero" => $this->_numero,
            ":monto" => $this->_monto,
            ":exonerar" => $this->_exonerar,
            ":usuario" => $this->_usuario
        );
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
    public function anularPagoAll(){
        $query = "call sp_pagoPagosVendedorMantenimiento(:flag,:idBoleta,:idPersona,:serie,:numero,:monto,:exonerar,:usuario);";
        
        foreach ($this->_chkdel as $value) {
            $parms = array(
                ":flag"=>2,
                ":idBoleta" => AesCtr::de($value),
                ":idPersona" => '',              
                ":serie" => '',
                ":numero" => '',
                ":monto" => '',
                ":exonerar" => '',
                ":usuario" => $this->_usuario
            );
            $this->execute($query,$parms);
        }
        $data = array('result'=>1);
        return $data;
    }
    public function anularPago(){
        $query = "call sp_pagoPagosVendedorMantenimiento(:flag,:idComision,:idPersona,:serie,:numero,:monto,:exonerar,:usuario);";
        
        $parms = array(
            ":flag"=>3,
            ":idComision" => $this->_idBoleta,
            ":idPersona" => '',              
            ":serie" => '',
            ":numero" => '',
            ":monto" => '',
            ":exonerar" => '',
            ":usuario" => $this->_usuario
        );
        $this->execute($query,$parms);
        
        $data = array('result'=>1);        
        return $data;
    }
    
    public function validaImagenes(){
        $query = "call sp_ordseOrdenServicioConsultas(:flag,:criterio);";
        
        $parms = array(
            ":flag"=>6,
            ":criterio" => $this->_idOS
        );
        $data = $this->queryOne($query,$parms);
        
        return $data;
    }
    
}