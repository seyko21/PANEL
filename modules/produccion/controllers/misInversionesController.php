<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 30-09-2014 00:09:45 
* Descripcion : misInversionesController.php
* ---------------------------------------
*/    

class misInversionesController extends Controller{

    public function __construct() {
        $this->loadModel("misInversiones");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexMisInversiones");
    }
    
    public function getGridMisInversiones(){
        $consultar   = Session::getPermiso('MIINVCC');        
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->misInversionesModel->getMisInversiones();
        
        $num = Obj::run()->misInversionesModel->_iDisplayStart;
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
                $encryptReg = Aes::en($aRow['id_inversion']);
                
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $axion = '"<div class=\"btn-group\">';
                 
                if($consultar['permiso']){
                    $axion .= '<button type=\"button\" class=\"'.$consultar['theme'].'\" title=\"'.$consultar['accion'].'\" onclick=\"misInversiones.getFormEditMisInversiones(this,\''.$encryptReg.'\')\">';
                    $axion .= '    <i class=\"'.$consultar['icono'].'\"></i>';
                    $axion .= '</button>';
                }               
                
                $axion .= ' </div>" ';
                
                $c1 = $aRow['socio'];
                $c2 = Functions::cambiaf_a_normal($aRow['fecha_inversion']);
                $c3 = number_format($aRow['monto_invertido'],2);
                $c4 = number_format($aRow['monto_asignado'],2);
                $c5 = number_format($aRow['monto_saldo'],2);
                                
                
                /*registros a mostrar*/
                $sOutput .= '["'.($num++).'","'.$c1.'","'.$c2.'","'.$c3.'","'.$c4.'","'.$c5.'",'.$axion.' ';

                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }
    
    /*carga formulario (newMisInversiones.phtml) para nuevo registro: MisInversiones*/
    public function getFormNewMisInversiones(){
        Obj::run()->View->render("formNewMisInversiones");
    }
     
    
}

?>