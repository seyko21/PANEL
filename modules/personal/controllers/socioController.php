<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 03-09-2014 02:09:26 
* Descripcion : socioController.php
* ---------------------------------------
*/    

class socioController extends Controller{

    public function __construct() {
        $this->loadModel(array('modulo'=>'personal','modelo'=>'socio'));
        $this->loadController(array('modulo'=>'personal','controller'=>'registrarVendedor')); 
    }
    
    public function index(){ 
        Obj::run()->View->render("indexSocio");
    }
    
  public function getGridSocio() {
        $editar = Session::getPermiso('REGSOED');        
        $mail = Session::getPermiso('REGSOEE');
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->socioModel->getGridSocio();
        
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
                    $estado = '<button type=\"button\" class=\"btn btn-success btn-xs\" title=\"Desactivar\" onclick=\"socio.postDesactivarSocio(this,\''.$encryptReg.'\')\">Activo</button>';
                }elseif($aRow['estado'] == 'I'){
                    $estado = '<button type=\"button\" class=\"btn btn-danger btn-xs\" title=\"Activar\" onclick=\"socio.postActivarSocio(this,\''.$encryptReg.'\')\">Inactivo</button>';
                }
                if ($aRow['monto_invertido'] == 0)
                    $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" name=\"'.TAB_SOCIO.'chk_delete[]\" value=\"'.$encryptReg.'\">';
                else {
                    $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" name=\"'.TAB_SOCIO.'chk_delete[]\" disabled>';
                }
                
                /*datos de manera manual*/
                $sOutput .= '["'.$chk.'","'.$aRow['numerodocumento'].'","'.$aRow['nombrecompleto'].'","'.$aRow['email'].'","'.$aRow['telefono'].'","'.number_format($aRow['monto_invertido'],2).'","'.number_format($aRow['monto_asignado'],2).'","'.number_format($aRow['monto_saldo'],2).'","'.$estado.'", ';
                

                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';
                
                if($editar['permiso']){
                    $sOutput .= '<button type=\"button\" class=\"'.$editar['theme'].'\" title=\"'.$editar['accion'].'\" onclick=\"socio.getFormEditSocio(this,\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"'.$editar['icono'].'\"></i>';
                    $sOutput .= '</button>';
                }       
                if ($mail['permiso']) {
                    $sOutput .= '<button type=\"button\" class=\"'.$mail['theme'].'\" title=\"' . $mail['accion'] . '\" onclick=\"socio.postAcceso(this,\'' . $idUser . '\',\'' . $aRow['nombrecompleto'] . '\',\'' . $aRow['email'] . '\')\">';
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
   
    
    /*carga formulario (newSocio.phtml) para nuevo registro: Socio*/
    public function getFormNewSocio(){
        Obj::run()->View->render("formNewSocio");
    }
    
    /*carga formulario (editSocio.phtml) para editar registro: Socio*/
    public function getFormEditSocio(){
        Obj::run()->View->render("formEditSocio");
    }
    
    /*envia datos para grabar registro: Socio*/
    public function postNewSocio(){
        $data = Obj::run()->socioModel->mantenimientoSocio();        
        echo json_encode($data);
    }
        
    /*envia datos para editar registro: Socio*/
    public function postEditSocio(){
        $data = Obj::run()->socioModel->mantenimientoSocio();        
        echo json_encode($data);
    }
    
    /*envia datos para eliminar registro: Socio*/
    public function postDeleteSocioAll(){
        $data = Obj::run()->socioModel->deleteSocioAll();        
        echo json_encode($data);
    }
    
    public static function findSocio(){
        $data = Obj::run()->socioModel->findSocio();
        
        return $data;
    }  
    
    public static function getDepartamentos(){ 
        $data = Obj::run()->registrarVendedorModel->getDepartamentos();        
        return $data;
    }
    
    public function getProvincias(){
        $data = Obj::run()->registrarVendedorModel->getProvincias();        
        echo $data;
    }
    
    public static function getProvinciasEst($dep=''){
        $data = Obj::run()->registrarVendedorModel->getProvincias($dep);        
        return $data;
    }
    
    public function getUbigeo(){
        $data = Obj::run()->registrarVendedorModel->getUbigeo();        
        echo $data;
    }
    
    public static function getUbigeoEst($pro=''){
        $data = Obj::run()->registrarVendedorModel->getUbigeo($pro);        
        return $data;
    }    
    public function postDesactivarSocio(){
        $data = Obj::run()->socioModel->postDesactivarSocio();
        
        echo json_encode($data);
    }
    
    public function postActivarSocio(){
        $data = Obj::run()->socioModel->postActivarSocio();
        
        echo json_encode($data);
    }    
  
    public function postPass() {
        $data = Obj::run()->registrarVendedorController->postPassVendedor();
        echo $data;
    }

    public function postAcceso() {
        $idd = Formulario::getParam('_id');
        $nombres = Formulario::getParam('_nombres');
        $email = Formulario::getParam('_mail');
        $data = Obj::run()->registrarVendedorController->getParametros('EMAIL');        
        $data1 = Obj::run()->registrarVendedorController->getParametros('EMCO');        
        $emailEmpresa = $data['valor'];
        $empresa = $data1['valor'];
        $persona = str_replace(' ', '_',$nombres );
        $body = '
            <h3><b>ACCESOS</b></h3>
            <h3>Estimado: ' . $nombres . '</h3>
            <p>Este es un mensaje automatico enviado desde www.sevend.pe</p>
            <table border="0" style="border-collapse:collapse">
               <tr>
                    <td>
                        <p>El motivo del mensaje es porque Usted a sido agregado como usuario al sistema de SEVEND.</p>
                        <p><a href="' . BASE_URL . 'personal/socio/confirm/'.$idd.'/'.$persona.'">Pulse aqui</a> para ingresar al sistema.</p>
                    </td>
               </tr>
            </table>';

        $mail = new PHPMailer(); // defaults to using php "mail()"

        //$mail->IsSMTP();
    
        $mail->SetFrom($emailEmpresa, $empresa);

        $mail->AddAddress($email, $nombres);

        $mail->Subject = "Accesos a SEVEND";

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
        Obj::run()->View->vendedor = $id;
        Obj::run()->View->nombres = str_replace('_', ' ',$nom );
        
        $v = AesCtr::de($id);

        Obj::run()->View->render('newClaveSocio', false);
    } 
}

?>