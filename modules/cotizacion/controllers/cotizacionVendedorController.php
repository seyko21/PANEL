<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 22-09-2014 16:09:41 
* Descripcion : cotizacionVendedorController.php
* ---------------------------------------
*/    

class cotizacionVendedorController extends Controller{

    public function __construct() {
        $this->loadModel("cotizacionVendedor");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexCotizacionVendedor");
    }
    
    public function getGridCotizacionVendedor(){
        $consultar   = Session::getPermiso('COXVECC');
        $exportarpdf = Session::getPermiso('COXVEEP');
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->cotizacionVendedorModel->getCotizacionVendedor();
        
        $num = Obj::run()->cotizacionVendedorModel->_iDisplayStart;
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
                $encryptReg = Aes::en($aRow['id_cotizacion']);
                
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
                $xestado = '"<div class=\"'.$et.'\">'.$estado.'</div>"';
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $axion = '"<div class=\"btn-group\">';
                 
                if($consultar['permiso']){
                    $axion .= '<button type=\"button\" class=\"'.$consultar['theme'].'\" title=\"'.$consultar['accion'].'\" onclick=\"cotizacionVendedor.getFormCotizacion(this,\''.$encryptReg.'\')\">';
                    $axion .= '    <i class=\"'.$consultar['icono'].'\"></i>';
                    $axion .= '</button>';
                }                
                if($exportarpdf['permiso']){
                    $axion .= '<button type=\"button\" class=\"'.$exportarpdf['theme'].'\" title=\"'.$exportarpdf['accion'].'\" onclick=\"cotizacionVendedor.postPDF(this,\''.$encryptReg.'\')\">';
                    $axion .= '    <i class=\"'.$exportarpdf['icono'].'\"></i>';
                    $axion .= '</button>';
                }
                
                $axion .= ' </div>" ';
                
                /*registros a mostrar*/
                $sOutput .= '["'.$aRow['cotizacion_numero'].'","'.$aRow['dni'].'","'.$aRow['vendedor'].'","'.$aRow['fechacoti'].'","'.$aRow['meses_contrato'].'","'.  number_format($aRow['mtotal'],2).'",'.$xestado.','.$axion.' ';
                
                

                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }
    
    /*carga formulario (newCotizacionVendedor.phtml) para nuevo registro: CotizacionVendedor*/
    public function getFormNewCotizacionVendedor(){
        Obj::run()->View->render("formNewCotizacionVendedor");
    }
    
    
}

?>