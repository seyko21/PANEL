<?php
/*
 * --------------------------------------
 * creado por:  RDCC
 * fecha: 03.01.2014
 * indexController.php
 * --------------------------------------
 */
class configurarUsuariosController extends Controller{
    
    public function __construct() {
        $this->loadModel('configurarUsuarios');
    }

    public function index(){ 
        Obj::run()->View->render('index');
    }

    public function getUsuarios(){ 
        $editar = Session::getPermiso('CUSED');
        $eliminar = Session::getPermiso('CUSDE');
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->configurarUsuariosModel->getUsuarios();
        
        if(!isset($rResult['error'])){  
            $iTotal         = isset($rResult[0]['total'])?$rResult[0]['total']:0;
            
            $sOutput = '{';
            $sOutput .= '"sEcho": '.intval($sEcho).', ';
            $sOutput .= '"iTotalRecords": '.$iTotal.', ';
            $sOutput .= '"iTotalDisplayRecords": '.$iTotal.', ';
            $sOutput .= '"aaData": [ ';
            foreach ( $rResult as $aRow ){
                
                if($aRow['estado'] == 'A'){
                    $estado = '<span class=\"label label-success\">Activo</span>';
                }elseif($aRow['estado'] == 'I'){
                    $estado = '<span class=\"label label-danger\">Inactivo</span>';
                }
                
                /*datos de manera manual*/
                $sOutput .= '["'.$aRow['usuario'].'","'.$aRow['nombrecompleto'].'","'.$aRow['roles'].'","'.$estado.'","'.$aRow['fecha_acceso'].'", ';

                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_usuario']);

                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';
                
                if($editar['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"btn btn-primary btn-xs\" title=\"'.$editar['accion'].'\" onclick=\"configurarUsuarios.getEditUsuario(this,\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"fa fa-edit fa-lg\"></i>';
                    $sOutput .= '</button>';
                }
                if($eliminar['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"btn btn-danger btn-xs\" title=\"'.$eliminar['accion'].'\" onclick=\"configurarUsuarios.postDeleteUsuario(\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"fa fa-ban fa-lg\"></i>';
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
    
    public function getNuevoUsuario(){ 
        Obj::run()->View->render('nuevoUsuario');
    }
    
    public function getEditUsuario(){ 
        Obj::run()->View->render('editarUsuario');
    }
    
    public static function getUsuario(){ 
        $rResult = Obj::run()->configurarUsuariosModel->getUsuario();
        return $rResult;
    }
    
    public static function getRolesUser(){ 
        $rResult = Obj::run()->configurarUsuariosModel->getRolesUser();
        return $rResult;
    }
    
    public function getFormEmpleado(){ 
        Obj::run()->View->render('buscarEmpleado');
    }
    
    public static function getRoles(){
        $rResult = Obj::run()->configurarUsuariosModel->getRoles();
        return $rResult;
    }
    
    public function getEmpleados(){ 
        $tab = $this->post('_tab');
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->configurarUsuariosModel->getEmpleados();
        
        if(!isset($rResult['error'])){  
            $iTotal         = isset($rResult[0]['total'])?$rResult[0]['total']:0;
            
            $sOutput = '{';
            $sOutput .= '"sEcho": '.intval($sEcho).', ';
            $sOutput .= '"iTotalRecords": '.$iTotal.', ';
            $sOutput .= '"iTotalDisplayRecords": '.$iTotal.', ';
            $sOutput .= '"aaData": [ ';
            foreach ( $rResult as $key=>$aRow ){
                /*antes de enviar id se encrypta*/
                $encryptReg  = Aes::en($aRow['persona']);
                $encryptReg2 = Aes::en($aRow['id_persona']);
                
                $nom = '<a href=\"javascript:;\" onclick=\"configurarUsuariosScript.setEmpleado({'.$tab.'txt_empleado:\''.$encryptReg.'\','.$tab.'txt_idpersona:\''.$encryptReg2.'\', '.$tab.'txt_empleadodesc:\''.$aRow['nombrecompleto'].'\', '.$tab.'txt_email:\''.$aRow['email'].'\'});\" >'.$aRow['nombrecompleto'].'</a>';
                
                /*datos de manera manual*/
                $sOutput .= '["'.(++$key).'","'.$nom.'" ';

                $sOutput .= '],';
            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;
    }
    
    public function postNuevoUsuario(){
        $data = Obj::run()->configurarUsuariosModel->mantenimientoUsuario();
        
        echo json_encode($data);
    }
    
    public function postEditarUsuario(){
        $data = Obj::run()->configurarUsuariosModel->editarUsuario();
        
        echo json_encode($data);
    }
    
}

?>
