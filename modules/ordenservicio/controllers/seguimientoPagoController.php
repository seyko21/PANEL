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
    
    public function getConsulta(){ 
        Obj::run()->View->render("consultarTiempoOrden");
    }
    
    public function getGridSeguimientoPago(){
        $pagar   = Session::getPermiso('SEGPAPG');
        $consultar   = Session::getPermiso('SEGPACC');
        
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
                /*registros a mostrar*/
                $sOutput .= '["'.($num++).'","'.$aRow['orden_numero'].'","<a href=\"javascript:;\" onclick=\"persona.getDatosPersonales(\''.$idPersona.'\');\">'.$aRow['cliente'].'</a>","'.$aRow['creador'].'","'.$estado.'","'.$aRow['fecha'].'","S/.'.number_format($aRow['monto_total'],2).'",';
                
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
                if($consultar['permiso']){
                    $sOutput .= '<button type=\"button\" class=\"'.$consultar['theme'].'\" title=\"'.$consultar['accion'].'\" onclick=\"seguimientoPago.getConsulta(\''.$encryptReg.'\',\''.$aRow['orden_numero'].'\')\">';
                    $sOutput .= '    <i class=\"'.$consultar['icono'].'\"></i>';
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
    
    public function getGridTiempoOrden(){
       
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->seguimientoPagoModel->getGridTiempoOrden();
        
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
                                               
                switch($aRow['estado']) {
                    case "E":
                        $et = 'label label-default';
                        $estado = SEGCO_5;
                        break;
                    case "P":
                        $et = 'label label-warning';
                        $estado = SEGPA_7;
                        break;                    
                    case "T":
                        $et = 'label label-success';
                        $estado = SEGPA_8;
                        break;
                    case "F":
                        $et = 'label label-info';
                        $estado = SEGPA_29;
                        break;
                    case "A":
                        $et = 'label label-danger';
                        $estado = SEGPA_9;
                        break;   
                    case "R":
                        $et = 'label bg-color-magenta txt-color-white';
                        $estado = SEGPA_30;
                        break;
                }
                $xestado = '"<div class=\"'.$et.'\" style=\"text-align:center;color:#fff\">'.$estado.'</div>"';
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */                                            
                
                /*registros a mostrar*/
                $sOutput .= '["'.($num++).'","'.$aRow['fecha_estado'].'","'.$aRow['observacion'].'",'.$xestado.' ';
                
                
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
    
    public function getCronograma(){
        $data = Obj::run()->seguimientoPagoModel->getCronograma();
            
        return $data;
    }
    
    public function postPagarOrden(){
        $data = Obj::run()->seguimientoPagoModel->postPagarOrden();
        
        echo json_encode($data);
    }
    
}

?>