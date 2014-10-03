<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 02-10-2014 23:10:16 
* Descripcion : retornoInversionController.php
* ---------------------------------------
*/    

class retornoInversionController extends Controller{

    public function __construct() {
        $this->loadModel("retornoInversion");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexRetornoInversion");
    }
    
    public function getConsulta(){ 
        Obj::run()->View->render("consultarROIOrden");
    }
        
    
    public function getGridRetornoInversion(){
        $consultar   = Session::getPermiso('REINVCC');
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->retornoInversionModel->getRetornoInversion();
        
        $num = Obj::run()->retornoInversionModel->_iDisplayStart;
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
                $idProducto = Aes::en($aRow['id_producto']);
                $idSocio  = Aes::en($aRow['id_persona']);
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                        
                                               
                $axion = '"<div class=\"btn-group\">';
                 
                if($consultar['permiso']){
                    $axion .= '<button type=\"button\" class=\"'.$consultar['theme'].'\" title=\"'.$consultar['accion'].'\" onclick=\"retornoInversion.getConsulta(this,\''.$idProducto.'\',\''.$idSocio.'\')\">';
                    $axion .= '    <i class=\"'.$consultar['icono'].'\"></i>';
                    $axion .= '</button>';
                }             
                
                $axion .= ' </div>" ';
                 $c1 =$aRow['socio'];
                 $c2 =$aRow['codigos'];
                 $c3 =$aRow['ubicacion'];                                  
                 $c4 = number_format($aRow['inversion'],2);
                 $c5 = number_format($aRow['ingresos'],2);
                 $c6 = number_format($aRow['roi']*100,2);
                 $c7 =number_format($aRow['porcentaje_ganacia']*100).' %';  
                /*registros a mostrar*/
                $sOutput .= '["'.($num++).'","'.$c1.'","'.$c2.'","'.$c3.'","'.$c7.'","'.$c4.'","'.$c5.'","'.$c6.'",'.$axion.' ';

                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }
    
    
    public function getGridRoiOs(){        
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->retornoInversionModel->getGridRoiOs();
        
        $num = Obj::run()->retornoInversionModel->_iDisplayStart;
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
                                                                            
                $c1 =$aRow['orden_numero'];
                $c2 =$aRow['codigo'];
                $c3 =$aRow['fecha'];
                $c4 = Functions::convertirDiaMes($aRow['cantidad_mes']);
                $c5 = number_format($aRow['importe'],2);
                $c6 = number_format($aRow['impuesto'],2);
                $c7 = number_format($aRow['monto_total'],2);
                $c8 = number_format($aRow['comision_venta'],2);
                $c9 = number_format($aRow['egresos'],2);
                $c10 = number_format($aRow['total_utilidad'],2);                    
                $c11 = number_format($aRow['monto_utilidad'],2);
                /*registros a mostrar*/
                $sOutput .= '["'.$c1.'","'.$c2.'","'.$c3.'","'.$c4.'","'.$c5.'","'.$c6.'","'.$c7.'","'.$c8.'","'.$c9.'","'.$c10.'","'.$c11.'"';

                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }    
    
  
}

?>