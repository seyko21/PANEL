<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 29-08-2014 02:08:11 
* Descripcion : consultaPermisosController.php
* ---------------------------------------
*/    

class consultaPermisosController extends Controller{

    public function __construct() {
        $this->loadModel("consultaPermisos");
        $this->loadController(array('modulo'=>'panel','controller'=>'fichaTecnica')); 
    }
    
    public function index(){ 
        Obj::run()->View->render("indexConsultaPermisos");
    }
    
   public function getGridConsultaPermiso(){              
       $agregar = Session::getPermiso('PERVEAG'); 
       $exportarpdf   = Session::getPermiso('PERVEEP');
       $exportarexcel = Session::getPermiso('PERVEEX');  
       
       $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->consultaPermisosModel->getGridConsultaPermiso();
        
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
                
                ($aRow['fecha_inicio'] != '')?$fi = Functions::cambiaf_a_normal($aRow['fecha_inicio']):$fi='-';
                ($aRow['fecha_final'] != '')?$ff = Functions::cambiaf_a_normal($aRow['fecha_final']):$ff='-';                                         
                
                $ffd = '<span class=\"label label-danger\">'.$ff.'</span>';                
                
                /*datos de manera manual*/
                $sOutput .= '["'.$aRow['distrito'].'","'.$aRow['ubicacion'].'","'.$fi.'","'.$ffd.'","'.$aRow['dimesion_area'].'", ';
                
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';
                
                //Visualizar Detalle
                if($exportarpdf['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"'.$exportarpdf['theme'].'\" title=\"'.$exportarpdf['accion'].'\" onclick=\"consultaPermisos.postPDF(this,\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"'.$exportarpdf['icono'].'\"></i>';
                    $sOutput .= '</button>';
                }
                if($exportarexcel['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"'.$exportarexcel['theme'].'\" title=\"'.$exportarexcel['accion'].'\" onclick=\"consultaPermisos.postExcel(this,\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"'.$exportarexcel['icono'].'\"></i>';
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
    
    public function getGridIndexConsultaPermiso(){                    
       $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->consultaPermisosModel->getGridIndexConsultaPermiso();
        
        if(!isset($rResult['error'])){  
            $iTotal         = isset($rResult[0]['total'])?$rResult[0]['total']:0;

            $sOutput = '{';
            $sOutput .= '"sEcho": '.intval($sEcho).', ';
            $sOutput .= '"iTotalRecords": '.$iTotal.', ';
            $sOutput .= '"iTotalDisplayRecords": '.$iTotal.', ';
            $sOutput .= '"aaData": [ ';
            foreach ( $rResult as $key=>$aRow ){
                            
                ($aRow['fecha_inicio'] != '')?$fi = Functions::cambiaf_a_normal($aRow['fecha_inicio']):$fi='-';
                ($aRow['fecha_final'] != '')?$ff = Functions::cambiaf_a_normal($aRow['fecha_final']):$ff='-';                                         
                
                $ffd = '<span class=\"label label-danger\">'.$ff.'</span>';                
                
                /*datos de manera manual*/
                $sOutput .= '["'.$aRow['distrito'].'","'.$aRow['ubicacion'].'","'.$ffd.'" ';
                

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
    
   public function postPDF(){ 
      $data = Obj::run()->fichaTecnicaController->postPDF();        
      echo $data;
   }
    
   public function postExcel(){
      $data = Obj::run()->fichaTecnicaController->postExcel();        
      echo $data;
   }    
    
    
}

?>