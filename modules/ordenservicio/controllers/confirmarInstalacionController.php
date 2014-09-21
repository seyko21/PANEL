<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 21-09-2014 05:09:42 
* Descripcion : confirmarInstalacionController.php
* ---------------------------------------
*/    

class confirmarInstalacionController extends Controller{

    public function __construct() {
        $this->loadModel("confirmarInstalacion");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexConfirmarInstalacion");
    }
    
    public function getGridConfirmarInstalacion(){
        $archivo   = Session::getPermiso('COINSAJ');
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->confirmarInstalacionModel->getConfirmarInstalacion();
        
        $num = Obj::run()->confirmarInstalacionModel->_iDisplayStart;
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
                
                if(empty($aRow['imagen'])){
                    $conf = '<span class=\"label label-danger\">No</span>';
                }else{
                    $conf = '<span class=\"label label-success\">Si</span>';
                }
                
                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_ordenserviciod']);
                
                /*registros a mostrar*/
                $sOutput .= '["'.$aRow['orden_numero'].'","'.$aRow['codigo'].'","'.$aRow['ubicacion'].' - '.$aRow['descripcion'].'","'.$aRow['fecha_instalacion'].'","'.$conf.'", ';
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';
                 
                if($archivo['permiso']){
                    $sOutput .= '<button type=\"button\" class=\"'.$archivo['theme'].'\" title=\"'.COINS_2.'\" onclick=\"confirmarInstalacion.getFormConfirmarInstalacion(this,\''.$encryptReg.'\',\''.$aRow['codigo'].'\')\">';
                    $sOutput .= '    <i class=\"'.$archivo['icono'].'\"></i>';
                    $sOutput .= '</button>';
                }
                
                $sOutput .= ' </div>" ';
                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }
    
    public function getFormConfirmarInstalacion(){
        Obj::run()->View->render("formConfirmarInstalacion");
    }
    
    public function postConfirmarInstalacion(){
        $data = Obj::run()->confirmarInstalacionModel->confirmarInstalacion();
        
        echo json_encode($data);
    }
    
    public function getImagen(){
        $data = Obj::run()->confirmarInstalacionModel->getImagen();
        
        return $data;
    }
    
    public function postImagen(){
        $p = Obj::run()->confirmarInstalacionModel->_idOrdenDetalle;
        
        if (!empty($_FILES)) {
            $targetPath = ROOT . 'public' . DS .'img' .DS . 'confirmacion' . DS;
            $tempFile = $_FILES['file']['tmp_name'];
            $file = $p.'_'.$_FILES['file']['name'];
            $targetFile = $targetPath.$file;
            if (move_uploaded_file($tempFile, $targetFile)) {
               $array = array("img" => $targetPath, "thumb" => $targetPath,'archivo'=>$file);
               
               Obj::run()->confirmarInstalacionModel->insertImagen($file);
            }
            echo json_encode($array);
        }
    }
    
    public function deleteImagen() {
        $data = Obj::run()->confirmarInstalacionModel->deleteImagen();
        
        $file = Formulario::getParam('_doc');
        
        $file = str_replace("/","\\", $file);
        
        $targetPath =  $file;
        
        unlink($targetPath);
        
        echo json_encode($data);
    }
    
}

?>