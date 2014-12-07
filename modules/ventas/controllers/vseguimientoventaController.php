<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 18-11-2014 17:11:41 
* Descripcion : vseguimientoventaController.php
* ---------------------------------------
*/    

class vseguimientoventaController extends Controller{

    public function __construct() {
        $this->loadModel(array('modulo'=>'ventas','modelo'=>'vseguimientoventa'));
        $this->loadController(array('modulo'=>'ventas','controller'=>'generarVenta')); 
        $this->loadController(array('modulo'=>'ventas','controller'=>'cajaApertura'));    
    }
    
    public function index(){ 
        Obj::run()->View->render("indexVseguimientoventa");
    }
    
    public function getGridVseguimientoventa(){
        $pagar   = Session::getPermiso('VSEVEPG');
        $exportarpdf   = Session::getPermiso('VSEVEEP');
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->vseguimientoventaModel->getVseguimientoventa();
        
        $validarCaja = $this->getValidarCaja();
        
        $num = Obj::run()->vseguimientoventaModel->_iDisplayStart;
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
                $encryptReg = Aes::en($aRow['id_docventa']);
                
                 switch($aRow['estado']){
                    case 'E':
                        $estado = '<span class=\"label label-default\">'.SEGCO_5.'</span>';
                        break;                  
                    case 'A':
                        $estado = '<span class=\"label label-danger\">'.SEGPA_9.'</span>';
                        break;                 
                }
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $axion = '"<div class=\"btn-group\">';
                 
                if($pagar['permiso'] && $validarCaja['existe'] > 0 ){
                    $axion .= '<button type=\"button\" class=\"'.$pagar['theme'].'\" title=\"'.$pagar['accion'].'\" onclick=\"vseguimientoventa.getFormPagarVenta(this,\''.$encryptReg.'\',\''.$aRow['monto_saldo'].'\')\">';
                    $axion .= '    <i class=\"'.$pagar['icono'].'\"></i>';
                    $axion .= '</button>';
                }else{
                     $axion .= '<button type=\"button\" class=\"'.$pagar['theme'].'\" title=\"'.$pagar['accion'].'\" disabled >';
                    $axion .= '    <i class=\"'.$pagar['icono'].'\"></i>';
                    $axion .= '</button>';
                }
                if($exportarpdf['permiso'] == 1){
                    $axion .= '<button type=\"button\" class=\"'.$exportarpdf['theme'].'\" title=\"'.$exportarpdf['accion'].'\" onclick=\"vseguimientoventa.postPDF(this,\''.$encryptReg.'\',\''.$aRow['codigo_impresion'].'\')\">';
                    $axion .= '    <i class=\"'.$exportarpdf['icono'].'\"></i>';
                    $axion .= '</button>';
                }
                $axion .= ' </div>" ';
                
                /*registros a mostrar*/
                if($aRow['monto_saldo'] > 0){
                    $saldo = '<span class=\"badge bg-color-red\">'.number_format($aRow['monto_saldo'],2).'</span>';
                }else{
                    $saldo = number_format($aRow['monto_saldo'],2);
                }
                
                $idPersona = Aes::en($aRow['id_persona']);
                $nombre = '<a href=\"javascript:;\" onclick=\"persona.getDatosPersonales(\''.$idPersona.'\');\">'.$aRow['nombre_descripcion'].'</a>';
           
                $sOutput .= '["'.($num++).'","'.$aRow['codigo_impresion'].'","'.$nombre.'","'.  Functions::cambiaf_a_normal($aRow['fecha']).'","'.$aRow['moneda'].'","'.number_format($aRow['monto_total'],2).'","'.$saldo.'","'.$estado.'",'.$axion.' ';
                
                $sOutput .= '],';
                
            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }
    
    /*Formulario para pagar Cuenta - Saldo de Cliente*/
    public function getFormPagarVenta(){
        Obj::run()->View->render("formPagarVenta");
    }

    /*envia datos para editar registro: Vseguimientoventa*/
    public function postPagarVenta(){
        $data = Obj::run()->vseguimientoventaModel->newPagoVenta();
        
        echo json_encode($data);
    }
    
     public function postPDF(){
        $data = Obj::run()->generarVentaController->postPDF();
        
        return $data;
     }
    
     public function getValidarCaja(){ 
        $data = Obj::run()->cajaAperturaController->getValidarCaja();        
        return $data;
    }   
}

?>