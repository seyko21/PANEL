<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 25-09-2014 23:09:14 
* Descripcion : movimientosOSController.php
* ---------------------------------------
*/    

class movimientosOSController extends Controller{

    public function __construct() {
        $this->loadModel("movimientosOS");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexMovimientosOS");
    }
    
    public function getGridMovimientosOS(){
        
        $consultar   = Session::getPermiso('MOVOSCC');
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->movimientosOSModel->getMovimientosOS();
        
        $num = Obj::run()->movimientosOSModel->_iDisplayStart;
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
                
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $axion = '"<div class=\"btn-group\">';
                 
                if($consultar['permiso']){
                    $axion .= '<button type=\"button\" class=\"'.$consultar['theme'].'\" title=\"'.$consultar['accion'].'\" onclick=\"cotizacionVendedor.getConsulta(\''.$encryptReg.'\')\">';
                    $axion .= '    <i class=\"'.$consultar['icono'].'\"></i>';
                    $axion .= '</button>';
                }                

                $axion .= ' </div>" ';                                
                
                $m = number_format($aRow['monto_total_descuento'],2);
                $i = number_format($aRow['monto_impuesto'],2);
                $ig = number_format($aRow['ingresos'],2);
                $eg = number_format($aRow['egresos'],2);                
                $cv = number_format($aRow['comision_vendedor'],2);
                $ut1 = number_format($aRow['utilidad_principal'],2);
                $oi = number_format($aRow['otros_ingresos'],2);                
                $oe = number_format($aRow['otros_egresos'],2);
                $ut2 = number_format($aRow['utilidad_secundaria'],2);
                /*registros a mostrar*/
                $sOutput .= '["'.$aRow['orden_numero'].'","'.$aRow['fecha_contrato'].'","'.$m.'","'.$i.'","'.$ig.'","'.$eg.'","'.$cv.'","<b>'.$ut1.'</b>","'.$oi.'","'.$oe.'","<b>'.$ut2.'</b>",'.$axion.' ';

                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }
    
    /*carga formulario (newMovimientosOS.phtml) para nuevo registro: MovimientosOS*/
    public function getFormNewMovimientosOS(){
        Obj::run()->View->render("formNewMovimientosOS");
    }
    
    /*carga formulario (editMovimientosOS.phtml) para editar registro: MovimientosOS*/
    public function getFormEditMovimientosOS(){
        Obj::run()->View->render("formEditMovimientosOS");
    }
    
    /*busca data para editar registro: MovimientosOS*/
    public static function findMovimientosOS(){
        $data = Obj::run()->movimientosOSModel->findMovimientosOS();
            
        return $data;
    }
    
    /*envia datos para grabar registro: MovimientosOS*/
    public function postNewMovimientosOS(){
        $data = Obj::run()->movimientosOSModel->newMovimientosOS();
        
        echo json_encode($data);
    }
    
    /*envia datos para editar registro: MovimientosOS*/
    public function postEditMovimientosOS(){
        $data = Obj::run()->movimientosOSModel->editMovimientosOS();
        
        echo json_encode($data);
    }
    
    /*envia datos para eliminar registro: MovimientosOS*/
    public function postDeleteMovimientosOS(){
        $data = Obj::run()->movimientosOSModel->deleteMovimientosOS();
        
        echo json_encode($data);
    }
    
    /*envia datos para eliminar registros: MovimientosOS*/
    public function postDeleteMovimientosOSAll(){
        $data = Obj::run()->movimientosOSModel->deleteMovimientosOSAll();
        
        echo json_encode($data);
    }
    
}

?>