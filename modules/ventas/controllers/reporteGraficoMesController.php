<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 20-11-2014 17:11:15 
* Descripcion : reporteGraficoMesController.php
* ---------------------------------------
*/    

class reporteGraficoMesController extends Controller{

    public function __construct() {
        $this->loadModel("reporteGraficoMes");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexReporteGraficoMes");
    }
    
    public function getGrafico() {
        $data = Obj::run()->reporteGraficoMesModel->getGrafico();
        echo json_encode($data);
        
    }
    public static function getMoneda() {
        $data = Obj::run()->reporteGraficoMesModel->getMoneda();
        return $data;
        
    }    
}

?>