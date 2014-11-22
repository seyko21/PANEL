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
                $idPersona = Aes::en($aRow['id_persona']);
                
                /*solo se anulara las ordenes que estan en estado E, porque no debde tener ningun pago hecho*/
                $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" disabled=\"disabled\"  >';
                /*campo que maneja los estados, para el ejemplo aqui es ACTIVO, coloca tu campo*/
                switch($aRow['estado']){
                    case 'T':
                        $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" name=\"'.GNOSE.'chk_delete[]\" value=\"'.$encryptReg.'\"  >'; 
                        $estado = '<span class=\"label label-success\">'.SEGPA_8.'</span>';
                        break;
                    case 'R':
                        $estado = '<span class=\"label label-info\">'.SEGPA_30.'</span>';
                        break;
                }
                
                $total = 'S/.'.number_format($aRow['monto_total'],2);                                
                
                /*registros a mostrar*/
                $sOutput .= '["'.$chk.'","'.$aRow['orden_numero'].'","<a href=\"javascript:;\" onclick=\"persona.getDatosPersonales(\''.$idPersona.'\');\">'.$aRow['cliente'].'</a>","'.$aRow['fecha'].'","'.$total.'","'.$estado.'",';
                
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';
                 
                if($editar['permiso']){
                    if($aRow['estado'] == 'R'){
                        $sOutput .= '<button type=\"button\" class=\"'.$editar['theme'].' disabled\" title=\"'.GENRE_1.'\" disabled>';
                        $sOutput .= '    <i class=\"'.$editar['icono'].'\"></i>';
                        $sOutput .= '</button>';
                    }else{
                        $sOutput .= '<button type=\"button\" class=\"'.$editar['theme'].'\" title=\"'.GENRE_1.'\" onclick=\"renovacion.getFormRenovacion(\''.$encryptReg.'\',\''.$aRow['orden_numero'].'\')\">';
                        $sOutput .= '    <i class=\"'.$editar['icono'].'\"></i>';
                        $sOutput .= '</button>';
                    }
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
    
    public function getOrdenServicio(){
        $data = Obj::run()->renovacionModel->getOrdenServicio();
        return $data;
    }
    
    public static function getProductosCotizados(){
        $data = Obj::run()->renovacionModel->getProductosCotizados();
        
        return $data;
    }
    
    public function postRenovacion(){ 
        $data = Obj::run()->renovacionModel->postRenovacion();
        
        echo json_encode($data);
    }
    
}