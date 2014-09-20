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
                
                switch($aRow['estado']){
                    case 'E':
                        $estado = '<span class=\"label label-default\">'.SEGPA_6.'</span>';
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
                    default:
                        $estado = '';
                        break;
                }
                /*registros a mostrar*/
                $sOutput .= '["'.($num++).'","'.$aRow['orden_numero'].'","'.$aRow['nombrecompleto'].'","'.$aRow['cliente'].'","'.$estado.'","'.$aRow['fecha'].'","'.number_format($aRow['mora'],2).'","'.number_format($aRow['monto_total'],2).'",';
                
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';
                 
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
    
}