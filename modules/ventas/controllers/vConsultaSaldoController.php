<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 18-11-2014 22:11:42 
* Descripcion : vConsultaSaldoController.php
* ---------------------------------------
*/    

class vConsultaSaldoController extends Controller{

    public function __construct() {
        $this->loadModel(array('modulo'=>'ventas','modelo'=>'vConsultaSaldo'));
        $this->loadController(array('modulo'=>'ventas','controller'=>'generarVenta')); 
        
    }
    
    public function index(){ 
        Obj::run()->View->render("indexVConsultaSaldo");
    }
    
    public function getGridVConsultaSaldo(){        
       $exportarpdf   = Session::getPermiso('VCSCLEP');
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->vConsultaSaldoModel->getVConsultaSaldo();
        
        $num = Obj::run()->vConsultaSaldoModel->_iDisplayStart;
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
                $encryptReg = Aes::en($aRow['id_docventa']);
                
                 switch($aRow['estado']){
                    case 'E':
                        $estado = '<span class=\"label label-default\">'.SEGCO_5.'</span>';
                        break;                  
                    case 'A':
                        $estado = '<span class=\"label label-danger\">'.SEGPA_9.'</span>';
                        break;                 
                }
                
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $axion = '"<div class=\"btn-group\">';
                 
                if($exportarpdf['permiso'] == 1){
                    $axion .= '<button type=\"button\" class=\"'.$exportarpdf['theme'].'\" title=\"'.$exportarpdf['accion'].'\" onclick=\"vConsultaSaldo.postPDF(this,\''.$encryptReg.'\',\''.$aRow['codigo_impresion'].'\')\">';
                    $axion .= '    <i class=\"'.$exportarpdf['icono'].'\"></i>';
                    $axion .= '</button>';
                }
                
                $axion .= ' </div>" ';
                if ($aRow['monto_saldo'] > 0):
                    $saldo = '<span class=\"badge bg-color-red\">'.number_format($aRow['monto_saldo'],2).'</span>';
                else :
                     $saldo = number_format($aRow['monto_saldo'],2);
                endif;
                                
                $idPersona = Aes::en($aRow['id_persona']);
                $nombre = '<a href=\"javascript:;\" onclick=\"persona.getDatosPersonales(\''.$idPersona.'\');\">'.$aRow['nombre_descripcion'].'</a>';
           
                $pagado = number_format($aRow['monto_asignado'],2);
                /*registros a mostrar*/
                $sOutput .= '["'.($num++).'","'.$aRow['codigo_impresion'].'","'.$nombre.'","'.  Functions::cambiaf_a_normal($aRow['fecha']).'","'.$aRow['moneda'].'","'.number_format($aRow['monto_total'],2).'","'.$pagado.'","'.$saldo.'","'.$estado.'",'.$axion.' ';

                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }
    
    public function getGridIndexConsultaSaldo(){        
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->vConsultaSaldoModel->getIndexConsultaSaldo();
        
        $num = Obj::run()->vConsultaSaldoModel->_iDisplayStart;
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
                $encryptReg = Aes::en($aRow['id_docventa']);
                
                if ($aRow['monto_saldo'] > 0):
                    $saldo = '<span class=\"badge bg-color-red\">'.number_format($aRow['monto_saldo'],2).'</span>';
                else :
                     $saldo = number_format($aRow['monto_saldo'],2);
                endif;
                                
                $nombre = $aRow['nombre_descripcion'];
                /*registros a mostrar*/
                $sOutput .= '["'.$aRow['codigo_impresion'].'","'.$nombre.'","'.  Functions::cambiaf_a_normal($aRow['fecha']).'","'.$aRow['moneda'].'","'.number_format($aRow['monto_total'],2).'","'.$saldo.'" ';

                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }    
    
    public function postPDF(){
        $data = Obj::run()->generarVentaController->postPDF();
        
        return $data;
     }
    
}

?>