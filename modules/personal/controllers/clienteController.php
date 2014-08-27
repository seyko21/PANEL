<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 21-08-2014 07:08:16 
* Descripcion : clienteController.php
* ---------------------------------------
*/    

class clienteController extends Controller{

    public function __construct() {
        $this->loadModel("cliente");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexCliente");
    }
    
    public function getGridCliente(){
        $editar = Session::getPermiso('REGCLED');
        $agre = Session::getPermiso('REGCLAG');
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->clienteModel->getClientes();
        
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
                
                if($aRow['estado'] == 'A'){
                    if($editar['permiso']){
                        $estado = '<button type=\"button\" class=\"btn btn-success btn-xs\" title=\"'.BTN_DESACT.'\" onclick=\"cliente.postDesactivarCliente(this,\''.$encryptReg.'\')\"><i class=\"fa fa-check\"></i> '.LABEL_ACT.'</button>';
                    }else{
                        $estado = '<span class=\"label label-success\">'.LABEL_ACT.'</span>';
                    }
                }elseif($aRow['estado'] == 'I'){
                    if($editar['permiso']){
                        $estado = '<button type=\"button\" class=\"btn btn-danger btn-xs\" title=\"'.BTN_ACT.'\" onclick=\"cliente.postActivarCliente(this,\''.$encryptReg.'\')\"><i class=\"fa fa-ban\"></i> '.LABEL_DESACT.'</button>';
                    }else{
                        $estado = '<span class=\"label label-danger\">'.LABEL_DESACT.'</span>';
                    }
                }
                
                $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" name=\"'.REGCL.'chk_delete[]\" value=\"'.$encryptReg.'\">';
                
                /*datos de manera manual*/
                $sOutput .= '["'.$chk.'","'.$aRow['numerodocumento'].'","'.$aRow['nombrecompleto'].'","'.$estado.'", ';
                

                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';
                
                if($editar['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"btn btn-primary btn-xs\" title=\"'.$editar['accion'].'\" onclick=\"cliente.getEditarCliente(this,\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"fa fa-edit fa-lg\"></i>';
                    $sOutput .= '</button>';
                }
                if($agre['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"btn bg-color-green txt-color-white btn-xs\" title=\"'.LABEL_RC5.'\" onclick=\"cliente.getGridRepresentantes(\''.$encryptReg.'\',\''.$aRow['nombrecompleto'].'\')\">';
                    $sOutput .= '    <i class=\"fa fa-user fa-lg\"></i>';
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
    
    public function getGridRepresentantes(){
        $editar = Session::getPermiso('REGCLED');
        $agre = Session::getPermiso('REGCLAG');
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->clienteModel->getRepresentantes();
        
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
                
                if($aRow['estado'] == 'A'){
                    if($editar['permiso']){
                        $estado = '<button type=\"button\" class=\"btn btn-success btn-xs\" title=\"'.BTN_DESACT.'\" onclick=\"cliente.postDesactivarRepresentante(this,\''.$encryptReg.'\')\"><i class=\"fa fa-check\"></i> '.LABEL_ACT.'</button>';
                    }else{
                        $estado = '<span class=\"label label-success\">'.LABEL_ACT.'</span>';
                    }
                }elseif($aRow['estado'] == 'I'){
                    if($editar['permiso']){
                        $estado = '<button type=\"button\" class=\"btn btn-danger btn-xs\" title=\"'.BTN_ACT.'\" onclick=\"cliente.postActivarRepresentante(this,\''.$encryptReg.'\')\"><i class=\"fa fa-ban\"></i> '.LABEL_DESACT.'</button>';
                    }else{
                        $estado = '<span class=\"label label-danger\">'.LABEL_DESACT.'</span>';
                    }
                }
                
                $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" name=\"'.REGCL.'chk_deleterp[]\" value=\"'.$encryptReg.'\">';
                
                /*datos de manera manual*/
                $sOutput .= '["'.$chk.'","'.$aRow['numerodocumento'].'","'.$aRow['nombrecompleto'].'","'.$estado.'", ';
                

                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';
                
                if($editar['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"btn btn-primary btn-xs\" title=\"'.$editar['accion'].'\" onclick=\"cliente.getEditarRepresentante(this,\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"fa fa-edit fa-lg\"></i>';
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
    
    /*carga formulario (newCliente.phtml) para nuevo registro: Cliente*/
    public function getFormNewCliente(){
        Obj::run()->View->render("formNewCliente");
    }
    
    public function getFormNewRepresentante(){
        Obj::run()->View->render("formNewRepresentante");
    }
    
    /*carga formulario (editCliente.phtml) para editar registro: Cliente*/
    public function getFormEditCliente(){
        Obj::run()->View->render("formEditCliente");
    }
    
    public function getEditarRepresentante(){
        Obj::run()->View->render("formEditarRepresentante");
    }
    
    public static function getDepartamentos(){ 
        $data = Obj::run()->clienteModel->getDepartamentos();
        
        return $data;
    }
    
    public static function getProvinciasEst($dep=''){
        $data = Obj::run()->clienteModel->getProvincias($dep);
        
        return $data;
    }
    
    public static function getUbigeoEst($pro=''){
        $data = Obj::run()->clienteModel->getUbigeo($pro);
        
        return $data;
    }
    
    public static function findCliente(){
        $data = Obj::run()->clienteModel->findCliente();
        
        return $data;
    }
    
    /*envia datos para grabar registro: Cliente*/
    public function postNewCliente(){
        $data = Obj::run()->clienteModel->newCliente();
        
        echo json_encode($data);
    }
    
    public function postNewRepresentante(){
        $data = Obj::run()->clienteModel->newRepresentante();
        
        echo json_encode($data);
    }
    
    /*envia datos para editar registro: Cliente*/
    public function postEditCliente(){
        $data = Obj::run()->clienteModel->editCliente();
        
        echo json_encode($data);
    }
    
    public function postDesactivarCliente(){
        $data = Obj::run()->clienteModel->postDesactivarCliente();
        
        echo json_encode($data);
    }
    
    public function postActivarCliente(){
        $data = Obj::run()->clienteModel->postActivarCliente();
        
        echo json_encode($data);
    }
    
    /*envia datos para eliminar registro: Cliente*/
    public function postDeleteClienteAll(){
        $data = Obj::run()->clienteModel->deleteClienteAll();
        
        echo json_encode($data);
    }
    
    public function postDeleteRepresentanteAll(){
        $data = Obj::run()->clienteModel->deleteClienteAllRp();
        
        echo json_encode($data);
    }
    
}

?>