<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 20-09-2014 20:09:46 
* Descripcion : terminarContratoController.php
* ---------------------------------------
*/    

class terminarContratoController extends Controller{

    public function __construct() {
        $this->loadModel("terminarContrato");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexTerminarContrato");
    }
    
    public function getGridTerminarContrato(){
        $editar   = Session::getPermiso('TERCOED');
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->terminarContratoModel->getTerminarContrato();
        
        $num = Obj::run()->terminarContratoModel->_iDisplayStart;
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
                
                /*campo que maneja los estados, para el ejemplo aqui es ACTIVO, coloca tu campo*/
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
                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_ordenservicio']);
                $idPersona = Aes::en($aRow['id_persona']);
                
                /*registros a mostrar*/
                $sOutput .= '["'.$aRow['orden_numero'].'","'.$aRow['cotizacion_numero'].'","<a href=\"javascript:;\" onclick=\"persona.getDatosPersonales(\''.$idPersona.'\');\">'.$aRow['cliente'].'</a>","'.$aRow['fecha'].'","'.number_format($aRow['monto_total'],2).'","'.$estado.'",';

                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';
                 
                if($editar['permiso']){
                    $sOutput .= '<button type=\"button\" class=\"'.$editar['theme'].'\" title=\"'.TERCO_2.'\" onclick=\"terminarContrato.getFormTerminarContrato(this,\''.$encryptReg.'\',\''.$aRow['orden_numero'].'\')\">';
                    $sOutput .= '    <i class=\"'.$editar['icono'].'\"></i>';
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
    
    public function getFormTerminarContrato(){
        Obj::run()->View->render("formTerminarContrato");
    }
    
    /*envia datos para grabar registro: TerminarContrato*/
    public function postTerminarContrato(){
        $data = Obj::run()->terminarContratoModel->terminarContrato();
        
        echo json_encode($data);
    }
    
}

?>