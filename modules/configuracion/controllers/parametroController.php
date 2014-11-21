<?php
/*
* --------------------------------------
* fecha: 07-08-2014 02:08:17 
* Descripcion : parametroController.php
* --------------------------------------
*/    

class parametroController extends Controller{

    public function __construct() {
        $this->loadModel('parametro');
    }
    
    public function index(){ 
        Obj::run()->View->render('indexParametro');
    }
    
    public function getGridParametro(){
       $editar = Session::getPermiso('PARMED');
        $eliminar = Session::getPermiso('PARMDE');
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->parametroModel->getGridParametro();
        
        if(!isset($rResult['error'])){  
            $iTotal         = isset($rResult[0]['total'])?$rResult[0]['total']:0;
            
            $sOutput = '{';
            $sOutput .= '"sEcho": '.intval($sEcho).', ';
            $sOutput .= '"iTotalRecords": '.$iTotal.', ';
            $sOutput .= '"iTotalDisplayRecords": '.$iTotal.', ';
            $sOutput .= '"aaData": [ ';
            foreach ( $rResult as $key=>$aRow ){                           
                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_parametro']);

               if($aRow['estado'] == 'A'){
                    if($editar['permiso']){
                        $estado = '<button type=\"button\" class=\"btn btn-success btn-xs\" title=\"'.BTN_DESACT.'\" onclick=\"parametro.postDesactivar(this,\''.$encryptReg.'\')\"><i class=\"fa fa-check\"></i> '.LABEL_ACT.'</button>';
                    }else{
                        $estado = '<span class=\"label label-success\">'.LABEL_ACT.'</span>';
                    }
                }elseif($aRow['estado'] == 'I'){
                    if($editar['permiso']){
                        $estado = '<button type=\"button\" class=\"btn btn-danger btn-xs\" title=\"'.BTN_ACT.'\" onclick=\"parametro.postActivar(this,\''.$encryptReg.'\')\"><i class=\"fa fa-ban\"></i> '.LABEL_DESACT.'</button>';
                    }else{
                        $estado = '<span class=\"label label-danger\">'.LABEL_DESACT.'</span>';
                    }
                }     
                $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" name=\"'.T100.'chk_delete[]\" value=\"'.$encryptReg.'\">';
                
                /*datos de manera manual*/
                $sOutput .= '["'.$chk.'","'.$aRow['nombre'].'","'.$aRow['valor'].'","'.$aRow['alias'].'","'.$estado.'", ';
                

                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';
                
                if($editar['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"'.$editar['theme'].'\" title=\"'.$editar['accion'].'\" onclick=\"parametro.getEditarParametro(\''.$encryptReg.'\')\">';
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
    public function getNuevoParametro(){ 
        Obj::run()->View->render('nuevoParametro');
    }
    
    public function getEditarParametro(){ 
        Obj::run()->View->render('editarParametro');
    }
    
    public static function getParametro(){ 
        $data = Obj::run()->parametroModel->getParametro();
        
        return $data;
    }
    
    public function postNuevoParametro(){ 
        $data = Obj::run()->parametroModel->mantenimientoParametro();
        
        echo json_encode($data);
    }
    
    public function postEditarParametro(){ 
        $data = Obj::run()->parametroModel->mantenimientoParametro();
        
        echo json_encode($data);
    }
    
    public function postDeleteParametro(){ 
        $data = Obj::run()->parametroModel->mantenimientoParametro();
        
        echo json_encode($data);
    }
    
    public function postDeleteParametroAll(){ 
        $data = Obj::run()->parametroModel->mantenimientoParametroAll();
        
        echo json_encode($data);
    }
    public function postDesactivar(){
        $data = Obj::run()->parametroModel->postDesactivar();
        
        echo json_encode($data);
    }
    
    public function postActivar(){
        $data = Obj::run()->parametroModel->postActivar();
        
        echo json_encode($data);
    }          
    
}

?>