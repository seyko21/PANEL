<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 27-09-2014 23:09:28 
* Descripcion : saldoCobrarController.php
* ---------------------------------------
*/    

class saldoCobrarController extends Controller{

    public function __construct() {
        $this->loadModel("saldoCobrar");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexSaldoCobrar");
    }    
    public function getGridSaldoCobrar(){
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->saldoCobrarModel->getSaldoCobrar();
        
        $num = Obj::run()->saldoCobrarModel->_iDisplayStart;
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
                
                $c1 = $aRow['id_comision'];
                $c2 = $aRow['orden_numero'];
                $c3 = $aRow['nombrecompleto'];
                $c4 = $aRow['fecha'];
                $c5 = (number_format($aRow['porcentaje_comision']*100)).' %';
                $c6 = number_format($aRow['comision_venta'],2);
                $c7 = number_format($aRow['comision_asignado'],2);
                $c8 = number_format($aRow['comision_saldo'],2);
                $c9 = Functions::convertirDiaMes($aRow['meses_contrato']);
                $c10 =$aRow['numero_cuota'];
                /*registros a mostrar*/
                $sOutput .= '["'.$c10.'","'.$c2.'","'.$c3.'","'.$c5.'","'.$c9.'","'.$c4.'","'.$c6.'","'.$c7.'","'.$c8.'" ';

                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }

    public function getGridIndexSaldoCobrar(){
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->saldoCobrarModel->getIndexSaldoCobrar();
        
        $num = Obj::run()->saldoCobrarModel->_iDisplayStart;
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
                
                $c1 = $aRow['orden_numero'];
                $c2 = (number_format($aRow['porcentaje_comision']*100)).' %';
                $c3 = number_format($aRow['comision_venta'],2);
                $c4 = number_format($aRow['comision_asignado'],2);
                $c5 = number_format($aRow['comision_saldo'],2);
 
                /*registros a mostrar*/
                $sOutput .= '["'.$c1.'","'.$c2.'","'.$c3.'","'.$c4.'","'.$c5.'" ';

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