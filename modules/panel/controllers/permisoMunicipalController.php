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
                                                
                /*datos de manera manual*/
                $sOutput .= '["'.$aRow['distrito'].'","'.$aRow['ubicacion'].'","'.$aRow['dimesion_area'].'","'.$aRow['fechaInicio'].'","'.$aRow['fechaFin'].'","'.$estado.'", ';
                
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';
                
                //Visualizar Detalle
                $sOutput .= '<button type=\"button\" class=\"btn bg-color-blue txt-color-white btn-xs\" title=\"Listar Caratula\" onclick=\"permisoMunicipal.getGridPermisoMunicipal(\''.$encryptReg.'\')\">';
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
                                                
                /*datos de manera manual*/
                $sOutput .= '["'.$aRow['fecha_inicio'].'","'.$aRow['fecha_final'].'","'.number_format($aRow['monto_pago'],2).'","'.$aRow['observacion'].'","'.$estado.'", ';
                
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';
                
                //Visualizar Detalle
                $sOutput .= '<button type=\"button\" class=\"btn bg-color-blue txt-color-white btn-xs\" title=\"Listar Caratula\" onclick=\"permisoMunicipal.getGridPermisoMunicipal(\''.$encryptReg.'\')\">';
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
            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;
    }    
    
    
    /*carga formulario (newPermisoMunicipal.phtml) para nuevo registro: PermisoMunicipal*/
    public function getFormNewPermisoMunicipal(){
        Obj::run()->View->render("formNewPermisoMunicipal");
    }
    
    /*carga formulario (editPermisoMunicipal.phtml) para editar registro: PermisoMunicipal*/
    public function getFormEditPermisoMunicipal(){
        Obj::run()->View->render("formEditPermisoMunicipal");
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