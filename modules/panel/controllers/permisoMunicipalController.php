<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 23-08-2014 22:08:31 
* Descripcion : permisoMunicipalController.php
* ---------------------------------------
*/    

class permisoMunicipalController extends Controller{

    public function __construct() {
        $this->loadModel("permisoMunicipal");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexPermisoMunicipal");
    }
    
    public function getGridFichaTecnica(){              
       $agregar = Session::getPermiso('PERMUAG');    
       
       $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->permisoMunicipalModel->getGridFichaTecnica();
        
        if(!isset($rResult['error'])){  
            $iTotal         = isset($rResult[0]['total'])?$rResult[0]['total']:0;
             $idx = 1;
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
                
                ($aRow['fechaInicio'] != '')?$fi = Functions::cambiaf_a_normal($aRow['fechaInicio']):$fi='-';
                ($aRow['fechaFin'] != '')?$ff = Functions::cambiaf_a_normal($aRow['fechaFin']):$ff='-';                                         
                /*datos de manera manual*/
                $sOutput .= '["'.$aRow['distrito'].'","'.$aRow['ubicacion'].'","'.$fi.'","'.$ff.'","'.$aRow['dimesion_area'].'","'.$estado.'", ';
                
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';
                
                //Visualizar Detalle
                $sOutput .= '<button id=\"'.PERMU.'btnProducto'.$idx.'\" type=\"button\" class=\"btn bg-color-blue txt-color-white btn-xs\" title=\"Listar Caratula\" onclick=\"permisoMunicipal.getGridPermisoMunicipal(\''.$encryptReg.'\')\">';
                $sOutput .= '    <i class=\"fa fa-search-plus fa-lg\"></i>';
                $sOutput .= '</button>';          
                if($agregar['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"btn bg-color-pink txt-color-white btn-xs\" title=\"'.$agregar['accion'].' Permiso Municipal\" onclick=\"permisoMunicipal.getFormNewPermisoMunicipal(this, \''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"fa fa-plus-circle fa-lg\"></i>';
                    $sOutput .= '</button>';
                }                                      
                
                $sOutput .= ' </div>" ';

                $sOutput = substr_replace( $sOutput, "", -1 );
                $sOutput .= '],';
                $idx++;
            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;
    }
    
public function getGridPermisoMunicipal(){              
       $editar = Session::getPermiso('FITECED');
       $eliminar = Session::getPermiso('FITECDE');   
       
       $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->permisoMunicipalModel->getGridPermisoMunicipal();
        
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
                }elseif($aRow['estado'] == 'I'){
                    $estado = '<span class=\"label label-danger\">Inactivo</span>';
                }
            
                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_permisomuni']);               
                $idProd = Aes::en($aRow['id_producto']); 
                ($aRow['fecha_inicio'] != '')?$fi = Functions::cambiaf_a_normal($aRow['fecha_inicio']):$fi='-';
                ($aRow['fecha_final'] != '')?$ff = Functions::cambiaf_a_normal($aRow['fecha_final']):$ff='-'; 
                
                /*datos de manera manual*/
                $sOutput .= '["'.$fi.'","'.$ff.'","'.number_format($aRow['monto_pago'],2).'","'.$aRow['observacion'].'","'.$estado.'", ';
                
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';
                
       
                //Acciones: 
                if($editar['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"btn btn-primary btn-xs\" title=\"'.$editar['accion'].'\" onclick=\"permisoMunicipal.getEditarPermisoMunicipal(\''.$encryptReg.'\',\''.$idProd.'\')\">';
                    $sOutput .= '    <i class=\"fa fa-edit fa-lg\"></i>';
                    $sOutput .= '</button>';
                }      
                 if($eliminar['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"btn btn-danger btn-xs\" title=\"'.$eliminar['accion'].'\" onclick=\"permisoMunicipal.postDeletePermisoMunicipal(this,\''.$encryptReg.'\')\">';
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
    
    public static function getUbicacion(){ 
        $data = Obj::run()->permisoMunicipalModel->getUbicacion();   
        $retorno = LABEL_A50;
        if(strlen($data['ubicacion']) > 0 or strlen($data['dimension_alto']) > 0 or strlen($data['dimension_ancho']) > 0 ){
            $retorno .= ' : '. $data['ubicacion'] . ' - '.$data['dimension_alto'].' x '.$data['dimension_ancho'].' m' ;
        }        
        echo $retorno;        
    }    
    public static function getPermisoMunicipal(){ 
        $data = Obj::run()->permisoMunicipalModel->getPermisoMunicipal();          
        return $data;
    }
    /*carga formulario (newPermisoMunicipal.phtml) para nuevo registro: PermisoMunicipal*/
    public function getFormNewPermisoMunicipal(){
        Obj::run()->View->render("formNewPermisoMunicipal");
    }
    
    /*carga formulario (editPermisoMunicipal.phtml) para editar registro: PermisoMunicipal*/
    public function getFormEditPermisoMunicipal(){
       Obj::run()->View->render('editarPermisoMunicipal');       
    }
    
    public function postNuevoPermisoMunicipal(){ 
        $data = Obj::run()->permisoMunicipalModel->mantenimientoPermisoMunicipal();
        
        echo json_encode($data);
    }
    
    public function postEditarPermisoMunicipal(){ 
        $data = Obj::run()->permisoMunicipalModel->mantenimientoPermisoMunicipal();
        
        echo json_encode($data);
    }  
    public function postDeletePermisoMunicipal(){ 
        $data = Obj::run()->permisoMunicipalModel->mantenimientoPermisoMunicipal();
        
        echo json_encode($data);
    }    
    

    
}

?>