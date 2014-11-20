<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 19-11-2014 22:11:59 
* Descripcion : reporteVentaDiaController.php
* ---------------------------------------
*/    

class reporteVentaDiaController extends Controller{

    public function __construct() {
        $this->loadModel("reporteVentaDia");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexReporteVentaDia");
    }
    
    public function getGraficoVentaDia() {
        $data = Obj::run()->reporteVentaDiaModel->getGraficoVentaDia();
        return $data;
    }
    
}

?>