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
                
                if($aRow['estado'] == 'A'){
                    $estado = '<span class=\"label label-success\">Activo</span>';
                }elseif($aRow['estado'] == 'I'){
                    $estado = '<span class=\"label label-danger\">Inactivo</span>';
                }
            
                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_tipopanel']);
                
                $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" name=\"'.T101.'chk_delete[]\" value=\"'.$encryptReg.'\">';
                
                /*datos de manera manual*/
                $sOutput .= '["'.$chk.'","'.$aRow['descripcion'].'","'.$estado.'", ';
                

                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';
                
                if($editar['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"btn btn-primary btn-xs\" title=\"'.$editar['accion'].'\" onclick=\"tipoPanel.getEditarTipoPanel(\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"fa fa-edit fa-lg\"></i>';
                    $sOutput .= '</button>';
                }
//                if($eliminar['permiso'] == 1){
//                    $sOutput .= '<button type=\"button\" class=\"btn btn-danger btn-xs\" title=\"'.$eliminar['accion'].'\" onclick=\"tipoPanel.postDeleteTipoPanel(\''.$encryptReg.'\')\">';
//                    $sOutput .= '    <i class=\"fa fa-ban fa-lg\"></i>';
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
}

?>