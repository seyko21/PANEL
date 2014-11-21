<?php
/*
* --------------------------------------
* fecha: 06-08-2014 03:08:36 
* Descripcion : conceptoController.php
* --------------------------------------
*/    

class conceptoController extends Controller{

    public function __construct() {
        $this->loadModel('concepto');
    }
    
    public function index(){ 
        Obj::run()->View->render('indexConcepto');
    }
    
    public function getGridConceptos(){
        $editar = Session::getPermiso('CONCED');
        $eliminar = Session::getPermiso('CONCDE');
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->conceptoModel->getGridConceptos();
        
        if(!isset($rResult['error'])){  
            $iTotal         = isset($rResult[0]['total'])?$rResult[0]['total']:0;
            
            $sOutput = '{';
            $sOutput .= '"sEcho": '.intval($sEcho).', ';
            $sOutput .= '"iTotalRecords": '.$iTotal.', ';
            $sOutput .= '"iTotalDisplayRecords": '.$iTotal.', ';
            $sOutput .= '"aaData": [ ';
            foreach ( $rResult as $key=>$aRow ){
                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_concepto']);
                
                if($aRow['estado'] == 'A'){
                    if($editar['permiso']){
                        $estado = '<button type=\"button\" class=\"btn btn-success btn-xs\" title=\"'.BTN_DESACT.'\" onclick=\"concepto.postDesactivar(this,\''.$encryptReg.'\')\"><i class=\"fa fa-check\"></i> '.LABEL_ACT.'</button>';
                    }else{
                        $estado = '<span class=\"label label-success\">'.LABEL_ACT.'</span>';
                    }
                }elseif($aRow['estado'] == 'I'){
                    if($editar['permiso']){
                        $estado = '<button type=\"button\" class=\"btn btn-danger btn-xs\" title=\"'.BTN_ACT.'\" onclick=\"concepto.postActivar(this,\''.$encryptReg.'\')\"><i class=\"fa fa-ban\"></i> '.LABEL_DESACT.'</button>';
                    }else{
                        $estado = '<span class=\"label label-danger\">'.LABEL_DESACT.'</span>';
                    }
                }                                
                                
                
                
                $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" name=\"'.T6.'chk_delete[]\" value=\"'.$encryptReg.'\">';
                
                /*datos de manera manual*/
                $sOutput .= '["'.$chk.'","'.$aRow['concepto'].'","'.$aRow['tipoconcepto'].'","'.$aRow['destino'].'","'.number_format($aRow['precio'],2).'","'.$estado.'", ';

                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';
                
                if($editar['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"'.$editar['theme'].'\" title=\"'.$editar['accion'].'\" onclick=\"concepto.getEditarConcepto(\''.$encryptReg.'\')\">';
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
    
    public static function getTipoConceptos(){ 
        $data = Obj::run()->conceptoModel->getTipoConceptos();
        
        return $data;
    }
    
    public function getNuevoConcepto(){ 
        Obj::run()->View->render('nuevoConcepto');
    }
    
    public function getEditarConcepto(){ 
        Obj::run()->View->render('editarConcepto');
    }
    
    public static function getConcepto(){ 
        $data = Obj::run()->conceptoModel->getConcepto();
        
        return $data;
    }
    
    public function postNuevoConcepto(){ 
        $data = Obj::run()->conceptoModel->mantenimientoConcepto();
        
        echo json_encode($data);
    }
    
    public function postEditarConcepto(){ 
        $data = Obj::run()->conceptoModel->mantenimientoConcepto();
        
        echo json_encode($data);
    }
    
    public function postDeleteConceptoAll(){
        $data = Obj::run()->conceptoModel->mantenimientoConceptoAll();
        
        echo json_encode($data);
    }
    public function postDesactivar(){
        $data = Obj::run()->conceptoModel->postDesactivar();
        
        echo json_encode($data);
    }
    
    public function postActivar(){
        $data = Obj::run()->conceptoModel->postActivar();
        
        echo json_encode($data);
    }      
    
}

?>