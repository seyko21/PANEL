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
        $query = "SELECT 
                t.fecha, t.moneda, t.ingresos, t.id_moneda, t.egresos,
                (t.ingresos - t.egresos) AS monto
            FROM(
            SELECT p.`fecha`, 
                    (SELECT CONCAT(m.sigla,' - ',m.descripcion) FROM pub_moneda m WHERE dd.`moneda` = m.id_moneda) AS moneda,
                    dd.moneda AS id_moneda,
                    (SELECT COUNT(d.`codigo_impresion`) AS codigo FROM `ven_documento` d WHERE p.`fecha` = d.`fecha` AND d.estado = 'E' AND d.moneda = dd.`moneda` ) AS numero_doc,	
                    SUM(p.`monto_pagado`) AS ingresos,                                         
                    (SELECT 
                           if( SUM(e.`monto`) is null, 0, SUM(e.`monto`) )                     
                    FROM `ven_egreso` e WHERE e.fecha = p.`fecha` and e.estado = 'E' ) AS egresos

                    FROM `ven_pago` p
                            INNER JOIN ven_documento dd ON dd.`id_docventa` = p.`id_docventa`
                    WHERE p.`estado` = 'E'		
                    GROUP BY 1,2,3
            ) AS t
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
    
    public function getListadoEgresos(){
        $query = "
        SELECT
            e.`id_egreso`,
            e.`descripcion`,
            e.`fecha`,
            e.`monto`,
            (select pm.sigla from pub_moneda pm where pm.id_moneda = e.`moneda`) as moneda,
            e.moneda as id_moneda,
            e.`estado`  
          FROM ven_egreso e
          where e.estado = :estado and e.fecha = :fecha 
          order by e.`descripcion`; ";
        
        $parms = array(
            ':estado'=>  "E",
            ':fecha'=>  $this->_fecha            
        );
        $data = $this->queryAll($query,$parms);      
        
        return $data;
    }        
    
}

?>