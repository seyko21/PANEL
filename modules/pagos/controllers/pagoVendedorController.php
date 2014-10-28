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
        $consultar  = Session::getPermiso('GPAVECC'); 

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
                $idPersona = Aes::en($aRow['id_persona']);
                
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
                        $axion .= '<button type=\"button\" class=\"'.$pagar['theme'].'\" title=\"'.$pagar['accion'].'\" onclick=\"pagoVendedor.getFormPagar(this,\''.$encryptReg.'\',\''.$c3.'\',\''.$aRow['comision_saldo'].'\',\''.$aRow['id_persona'].'\',\''.$aRow['id_ordenservicio'].'\')\">';
                        $axion .= '    <i class=\"'.$pagar['icono'].'\"></i>';
                        $axion .= '</button>';
                    }else{
                        $axion .= '<button type=\"button\" class=\"'.$pagar['theme'].'\" title=\"'.$pagar['accion'].'\" disabled >';
                        $axion .= '    <i class=\"'.$pagar['icono'].'\"></i>';
                        $axion .= '</button>';                        
                    }                   
                }
                if($consultar['permiso']){
                 if ($aRow['comision_asignado'] > 0 ){
                        $axion .= '<button type=\"button\" class=\"'.$consultar['theme'].'\" title=\"'.$consultar['accion'].'\" onclick=\"saldoVendedor.getConsulta(this,\''.$encryptReg.'\',\''.$c3.'\')\">';
                        $axion .= '    <i class=\"'.$consultar['icono'].'\"></i>';
                        $axion .= '</button>';
                    }else{
                        $axion .= '<button type=\"button\" class=\"'.$consultar['theme'].'\" title=\"'.$consultar['accion'].'\" disabled >';
                        $axion .= '    <i class=\"'.$consultar['icono'].'\"></i>';
                        $axion .= '</button>';
                    }
                }
                $axion .= ' </div>" ';                
                if ($aRow['comision_asignado'] > 0 ){
                    $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" name=\"'.GPAVE.'chk_delete[]\" value=\"'.$encryptReg.'\"  >'; 
                }else{
                    $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" name=\"'.GPAVE.'chk_delete[]\" disabled >'; 
                }
                /*registros a mostrar*/
                $sOutput .= '["'.($num++).'","'.$c2.'","<a href=\"javascript:;\" onclick=\"persona.getDatosPersonales(\''.$idPersona.'\');\">'.$c3.'</a>","'.$c4.'","'.$c5.'","'.$c6.'","'.$c7.'","'.$c8.'",'.$axion.' ';

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
    
    public function getValidaImagenes() {
        $data = Obj::run()->pagoVendedorModel->validaImagenes();
        
        return $data;
    }
    public function postPagoVendedor(){ 
        $data = Obj::run()->pagoVendedorModel->pagarComision();
        
        echo json_encode($data);
    }
    
    public function postAnularPagoAll(){ 
        $data = Obj::run()->pagoVendedorModel->anularPagoAll();
        
        echo json_encode($data);
    }
    
    public function postDeletePago(){ 
        $data = Obj::run()->pagoVendedorModel->anularPago();
        
        echo json_encode($data);
    }
    
}