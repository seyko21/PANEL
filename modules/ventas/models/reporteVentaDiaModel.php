<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 19-11-2014 22:11:59 
* Descripcion : reporteVentaDiaModel.php
* ---------------------------------------
*/ 

class reporteVentaDiaModel extends Model{

    private $_flag;
    private $_idReporteVentaDia;
    public  $_fecha;
    private $_usuario;
    
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
        $this->_idReporteVentaDia   = Aes::de(Formulario::getParam("_idReporteVentaDia"));    /*se decifra*/
        $this->_usuario     = Session::get("sys_idUsuario");
        
        $this->_fecha        =  Aes::de(Formulario::getParam("_fecha"));        
        
        $this->_iDisplayStart  = Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength = Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   = Formulario::getParam("iSortingCols");
        $this->_sSearch        = Formulario::getParam("sSearch");
    }
    
    public function getGraficoVentaDia(){
        $query = "select 
                t.fecha, t.moneda, t.monto, t.id_moneda
            from(
            SELECT p.`fecha`, 
                    (select concat(m.sigla,' - ',m.descripcion) from pub_moneda m where dd.`moneda` = m.id_moneda) as moneda,
                    dd.moneda as id_moneda,
                    (select count(d.`codigo_impresion`) as codigo from `ven_documento` d where p.`fecha` = d.`fecha` and d.estado = 'E' and d.moneda = dd.`moneda` ) as numero_doc,	
                    SUM(p.`monto_pagado`) as monto
                    from `ven_pago` p
                            inner join ven_documento dd on dd.`id_docventa` = p.`id_docventa`
                    where p.`estado` = 'E'		
                    group by 1,2,3
            ) as t
            where t.`fecha` = :fecha;";
        $parms = array(
            ':fecha' => date("Y-m-d")            
        );
        $data = $this->queryAll($query,$parms);

        return $data;
    }
    
    public function getListadoVenta(){
        $query = "
        SELECT
            d.`id_docventa`,
            d.`periodo`,
            d.`codigo_impresion`,
            DATE_FORMAT(d.`fecha`,'%d/%m/%Y')AS fecha,
            d.`id_persona`,
            (select pp.nombrecompleto from mae_persona pp where pp.id_persona = d.id_persona) as cliente,
            (select concat(`sigla`,' - ',`descripcion`) from pub_moneda mo where mo.id_moneda = d.`moneda`) as descripcion_moneda,
            (select `sigla` from pub_moneda mo where mo.id_moneda = d.`moneda`) as moneda,
            d.moneda as id_moneda,
            d.`monto_importe`,
            d.`tipo_doc`,
            d.`observacion`,
            d.estado,
            d.`monto_asignado`,
            d.`monto_saldo`
          FROM `ven_documento` d
          WHERE d.estado = :estado and d.`fecha` = :fecha 
          order by d.`fecha`, d.moneda desc, d.`codigo_impresion`  ";
        
        $parms = array(
            ':estado'=>  "E",
            ':fecha'=>  $this->_fecha            
        );
        $data = $this->queryAll($query,$parms);      
        
        return $data;
    }    
    
}

?>