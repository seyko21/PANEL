<?php
class cronogramaPagoController extends Controller{
    
    public function __construct() {
        $this->loadModel("cronogramaPago");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexCronogramaPago");
    }
    
    public function getGridOrdenes(){
        $pagar   = Session::getPermiso('CROPAPG');
        $generar  = Session::getPermiso('CROPAGN');
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->cronogramaPagoModel->getOrdenes();
        
        $num = Obj::run()->cronogramaPagoModel->_iDisplayStart;
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
                $idPersona = Aes::en($aRow['id_persona']);
                
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
                $cronograma = $aRow['cronogramaTotal'];
                if ($aRow['monto_total'] > $cronograma ):
                    $c6 = '<span class=\"badge bg-color-red\">S/.'.number_format($aRow['monto_total'],2).'</span>';    
                else:
                    $c6 = 'S/.'.number_format($aRow['monto_total'],2);
                endif;
                
                /*registros a mostrar*/
                $sOutput .= '["'.($num++).'","'.$aRow['orden_numero'].'","<a href=\"javascript:;\" onclick=\"persona.getDatosPersonales(\''.$idPersona.'\');\">'.$aRow['cliente'].'</a>","'.$aRow['creador'].'","'.$estado.'","'.$aRow['fecha'].'","'.number_format($aRow['mora'],2).'","'.$c6.'",';
                
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';
                if($generar['permiso']){
                    if ($aRow['estado'] != 'F'):
                        $sOutput .= '<button type=\"button\" class=\"'.$generar['theme'].'\" title=\"'.$generar['accion'].' '.GNOSE_2.'\" onclick=\"generarOrden.getFormCronograma(this,\''.$encryptReg.'\',\''.$aRow['monto_total'].'\',\''.$aRow['orden_numero'].'\')\">';
                        $sOutput .= '    <i class=\"'.$generar['icono'].'\"></i>';
                        $sOutput .= '</button>';
                    else:
                        $sOutput .= '<button type=\"button\" class=\"'.$generar['theme'].'\" title=\"'.$generar['accion'].' '.GNOSE_2.'\" disabled >';
                        $sOutput .= '    <i class=\"'.$generar['icono'].'\"></i>';
                        $sOutput .= '</button>';
                    endif;
                } 
                if($pagar['permiso']){
                    $sOutput .= '<button type=\"button\" class=\"'.$pagar['theme'].'\" title=\"'.$pagar['accion'].'\" onclick=\"cronogramaPago.getFormPagarOrden(this,\''.$encryptReg.'\',\''.$aRow['orden_numero'].'\')\">';
                    $sOutput .= '    <i class=\"'.$pagar['icono'].'\"></i>';
                    $sOutput .= '</button>';
                }
                $sOutput .= '</div>"';
                
                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }
    
    public function getFormPagarOrden(){
        Obj::run()->View->render("formPagarOrden");
    }
    
    public function getFormPagarOrdenParametros(){
        Obj::run()->View->render("formPagarOrdenParametros");
    }
    
    public function getFormReprogramar(){
        Obj::run()->View->render("formReprogramar");
    }
    
    public function getTableCronograma(){
        Obj::run()->View->render("tableCronograma");
    }
    
    public function getCronograma(){
        $data = Obj::run()->cronogramaPagoModel->getCronograma();
            
        return $data;
    }
    
    public function postPagarOrden(){
        $data = Obj::run()->cronogramaPagoModel->postPagarOrden();
        
        echo json_encode($data);
    }
    
    public function postReprogramar(){
        $data = Obj::run()->cronogramaPagoModel->postReprogramar();
        
        echo json_encode($data);
    }
    
    public function postAnularPago(){
        $data = Obj::run()->cronogramaPagoModel->postAnularPago();
        
        echo json_encode($data);
    }
    
}