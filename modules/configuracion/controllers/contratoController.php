<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 12-09-2014 17:09:12 
* Descripcion : contratoController.php
* ---------------------------------------
*/    

class contratoController extends Controller{

    public function __construct() {
        $this->loadModel("contrato");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexContrato");
    }
    
    public function getGridContrato(){
        $editar   = Session::getPermiso('CONTRED');
        $adjuntar = Session::getPermiso('CONTRAJ');
        $clonar   = Session::getPermiso('CONTRCL');
        $sEcho    =   $this->post('sEcho');
        
        $rResult = Obj::run()->contratoModel->getContrato();
        
        $num = Obj::run()->contratoModel->_iDisplayStart;
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
                $encryptReg = Aes::en($aRow['id_contrato']);
                
                if($aRow['estado'] == 'A'){
                    if($editar['permiso']){
                        $estado = '<button type=\"button\" class=\"btn btn-success btn-xs\" title=\"'.BTN_DESACT.'\" onclick=\"contrato.postDesactivar(this,\''.$encryptReg.'\')\"><i class=\"fa fa-check\"></i> '.LABEL_ACT.'</button>';
                    }else{
                        $estado = '<span class=\"label label-success\">'.LABEL_ACT.'</span>';
                    }
                }elseif($aRow['estado'] == 'I'){
                    if($editar['permiso']){
                        $estado = '<button type=\"button\" class=\"btn btn-danger btn-xs\" title=\"'.BTN_ACT.'\" onclick=\"contrato.postActivar(this,\''.$encryptReg.'\')\"><i class=\"fa fa-ban\"></i> '.LABEL_DESACT.'</button>';
                    }else{
                        $estado = '<span class=\"label label-danger\">'.LABEL_DESACT.'</span>';
                    }
                }     
                
                 if($aRow['visible'] == '1'){
                    if($editar['permiso']){
                        $visible = '<button type=\"button\" class=\"btn btn-success btn-xs\" title=\"'.BTN_DESACT.'\" onclick=\"contrato.postDesactivarVisible(this,\''.$encryptReg.'\')\"><i class=\"fa fa-check\"></i> '.LABEL_ACT.'</button>';
                    }else{
                        $visible = '<span class=\"label label-success\">'.LABEL_ACT.'</span>';
                    }
                }elseif($aRow['visible'] == '0'){
                    if($editar['permiso']){
                        $visible = '<button type=\"button\" class=\"btn btn-danger btn-xs\" title=\"'.BTN_ACT.'\" onclick=\"contrato.postActivarVisible(this,\''.$encryptReg.'\')\"><i class=\"fa fa-ban\"></i> '.LABEL_DESACT.'</button>';
                    }else{
                        $visible = '<span class=\"label label-danger\">'.LABEL_DESACT.'</span>';
                    }
                }     
                
                $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" name=\"'.CONTR.'chk_delete[]\" value=\"'.$encryptReg.'\">';
                                
                
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $axion = '"<div class=\"btn-group\">';
                 
                if($editar['permiso']){
                    $axion .= '<button type=\"button\" class=\"'.$editar['theme'].'\" title=\"'.$editar['accion'].'\" onclick=\"contrato.getFormEditContrato(\''.$encryptReg.'\')\">';
                    $axion .= '    <i class=\"'.$editar['icono'].'\"></i>';
                    $axion .= '</button>';
                }
                if($adjuntar['permiso']){
                    $axion .= '<button type=\"button\" class=\"'.$adjuntar['theme'].'\" title=\"'.$adjuntar['accion'].' (Imagen)\" onclick=\"contrato.getFormAdjuntar(this,\''.$encryptReg.'\')\">';
                    $axion .= '    <i class=\"'.$adjuntar['icono'].'\"></i>';
                    $axion .= '</button>';
                }
                if($clonar['permiso'] ){
                    $axion .= '<button type=\"button\" class=\"'.$clonar['theme'].'\" title=\"'.$clonar['accion'].'\" onclick=\"contrato.getClonar(\''.$encryptReg.'\')\">';
                    $axion .= '    <i class=\"'.$clonar['icono'].'\"></i>';
                    $axion .= '</button>';
                }
                
                $axion .= ' </div>" ';
                
                /*registros a mostrar*/
                $sOutput .= '["'.$chk.'","'.$aRow['nombre'].'","'.$aRow['fecha_creacion'].'","'.$visible.'","'.$estado.'",'.$axion.' ';
                
                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }
    
    /*carga formulario (newContrato.phtml) para nuevo registro: Contrato*/
    public function getFormNewContrato(){
        Obj::run()->View->render("formNewContrato");
    }
    
    /*carga formulario (editContrato.phtml) para editar registro: Contrato*/
    public function getFormEditContrato(){
        Obj::run()->View->render("formEditContrato");
    }
    
    public function getFormClonarContrato(){
        Obj::run()->View->render('formClonarContrato'); 
    }
    
    public function getFormAdjuntar() {    
        Obj::run()->View->idContrato = Formulario::getParam('_idContrato');
        Obj::run()->View->render('formAdjuntarImgContrato');
    }

    
    /*busca data para editar registro: Contrato*/
    public static function findContrato(){
        $data = Obj::run()->contratoModel->findContrato();
            
        return $data;
    }
    
    /*envia datos para grabar registro: Contrato*/
    public function postNewContrato(){
        $data = Obj::run()->contratoModel->mantenimientoContrato();
        
        echo json_encode($data);
    }
    
    /*envia datos para editar registro: Contrato*/
    public function postEditContrato(){
        $data = Obj::run()->contratoModel->mantenimientoContrato();
        
        echo json_encode($data);
    }
    
    /*envia datos para eliminar registros: Contrato*/
    public function postDeleteContratoAll(){
        $data = Obj::run()->contratoModel->mantenimientoContratoAll();
        
        echo json_encode($data);
    }
    
    public function postDesactivar(){
        $data = Obj::run()->contratoModel->postDesactivar();
        
        echo json_encode($data);
    }
    
    public function postActivar(){
        $data = Obj::run()->contratoModel->postActivar();
        
        echo json_encode($data);
    }       
    
    public function postDesactivarVisible(){
        $data = Obj::run()->contratoModel->postDesactivarVisible();
        
        echo json_encode($data);
    }
    
    public function postActivarVisible(){
        $data = Obj::run()->contratoModel->postActivarVisible();
        
        echo json_encode($data);
    }       
    
    public function adjuntarImagen() {

        $p = Obj::run()->contratoModel->_idContrato;
        
        if (!empty($_FILES)) {
            $targetPath = ROOT . 'public' . DS .'img' .DS . 'uploads' . DS;
            $tempFile = $_FILES['file']['tmp_name'];                     
            
            $file = $p.'_'.time().rand(0,10).'_'.$_FILES['file']['name'];               
            $targetFile = $targetPath.$file;            
            
            if (move_uploaded_file($tempFile, $targetFile)) {
               $array = array("img" => $targetPath, "thumb" => $targetPath,'archivo'=>$file);
               
               Obj::run()->contratoModel->adjuntarImagen($file);
            }
            echo json_encode($array);
        }
    }
    
    public function deleteAdjuntar() {
        $data = Obj::run()->contratoModel->deleteAdjuntar();
        
        $file = Formulario::getParam('_img');
        
        $file = str_replace("/","\\", $file);
        
        $targetPath =  $file;
        
        unlink($targetPath);
        
        echo json_encode($data);
    }    
}

?>