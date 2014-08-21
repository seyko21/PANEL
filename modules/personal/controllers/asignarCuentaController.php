<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 19-08-2014 06:08:51 
* Descripcion : asignarCuentaController.php
* ---------------------------------------
*/    

class asignarCuentaController extends Controller{

    public function __construct() {
        $this->loadModel("asignarCuenta");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexAsignarCuenta");
    }
    
    public function getGridAsignarCuenta(){
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->asignarCuentaModel->gridAsignarCuenta();
        
        if(!isset($rResult['error'])){  
            $iTotal         = isset($rResult[0]['total'])?$rResult[0]['total']:0;
            
            $sOutput = '{';
            $sOutput .= '"sEcho": '.intval($sEcho).', ';
            $sOutput .= '"iTotalRecords": '.$iTotal.', ';
            $sOutput .= '"iTotalDisplayRecords": '.$iTotal.', ';
            $sOutput .= '"aaData": [ ';
            foreach ( $rResult as $key=>$aRow ){
                
                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_asignacion']);
                $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" name=\"'.ASCU.'chk_delete[]\" value=\"'.$encryptReg.'\">';
                /*datos de manera manual*/
                $sOutput .= '["'.$chk.'","'.$aRow['codigo'].'","'.$aRow['lado'].' - '.$aRow['ubicacion'].'","'.$aRow['nombrecompleto'].'","'.$aRow['porcentaje_comision'].'" ';

                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
//                $sOutput .= '"<div class=\"btn-group\">';
//                
//                if($eliminar['permiso']){
//                    $sOutput .= '<button type=\"button\" class=\"btn btn-danger\" title=\"'.$eliminar['accion'].'\" onclick=\"generarCotizacion.postEmail(this,\''.$encryptReg.'\')\">';
//                    $sOutput .= '    <i class=\"fa fa-envelope-o fa-lg\"></i>';
//                    $sOutput .= '</button>';
//                }
//                
//                $sOutput .= ' </div>" ';

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
    
    /*carga formulario (newAsignarCuenta.phtml) para nuevo registro: AsignarCuenta*/
    public function getFormNewAsignarCuenta(){
        Obj::run()->View->render("formNewAsignarCuenta");
    }
    
    /*carga formulario (editAsignarCuenta.phtml) para editar registro: AsignarCuenta*/
    public function getFormEditAsignarCuenta(){
        Obj::run()->View->render("formEditAsignarCuenta");
    }
    
    /*productos sin asignar*/
    public static function getProductos(){
        $data = Obj::run()->asignarCuentaModel->getProductos();
        
        return $data;
    }
    
    /*envia datos para grabar registro: AsignarCuenta*/
    public function postNewAsignarCuenta(){
        $data = Obj::run()->asignarCuentaModel->newAsignarCuenta();
        
        echo json_encode($data);
    }
    
    /*envia datos para editar registro: AsignarCuenta*/
    public function postEditAsignarCuenta(){
        $data = Obj::run()->asignarCuentaModel->editAsignarCuenta();
        
        echo json_encode($data);
    }
    
    /*envia datos para eliminar registro: AsignarCuenta*/
    public function postDeleteAsignarCuentaAll(){
        $data = Obj::run()->asignarCuentaModel->deleteAsignarCuentaAll();
        
        echo json_encode($data);
    }
    
}

?>