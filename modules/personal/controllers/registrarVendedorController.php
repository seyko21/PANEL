<?php

/*
 * --------------------------------------
 * fecha: 10-08-2014 06:08:26 
 * Descripcion : registrarVendedorController.php
 * --------------------------------------
 */

class registrarVendedorController extends Controller {

    public function __construct() {
        $this->loadModel(array('modulo' => 'personal', 'modelo' => 'registrarVendedor'));
        $this->loadController(array('modulo' => 'index', 'controller' => 'login'));
        $this->loadController(array('modulo' => 'usuarios', 'controller' => 'configurarUsuarios'));
    }

    public function index() {
        Obj::run()->View->render('indexVendedor');
    }
    
    public function getFormViewFoto(){ 
        Obj::run()->View->render('formViewFoto');
    } 

    public function getGridVendedor() {
        $editar = Session::getPermiso('REGVEED');
        $adjuntar = Session::getPermiso('REGVEAJ');
        $mail = Session::getPermiso('REGVEEE');

        $sEcho = $this->post('sEcho');

        $rResult = Obj::run()->registrarVendedorModel->getGridVendedor();

        if (!isset($rResult['error'])) {
            $iTotal = isset($rResult[0]['total']) ? $rResult[0]['total'] : 0;

            $sOutput = '{';
            $sOutput .= '"sEcho": ' . intval($sEcho) . ', ';
            $sOutput .= '"iTotalRecords": ' . $iTotal . ', ';
            $sOutput .= '"iTotalDisplayRecords": ' . $iTotal . ', ';
            $sOutput .= '"aaData": [ ';
            foreach ($rResult as $key => $aRow) {



                /* antes de enviar id se encrypta */
                $encryptReg = Aes::en($aRow['id_persona']);
                $idUser = Aes::en($aRow['id_usuario']);

                if ($aRow['estado'] == 'A') {
                    $estado = '<button type=\"button\" class=\"btn btn-success btn-xs\" title=\"Desactivar\" onclick=\"registrarVendedor.postDesactivarVendedor(this,\'' . $encryptReg . '\')\">Activo</button>';
                } elseif ($aRow['estado'] == 'I') {
                    $estado = '<button type=\"button\" class=\"btn btn-danger btn-xs\" title=\"Activar\" onclick=\"registrarVendedor.postActivarVendedor(this,\'' . $encryptReg . '\')\">Inactivo</button>';
                }

                $chk = '<input id=\"c_' . ( ++$key) . '\" type=\"checkbox\" name=\"' . T7 . 'chk_delete[]\" value=\"' . $encryptReg . '\">';
                
                $ruta = BASE_URL.'public/files/fotos/'.$aRow['foto'];
                $foto = '<img border=\"0\" src=\"'.BASE_URL.'public/img/sin_foto.jpg\" width=\"70px\" height=\"40px\">';
                if($aRow['foto'] != ''){
                    $foto = '<img border=\"0\" src=\"'.$ruta.'\" width=\"70px\" height=\"40px\" onclick=\"registrarVendedor.getFormViewFoto(\''.AesCtr::en($ruta).'\');\" style=\"cursor:pointer\">';
                }
                /* datos de manera manual */
                $sOutput .= '["' . $chk . '","'.$foto.'","' . $aRow['numerodocumento'] . '","' . $aRow['dni'] . '","' . $aRow['nombrecompleto'] . '","' . $aRow['email'] . '","' . $aRow['telefono'] . '","' . $estado . '", ';


                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';

                if ($editar['permiso']) {
                    $sOutput .= '<button type=\"button\" class=\"'.$editar['theme'].'\" title=\"' . $editar['accion'] . '\" onclick=\"registrarVendedor.getEditarVendedor(this,\'' . $encryptReg . '\')\">';
                    $sOutput .= '    <i class=\"'.$editar['icono'].'\"></i>';
                    $sOutput .= '</button>';
                }
                if ($adjuntar['permiso']) {
                    $sOutput .= '<button type=\"button\" class=\"'.$adjuntar['theme'].'\" title=\"' . $adjuntar['accion'] . ' documento\" onclick=\"registrarVendedor.getFormAdjuntar(this,\'' . $encryptReg . '\')\">';
                    $sOutput .= '    <i class=\"'.$adjuntar['icono'].'\"></i>';
                    $sOutput .= '</button>';
                }
                if ($mail['permiso']) {
                    $sOutput .= '<button type=\"button\" class=\"'.$mail['theme'].'\" title=\"' . $mail['accion'] . '\" onclick=\"registrarVendedor.postAccesoVendedor(this,\'' . $idUser . '\',\'' . $aRow['nombrecompleto'] . '\',\'' . $aRow['email'] . '\')\">';
                    $sOutput .= '    <i class=\"'.$mail['icono'].'\"></i>';
                    $sOutput .= '</button>';
                }
                $sOutput .= ' </div>" ';

                $sOutput = substr_replace($sOutput, "", -1);
                $sOutput .= '],';
            }
            $sOutput = substr_replace($sOutput, "", -1);
            $sOutput .= '] }';
        } else {
            $sOutput = $rResult['error'];
        }

        echo $sOutput;
    }

    public function getNuevoVendedor() {
        Obj::run()->View->render('nuevoVendedor');
    }

    public function getEditarVendedor() {
        Obj::run()->View->render('editarVendedor');
    }

    public function getFormAdjuntar() {    
        Obj::run()->View->idVendedor = Formulario::getParam('_idPersona');
        Obj::run()->View->render('formAdjuntar');
    }

    public static function getDepartamentos() {
        $data = Obj::run()->registrarVendedorModel->getDepartamentos();

        return $data;
    }

    public function getProvincias() {
        $data = Obj::run()->registrarVendedorModel->getProvincias();

        echo json_encode($data);
    }

    public static function getProvinciasEst($dep = '') {
        $data = Obj::run()->registrarVendedorModel->getProvincias($dep);

        return $data;
    }

    public function getUbigeo() {
        $data = Obj::run()->registrarVendedorModel->getUbigeo();

        echo json_encode($data);
    }

    public static function getUbigeoEst($pro = '') {
        $data = Obj::run()->registrarVendedorModel->getUbigeo($pro);

        return $data;
    }

    public function postNuevoVendedor() {
        $data = Obj::run()->registrarVendedorModel->mantenimientoVendedor();

        echo json_encode($data);
    }

    public static function findVendedor() {
        $data = Obj::run()->registrarVendedorModel->findVendedor();

        return $data;
    }

    public function postDeleteVendedorAll() {
        $data = Obj::run()->registrarVendedorModel->mantenimientoVendedorAll();

        echo json_encode($data);
    }

    public function postDesactivarVendedor() {
        $data = Obj::run()->registrarVendedorModel->postDesactivarVendedor();

        echo json_encode($data);
    }

    public function postActivarVendedor() {
        $data = Obj::run()->registrarVendedorModel->postActivarVendedor();

        echo json_encode($data);
    }  
    
    public function postAccesoVendedor() {
        $data = Obj::run()->configurarUsuariosController->postAcceso();
        echo $data;
    }

    public function adjuntarDocumento() {
//        header("Access-Control-Allow-Origin: *");
//        header('Content-type: application/json');
        $p = Obj::run()->registrarVendedorModel->_idPersona;
        
        if (!empty($_FILES)) {
            $targetPath = ROOT . 'public' . DS .'files' .DS . 'docs' . DS;
            $tempFile = $_FILES['file']['tmp_name'];
            $file = $p.'_'.$_FILES['file']['name'];
            $targetFile = $targetPath.$file;
            if (move_uploaded_file($tempFile, $targetFile)) {
               $array = array("img" => $targetPath, "thumb" => $targetPath,'archivo'=>$file);
               
               Obj::run()->registrarVendedorModel->adjuntarDocumento($file);
            }
            echo json_encode($array);
        }
    }
    
    public function deleteAdjuntar() {
        $data = Obj::run()->registrarVendedorModel->deleteAdjuntar();
        
        $file = Formulario::getParam('_doc');
        
        $file = str_replace("/","\\", $file);
        
        $targetPath =  $file;
        
        unlink($targetPath);
        
        echo json_encode($data);
    }

}

?>
