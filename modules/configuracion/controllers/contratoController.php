<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 12-09-2014 17:09:12 
* Descripcion : contratoController.php
* ---------------------------------------
*/    

class contratoController extends Controller{

    public function __construct() {
        $this->loadModel("contrato");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexContrato");
    }
    
    public function getGridContrato(){
        $editar   = Session::getPermiso('CONTRED');
        $adjuntar = Session::getPermiso('CONTRAJ');
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->contratoModel->getContrato();
        
        $num = Obj::run()->contratoModel->_iDisplayStart;
        if($num >= 10){
            $num++;
        }else{
            $num = 1;
        }
        
        if(!isset($rResult['error'])){  
            $iTotal         = isset($rResult[0]['total'])?$rResult[0]['total']:0;
            
            $sOutput = '{';
            $sOutput .= '"sEcho": '.intval($sEcho).', ';
            $sOutput .= '"iTotalRecords": '.$iTotal.', ';
            $sOutput .= '"iTotalDisplayRecords": '.$iTotal.', ';
            $sOutput .= '"aaData": [ ';     
            
            foreach ( $rResult as $key=>$aRow ){
                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_contrato']);
                
                if($aRow['estado'] == 'A'){
                    if($editar['permiso']){
                        $estado = '<button type=\"button\" class=\"btn btn-success btn-xs\" title=\"'.BTN_DESACT.'\" onclick=\"parametro.postDesactivar(this,\''.$encryptReg.'\')\"><i class=\"fa fa-check\"></i> '.LABEL_ACT.'</button>';
                    }else{
                        $estado = '<span class=\"label label-success\">'.LABEL_ACT.'</span>';
                    }
                }elseif($aRow['estado'] == 'I'){
                    if($editar['permiso']){
                        $estado = '<button type=\"button\" class=\"btn btn-danger btn-xs\" title=\"'.BTN_ACT.'\" onclick=\"parametro.postActivar(this,\''.$encryptReg.'\')\"><i class=\"fa fa-ban\"></i> '.LABEL_DESACT.'</button>';
                    }else{
                        $estado = '<span class=\"label label-danger\">'.LABEL_DESACT.'</span>';
                    }
                }     
                $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" name=\"'.CONTR.'chk_delete[]\" value=\"'.$encryptReg.'\">';
                                
                
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $axion = '"<div class=\"btn-group\">';
                 
                if($editar['permiso']){
                    $axion .= '<button type=\"button\" class=\"'.$editar['theme'].'\" title=\"'.$editar['accion'].'\" onclick=\"contrato.getFormEditContrato(this,\''.$encryptReg.'\')\">';
                    $axion .= '    <i class=\"'.$editar['icono'].'\"></i>';
                    $axion .= '</button>';
                }
                if($adjuntar['permiso']){
                    $axion .= '<button type=\"button\" class=\"'.$adjuntar['theme'].'\" title=\"'.$adjuntar['accion'].' (Imagen)\" onclick=\"contrato.getFormAdjuntar(this,\''.$encryptReg.'\')\">';
                    $axion .= '    <i class=\"'.$adjuntar['icono'].'\"></i>';
                    $axion .= '</button>';
                }
                
                $axion .= ' </div>" ';
                
                /*registros a mostrar*/
                $sOutput .= '["'.$chk.'","'.$aRow['nombre'].'","'.  Functions::cambiaf_a_normal($aRow['fecha_creacion']).'","'.$estado.','.$axion.'" ';
                
                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }
    
    /*carga formulario (newContrato.phtml) para nuevo registro: Contrato*/
    public function getFormNewContrato(){
        Obj::run()->View->render("formNewContrato");
    }
    
    /*carga formulario (editContrato.phtml) para editar registro: Contrato*/
    public function getFormEditContrato(){
        Obj::run()->View->render("formEditContrato");
    }
    
    /*busca data para editar registro: Contrato*/
    public static function findContrato(){
        $data = Obj::run()->contratoModel->findContrato();
            
        return $data;
    }
    
    /*envia datos para grabar registro: Contrato*/
    public function postNewContrato(){
        $data = Obj::run()->contratoModel->mantenimientoContrato();
        
        echo json_encode($data);
    }
    
    /*envia datos para editar registro: Contrato*/
    public function postEditContrato(){
        $data = Obj::run()->contratoModel->mantenimientoContrato();
        
        echo json_encode($data);
    }
    
    /*envia datos para eliminar registros: Contrato*/
    public function postDeleteContratoAll(){
        $data = Obj::run()->contratoModel->mantenimientoContratoAll();
        
        echo json_encode($data);
    }
    
}

?>