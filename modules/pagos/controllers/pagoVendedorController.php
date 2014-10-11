<?php

class pagoVendedorController extends Controller{

    public function __construct() {
        $this->loadModel("pagoVendedor");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexPagoVendedor");
    }
    
    public function getGridPagosVendedor(){
            
        $pagar  = Session::getPermiso('GPAVEPG'); 

        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->pagoVendedorModel->getPagosVendedor();
        
        $num = Obj::run()->pagoVendedorModel->_iDisplayStart;
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
                    $axion .= '<button type=\"button\" class=\"'.$pagar['theme'].'\" title=\"'.$pagar['accion'].'\" onclick=\"pagoVendedor.getFormPagar(this,\''.$encryptReg.'\',\''.$c3.'\',\''.$c8.'\',\''.$aRow['id_persona'].'\')\">';
                    $axion .= '    <i class=\"'.$pagar['icono'].'\"></i>';
                    $axion .= '</button>';
                }
                $axion .= ' </div>" ';                
                
                $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" name=\"'.GPAVE.'chk_delete[]\" value=\"'.$encryptReg.'\"  >'; 
                
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
        Obj::run()->View->render("formPagarVendedor");
    }
    
    public function postPagoVendedor(){ 
        $data = Obj::run()->pagoVendedorModel->pagarComision();
        
        echo json_encode($data);
    }
    
    public function postAnularCotizacionAll(){ 
        $data = Obj::run()->pagoVendedorModel->anularCotizacionAll();
        
        echo json_encode($data);
    }
    
}