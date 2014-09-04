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
                
                if($aRow['estado'] == 'A'){
                    $estado = '<button type=\"button\" class=\"btn btn-success btn-xs\" title=\"Desactivar\" onclick=\"socio.postDesactivar(this,\''.$encryptReg.'\')\">Activo</button>';
                }elseif($aRow['estado'] == 'I'){
                    $estado = '<button type=\"button\" class=\"btn btn-danger btn-xs\" title=\"Activar\" onclick=\"socio.postActivar(this,\''.$encryptReg.'\')\">Inactivo</button>';
                }
                
                $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" name=\"'.TAB_SOCIO.'chk_delete[]\" value=\"'.$encryptReg.'\">';
                
                /*datos de manera manual*/
                $sOutput .= '["'.$chk.'","'.$aRow['numerodocumento'].'","'.$aRow['nombrecompleto'].'","'.$aRow['email'].'","'.$aRow['telefono'].'","'.number_format($aRow['monto_invertido'],2).'","'.$estado.'", ';
                

                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';
                
                if($editar['permiso']){
                    $sOutput .= '<button type=\"button\" class=\"btn btn-primary btn-xs\" title=\"'.$editar['accion'].'\" onclick=\"socio.getFormEditSocio(this,\''.$encryptReg.'\')\">';
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
    
  
    
}

?>