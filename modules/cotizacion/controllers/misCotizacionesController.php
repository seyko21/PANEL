<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 20-09-2014 23:09:13 
* Descripcion : misCotizacionesController.php
* ---------------------------------------
*/    

class misCotizacionesController extends Controller{

    public function __construct() {
        $this->loadModel("misCotizaciones");
        $this->loadController(array('modulo' => 'cotizacion', 'controller' => 'generarCotizacion')); 
    }
    
    public function index(){ 
        Obj::run()->View->render("indexMisCotizaciones");
    }
    
    public function getGridMisCotizaciones(){
        $exportarpdf   = Session::getPermiso('MISCOEP');
        $consultar   = Session::getPermiso('MISCOCC');
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->misCotizacionesModel->getGridCotizacion();
        
        $num = Obj::run()->misCotizacionesModel->_iDisplayStart;
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
                $numCotizacion = $aRow['cotizacion_numero'];
                
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
                               
                $sOutput .= '"<div class=\"'.$et.'\">'.$estado.'</div>"';
                                
                $sOutput .= ',"<div class=\"btn-group\">';    
                if($consultar['permiso']){
                    $sOutput .= '<button type=\"button\" class=\"'.$consultar['theme'].'\" title=\"'.$consultar['accion'].'\" onclick=\"cotizacionVendedor.getConsulta(\''.$encryptReg.'\',\''.$numCotizacion.'\')\">';
                    $sOutput .= '    <i class=\"'.$consultar['icono'].'\"></i>';
                    $sOutput .= '</button>';
                }  
                if($exportarpdf['permiso']){
                    $sOutput .= '<button type=\"button\" class=\"'.$exportarpdf['theme'].'\" title=\"'.$exportarpdf['accion'].'\" onclick=\"misCotizaciones.postPDF(this,\''.$encryptReg.'\')\">';
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
    
    public function postPDF($n=''){
         $data = Obj::run()->generarCotizacionController->postPDF($n);
         return $data;
    }
    
}

?>