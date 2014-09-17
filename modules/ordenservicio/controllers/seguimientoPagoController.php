<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 16-09-2014 22:09:43 
* Descripcion : seguimientoPagoController.php
* ---------------------------------------
*/    

class seguimientoPagoController extends Controller{

    public function __construct() {
        $this->loadModel("seguimientoPago");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexSeguimientoPago");
    }
    
    public function getGridSeguimientoPago(){
        $pagar   = Session::getPermiso('SEGPAPG');
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->seguimientoPagoModel->getSeguimientoPago();
        
        $num = Obj::run()->seguimientoPagoModel->_iDisplayStart;
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
                
                /*registros a mostrar*/
                $sOutput .= '["'.($num++).'","'.$aRow['orden_numero'].'","'.$aRow['nombrecompleto'].'","'.$aRow['cliente'].'","'.number_format($aRow['monto_total'],2).'",';
                
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';
                 
                if($pagar['permiso']){
                    $sOutput .= '<button type=\"button\" class=\"'.$pagar['theme'].'\" title=\"'.$pagar['accion'].'\" onclick=\"seguimientoPago.getFormPagarOrden(this,\''.$encryptReg.'\',\''.$aRow['orden_numero'].'\')\">';
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
    
    public static function getCronograma(){
        $data = Obj::run()->seguimientoPagoModel->getCronograma();
            
        return $data;
    }
    
    public function postPagarOrden(){
        $data = Obj::run()->seguimientoPagoModel->postPagarOrden();
        
        echo json_encode($data);
    }
    
}

?>