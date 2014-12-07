<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 20-11-2014 17:11:15 
* Descripcion : reporteGraficoMesModel.php
* ---------------------------------------
*/ 

class reporteGraficoMesModel extends Model{

    private $_flag;
    private $_idReporteGraficoMes;
    private $_periodo;
    private $_moneda;
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
        $this->_idReporteGraficoMes   = Aes::de(Formulario::getParam("_idReporteGraficoMes"));    /*se decifra*/
        $this->_usuario     = Session::get("sys_idUsuario");
        
        $this->_periodo        = Formulario::getParam("_periodo");
        $this->_moneda        = Formulario::getParam("_idMoneda");
        
        $this->_iDisplayStart  = Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength = Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   = Formulario::getParam("iSortingCols");
        $this->_sSearch        = Formulario::getParam("sSearch");
    }
    
    public function getGrafico(){
        $query = "
             SELECT MONTH(m.`fecha_caja`) AS mes, m.`moneda` AS id_moneda,
                    (SELECT CONCAT(mo.sigla,' - ',mo.descripcion) FROM pub_moneda mo WHERE m.`moneda` = mo.id_moneda) AS moneda,

                    SUM(m.`monto_inicial`) AS monto_inicial, SUM(m.`total_ingresos`) AS total_ingresos, 
                    SUM(m.`total_egresos`) AS total_egresos, SUM(m.`total_saldo`) AS monto
             FROM `ven_movimientos_caja` m      
             WHERE YEAR( m.`fecha_caja`) = :periodo AND m.`moneda` = :moneda
             GROUP BY 1,2,3
            ";
        $parms = array(
            ':periodo' =>$this->_periodo,
            ':moneda' =>$this->_moneda
        );
        $data = $this->queryAll($query,$parms);        
        
        return $data;
    }
    
    
     public function getMoneda(){
        $query = "
           SELECT
                `id_moneda` AS id,
                CONCAT(`sigla`,' - ',`descripcion`) AS descripcion
              FROM `pub_moneda`
              WHERE estado = :estado              
            ";
        $parms = array(
            ':estado' =>'A'            
        );
        $data = $this->queryAll($query,$parms);
        
        return $data;
    }
    
}

?>