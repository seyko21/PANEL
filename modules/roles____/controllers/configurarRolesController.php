<?php
/*
 * --------------------------------------
 * creado por:  ...
 * fecha: 03.01.2014
 * configurarRolesController.php
 * --------------------------------------
 */
class configurarRolesController extends Controller{
    
    public function __construct() {
        $this->loadModel('configurarRoles');
    }

    public function index(){
        Obj::run()->View->render('index');
    }

    public function getRoles(){ 
        $accesos  = Session::getPermiso('CROAC');
        $eliminar = Session::getPermiso('CRODE');
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->configurarRolesModel->getRoles();
        
        if(!isset($rResult['error'])){  
            $iTotal         = isset($rResult[0]['total'])?$rResult[0]['total']:0;
            
            $sOutput = '{';
            $sOutput .= '"sEcho": '.intval($sEcho).', ';
            $sOutput .= '"iTotalRecords": '.$iTotal.', ';
            $sOutput .= '"iTotalDisplayRecords": '.$iTotal.', ';
            $sOutput .= '"aaData": [ ';
            foreach ( $rResult as $aRow ){
                
                if($aRow['activo'] == 1){
                    $estado = '<span class=\"label label-success\">'.$aRow['estado'].'</span>';
                }else{
                    $estado = '<span class=\"label label-danger\">'.$aRow['estado'].'</span>';
                }
                
                /*datos de manera manual*/
                $sOutput .= '["'.$aRow['id_rol'].'","'.$aRow['rol'].'","'.$estado.'", ';

                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_rol']);

                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div data-key=\"'.$encryptReg.'\" class=\"btn-group\">';
                
                
                
                $sOutput .= '<button type=\"button\" class=\"btn btn-primary btnEditarRol\" title=\"Editar\" onclick=\"configurarRoles.getRol(\''.$encryptReg.'\')\">';
                $sOutput .= '    <i class=\"fa fa-edit fa-lg\"></i>';
                $sOutput .= '</button>';
                
                if($eliminar['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"btn btn-danger btnDeleteRol\" title=\"'.$eliminar['accion'].'\" onclick=\"configurarRoles.postDeleteRol(\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"fa fa-ban fa-lg\"></i>';
                    $sOutput .= '</button>';
                }
                
                if($accesos['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"btn btn-primary btnAccesosRol\" title=\"'.$accesos['accion'].'\" onclick=\"configurarRoles.getAccesos(\''.$encryptReg.'\',\''.$aRow['rol'].'\')\">';
                    $sOutput .= '    <i class=\"fa fa-unlock fa-lg\"></i>';
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
    
    public function getNuevoRol(){ 
        Obj::run()->View->render('nuevoRol');
    }
    
    public function getEditRol(){ 
        Obj::run()->View->key = $this->post('_key'); 
        Obj::run()->View->render('editarRol');
    }
    
    public static function getRol($idRol){ 
        $data = Obj::run()->configurarRolesModel->getRol($idRol);
        
        return $data;
    }
    
    public static function getDominios($idRol){ 
        $data = Obj::run()->configurarRolesModel->consultasRol(2,$idRol);
        
        return $data;
    }
    
    public static function getModulos($idDominio){ 
        $data = Obj::run()->configurarRolesModel->consultasRol(3,$idDominio);
        
        return $data;
    }
    
    public static function getMenuPrincipal($idModulo){ 
        $data = Obj::run()->configurarRolesModel->consultasRol(4,$idModulo);
        
        return $data;
    }
    
    public static function getMenuOpciones($idRol,$idMenuPrincipal){ 
        $data = Obj::run()->configurarRolesModel->consultarMenuOpciones(5,$idRol,$idMenuPrincipal);
        
        return $data;
    }
    
    public static function getAcciones($idRolOpciones){ 
        $data = Obj::run()->configurarRolesModel->consultasRol(6,$idRolOpciones);
        
        return $data;
    }
    
    public function getAccesos(){ 
        Obj::run()->View->keyRol = $this->post('_key');
        Obj::run()->View->render('accesos');
    }
    
    public function getOpcionRolAxions(){ 
        Obj::run()->View->_rolOpcion = $this->post('_rolOpcion');
        Obj::run()->View->render('accesosRolOpcion');
    }
    
    public function postRol(){ 
        $data = Obj::run()->configurarRolesModel->mantenimientoRol();
        
        echo json_encode($data);
    }
    
    public function postDeleteRol(){ 
        $data = Obj::run()->configurarRolesModel->mantenimientoRol();
        
        echo json_encode($data);
    }
    
    public function postOpcion(){ 
        $data = Obj::run()->configurarRolesModel->mantenimientoRolOpcion();
        
        echo json_encode($data);
    }
    
    public function postAccionOpcionRol(){ 
        $data = Obj::run()->configurarRolesModel->mantenimientoRolOpcionAccion();
        
        echo json_encode($data); 
    }
    
}

?>
