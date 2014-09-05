<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 04-09-2014 07:09:53 
* Descripcion : seguimientoCotizacionController.php
* ---------------------------------------
*/    

class seguimientoCotizacionController extends Controller{

    public function __construct() {
        $this->loadModel("seguimientoCotizacion");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexSeguimientoCotizacion");
    }
    
    public function getGridSeguimientoCotizacion(){
        $editar   = Session::getPermiso('SEGCOED');
        $eliminar = Session::getPermiso('SEGCODE');
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->seguimientoCotizacionModel->getSeguimientoCotizacion();
        
        $num = Obj::run()->seguimientoCotizacionModel->_iDisplayStart;
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
            $p = 0;
            foreach ( $rResult as $key=>$aRow ){
                $p++;
                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_cotizacion']);
                
                
                
                $chk = '<input id=\"c_'.$p.'\" type=\"checkbox\" name=\"'.SEGCO.'chk_delete[]\" value=\"'.$encryptReg.'\">';
                /*datos de manera manual*/
                $sOutput .= '["'.$chk.'","'.$aRow['cotizacion_numero'].'","'.$aRow['nombrecompleto'].'","'.$aRow['fechacoti'].'","'.$aRow['meses_contrato'].'","'.Functions::cambiaf_a_normal($aRow['vencimiento']).'","'.  number_format($aRow['total'],2).'", ';

                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $selE = '';
                $selP = '';
                $selO = '';
                $selR = '';
                switch($aRow['estado']) {
                    case "E":
                        $selE = 'selected=\"selected\"';
                        break;
                    case "P":
                        $selP = 'selected=\"selected\"';
                        break;
                    case "O":
                        $selO = 'selected=\"selected\"';
                        break;
                    case "R":
                        $selR = 'selected=\"selected\"';
                        break;
                }
                $sOutput .= '"<div class=\"smart-form\">';
                $sOutput .= '<label class=\"select\">';
                $sOutput .= '<select id=\"'.SEGCO.$p.'lst_estado\" onchange=\"seguimientoCotizacion.getFormObservacion(\''.$encryptReg.'\',this.value,\''.$aRow['estado'].'\',\''.SEGCO.$p.'lst_estado\');\">';
                $sOutput .= '<option value=\"E\" '.$selE.'>Emitido</option>';
                $sOutput .= '<option value=\"P\" '.$selP.'>Procesado</option>';
                $sOutput .= '<option value=\"O\" '.$selO.'>Orden de servicio</option>';
                $sOutput .= '<option value=\"R\" '.$selR.'>Rechazado</option>';
                $sOutput .= '</select><i></i>';
                $sOutput .= '</label>';
                $sOutput .= ' </div>" ';
                
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
    
    /*carga formulario (newSeguimientoCotizacion.phtml) para nuevo registro: SeguimientoCotizacion*/
    public function getFormObservacion(){
        Obj::run()->View->render("formObservacion");
    }
    
    /*envia datos para grabar registro: SeguimientoCotizacion*/
    public function postObservacion(){
        $data = Obj::run()->seguimientoCotizacionModel->newSeguimientoCotizacion();
        
        echo json_encode($data);
    }
    
}

?>