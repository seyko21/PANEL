<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 11-10-2014 05:10:22 
* Descripcion : pagoSocioController.php
* ---------------------------------------
*/    

class pagoSocioController extends Controller{

    public function __construct() {
        $this->loadModel("pagoSocio");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexPagoSocio");
    }
    
    public function getGridPagoSocio(){
       $pagar  = Session::getPermiso('GPASOPG'); 

       $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->pagoSocioModel->getPagoSocio();
        
        $num = Obj::run()->pagoSocioModel->_iDisplayStart;
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
            
            foreach ( $rResult as $key=>$aRow ){
                                
                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_comision']);
                
                $c1 = $aRow['id_comision'];
                $c2 = $aRow['orden_numero'];
                $c3 = $aRow['nombrecompleto'];
                $c4 = $aRow['fecha'];
                $c5 = (number_format($aRow['porcentaje_comision']*100)).' %';
                $c6 = number_format($aRow['comision_venta'],2);
                $c7 = number_format($aRow['comision_asignado'],2);
                $c8 = number_format($aRow['comision_saldo'],2);

                $axion = '"<div class=\"btn-group\">';
                if($pagar['permiso']){
                    if ($aRow['comision_saldo'] > 0 ){
                        $axion .= '<button type=\"button\" class=\"'.$pagar['theme'].'\" title=\"'.$pagar['accion'].'\" onclick=\"pagoSocio.getFormPagar(this,\''.$encryptReg.'\',\''.$c3.'\',\''.$aRow['comision_saldo'].'\',\''.$aRow['id_persona'].'\')\">';
                        $axion .= '    <i class=\"'.$pagar['icono'].'\"></i>';
                        $axion .= '</button>';
                    }else{
                        $axion .= '<button type=\"button\" class=\"'.$pagar['theme'].'\" title=\"'.$pagar['accion'].'\" disabled >';
                        $axion .= '    <i class=\"'.$pagar['icono'].'\"></i>';
                        $axion .= '</button>';                        
                    }
                }
                $axion .= ' </div>" ';                
                if ($aRow['comision_asignado'] > 0 ){
                    $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" name=\"'.GPASO.'chk_delete[]\" value=\"'.$encryptReg.'\"  >'; 
                }else{
                    $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" name=\"'.GPASO.'chk_delete[]\" disabled >'; 
                }
                /*registros a mostrar*/
                $sOutput .= '["'.$chk.'","'.($num++).'","'.$c2.'","'.$c3.'","'.$c4.'","'.$c5.'","'.$c6.'","'.$c7.'","'.$c8.'",'.$axion.' ';

                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
            
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }
    
    public function getFormPagar(){ 
        Obj::run()->View->render("formPagarSocio");
    }
    
    public function postPagoVendedor(){ 
        $data = Obj::run()->pagoSocioModel->pagarComision();
        
        echo json_encode($data);
    }
    
    public function postAnularPagoAll(){ 
        $data = Obj::run()->pagoSocioModel->anularPagoAll();
        
        echo json_encode($data);
    }
}

?>