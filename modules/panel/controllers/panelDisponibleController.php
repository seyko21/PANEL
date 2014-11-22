<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 21-11-2014 23:11:45 
* Descripcion : panelDisponibleController.php
* ---------------------------------------
*/    

class panelDisponibleController extends Controller{

    public function __construct() {
        $this->loadModel("panelDisponible");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexPanelDisponible");
    }
    
    public function getGridPanelDisponible(){

        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->panelDisponibleModel->getPanelDisponible();
        
        $num = Obj::run()->panelDisponibleModel->_iDisplayStart;
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
                              
                 $col1 = $aRow['ubicacion'];
                 $col2 = $aRow['dimesion_area'].' m<sup>2</sup>';
                 $col3 = $aRow['distrito'];
                 $col4 = $aRow['elemento'];
                 $col5 = $aRow['codigos'];
                
                /*registros a mostrar*/
                $sOutput .= '["'.($num++).'","'.$col1.'","'.$col2.'","'.$col3.'","'.$col4.'","'.$col5.'" ';

                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }    
    
    public static function getListadoCiudad(){ 
       $data = Obj::run()->panelDisponibleModel->getCiudad();            
       return $data;
    }    
    
}

?>