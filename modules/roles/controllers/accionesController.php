<?php
/*
 * Documento   : accionesController
 * Creado      : 05-jul-2014
 * Autor       : ..... .....
 * Descripcion : 
 */
class accionesController extends Controller{
    
    public function __construct() {
        $this->loadModel('acciones');
    }

    public function index(){}
    
    public function acciones(){
        Obj::run()->View->render('acciones');
    }
    
    public function getAcciones(){ 
        $eliminar = Session::getPermiso('ACCDE');
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->accionesModel->getAcciones();
        
        if(!isset($rResult['error'])){  
            $iTotal         = isset($rResult[0]['total'])?$rResult[0]['total']:0;
            
            $sOutput = '{';
            $sOutput .= '"sEcho": '.intval($sEcho).', ';
            $sOutput .= '"iTotalRecords": '.$iTotal.', ';
            $sOutput .= '"iTotalDisplayRecords": '.$iTotal.', ';
            $sOutput .= '"aaData": [ ';
            foreach ( $rResult as $aRow ){
                
                if($aRow['activo'] == 1){
                    $estado = '<span class=\"label label-success\">'.$aRow['estado'].'</span>';
                }else{
                    $estado = '<span class=\"label label-danger\">'.$aRow['estado'].'</span>';
                }
                
                /*datos de manera manual*/
                $sOutput .= '["'.$aRow['id_acciones'].'","'.$aRow['accion'].'","'.$aRow['alias'].'","'.$estado.'", ';

                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_acciones']);

                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';
                
                $sOutput .= '<button type=\"button\" class=\"btn btn-primary btnEditarAxion\" title=\"Editar\" onclick=\"acciones.getAccion(\''.$encryptReg.'\')\">';
                $sOutput .= '    <i class=\"fa fa-edit fa-lg\"></i>';
                $sOutput .= '</button>';
                
                if($eliminar['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"btn btn-danger btnDeleteAxion\" title=\"'.$eliminar['accion'].'\" onclick=\"acciones.postDeleteAccion(\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"fa fa-ban fa-lg\"></i>';
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
    
    public function getNuevaAccion(){
        Obj::run()->View->render('nuevaAccion');
    }
    
    public function getEditAccion(){ 
        Obj::run()->View->key = $this->post('_key'); 
        Obj::run()->View->render('editarAccion');
    }
    
    public static function getAccion($id){ 
        $data = Obj::run()->accionesModel->getAccion($id);
        
        return $data;
    }
    
    public function postAccion(){ 
        $data = Obj::run()->accionesModel->mantenimientoAccion();
        
        echo json_encode($data);
    }
    
    public function postDeleteAccion(){ 
        $data = Obj::run()->accionesModel->mantenimientoAccion();
        
        echo json_encode($data);
    }
    
}
?>
