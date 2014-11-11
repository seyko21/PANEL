<?php
/*
 * Documento   : loginController
 * Creado      : 30-ene-2014, 19:25:17
 * Autor       : RDCC
 * Descripcion :
 */
class loginController extends Controller{
    
    public function __construct() {
        $this->loadModel(array('modulo'=>'index','modelo'=>'login'));
        $this->loadController(array('modulo' => 'usuarios', 'controller' => 'configurarUsuarios'));
    }

    public function index() {
        $data = Obj::run()->loginModel->getValidar();
  
        if(isset($data['id_usuario'])){
            Session::set('sys_idUsuario', $data['id_usuario']);
            Session::set('sys_idPersona', $data['id_persona']);
            Session::set('sys_usuario', $data['usuario']);
            Session::set('sys_nombreUsuario', $data['nombrecompleto']);
            Obj::run()->loginModel->postLastLogin();
            /*los roles*/
            Session::set('sys_roles', Obj::run()->loginModel->getRoles());
            
            $rol = Session::get('sys_roles');
            /*asignando rol por defecto*/
            Session::set('sys_defaultRol',$rol[0]['id_rol']);
                        
        }
        echo json_encode($data);
    }
    
    public function logout(){
        Session::destroy();
        $result = array('result' =>1);
        echo json_encode($result);
    }
    
    public function postAcceso(){
  
        $email = Formulario::getParam('txtUser');
        $data = Obj::run()->loginModel->getBuscarUsuario();     
        
        if ($data == false){
            $data = array('result' => 3);
            echo json_encode($data);
            return ;
        }
                
        //Extraer datos de Correo
        $idd = Aes::en($data['id_usuario']);
        $nombres = $data['nombrecompleto'];
        
        $data = Obj::run()->configurarUsuariosController->getParametros('EMAIL');        
        $data1 = Obj::run()->configurarUsuariosController->getParametros('EMCO');        
        $emailEmpresa = $data['valor'];
        $empresa = $data1['valor'];
        $persona = str_replace(' ', '_',$nombres );
        $body = '
            <h3><b>Recuperar Acceso</b></h3>
            <h3>Estimado: ' . $nombres . '</h3>            
            <table border="0" style="border-collapse:collapse">
               <tr>
                    <td>
                        <p>El motivo del mensaje es porque Usted a solicitado recuperar su clave del sistema en '.LB_EMPRESA.'.</p>
                        <p><a href="' . BASE_URL . 'usuarios/configurarUsuarios/confirm/'.$idd.'/'.$persona.'">Pulse aqui</a> para ingresar al sistema.</p>
                    </td>
               </tr>
            </table>';

        $mail = new PHPMailer(); // defaults to using php "mail()"

        //$mail->IsSMTP();
    
        $mail->SetFrom($emailEmpresa, $empresa);

        $mail->AddAddress($email, $nombres);

        $mail->Subject = "Proceso de recuperacion de Acceso en ".LB_EMPRESA;

        $mail->MsgHTML($body);

        if ($mail->Send()) {
            $data = array('result' => 1);
        } else {
            $data = array('result' => 2);
        }

        echo json_encode($data);
    }
    
}
?>
