<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 29-09-2014 17:09:58 
* Descripcion : cronogramaClienteController.php
* ---------------------------------------
*/    

class cronogramaClienteController extends Controller{

    public function __construct() {
        $this->loadModel("cronogramaCliente");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexCronogramaCliente");
    }
    
    public function getGridCronogramaCliente(){
            
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->cronogramaClienteModel->getCronogramaCliente();
        
        $num = Obj::run()->cronogramaClienteModel->_iDisplayStart;
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
                
               /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_compromisopago']);
               
                
                $c1 = $aRow['orden_numero'];
                $c2 = $aRow['numero_cuota'];
                $c3 = Functions::cambiaf_a_normal($aRow['fecha_programada']);
                $c4 = $aRow['descripcion_cliente'];
                $c5 = number_format($aRow['costo_mora'],2);
                $c6 = number_format($aRow['monto_pago'],2);
                $fp = Functions::cambiaf_a_normal($aRow['fecha_reprogramada']);
                switch($aRow['estado']){
                    case 'E':
                        $estado = '<span class=\"label label-default\">'.CROPA_2.'</span>';
                        break;                    
                    case 'P':
                        $estado = '<span class=\"label label-warning\">'.CROPA_3.'</span>';
                        break;                 
                    default:
                        $estado = '';
                        break;
                }
                /*registros a mostrar*/
                $sOutput .= '["'.$c1.'","'.$c2.'","'.$c3.'","'.$c4.'","'.$c5.'","'.$c6.'","'.$fp.'","'.$estado.'" ';

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