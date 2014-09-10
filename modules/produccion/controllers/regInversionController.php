<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 03-09-2014 14:09:13 
* Descripcion : regInversionController.php
* ---------------------------------------
*/    

class regInversionController extends Controller{

    public function __construct() {        
        $this->loadModel(array('modulo'=>'produccion','modelo'=>'regInversion'));              
        $this->loadController(array('modulo'=>'personal','controller'=>'socio'));  
    }
    
    public function index(){ 
        Obj::run()->View->render("indexRegInversion");
    }
    
    public function getGridSocio(){
        $editar = Session::getPermiso('REINVED');
        $agre = Session::getPermiso('REINVVP');
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->regInversionModel->getGridSocio();
        
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
                        $estado = '<button type=\"button\" class=\"btn btn-success btn-xs\" title=\"'.BTN_DESACT.'\" onclick=\"regInversion.postDesactivarSocio(this,\''.$encryptReg.'\')\"><i class=\"fa fa-check\"></i> '.LABEL_ACT.'</button>';
                    }else{
                        $estado = '<span class=\"label label-success\">'.LABEL_ACT.'</span>';
                    }
                }elseif($aRow['estado'] == 'I'){
                    if($editar['permiso']){
                        $estado = '<button type=\"button\" class=\"btn btn-danger btn-xs\" title=\"'.BTN_ACT.'\" onclick=\"regInversion.postActivarSocio(this,\''.$encryptReg.'\')\"><i class=\"fa fa-ban\"></i> '.LABEL_DESACT.'</button>';
                    }else{
                        $estado = '<span class=\"label label-danger\">'.LABEL_DESACT.'</span>';
                    }
                }        
                                
                 if ($aRow['monto_invertido'] == 0)
                    $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" name=\"'.TAB_SOCIO.'chk_delete[]\" value=\"'.$encryptReg.'\">';
                else {
                    $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" name=\"'.TAB_SOCIO.'chk_delete[]\" disabled>';
                }
                /*datos de manera manual*/
                $sOutput .= '["'.$chk.'","'.$aRow['numerodocumento'].'","'.$aRow['nombrecompleto'].'","'.number_format($aRow['monto_invertido'],2).'","'.$estado.'", ';
                

                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';
                
                if($editar['permiso']){
                    $sOutput .= '<button type=\"button\" class=\"'.$editar['theme'].'\" title=\"'.$editar['accion'].'\" onclick=\"regInversion.getFormEditSocio(this,\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"'.$editar['icono'].'\"></i>';
                    $sOutput .= '</button>';
                }                               
                if($agre['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"'.$agre['theme'].'\" title=\"'.$agre['accion'].'\" onclick=\"regInversion.getGridInversion(\''.$encryptReg.'\',\''.$aRow['nombrecompleto'].'\')\">';
                    $sOutput .= '    <i class=\"'.$agre['icono'].'\"></i>';
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
    
     public function getGridInversion(){
        $editar = Session::getPermiso('REINVED');        
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->regInversionModel->getGridInversion();
        
        if(!isset($rResult['error'])){  
            $iTotal         = isset($rResult[0]['total'])?$rResult[0]['total']:0;
            
            $sOutput = '{';
            $sOutput .= '"sEcho": '.intval($sEcho).', ';
            $sOutput .= '"iTotalRecords": '.$iTotal.', ';
            $sOutput .= '"iTotalDisplayRecords": '.$iTotal.', ';
            $sOutput .= '"aaData": [ ';
            foreach ( $rResult as $key=>$aRow ){
                
                
            
                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_inversion']);
                
                  if ($aRow['monto_asignado'] == 0)
                    $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" name=\"'.REINV.'chk_chkdelinv[]\" value=\"'.$encryptReg.'\">';
                else {
                    $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" name=\"'.REINV.'chk_chkdelinv[]\" disabled>';
                }                                
                
                /*datos de manera manual*/
                $sOutput .= '["'.$chk.'","'.functions::cambiaf_a_normal($aRow['fecha_inversion']).'","'.number_format($aRow['monto_invertido'],2).'","'.number_format($aRow['monto_asignado'],2).'","'.number_format($aRow['monto_saldo'],2).'",';
                
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';
                
                if($editar['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"'.$editar['theme'].'\" title=\"'.$editar['accion'].'\" onclick=\"regInversion.getEditarInversion(this,\''.$encryptReg.'\')\">';
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
    
    public function getFormNewSocio(){
        Obj::run()->View->render("formNewSocio");
    }
    public function getFormEditSocio(){
        Obj::run()->View->render("formEditSocio");
    }
    
    /*carga formulario (newRegInversion.phtml) para nuevo registro: RegInversion*/
    public function getFormNewRegInversion(){
        Obj::run()->View->render("formNewRegInversion");
    }           
    /*carga formulario (editRegInversion.phtml) para editar registro: RegInversion*/
    public function getFormEditInversion(){
        Obj::run()->View->render("formEditInversion");
    }
    
    public static function findInversion(){
        $data = Obj::run()->regInversionModel->findInversion();
        
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
            
    /*envia datos para grabar registro: Socio*/
    public function postNewSocio(){
        $data = Obj::run()->socioController->postNewSocio();        
        echo $data;
    }
        
    /*envia datos para editar registro: Socio*/
    public function postEditSocio(){
        $data = Obj::run()->socioController->postEditSocio();        
        echo $data;
    }
    
     /*envia datos para eliminar registro: Socio*/
    public function postDeleteSocioAll(){
        $data = Obj::run()->socioController->postDeleteSocioAll();        
        echo $data;
    }   
        
    /*envia datos para grabar registro: RegInversion*/
    public function postNewInversion(){
        $data = Obj::run()->regInversionModel->mantenimientoInversion();
        
        echo json_encode($data);
    }    
    /*envia datos para editar registro: RegInversion*/
    public function postEditInversion(){
        $data = Obj::run()->regInversionModel->mantenimientoInversion();
        
        echo json_encode($data);
    }
    
    /*envia datos para eliminar registro: RegInversion*/
    public function postDeleteInversionAll(){
        $data = Obj::run()->regInversionModel->deleteInversionAll();
        
        echo json_encode($data);
    }
    public function postDesactivarSocio(){
        $data = Obj::run()->socioController->postDesactivarSocio();        
        echo $data;
    }    
    public function postActivarSocio(){
        $data = Obj::run()->socioController->postActivarSocio();        
        echo $data;
    }    
      
    
}

?>