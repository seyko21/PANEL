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
        $this->loadModel(array('modulo' => 'usuarios', 'modelo' => 'configurarUsuarios'));        
    }

    public function index(){ 
        Obj::run()->View->render('index');
    }

    public function getUsuarios(){ 
        $editar = Session::getPermiso('CUSED');
        $eliminar = Session::getPermiso('CUSDE');
        $mail = Session::getPermiso('CUSEE');
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
                    $sOutput .= '<button type=\"button\" class=\"'.$editar['theme'].'\" title=\"'.$editar['accion'].'\" onclick=\"configurarUsuarios.getEditUsuario(this,\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"'.$editar['icono'].'\"></i>';
                    $sOutput .= '</button>';
                }
                if($eliminar['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"'.$eliminar['theme'].'\" title=\"'.$eliminar['accion'].'\" onclick=\"configurarUsuarios.postDeleteUsuario(\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"'.$eliminar['icono'].'\"></i>';
                    $sOutput .= '</button>';
                } 
                if ($mail['permiso']) {
                    $sOutput .= '<button type=\"button\" class=\"'.$mail['theme'].'\" title=\"' . $mail['accion'] . '\" onclick=\"configurarUsuarios.postAcceso(this,\'' . $encryptReg . '\',\'' . $aRow['nombrecompleto'] . '\',\'' . $aRow['usuario'] . '\')\">';
                    $sOutput .= '    <i class=\"'.$mail['icono'].'\"></i>';
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
    
    public function postDeleteUsuario(){
        $data = Obj::run()->configurarUsuariosModel->deleteUsuario();
        
        echo json_encode($data);
    }
    public function postPass() {
         $data = Obj::run()->configurarUsuariosModel->postPass();
        echo json_encode($data);
    }
    
    public function getParametros($p) {
        $data = Obj::run()->configurarUsuariosModel->getParametros($p);
        return $data;
    }   

    public function postAcceso() {
        $idd = Formulario::getParam('_id');
        $nombres = Formulario::getParam('_nombres');
        $email = Formulario::getParam('_mail');
        $data = Obj::run()->configurarUsuariosController->getParametros('EMAIL');        
        $data1 = Obj::run()->configurarUsuariosController->getParametros('EMCO');        
        $emailEmpresa = $data['valor'];
        $empresa = $data1['valor'];
        $persona = str_replace(' ', '_',$nombres );
        $body = '
            <h3><b>ACCESOS</b></h3>
            <h3>Estimado: ' . $nombres . '</h3>
            <p>Este es un mensaje automatico enviado desde '.BASE_URL.'</p>
            <table border="0" style="border-collapse:collapse">
               <tr>
                    <td>
                        <p>El motivo del mensaje es porque Usted a sido agregado como usuario al sistema de '.LB_EMPRESA.'.</p>
                        <p><a href="' . BASE_URL . 'usuarios/configurarUsuarios/confirm/'.$idd.'/'.$persona.'">Pulse aqui</a> para ingresar al sistema.</p>
                    </td>
               </tr>
            </table>';

        $mail = new PHPMailer(); // defaults to using php "mail()"

        //$mail->IsSMTP();
    
        $mail->SetFrom($emailEmpresa, $empresa);

        $mail->AddAddress($email, $nombres);

        $mail->Subject = "Accesos a ".LB_EMPRESA;

        $mail->MsgHTML($body);

        /* validar si dominio de correo existe */
        if ($mail->Send()) {
            $data = array('result' => 1);
        } else {
            $data = array('result' => 2);
        }

        echo json_encode($data);
    }

    /* llama html para actualizar clave de Socio */
    public function confirm($id, $nom) {
        Obj::run()->View->idd = $id;
        Obj::run()->View->nombres = str_replace('_', ' ',$nom );
        
        $v = AesCtr::de($id);

        Obj::run()->View->render('newClavePersona', false);
    }    
}

?>
