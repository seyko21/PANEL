<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 06-11-2014 16:11:31 
* Descripcion : vunidadMedidaController.php
* ---------------------------------------
*/    

class vunidadMedidaController extends Controller{

    public function __construct() {
        $this->loadModel("vunidadMedida");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexVunidadMedida");
    }
    
    public function getGridVunidadMedida(){
        $editar   = Session::getPermiso('VUNIDED');
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->vunidadMedidaModel->getVunidadMedida();
        
        $num = Obj::run()->vunidadMedidaModel->_iDisplayStart;
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
                $encryptReg = Aes::en($aRow['id_unidadmedida']);
                
                /*campo que maneja los estados, para el ejemplo aqui es ACTIVO, coloca tu campo*/
                if($aRow['estado'] == 'A'){
                    if($editar['permiso']){
                        $estado = '<button type=\"button\" class=\"btn btn-success btn-xs\" title=\"'.BTN_DESACT.'\" onclick=\"vunidadMedida.postDesactivar(this,\''.$encryptReg.'\')\"><i class=\"fa fa-check\"></i> '.LABEL_ACT.'</button>';
                    }else{
                        $estado = '<span class=\"label label-success\">'.LABEL_ACT.'</span>';
                    }
                }elseif($aRow['estado'] == 'I'){
                    if($editar['permiso']){
                        $estado = '<button type=\"button\" class=\"btn btn-danger btn-xs\" title=\"'.BTN_ACT.'\" onclick=\"vunidadMedida.postActivar(this,\''.$encryptReg.'\')\"><i class=\"fa fa-ban\"></i> '.LABEL_DESACT.'</button>';
                    }else{
                        $estado = '<span class=\"label label-danger\">'.LABEL_DESACT.'</span>';
                    }
                }
                
                if($aRow['uso'] == 0):
                    $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" name=\"'.VUNID.'chk_delete[]\" value=\"'.$encryptReg.'\">';
                else:
                    $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" name=\"'.VUNID.'chk_delete[]\" disabled >';
                endif;
                                               
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $axion = '"<div class=\"btn-group\">';
                if($editar['permiso']){
                    $axion .= '<button type=\"button\" class=\"'.$editar['theme'].'\" title=\"'.$editar['accion'].'\" onclick=\"vunidadMedida.getFormEditVunidadMedida(this,\''.$encryptReg.'\')\">';
                    $axion .= '    <i class=\"'.$editar['icono'].'\"></i>';
                    $axion .= '</button>';
                }
                $axion .= ' </div>" ';
                
                /*registros a mostrar*/
                $sOutput .= '["'.$chk.'","'.$aRow['nombre'].'","'.$aRow['sigla'].'","'.$estado.'",'.$axion.' ';

                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }
    
    public function getAddListUnidadMedida(){ 
        $data = Obj::run()->vunidadMedidaModel->getLastUnidadMedida();
        
        echo json_encode($data);
    }    
    
    /*carga formulario (newVunidadMedida.phtml) para nuevo registro: VunidadMedida*/
    public function getFormNewVunidadMedida(){
        Obj::run()->View->render("formNewVunidadMedida");
    }
    
    /*carga formulario (editVunidadMedida.phtml) para editar registro: VunidadMedida*/
    public function getFormEditVunidadMedida(){
        Obj::run()->View->render("formEditVunidadMedida");
    }
    
    /*busca data para editar registro: VunidadMedida*/
    public static function findVunidadMedida(){
        $data = Obj::run()->vunidadMedidaModel->findVunidadMedida();
            
        return $data;
    }
    
    /*envia datos para grabar registro: VunidadMedida*/
    public function postNewVunidadMedida(){
        $data = Obj::run()->vunidadMedidaModel->newVunidadMedida();
        
        echo json_encode($data);
    }
    
    /*envia datos para editar registro: VunidadMedida*/
    public function postEditVunidadMedida(){
        $data = Obj::run()->vunidadMedidaModel->editVunidadMedida();
        
        echo json_encode($data);
    }
    
    /*envia datos para eliminar registros: VunidadMedida*/
    public function postDeleteVunidadMedidaAll(){
        $data = Obj::run()->vunidadMedidaModel->deleteVunidadMedidaAll();
        
        echo json_encode($data);
    }

    public function postDesactivar(){
        $data = Obj::run()->vunidadMedidaModel->postDesactivar();
        
        echo json_encode($data);
    }
    
    public function postActivar(){
        $data = Obj::run()->vunidadMedidaModel->postActivar();
        
        echo json_encode($data);
    }      
    
}

?>