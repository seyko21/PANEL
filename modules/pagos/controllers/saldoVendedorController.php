<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 26-09-2014 21:09:55 
* Descripcion : saldoVendedorController.php
* ---------------------------------------
*/    

class saldoVendedorController extends Controller{

    public function __construct() {
        $this->loadModel("saldoVendedor");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexSaldoVendedor");
    }
    
    public function getGridSaldoVendedor(){
        $consultar   = Session::getPermiso('SAVENCC');
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->saldoVendedorModel->getSaldoVendedor();
        
        $num = Obj::run()->saldoVendedorModel->_iDisplayStart;
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
                
    
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $axion = '"<div class=\"btn-group\">';
                 
                if($consultar['permiso']){
                    if ($aRow['comision_asignado'] > 0 ){
                        $axion .= '<button type=\"button\" class=\"'.$consultar['theme'].'\" title=\"'.$consultar['accion'].'\" onclick=\"saldoVendedor.getFormEditSaldoVendedor(this,\''.$encryptReg.'\')\">';
                        $axion .= '    <i class=\"'.$consultar['icono'].'\"></i>';
                        $axion .= '</button>';
                    }else{
                        $axion .= '<button type=\"button\" class=\"'.$consultar['theme'].'\" title=\"'.$consultar['accion'].'\" disabled >';
                        $axion .= '    <i class=\"'.$consultar['icono'].'\"></i>';
                        $axion .= '</button>';
                    }
                }
                
                $axion .= ' </div>" ';
                $c1 = $aRow['id_comision'];
                $c2 = $aRow['orden_numero'];
                $c3 = $aRow['nombrecompleto'];
                $c4 = $aRow['fecha'];
                $c5 = (number_format($aRow['porcentaje_comision']*100)).' %';
                $c6 = number_format($aRow['comision_venta'],2);
                $c7 = number_format($aRow['comision_asignado'],2);
                $c8 = number_format($aRow['comision_saldo'],2);
 
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
    

    /*carga formulario (editSaldoVendedor.phtml) para editar registro: SaldoVendedor*/
    public function getFormEditSaldoVendedor(){
        Obj::run()->View->render("formEditSaldoVendedor");
    }
    
   
    
}

?>