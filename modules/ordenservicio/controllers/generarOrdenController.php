<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 09-09-2014 06:09:13 
* Descripcion : generarOrdenController.php
* ---------------------------------------
*/    

class generarOrdenController extends Controller{

    public function __construct() {
        $this->loadModel("generarOrden");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexGenerarOrden");
    }
    
    public function getGridGenerarOrden(){
        $editar   = Session::getPermiso('ORSERED');
        $eliminar = Session::getPermiso('ORSERDE');
        $generar  = Session::getPermiso('ORSERGN');
       
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->generarOrdenModel->getGenerarOrden();
        
        $num = Obj::run()->generarOrdenModel->_iDisplayStart;
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
                
                /*campo que maneja los estados, para el ejemplo aqui es ACTIVO, coloca tu campo*/
//                if($aRow['estado'] == 1){
//                    $estado = '<span class=\"label label-success\">'.LABEL_ACT.'</span>';
//                }else{
//                    $estado = '<span class=\"label label-danger\">'.LABEL_DES.'</span>';
//                }
                
                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_ordenservicio']);
                
                
                
                /*registros a mostrar*/
                $sOutput .= '["'.($num++).'","'.$aRow['orden_numero'].'","'.$aRow['nombrecompleto'].'","'.$aRow['cliente'].'","xx",';
                
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';
                 
                if($generar['permiso']){
                    $sOutput .= '<button type=\"button\" class=\"'.$generar['theme'].'\" title=\"'.$generar['accion'].' '.GNOSE_2.'\" onclick=\"generarOrden.getFormCronograma(this,\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"'.$generar['icono'].'\"></i>';
                    $sOutput .= '</button>';
                }
//                if($eliminar['permiso']){
//                    $axion .= '<button type=\"button\" class=\"'.$eliminar['theme'].'\" title=\"'.$eliminar['accion'].'\" onclick=\"generarOrden.postDeleteGenerarOrden(this,\''.$encryptReg.'\')\">';
//                    $axion .= '    <i class=\"'.$eliminar['icono'].'\"></i>';
//                    $axion .= '</button>';
//                }
                
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
    
    public function getFormCronograma(){
        Obj::run()->View->render("formCronograma");
    }
    
    /*envia datos para grabar registro: GenerarOrden*/
    public function postNewGenerarOrden(){
        $data = Obj::run()->generarOrdenModel->newGenerarOrden();
        
        echo json_encode($data);
    }
    
   
    
    
    
    
    
}

?>