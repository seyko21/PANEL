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
        
        $editar = Session::getPermiso('ASCUED');
        
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
                
                if($aRow['estado'] == 'R'){
                    $estado = '<span class=\"label label-success\">'.LABEL_ACT.'</span>';
                }elseif($aRow['estado'] == 'A'){
                    $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" name=\"'.ASCU.'chk_delete[]\" disabled=\"disabled\">';
                    $estado = '<span class=\"label label-danger\">'.LABEL_AN.'</span>';
                }
                
                /*datos de manera manual*/
                $sOutput .= '["'.$chk.'","'.$aRow['codigo'].'","'.$aRow['fecha_creacion'].'","'.$aRow['ubicacion'].' - '.$aRow['lado'].'","'.$aRow['nombrecompleto'].'","'.($aRow['porcentaje_comision']*100).' %","'.$estado.'" , ';

                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';
                if($editar['permiso'] == 1 and $aRow['estado'] == 'R'){
                    $sOutput .= '<button type=\"button\" class=\"'.$editar['theme'].'\" title=\"'.$editar['accion'].'\" onclick=\"asignarCuenta.getEditarCuenta(this,\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"'.$editar['icono'].'\"></i>';
                    $sOutput .= '</button>';
                }
                $sOutput .= ' </div>" ';

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
        Obj::run()->View->render("editarAsignarCuenta");
    }
    
    /*productos sin asignar*/
    public static function getProductos(){
        $data = Obj::run()->asignarCuentaModel->getProductos();
        
        return $data;
    }
    //Comision por defecto que viene de pub_parametro
    public static function getComision(){
        $data = Obj::run()->asignarCuentaModel->getComision();
        
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
    public function postAnularAsignarCuentaAll(){
        $data = Obj::run()->asignarCuentaModel->anularAsignarCuentaAll();
        
        echo json_encode($data);
    }
    
    public static function findAsignarCuenta(){
         $data = Obj::run()->asignarCuentaModel->findAsignarCuenta();
        
        return $data;
    }
    
}

?>