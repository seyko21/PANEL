<?php
/*
* --------------------------------------
* fecha: 08-08-2014 03:08:55 
* Descripcion : tipoPanelController.php
* --------------------------------------
*/    

class tipoPanelController extends Controller{

    public function __construct() {
        $this->loadModel('tipoPanel');
    }
    
    public function index(){ 
        Obj::run()->View->render('indexTipoPanel');
    }

    public function getGridTipoPanel(){
       $editar = Session::getPermiso('TIPAED');
       $eliminar = Session::getPermiso('TIPADE');
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->tipoPanelModel->getGridTipoPanel();
        
        if(!isset($rResult['error'])){  
            $iTotal         = isset($rResult[0]['total'])?$rResult[0]['total']:0;
            
            $sOutput = '{';
            $sOutput .= '"sEcho": '.intval($sEcho).', ';
            $sOutput .= '"iTotalRecords": '.$iTotal.', ';
            $sOutput .= '"iTotalDisplayRecords": '.$iTotal.', ';
            $sOutput .= '"aaData": [ ';
            foreach ( $rResult as $key=>$aRow ){
                             
                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_tipopanel']);
                
                if($aRow['estado'] == 'A'){
                    if($editar['permiso']){
                        $estado = '<button type=\"button\" class=\"btn btn-success btn-xs\" title=\"'.BTN_DESACT.'\" onclick=\"tipoPanel.postDesactivar(this,\''.$encryptReg.'\')\"><i class=\"fa fa-check\"></i> '.LABEL_ACT.'</button>';
                    }else{
                        $estado = '<span class=\"label label-success\">'.LABEL_ACT.'</span>';
                    }
                }elseif($aRow['estado'] == 'I'){
                    if($editar['permiso']){
                        $estado = '<button type=\"button\" class=\"btn btn-danger btn-xs\" title=\"'.BTN_ACT.'\" onclick=\"tipoPanel.postActivar(this,\''.$encryptReg.'\')\"><i class=\"fa fa-ban\"></i> '.LABEL_DESACT.'</button>';
                    }else{
                        $estado = '<span class=\"label label-danger\">'.LABEL_DESACT.'</span>';
                    }
                }             
                
                $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" name=\"'.T101.'chk_delete[]\" value=\"'.$encryptReg.'\">';
                
                /*datos de manera manual*/
                $sOutput .= '["'.$chk.'","'.$aRow['descripcion'].'","'.$estado.'", ';
                

                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';
                
                if($editar['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"'.$editar['theme'].'\" title=\"'.$editar['accion'].'\" onclick=\"tipoPanel.getEditarTipoPanel(\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"'.$editar['icono'].'\"></i>';
                    $sOutput .= '</button>';
                }
//                if($eliminar['permiso'] == 1){
//                    $sOutput .= '<button type=\"button\" class=\"'.$eliminar['theme'].'\" title=\"'.$eliminar['accion'].'\" onclick=\"tipoPanel.postDeleteTipoPanel(\''.$encryptReg.'\')\">';
//                    $sOutput .= '    <i class=\"'.$eliminar['theme'].'\"></i>';
//                    $sOutput .= '</button>';
//                }
                
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
      public function getNuevoTipoPanel(){ 
        Obj::run()->View->render('nuevoTipoPanel');
    }
    
    public function getEditarTipoPanel(){ 
        Obj::run()->View->render('editarTipoPanel');
    }
    
       
    public function getAddListTipoPanel(){ 
        $data = Obj::run()->tipoPanelModel->getLastTipoPanel();
        
        echo json_encode($data);
    }
        
    public static function getTipoPanel(){ 
        $data = Obj::run()->tipoPanelModel->getTipoPanel();
        
        return $data;
    }
    
    public function postNuevoTipoPanel(){ 
        $data = Obj::run()->tipoPanelModel->mantenimientoTipoPanel();
        
        echo json_encode($data);
    }
    
    public function postEditarTipoPanel(){ 
        $data = Obj::run()->tipoPanelModel->mantenimientoTipoPanel();
        
        echo json_encode($data);
    }
    
    public function postDeleteTipoPanel(){ 
        $data = Obj::run()->tipoPanelModel->mantenimientoTipoPanel();
        
        echo json_encode($data);
    }
    
    public function postDeleteTipoPanelAll(){ 
        $data = Obj::run()->tipoPanelModel->mantenimientoTipoPanelAll();
        
        echo json_encode($data);
    }    
    public function postDesactivar(){
        $data = Obj::run()->tipoPanelModel->postDesactivar();
        
        echo json_encode($data);
    }
    
    public function postActivar(){
        $data = Obj::run()->tipoPanelModel->postActivar();
        
        echo json_encode($data);
    }      
}

?>