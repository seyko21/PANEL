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
                $estado = '';
                switch($aRow['estado']) {
                    case "E":
                        $selE = 'selected=\"selected\"';
                        $estado = SEGCO_5;
                        break;
                    case "P":
                        $selP = 'selected=\"selected\"';
                        $estado = SEGCO_6;
                        break;
                    case "O":
                        $selO = 'selected=\"selected\"';
                        $estado = SEGCO_7;
                        break;
                    case "R":
                        $selR = 'selected=\"selected\"';
                        $estado = SEGCO_8;
                        break;
                }
                if($aRow['estado'] == 'E'){
                    $sOutput .= '"<div class=\"smart-form\">';
                    $sOutput .= '<label class=\"select\">';
                    $sOutput .= '<select id=\"'.SEGCO.$p.'lst_estado\" onchange=\"seguimientoCotizacion.getFormObservacion(\''.$encryptReg.'\',this.value,\''.$aRow['estado'].'\',\''.SEGCO.$p.'lst_estado\');\">';
                    $sOutput .= '<option value=\"E\" '.$selE.'>'.SEGCO_5.'</option>';
                    $sOutput .= '<option value=\"P\" '.$selP.'>'.SEGCO_6.'</option>';
    //                $sOutput .= '<option value=\"O\" '.$selO.'>'.SEGCO_7.'</option>';
                    $sOutput .= '<option value=\"R\" '.$selR.'>'.SEGCO_8.'</option>';
                    $sOutput .= '</select><i></i>';
                    $sOutput .= '</label>';
                    $sOutput .= ' </div>" ';
                }else{
                    $sOutput .= '"<div class=\"label label-success\">'.$estado.'</div>" ';
                }
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