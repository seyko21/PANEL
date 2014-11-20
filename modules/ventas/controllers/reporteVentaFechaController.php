<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 20-11-2014 00:11:17 
* Descripcion : reporteVentaFechaController.php
* ---------------------------------------
*/    

class reporteVentaFechaController extends Controller{

    public function __construct() {
        $this->loadModel("reporteVentaFecha");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexReporteVentaFecha");
    }
    
    public function getGridReporteVentaFecha(){
        $consultar   = Session::getPermiso('VRPT2CC');
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->reporteVentaFechaModel->getReporteVentaFecha();
        
        $num = Obj::run()->reporteVentaFechaModel->_iDisplayStart;
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
                $encryptReg = Aes::en($aRow['fecha']);
                
                
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $axion = '"<div class=\"btn-group\">';
                 
                if($consultar['permiso']){
                    $axion .= '<button type=\"button\" class=\"'.$consultar['theme'].'\" title=\"'.$consultar['accion'].'\" onclick=\"reporteVentaFecha.getFormConsultarVenta(this,\''.$encryptReg.'\')\">';
                    $axion .= '    <i class=\"'.$consultar['icono'].'\"></i>';
                    $axion .= '</button>';
                }
                
                $axion .= ' </div>" ';                
                /*registros a mostrar*/
                $sOutput .= '["'.($num++).'","'.  Functions::cambiaf_a_normal($aRow['fecha']).'","'.$aRow['numero_doc'].'","'.$aRow['moneda'].'","'.number_format($aRow['monto'],2).'",'.$axion.' ';

                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }
    
    /*carga formulario (newReporteVentaFecha.phtml) para nuevo registro: ReporteVentaFecha*/
    public function getFormConsultaVenta(){
        Obj::run()->View->render("formNewReporteVentaFecha");
    }
    
    
    
}

?>