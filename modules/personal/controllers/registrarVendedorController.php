<?php
/*
* --------------------------------------
* fecha: 10-08-2014 06:08:26 
* Descripcion : registrarVendedorController.php
* --------------------------------------
*/    

class registrarVendedorController extends Controller{

    public function __construct() {
        $this->loadModel(array('modulo'=>'personal','modelo'=>'registrarVendedor'));
        $this->loadController(array('modulo'=>'index','controller'=>'login'));
    }
    
    public function index(){ 
        Obj::run()->View->render('indexVendedor');
    }
    

    public function getGridVendedor() {
        $editar = Session::getPermiso('REGVEED');
        $adjuntar = Session::getPermiso('REGVEAJ');
        $mail = Session::getPermiso('REGVEEE');
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->registrarVendedorModel->getGridVendedor();
        
        if(!isset($rResult['error'])){  
            $iTotal         = isset($rResult[0]['total'])?$rResult[0]['total']:0;
            
            $sOutput = '{';
            $sOutput .= '"sEcho": '.intval($sEcho).', ';
            $sOutput .= '"iTotalRecords": '.$iTotal.', ';
            $sOutput .= '"iTotalDisplayRecords": '.$iTotal.', ';
            $sOutput .= '"aaData": [ ';
            foreach ( $rResult as $key=>$aRow ){
                
                
            
                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_persona']);
                $idUser = Aes::en($aRow['id_usuario']);
                
                if($aRow['estado'] == 'A'){
                    $estado = '<button type=\"button\" class=\"btn btn-success btn-xs\" title=\"Desactivar\" onclick=\"registrarVendedor.postDesactivarVendedor(this,\''.$encryptReg.'\')\">Activo</button>';
                }elseif($aRow['estado'] == 'I'){
                    $estado = '<button type=\"button\" class=\"btn btn-danger btn-xs\" title=\"Activar\" onclick=\"registrarVendedor.postActivarVendedor(this,\''.$encryptReg.'\')\">Inactivo</button>';
                }
                
                $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" name=\"'.T7.'chk_delete[]\" value=\"'.$encryptReg.'\">';
                
                /*datos de manera manual*/
                $sOutput .= '["'.$chk.'","'.$aRow['numerodocumento'].'","'.$aRow['dni'].'","'.$aRow['nombrecompleto'].'","'.$aRow['email'].'","'.$aRow['telefono'].'","'.$estado.'", ';
                

                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';
                
                if($editar['permiso']){
                    $sOutput .= '<button type=\"button\" class=\"btn btn-primary btn-xs\" title=\"'.$editar['accion'].'\" onclick=\"registrarVendedor.getEditarVendedor(this,\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"fa fa-edit fa-lg\"></i>';
                    $sOutput .= '</button>';
                }
                if($adjuntar['permiso']){
                    $sOutput .= '<button type=\"button\" class=\"btn bg-color-orange txt-color-white btn-xs\" title=\"'.$adjuntar['accion'].' documento\" onclick=\"registrarVendedor.getFormAdjuntar(this,\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"fa fa-external-link fa-lg\"></i>';
                    $sOutput .= '</button>';
                }
                if($mail['permiso']){
                    $sOutput .= '<button type=\"button\" class=\"btn bg-color-purple txt-color-white btn-xs\" title=\"Enviar acceso\" onclick=\"registrarVendedor.postAccesoVendedor(this,\''.$idUser.'\',\''.$aRow['nombrecompleto'].'\',\''.$aRow['email'].'\')\">';
                    $sOutput .= '    <i class=\"fa fa-envelope-o fa-lg\"></i>';
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
    
    public function getNuevoVendedor(){ 
        Obj::run()->View->render('nuevoVendedor');
    }
    
    public function getEditarVendedor(){ 
        Obj::run()->View->render('editarVendedor');
    }
    
    public function getFormAdjuntar(){ 
        Obj::run()->View->render('formAdjuntar');
    }
    
    public static function getDepartamentos(){ 
        $data = Obj::run()->registrarVendedorModel->getDepartamentos();
        
        return $data;
    }
    
    public function getProvincias(){
        $data = Obj::run()->registrarVendedorModel->getProvincias();
        
        echo json_encode($data);
    }
    
    public static function getProvinciasEst($dep=''){
        $data = Obj::run()->registrarVendedorModel->getProvincias($dep);
        
        return $data;
    }
    
    public function getUbigeo(){
        $data = Obj::run()->registrarVendedorModel->getUbigeo();
        
        echo json_encode($data);
    }
    
    public static function getUbigeoEst($pro=''){
        $data = Obj::run()->registrarVendedorModel->getUbigeo($pro);
        
        return $data;
    }
    
    public function postNuevoVendedor(){
        $data = Obj::run()->registrarVendedorModel->mantenimientoVendedor();
        
        echo json_encode($data);
    }
    
    public static function findVendedor(){
        $data = Obj::run()->registrarVendedorModel->findVendedor();
        
        return $data;
    }
    
    public function postDeleteVendedorAll(){
        $data = Obj::run()->registrarVendedorModel->mantenimientoVendedorAll();
        
        echo json_encode($data);
    }
    
    public function postDesactivarVendedor(){
        $data = Obj::run()->registrarVendedorModel->postDesactivarVendedor();
        
        echo json_encode($data);
    }
    
    public function postActivarVendedor(){
        $data = Obj::run()->registrarVendedorModel->postActivarVendedor();
        
        echo json_encode($data);
    }
    
    public function postPassVendedor(){
        $data = Obj::run()->registrarVendedorModel->postPassVendedor();        
        echo json_encode($data);
    }
        
}

?>
