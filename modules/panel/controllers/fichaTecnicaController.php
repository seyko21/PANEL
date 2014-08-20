<?php
/*
* --------------------------------------
* fecha: 15-08-2014 02:08:24 
* Descripcion : fichaTecnicaController.php
* --------------------------------------
*/    

class fichaTecnicaController extends Controller{

    public function __construct() {
        $this->loadModel('fichaTecnica');
    }
    
   public function index(){ 
        Obj::run()->View->render('indexFichaTecnica');
    }
    
   public function getGridFichaTecnica(){
       $editar = Session::getPermiso('FITECED');
       $eliminar = Session::getPermiso('FITECDE');
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->fichaTecnicaModel->getGridFichaTecnica();
        
        if(!isset($rResult['error'])){  
            $iTotal         = isset($rResult[0]['total'])?$rResult[0]['total']:0;
            
            $sOutput = '{';
            $sOutput .= '"sEcho": '.intval($sEcho).', ';
            $sOutput .= '"iTotalRecords": '.$iTotal.', ';
            $sOutput .= '"iTotalDisplayRecords": '.$iTotal.', ';
            $sOutput .= '"aaData": [ ';
            foreach ( $rResult as $key=>$aRow ){
                
                if($aRow['estado'] == 'A'){
                    $estado = '<span class=\"label label-success\">Activo</span>';
                }elseif($aRow['estado'] == 'B'){
                    $estado = '<span class=\"label label-danger\">Baja</span>';
                }
            
                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_producto']);
                
                $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" name=\"'.T101.'chk_delete[]\" value=\"'.$encryptReg.'\">';
                
                /*datos de manera manual*/
                $sOutput .= '["'.$chk.'","'.$aRow['id_producto'].'","'.$aRow['ubicacion'].'","'.$aRow['dimesion_area'].'","'.$aRow['NroCaratulas'].'","'.$estado.'", ';
                

                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';
                
                //Visualizar Detalle
                $sOutput .= '<button type=\"button\" class=\"btn bg-color-blueDark txt-color-white btn-xs\" title=\"Agregar Caratula\" onclick=\"fichaTecnica.getListaCaratulas(\''.$encryptReg.'\')\">';
                $sOutput .= '    <i class=\"fa fa-search-plus fa-lg\"></i>';
                $sOutput .= '</button>';  
                if($editar['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"btn btn-primary btn-xs\" title=\"'.$editar['accion'].'\" onclick=\"fichaTecnica.getEditarFichaTecnica(\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"fa fa-edit fa-lg\"></i>';
                    $sOutput .= '</button>';
                }                                                
//                if($eliminar['permiso'] == 1){
//                    $sOutput .= '<button type=\"button\" class=\"btn btn-danger btn-xs\" title=\"'.$eliminar['accion'].'\" onclick=\"fichaTecnica.postDeleteFichaTecnica(\''.$encryptReg.'\')\">';
//                    $sOutput .= '    <i class=\"fa fa-ban fa-lg\"></i>';
//                    $sOutput .= '</button>';
//                }
                
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
    public static function getCaratulas(){          
        $rResult = Obj::run()->fichaTecnicaModel->getCaratulas();
        return $rResult;
    } 
    public static function getDepartamentos(){ 
        $data = Obj::run()->fichaTecnicaModel->getDepartamentos();        
        return $data;
    }
    
    public function getProvincias(){
        $data = Obj::run()->fichaTecnicaModel->getProvincias();        
        echo json_encode($data);
    }
    
    public static function getProvinciasEst($dep=''){
        $data = Obj::run()->fichaTecnicaModel->getProvincias($dep);        
        return $data;
    }
    
    public function getUbigeo(){
        $data = Obj::run()->fichaTecnicaModel->getUbigeo();        
        echo json_encode($data);
    }
    
    public static function getUbigeoEst($pro=''){
        $data = Obj::run()->fichaTecnicaModel->getUbigeo($pro);        
        return $data;
    }
    
    public static function getTPanelFichaTecnica(){ 
        $data = Obj::run()->fichaTecnicaModel->getTPanelFichaTecnica();            
        return $data;
    }    
       
    public function getListaCaratulas(){
        Obj::run()->View->render('indexCaratula');
    }
    public function getNuevoFichaTecnica(){ 
        Obj::run()->View->render('nuevoFichaTecnica');
    }    
    public function getEditarFichaTecnica(){ 
        Obj::run()->View->render('editarFichaTecnica');
    }
    public function getNuevoCaratula(){ 
        Obj::run()->View->render('nuevoCaratula');
    }    
    
    public static function getFichaTecnica(){ 
        $data = Obj::run()->fichaTecnicaModel->getFichaTecnica();        
        return $data;
    }
    public static function getUbicacion(){ 
        $data = Obj::run()->fichaTecnicaModel->getUbicacion();   
        $retorno = LABEL_A23 . ' : '. $data['ubicacion'] . ' - '.$data['dimension_alto'].' x '.$data['dimension_ancho'].' m' ;
        echo $retorno;        
    }
    
    public function postNuevoFichaTecnica(){ 
        $data = Obj::run()->fichaTecnicaModel->mantenimientoFichaTecnica();
        
        echo json_encode($data);
    }
    
    public function postEditarFichaTecnica(){ 
        $data = Obj::run()->fichaTecnicaModel->mantenimientoFichaTecnica();
        
        echo json_encode($data);
    }
    
    public function postDeleteFichaTecnica(){ 
        $data = Obj::run()->fichaTecnicaModel->mantenimientoFichaTecnica();
        
        echo json_encode($data);
    }
    
    public function postDeleteFichaTecnicaAll(){ 
        $data = Obj::run()->fichaTecnicaModel->mantenimientoFichaTecnicaAll();
        
        echo json_encode($data);
    }        
    
}

?>