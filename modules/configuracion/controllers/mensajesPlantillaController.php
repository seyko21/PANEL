<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 09-12-2014 17:12:42 
* Descripcion : mensajesPlantillaController.php
* ---------------------------------------
*/    

class mensajesPlantillaController extends Controller{

    public function __construct() {
        $this->loadModel(array('modulo'=>'configuracion','modelo'=>'mensajesPlantilla'));
    }
    
    public function index(){ 
        Obj::run()->View->render("indexMensajesPlantilla");
    }
    
    public function getGridMensajes(){
        $editar   = Session::getPermiso('PMSJED');
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->mensajesPlantillaModel->getMensajes();
        
        $num = Obj::run()->mensajesPlantillaModel->_iDisplayStart;
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
                $encryptReg = Aes::en($aRow['id_mensaje']);
                if($aRow['estado'] == 'A'){
                    if($editar['permiso']){
                        $estado = '<button type=\"button\" class=\"btn btn-success btn-xs\" title=\"'.BTN_DESACT.'\" onclick=\"mensajesPlantilla.postDesactivar(this,\''.$encryptReg.'\')\"><i class=\"fa fa-check\"></i> '.LABEL_ACT.'</button>';
                    }else{
                        $estado = '<span class=\"label label-success\">'.LABEL_ACT.'</span>';
                    }
                }elseif($aRow['estado'] == 'I'){
                    if($editar['permiso']){
                        $estado = '<button type=\"button\" class=\"btn btn-danger btn-xs\" title=\"'.BTN_ACT.'\" onclick=\"mensajesPlantilla.postActivar(this,\''.$encryptReg.'\')\"><i class=\"fa fa-ban\"></i> '.LABEL_DESACT.'</button>';
                    }else{
                        $estado = '<span class=\"label label-danger\">'.LABEL_DESACT.'</span>';
                    }
                }     
                
                $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" name=\"'.PMSJ.'chk_delete[]\" value=\"'.$encryptReg.'\">';
                
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $axion = '"<div class=\"btn-group\">';
                 
                if($editar['permiso']){
                    $axion .= '<button type=\"button\" class=\"'.$editar['theme'].'\" title=\"'.$editar['accion'].'\" onclick=\"mensajesPlantilla.getFormEditMensajes(\''.$encryptReg.'\')\">';
                    $axion .= '    <i class=\"'.$editar['icono'].'\"></i>';
                    $axion .= '</button>';
                }
                
                $axion .= ' </div>" ';
                
                /*registros a mostrar*/
                $sOutput .= '["'.($chk).'","'.$aRow['asunto'].'","'.$aRow['alias'].'","'.$estado.'",'.$axion.' ';

                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }
    
    /*carga formulario (newMensajes.phtml) para nuevo registro: Mensajes*/
    public function getFormNewMensajes(){
        Obj::run()->View->render("formNewMensajes");
    }
    
    /*carga formulario (editMensajes.phtml) para editar registro: Mensajes*/
    public function getFormEditMensajes(){
        Obj::run()->View->render("formEditMensajes");
    }
    
    /*busca data para editar registro: Mensajes*/
    public static function findMensajes(){
        $data = Obj::run()->mensajesPlantillaModel->findMensajes();
            
        return $data;
    }
    
    /*envia datos para grabar registro: Mensajes*/
    public function postNewMensajes(){
        $data = Obj::run()->mensajesPlantillaModel->newMensajes();
        
        echo json_encode($data);
    }
    
    /*envia datos para editar registro: Mensajes*/
    public function postEditMensajes(){
        $data = Obj::run()->mensajesPlantillaModel->editMensajes();
        
        echo json_encode($data);
    }
    
    /*envia datos para eliminar registros: Mensajes*/
    public function postDeleteMensajesAll(){
        $data = Obj::run()->mensajesPlantillaModel->deleteMensajesAll();
        
        echo json_encode($data);
    }
    
    public function postDesactivar(){
        $data = Obj::run()->mensajesPlantillaModel->postDesactivar();
        
        echo json_encode($data);
    }
    
    public function postActivar(){
        $data = Obj::run()->mensajesPlantillaModel->postActivar();
        
        echo json_encode($data);
    }    
    
    public function getPlantillaMensaje($alias){
        $data = Obj::run()->mensajesPlantillaModel->getPlantillaMensaje($alias);
        return $data;
    }    
    
}

?>