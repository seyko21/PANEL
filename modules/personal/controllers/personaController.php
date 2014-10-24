<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 16-09-2014 03:09:14 
* Descripcion : personaController.php
* ---------------------------------------
*/    

class personaController extends Controller{

    public function __construct() {
        $this->loadModel(array('modulo'=>'personal','modelo'=>'persona'));
        $this->loadController(array('modulo'=>'personal','controller'=>'registrarVendedor')); 
    }
    
    public function index(){ 
        Obj::run()->View->render("indexPersona");
    }
    
    public function getGridPersona(){
        $editar = Session::getPermiso('REPERED');        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->personaModel->getGridPersona();
        
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
                    $estado = '<button type=\"button\" class=\"btn btn-success btn-xs\" title=\"Desactivar\" onclick=\"persona.postDesactivarPersona(this,\''.$encryptReg.'\')\">Activo</button>';
                }elseif($aRow['estado'] == 'I'){
                    $estado = '<button type=\"button\" class=\"btn btn-danger btn-xs\" title=\"Activar\" onclick=\"persona.postActivarPersona(this,\''.$encryptReg.'\')\">Inactivo</button>';
                }
                
                $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" name=\"'.REPER.'chk_delete[]\" value=\"'.$encryptReg.'\">';
                                
                /*datos de manera manual*/
                $sOutput .= '["'.$chk.'","'.$aRow['nombrecompleto'].'","'.$aRow['email'].'","'.$aRow['telefono'].'","'.$aRow['dni'].'","'.$aRow['ciudad'].'","'.$estado.'", ';
                

                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';
                
                if($editar['permiso']){
                    $sOutput .= '<button type=\"button\" class=\"'.$editar['theme'].'\" title=\"'.$editar['accion'].'\" onclick=\"persona.getFormEditPersona(this,\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"'.$editar['icono'].'\"></i>';
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
    
    /*carga formulario (newPersona.phtml) para nuevo registro: Persona*/
    public function getFormNewPersona(){
        Obj::run()->View->render("formNewPersona");
    }
    
   /*carga formulario (editPersona.phtml) para editar registro: Persona*/
    public function getFormEditPersona(){
        Obj::run()->View->render("formEditPersona");
    }
    
    public function getFormDatosPersonales(){
        Obj::run()->View->render("formDatosPersonales");
    }
    
    /*envia datos para grabar registro: Persona*/
    public function postNewPersona(){
        $data = Obj::run()->personaModel->mantenimientoPersona();        
        echo json_encode($data);
    }
        
    /*envia datos para editar registro: Persona*/
    public function postEditPersona(){
        $data = Obj::run()->personaModel->mantenimientoPersona();        
        echo json_encode($data);
    }
    
    /*envia datos para eliminar registro: Persona*/
    public function postDeletePersonaAll(){
        $data = Obj::run()->personaModel->deletePersonaAll();        
        echo json_encode($data);
    }
    
    public static function findPersona(){
        $data = Obj::run()->personaModel->findPersona();
        
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
    
    public function getDatosPersonales(){
        $data = Obj::run()->personaModel->findPersona();        
        return $data;
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
    public function postDesactivar(){
        $data = Obj::run()->personaModel->postDesactivar();
        
        echo json_encode($data);
    }
    
    public function postActivar(){
        $data = Obj::run()->personaModel->postActivar();
        
        echo json_encode($data);
    }  
    
}

?>