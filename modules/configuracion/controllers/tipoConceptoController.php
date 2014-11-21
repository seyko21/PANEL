<?php
/*
 * --------------------------------------
 * creado por:  RDCC
 * fecha: 03.01.2014
 * tipoConceptoController.php
 * --------------------------------------
 */
class tipoConceptoController extends Controller{
    
    public function __construct() {
        $this->loadModel('tipoConcepto');
    }

    public function index(){ 
        Obj::run()->View->render('indexTipoConcepto');
    }

    public function getTipoConceptos(){ 
        $editar = Session::getPermiso('TICNED');
        $eliminar = Session::getPermiso('TICNDE');
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->tipoConceptoModel->getTipoConceptos();
        
        if(!isset($rResult['error'])){  
            $iTotal         = isset($rResult[0]['total'])?$rResult[0]['total']:0;
            
            $sOutput = '{';
            $sOutput .= '"sEcho": '.intval($sEcho).', ';
            $sOutput .= '"iTotalRecords": '.$iTotal.', ';
            $sOutput .= '"iTotalDisplayRecords": '.$iTotal.', ';
            $sOutput .= '"aaData": [ ';
            foreach ( $rResult as $key=>$aRow ){
                
               /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_tipo']);              
            
              if($aRow['estado'] == 'A'){
                    if($editar['permiso']){
                        $estado = '<button type=\"button\" class=\"btn btn-success btn-xs\" title=\"'.BTN_DESACT.'\" onclick=\"tipoConcepto.postDesactivar(this,\''.$encryptReg.'\')\"><i class=\"fa fa-check\"></i> '.LABEL_ACT.'</button>';
                    }else{
                        $estado = '<span class=\"label label-success\">'.LABEL_ACT.'</span>';
                    }
                }elseif($aRow['estado'] == 'I'){
                    if($editar['permiso']){
                        $estado = '<button type=\"button\" class=\"btn btn-danger btn-xs\" title=\"'.BTN_ACT.'\" onclick=\"tipoConcepto.postActivar(this,\''.$encryptReg.'\')\"><i class=\"fa fa-ban\"></i> '.LABEL_DESACT.'</button>';
                    }else{
                        $estado = '<span class=\"label label-danger\">'.LABEL_DESACT.'</span>';
                    }
                }                                
                
                $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" name=\"'.T5.'chk_delete[]\" value=\"'.$encryptReg.'\">';

                
                /*datos de manera manual*/
                $sOutput .= '["'.$chk.'","'.$aRow['descripcion'].'","'.$estado.'", ';
               
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';
                
                if($editar['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"'.$editar['theme'].'\" title=\"'.$editar['accion'].'\" onclick=\"tipoConcepto.getEditarTipoConcepto(\''.$encryptReg.'\')\">';
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
    
    public function getNuevoTipoConcepto(){ 
        Obj::run()->View->render('nuevoTipoConcepto');
    }
    
    public function getEditarTipoConcepto(){ 
        Obj::run()->View->render('editarTipoConcepto');
    }
    
    public static function getTipoConcepto(){ 
        $data = Obj::run()->tipoConceptoModel->getTipoConcepto();
        
        return $data;
    }
    
    public function getAddListTipoConcepto(){ 
        $data = Obj::run()->tipoConceptoModel->getLastTipoConcepto();
        
        echo json_encode($data);
    }
    
    public function postNuevoTipoConcepto(){ 
        $data = Obj::run()->tipoConceptoModel->mantenimientoTipoConcepto();
        
        echo json_encode($data);
    }
    
    public function postEditarTipoConcepto(){ 
        $data = Obj::run()->tipoConceptoModel->mantenimientoTipoConcepto();
        
        echo json_encode($data);
    }
    
    public function postDeleteTipoConcepto(){ 
        $data = Obj::run()->tipoConceptoModel->mantenimientoTipoConcepto();
        
        echo json_encode($data);
    }
    
    public function postDeleteTipoConceptoAll(){ 
        $data = Obj::run()->tipoConceptoModel->mantenimientoTipoConceptoAll();
        
        echo json_encode($data);
    }
    
    public function postDesactivar(){
        $data = Obj::run()->tipoConceptoModel->postDesactivar();
        
        echo json_encode($data);
    }
    
    public function postActivar(){
        $data = Obj::run()->tipoConceptoModel->postActivar();
        
        echo json_encode($data);
    }    
    
}

?>
