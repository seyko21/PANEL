<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 18-09-2014 22:09:09 
* Descripcion : instalacionController.php
* ---------------------------------------
*/    

class instalacionController extends Controller{

    public function __construct() {
        $this->loadModel("instalacion");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexInstalacion");
    }
    
    public function getGridInstalacion(){
        $editar   = Session::getPermiso('ORINSED');
        $eliminar = Session::getPermiso('ORINSDE');
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->instalacionModel->getInstalacion();
        
        $num = Obj::run()->instalacionModel->_iDisplayStart;
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
            
            foreach ( $rResult as $aRow ){
                
                /*campo que maneja los estados, para el ejemplo aqui es ACTIVO, coloca tu campo*/
                if($aRow['activo'] == 1){
                    $estado = '<span class=\"label label-success\">'.LABEL_ACT.'</span>';
                }else{
                    $estado = '<span class=\"label label-danger\">'.LABEL_DES.'</span>';
                }
                
                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['ID_REGISTRO']);
                
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $axion = '"<div class=\"btn-group\">';
                 
                if($editar['permiso']){
                    $axion .= '<button type=\"button\" class=\"'.$editar['theme'].'\" title=\"'.$editar['accion'].'\" onclick=\"instalacion.getFormEditInstalacion(this,\''.$encryptReg.'\')\">';
                    $axion .= '    <i class=\"'.$editar['icono'].'\"></i>';
                    $axion .= '</button>';
                }
                if($eliminar['permiso']){
                    $axion .= '<button type=\"button\" class=\"'.$eliminar['theme'].'\" title=\"'.$eliminar['accion'].'\" onclick=\"instalacion.postDeleteInstalacion(this,\''.$encryptReg.'\')\">';
                    $axion .= '    <i class=\"'.$eliminar['icono'].'\"></i>';
                    $axion .= '</button>';
                }
                
                $axion .= ' </div>" ';
                
                /*registros a mostrar*/
                $sOutput .= '["'.($num++).'",'.$axion.',"'.$aRow['CAMPO 1'].'","'.$aRow['CAMPO 2'].'","'.$estado.'" ';

                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }
    
    public function getCaratulas(){
        $tab = $this->post('_tab');
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->instalacionModel->getCaratulas();
        
        if(!isset($rResult['error'])){  
            $iTotal         = isset($rResult[0]['total'])?$rResult[0]['total']:0;
            
            $sOutput = '{';
            $sOutput .= '"sEcho": '.intval($sEcho).', ';
            $sOutput .= '"iTotalRecords": '.$iTotal.', ';
            $sOutput .= '"iTotalDisplayRecords": '.$iTotal.', ';
            $sOutput .= '"aaData": [ ';
            foreach ( $rResult as $key=>$aRow ){
                $carat = $aRow['ubicacion'].' - '.$aRow['descripcion'];
                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_ordenserviciod']);
                
                $nom = '<a href=\"javascript:;\" onclick=\"simpleScript.setInput({'.$tab.'txt_idcaratula:\''.$encryptReg.'\', '.$tab.'txt_caratula:\''.$carat.'\'},\'#'.ORINS.'formBuscarCaratula\');\" >'.$aRow['codigo'].'</a>';
                
                /*datos de manera manual*/
                $sOutput .= '["'.(++$key).'","'.$nom.'","'.$carat.'","'.$aRow['orden_numero'].'" ';

                $sOutput .= '],';
            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;
    }

    /*carga formulario (newInstalacion.phtml) para nuevo registro: Instalacion*/
    public function getFormNewInstalacion(){
        Obj::run()->View->render("formNewInstalacion");
    }
    
    public function getFormBuscarCaratulta(){
        Obj::run()->View->render("formBuscarCaratula");
    }
    
    public function getFormBuscarConceptos(){ 
        Obj::run()->View->render('formBuscarConceptos');
    }
    
    public function getConceptos(){ 
        $data = Obj::run()->instalacionModel->getConceptos();
        
        return $data;
    }
    
    /*envia datos para grabar registro: Instalacion*/
    public function postNewInstalacion(){
        $data = Obj::run()->instalacionModel->newInstalacion();
        
        echo json_encode($data);
    }
    
    /*envia datos para eliminar registros: Instalacion*/
    public function postDeleteInstalacionAll(){
        $data = Obj::run()->instalacionModel->deleteInstalacionAll();
        
        echo json_encode($data);
    }
    
}

?>