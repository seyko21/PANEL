<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 26-09-2014 15:09:21 
* Descripcion : saldoClienteController.php
* ---------------------------------------
*/    

class saldoClienteController extends Controller{

    public function __construct() {
        $this->loadModel(array('modulo'=>'pagos','modelo'=>'saldoCliente'));
        $this->loadController(array('modulo'=>'ordenservicio','controller'=>'compromisoPagar'));                   
    }
    
    public function index(){ 
        Obj::run()->View->render("indexSaldoCliente");
    }    
    public function getGridSaldoCliente(){
        $exportarpdf   = Session::getPermiso('SACLIEP');
        $exportarexcel= Session::getPermiso('SACLIEX');
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->saldoClienteModel->getSaldoCliente();
                
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
                
                $axion = '"<div class=\"btn-group\">';
                                 
                if($exportarpdf['permiso']){
                    if ($aRow['estado'] == 'P'){
                        $axion .= '<button type=\"button\" class=\"'.$exportarpdf['theme'].'\" title=\"'.$exportarpdf['accion'].' pagos \" onclick=\"saldoCliente.postPDF(this,\'' . $encryptReg . '\')\"> ';
                        $axion .= '    <i class=\"'.$exportarpdf['icono'].'\"></i>';
                        $axion .= '</button>';
                    }else{
                        $axion .= '<button type=\"button\" class=\"'.$exportarpdf['theme'].'\" title=\"'.$exportarpdf['accion'].'\" disabled=\"disabled\" > ';
                        $axion .= '    <i class=\"'.$exportarpdf['icono'].'\"></i>';
                        $axion .= '</button>';
                    }
                }
                if($exportarexcel['permiso']){
                        if ($aRow['estado'] == 'P'){
                            $axion .= '<button type=\"button\" class=\"'.$exportarexcel['theme'].'\" title=\"'.$exportarexcel['accion'].' pagos \" onclick=\"saldoCliente.postExcel(this,\'' . $encryptReg . '\')\"> ';
                            $axion .= '    <i class=\"'.$exportarexcel['icono'].'\"></i>';
                            $axion .= '</button>';
                        }else{
                            $axion .= '<button type=\"button\" class=\"'.$exportarexcel['theme'].'\" title=\"'.$exportarexcel['accion'].'\" disabled=\"disabled\" > ';
                            $axion .= '    <i class=\"'.$exportarexcel['icono'].'\"></i>';
                            $axion .= '</button>';
                        }
                }
               
                $axion .= ' </div>" ';
                              
                $c1 = $aRow['numero_cuota'];
                $c2 = $aRow['orden_numero'];
                $c3 = Functions::cambiaf_a_normal($aRow['fecha_programada']);
                $idPersona = Aes::en($aRow['id_persona']);
                $c4 = '<a href=\"javascript:;\" onclick=\"persona.getDatosPersonales(\''.$idPersona.'\');\">'.$aRow['descripcion_cliente'].'</a>';
                $c5 = number_format($aRow['costo_mora'],2);
                $c6 = 'S/.'.number_format($aRow['monto_pago'],2);
                $fp = Functions::cambiaf_a_normal($aRow['fecha_pagoreal']);
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
                $sOutput .= '["'.$c1.'","'.$c2.'","'.$c3.'","'.$c4.'","'.$c5.'","'.$c6.'","'.$fp.'","'.$estado.'",'.$axion.' ';

                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }
    
    public function getGridIndexSaldoCliente(){
      
       $sEcho  =   $this->post('sEcho');
        
        $rResult = Obj::run()->saldoClienteModel->getIndexSaldoCliente();
        
        if(!isset($rResult['error'])){  
            $iTotal         = isset($rResult[0]['total'])?$rResult[0]['total']:0;
            
            $sOutput = '{';
            $sOutput .= '"sEcho": '.intval($sEcho).', ';
            $sOutput .= '"iTotalRecords": '.$iTotal.', ';
            $sOutput .= '"iTotalDisplayRecords": '.$iTotal.', ';
            $sOutput .= '"aaData": [ ';
            foreach ( $rResult as $key=>$aRow ){
                /*datos de manera manual*/
                                
                $c1 = $aRow['numero_cuota'];
                $c2 = $aRow['orden_numero'];
                $c3 = $aRow['cliente'];
                $c4 = Functions::cambiaf_a_normal($aRow['fecha_programada']);
                $c5 = 'S/.'.number_format($aRow['monto_pago'],2);
                
                              
                $sOutput .= '["'.$c1.'","'.$c2.'","'.$c3.'","'.$c4.'","'.$c5.'" ';
                               
                $sOutput = substr_replace( $sOutput, "", -1 );
                $sOutput .= '],';
            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;
    }                   
    
    public function getIndexSaldoClienteProximo(){
      
       $sEcho  =   $this->post('sEcho');
        
        $rResult = Obj::run()->saldoClienteModel->getIndexSaldoClienteProximo();
        
        if(!isset($rResult['error'])){  
            $iTotal         = isset($rResult[0]['total'])?$rResult[0]['total']:0;
            
            $sOutput = '{';
            $sOutput .= '"sEcho": '.intval($sEcho).', ';
            $sOutput .= '"iTotalRecords": '.$iTotal.', ';
            $sOutput .= '"iTotalDisplayRecords": '.$iTotal.', ';
            $sOutput .= '"aaData": [ ';
            foreach ( $rResult as $key=>$aRow ){
                /*datos de manera manual*/
                                
                $c1 = $aRow['numero_cuota'];
                $c2 = $aRow['orden_numero'];
                $c3 = $aRow['cliente'];
                $c4 = Functions::cambiaf_a_normal($aRow['fecha_programada']);
                $c5 = 'S/.'.number_format($aRow['monto_pago'],2);
                
                $sOutput .= '["'.$c1.'","'.$c2.'","'.$c3.'","'.$c4.'","'.$c5.'" ';
                               
                $sOutput = substr_replace( $sOutput, "", -1 );
                $sOutput .= '],';
            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;
    }          
    
    
    public function postPDF($n=''){
         return Obj::run()->compromisoPagarController->postPDF($n);
     }
     public function postExcel($n=''){
         return Obj::run()->compromisoPagarController->postExcel();
     }
}

?>