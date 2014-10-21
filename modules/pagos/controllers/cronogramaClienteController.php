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
    
    public function getConsulta(){
        Obj::run()->View->render('consultarCronogramaCliente');
    }
    
    public function getGridCronogramaCliente(){
        $consultar   = Session::getPermiso('CRPGCC');
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
                $encryptReg = Aes::en($aRow['id_ordenservicio']);
                               
                $c1 = $aRow['orden_numero'];
                $c2 = $aRow['cliente'];                
                $c3 = $aRow['fecha'];
                $c4 = number_format($aRow['mora'],2);
                $c5 = number_format($aRow['monto_total'],2);
                
                switch($aRow['estado']){
                    case 'E':
                        $estado = '<span class=\"label label-default\">'.SEGCO_5.'</span>';
                        break;
                    case 'T':
                        $estado = '<span class=\"label label-success\">'.SEGPA_8.'</span>';
                        break;
                    case 'P':
                        $estado = '<span class=\"label label-warning\">'.SEGPA_7.'</span>';
                        break;
                    case 'A':
                        $estado = '<span class=\"label label-danger\">'.SEGPA_9.'</span>';
                        break;
                    case 'F':
                        $estado = '<span class=\"label label-info\">'.SEGPA_29.'</span>';
                        break;
                }
                
                $axion = '"<div class=\"btn-group\">';
                 
                if($consultar['permiso']){
                    $axion .= '<button type=\"button\" class=\"'.$consultar['theme'].'\" title=\"'.$consultar['accion'].'\" onclick=\"cronogramaCliente.getConsulta(\'' . $encryptReg . '\',\'' . $c1 . '\')\"> ';
                    $axion .= '    <i class=\"'.$consultar['icono'].'\"></i>';
                    $axion .= '</button>';
                }else{
                    $axion .= '<button type=\"button\" class=\"'.$consultar['theme'].'\" title=\"'.$consultar['accion'].'\" disabled> ';
                    $axion .= '    <i class=\"'.$consultar['icono'].'\"></i>';
                    $axion .= '</button>';
                }
       
                $axion .= ' </div>" ';
                
                /*registros a mostrar*/
                $sOutput .= '["'.($num++).'","'.$c1.'","'.$c2.'","'.$c3.'","'.$c4.'","'.$c5.'","'.$estado.'",'.$axion.' ';

                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }   
 
    public function getGridCuotas(){
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->cronogramaClienteModel->getGridCuotas();
        
        if(!isset($rResult['error'])){  
            $iTotal         = isset($rResult[0]['total'])?$rResult[0]['total']:0;
            
            $sOutput = '{';
            $sOutput .= '"sEcho": '.intval($sEcho).', ';
            $sOutput .= '"iTotalRecords": '.$iTotal.', ';
            $sOutput .= '"iTotalDisplayRecords": '.$iTotal.', ';
            $sOutput .= '"aaData": [ ';     
            
            $noDelete = 0;
            foreach ( $rResult as $aRow ){
                
                switch($aRow['estado']){
                    case 'E':
                        $estado = '<span class=\"label label-default\" style=\"text-align:center;color:#fff\">'.CROPA_2.'</span>';
                        break;
                    case 'P':
                        $noDelete = 1;
                        $estado = '<span class=\"label label-success\" style=\"text-align:center;color:#fff\">'.CROPA_3.'</span>';
                        break;
                    case 'R':
                        $estado = '<span class=\"label label-warning\" style=\"text-align:center;color:#fff\">'.CROPA_4.'</span>';
                        break;
                    default:
                        $estado = '';
                        break;
                }
                
                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_compromisopago']);
                
                /*registros a mostrar*/
                $sOutput .= '["'.$aRow['numero_cuota'].'","'.number_format($aRow['monto_pago'], 2).'","'.$aRow['fechapago'].'","'.number_format($aRow['costo_mora'],2).'","'.$estado.'" ';
                                               
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