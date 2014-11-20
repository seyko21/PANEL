<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 20-11-2014 17:11:31 
* Descripcion : reporteProductoMesController.php
* ---------------------------------------
*/    

class reporteProductoMesController extends Controller{

    public function __construct() {
        $this->loadModel("reporteProductoMes");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexReporteProductoMes");
    }
    
    public function getGridReporteProductoMes(){
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->reporteProductoMesModel->getReporteProductoMes();
        
        $num = Obj::run()->reporteProductoMesModel->_iDisplayStart;
        if($num >= 10){
            $num++;
        }else{
            $num = 1;
        }
        
        if(!isset($rResult['error'])){  
            $iTotal         = isset($rResult[0]['total'])?$rResult[0]['total']:0;
            
            $sOutput = '{';
            $sOutput .= '"sEcho": '.intval($sEcho).', ';
            $sOutput .= '"iTotalRecords": '.$iTotal.', ';
            $sOutput .= '"iTotalDisplayRecords": '.$iTotal.', ';
            $sOutput .= '"aaData": [ ';     
            
            foreach ( $rResult as $aRow ){                                
                /*registros a mostrar*/
                $sOutput .= '["'.($num++).'","'.  Functions::cambiaf_a_normal($aRow['fecha']).'","'.$aRow['descripcion'].'","'.number_format($aRow['cantidad_comprado']).'","'.number_format($aRow['promedio_cantidad'],2).'","'.$aRow['sigla'].'" ';

                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }
    
   
    
}

?>