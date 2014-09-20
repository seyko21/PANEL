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
        $this->loadController(array('modulo' => 'cotizacion', 'controller' => 'generarCotizacion'));  
    }
    
    public function index(){ 
        Obj::run()->View->render("indexSeguimientoCotizacion");
    }
    
    public function getGridSeguimientoCotizacion(){

        $exportarpdf   = Session::getPermiso('SEGCOEP');
        
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
                
                /*datos de manera manual*/
                $sOutput .= '["'.$aRow['cotizacion_numero'].'","'.$aRow['nombrecompleto'].'","'.$aRow['fechacoti'].'","'.$aRow['meses_contrato'].'","'.Functions::cambiaf_a_normal($aRow['vencimiento']).'","'.  number_format($aRow['mtotal'],2).'", ';

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
                        $et = 'label label-default';
                        $selE = 'selected=\"selected\"';
                        $estado = SEGCO_5;
                        break;
                    case "P":
                        $et = 'label label-success';
                        $selP = 'selected=\"selected\"';
                        $estado = SEGCO_6;
                        break;
                    case "O":
                        $selO = 'selected=\"selected\"';
                        $estado = SEGCO_7;
                        break;
                    case "R":
                        $et = 'label label-warning';
                        $selR = 'selected=\"selected\"';
                        $estado = SEGCO_8;
                        break;
                    case "A":
                        $et = 'label label-danger';
                        $selR = '';
                        $estado = SEGPA_9;
                        break;
                }
                if($aRow['estado'] == 'E'){
                    $sOutput .= '"<div class=\"smart-form\">';
                    $sOutput .= '<label class=\"select\">';
                    $sOutput .= '<select id=\"'.SEGCO.$p.'lst_estado\" onchange=\"seguimientoCotizacion.getFormObservacion(\''.$encryptReg.'\',this.value,\''.$aRow['estado'].'\',\''.SEGCO.$p.'lst_estado\',\''.$aRow['cotizacion_numero'].'\');\">';
                    $sOutput .= '<option value=\"E\" '.$selE.'>'.SEGCO_5.'</option>';
                    $sOutput .= '<option value=\"P\" '.$selP.'>'.SEGCO_6.'</option>';
    //                $sOutput .= '<option value=\"O\" '.$selO.'>'.SEGCO_7.'</option>';
                    $sOutput .= '<option value=\"R\" '.$selR.'>'.SEGCO_8.'</option>';
                    $sOutput .= '</select><i></i>';
                    $sOutput .= '</label>';
                    $sOutput .= ' </div>"';
                }else{
                    $sOutput .= '"<div class=\"'.$et.'\">'.$estado.'</div>"';
                }
                
                $sOutput .= ',"<div class=\"btn-group\">';                
                if($exportarpdf['permiso']){
                    $sOutput .= '<button type=\"button\" class=\"'.$exportarpdf['theme'].'\" title=\"'.$exportarpdf['accion'].'\" onclick=\"seguimientoCotizacion.postPDF(this,\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"'.$exportarpdf['icono'].'\"></i>';
                    $sOutput .= '</button>';
                }
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
    
    public function postPDF($n=''){
         $data = Obj::run()->generarCotizacionController->postPDF($n);
         return $data;
    }
    /*envia datos para grabar registro: SeguimientoCotizacion*/
    public function postObservacion(){
        $data = Obj::run()->seguimientoCotizacionModel->newSeguimientoCotizacion();
        
        echo json_encode($data);
    }
    
}

?>