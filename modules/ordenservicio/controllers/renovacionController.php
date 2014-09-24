<?php

class renovacionController extends Controller{

    public function __construct() {
        $this->loadModel("renovacion");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexRenovacion");
    }
    
    public function gridRenovacion(){
        $editar   = Session::getPermiso('ORSERED');
        $email    = Session::getPermiso('ORSEREE');
        $generar  = Session::getPermiso('ORSERGN');
        $pdf      = Session::getPermiso('ORSEREP');
       
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->renovacionModel->getOrdenes();
        
        $num = Obj::run()->renovacionModel->_iDisplayStart;
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
                $encryptReg = Aes::en($aRow['id_ordenservicio']);
                
                /*solo se anulara las ordenes que estan en estado E, porque no debde tener ningun pago hecho*/
                $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" disabled=\"disabled\"  >';
                /*campo que maneja los estados, para el ejemplo aqui es ACTIVO, coloca tu campo*/
                switch($aRow['estado']){
                    case 'T':
                        $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" name=\"'.GNOSE.'chk_delete[]\" value=\"'.$encryptReg.'\"  >'; 
                        $estado = '<span class=\"label label-success\">'.SEGPA_8.'</span>';
                        break;
                    case 'F':
                        $estado = '<span class=\"label label-info\">'.SEGPA_29.'</span>';
                        break;
                }
                
                /*registros a mostrar*/
                $sOutput .= '["'.$chk.'","'.$aRow['orden_numero'].'","'.$aRow['nombrecompleto'].'","'.$aRow['fecha'].'","'.$estado.'",';
                
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';
                 
                if($editar['permiso']){
                    $sOutput .= '<button type=\"button\" class=\"'.$editar['theme'].'\" title=\"'.GENRE_1.'\" onclick=\"renovacion.getFormRenovacion(\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"'.$editar['icono'].'\"></i>';
                    $sOutput .= '</button>';
                }
                $sOutput .= '</div>"';
                
                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }
    
    public function getContRenovar(){
        Obj::run()->View->render("formRenovacion");
    }
    
}