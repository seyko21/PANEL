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
        //$this->loadModel('registrarVendedor');
    }
    
    public function index(){ 
        Obj::run()->View->render('indexVendedor');
    }
    
    public function getGridVendedor() {
        $editar = Session::getPermiso('REGVEED');
        $adjuntar = Session::getPermiso('REGVEADA');
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
                    $sOutput .= '<button type=\"button\" class=\"btn bg-color-orange txt-color-white btn-xs\" title=\"'.$adjuntar['accion'].'\" onclick=\"registrarVendedor.getFormAntecedentes(this,\''.$encryptReg.'\')\">';
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
    
    /*llama html para actualizar clave de vendedor*/
    public function confirm($id,$nom){
        Obj::run()->View->vendedor = $id;
        Obj::run()->View->nombres = $nom;
        
        $v = AesCtr::de($id);
        
        if(is_numeric($v)){
            Obj::run()->View->render('newClaveVendedor',false);
        }else{
            $this->redirect('index');
        }
        
    }

    public function postPassVendedor(){
        $data = Obj::run()->registrarVendedorModel->postPassVendedor();
        
        echo json_encode($data);
    }

    public function postAccesoVendedor(){
        $idVendedor     = Formulario::getParam('_idVendedor');
        $nomVendedor    = Formulario::getParam('_vendedor');
        $email          = Formulario::getParam('_mail');
        
        
        $cad = explode('@',$email);
               
       echo $body ='
            <h3>ACCESOS</h3>
            <h3>Estimado: '.$nomVendedor.'</h3>
        <table border="1" style="border-collapse:collapse">
           <tr>
                <td style="text-align:center">
                    <p>Usted a sido agregado como usuario a SEVEND.</p>
                    <p><a href="'.BASE_URL.'personal/registrarVendedor/confirm/'.$idVendedor.'/'.AesCtr::en($nomVendedor).'">Pulse aquí</a> para ingresar al sistema.</p>
                </td>
           </tr>
        </table>';
        
        
        $mail             = new PHPMailer(); // defaults to using php "mail()"
 
//        $body             = file_get_contents('contents.html');
//        $body             = eregi_replace("[\]",'',$html);

        $mail->AddReplyTo("name@gmail.com","First Last");

        $mail->SetFrom('name@gmail.com', 'First Last');

        $mail->AddReplyTo("name@gmail.com","First Last");

        $mail->AddAddress($email, 'admin@adm.com');

        $mail->Subject    = "Accesos a SEVEND";

        $mail->MsgHTML($body);

//        $mail->AddAttachment("public/img/phpmailer.gif");      // attachment
//        $mail->AddAttachment("public/img/phpmailer_mini.gif"); // attachment
        
        $data = array('result'=>2);
        /*validar si dominio de correo existe*/
        if(checkdnsrr($cad[1])){
            if($mail->Send()) {
                $data = array('result'=>1);
            } else {
                $data = array('result'=>2);
            }
        }
        
        echo json_encode($data);
    }
    
}

?>