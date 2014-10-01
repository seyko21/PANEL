<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 28-09-2014 00:09:34 
* Descripcion : pagosRecibidosController.php
* ---------------------------------------
*/    

class pagosRecibidosController extends Controller{

    public function __construct() {
        $this->loadModel("pagosRecibidos");
        $this->loadController(array("modulo"=>"pagos","controller"=>"saldoVendedor"));         
    }
    
    public function index(){ 
        Obj::run()->View->render("indexPagosRecibidos");
    }
    
    public function getGridPagosRecibidos(){
        $consultar   = Session::getPermiso('PAGRECC');
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->pagosRecibidosModel->getPagosRecibidos();
        
        $num = Obj::run()->pagosRecibidosModel->_iDisplayStart;
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
                $encryptReg = Aes::en($aRow['id_comision']);
                                
                $c2 = $aRow['orden_numero'];
                $c3 = $aRow['nombrecompleto'];
                $c4 = $aRow['fecha'];
                $c5 = (number_format($aRow['porcentaje_comision']*100)).' %';
                $c6 = number_format($aRow['comision_venta'],2);
                $c7 = number_format($aRow['comision_asignado'],2);
                $c8 = number_format($aRow['comision_saldo'],2);
                
                $axion = '"<div class=\"btn-group\">';
                 
                if($consultar['permiso']){
                    $axion .= '<button type=\"button\" class=\"'.$consultar['theme'].'\" title=\"'.$consultar['accion'].'\" onclick=\"pagosRecibidos.getConsulta(this,\''.$encryptReg.'\',\''.$c3.'\')\">';
                    $axion .= '    <i class=\"'.$consultar['icono'].'\"></i>';
                    $axion .= '</button>';
                }            
                
                $axion .= ' </div>" ';
                /*registros a mostrar*/
                $sOutput .= '["'.($num++).'","'.$c2.'","'.$c3.'","'.$c4.'","'.$c5.'","'.$c6.'","'.$c7.'","'.$c8.'",'.$axion.' ';                

                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }
    
    public function gridPagoVendedor(){
        $exportarpdf   = Session::getPermiso('PAGREEP');
        
        $sEcho  =   $this->post('sEcho');
        
        $rResult = Obj::run()->saldoVendedorController->getGridPagoVendedor();
        
        if(!isset($rResult['error'])){  
            $iTotal         = isset($rResult[0]['total'])?$rResult[0]['total']:0;
            
            $sOutput = '{';
            $sOutput .= '"sEcho": '.intval($sEcho).', ';
            $sOutput .= '"iTotalRecords": '.$iTotal.', ';
            $sOutput .= '"iTotalDisplayRecords": '.$iTotal.', ';
            $sOutput .= '"aaData": [ ';
            foreach ( $rResult as $key=>$aRow ){
                /*datos de manera manual*/
                
                $encryptReg = Aes::en($aRow['id_boleta']);
                
                $c1 = $aRow['boleta_numero'];
                $c2 = $aRow['fecha'];
                $c3 = $aRow['recibo_numero'];
                $c4 = $aRow['recibo_serie'];
                $c5 = $aRow['exonerado'];
                $c6 = number_format($aRow['monto_total'],2);
                $c7 = number_format($aRow['monto_retencion'],2);
                $c8 = number_format($aRow['monto_neto'],2);
                
                $axion = '"<div class=\"btn-group\">';
                if($exportarpdf['permiso']){
                    $axion .= '<button type=\"button\" class=\"'.$exportarpdf['theme'].'\" title=\"'.$exportarpdf['accion'].' pagos \" onclick=\"pagosRecibidos.postPDF(this,\'' . $encryptReg . '\')\"> ';
                    $axion .= '    <i class=\"'.$exportarpdf['icono'].'\"></i>';
                    $axion .= '</button>';
                }
                $axion .= ' </div>" ';
                
                $sOutput .= '["'.$c1.'","'.$c2.'","'.$c3.'","'.$c4.'","'.$c5.'","'.$c6.'","'.$c7.'","'.$c8.'",'.$axion.' ';
                               
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
    
    public function getConsulta(){ 
        Obj::run()->View->render('consultarPagoVendedor');
    }  
    
    public function postPDF($n=''){         
        return Obj::run()->saldoVendedorController->postPDF($n);
    }       
    
}

?>