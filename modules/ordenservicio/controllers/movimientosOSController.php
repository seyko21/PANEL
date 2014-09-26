<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 25-09-2014 23:09:14 
* Descripcion : movimientosOSController.php
* ---------------------------------------
*/    

class movimientosOSController extends Controller{

    public function __construct() {
        $this->loadModel("movimientosOS");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexMovimientosOS");
    }
    
    public function getGridMovimientosOS(){
        $editar   = Session::getPermiso('MOVOSED');
        $eliminar = Session::getPermiso('MOVOSDE');
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->movimientosOSModel->getMovimientosOS();
        
        $num = Obj::run()->movimientosOSModel->_iDisplayStart;
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
                    $axion .= '<button type=\"button\" class=\"'.$editar['theme'].'\" title=\"'.$editar['accion'].'\" onclick=\"movimientosOS.getFormEditMovimientosOS(this,\''.$encryptReg.'\')\">';
                    $axion .= '    <i class=\"'.$editar['icono'].'\"></i>';
                    $axion .= '</button>';
                }
                if($eliminar['permiso']){
                    $axion .= '<button type=\"button\" class=\"'.$eliminar['theme'].'\" title=\"'.$eliminar['accion'].'\" onclick=\"movimientosOS.postDeleteMovimientosOS(this,\''.$encryptReg.'\')\">';
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
    
    /*carga formulario (newMovimientosOS.phtml) para nuevo registro: MovimientosOS*/
    public function getFormNewMovimientosOS(){
        Obj::run()->View->render("formNewMovimientosOS");
    }
    
    /*carga formulario (editMovimientosOS.phtml) para editar registro: MovimientosOS*/
    public function getFormEditMovimientosOS(){
        Obj::run()->View->render("formEditMovimientosOS");
    }
    
    /*busca data para editar registro: MovimientosOS*/
    public static function findMovimientosOS(){
        $data = Obj::run()->movimientosOSModel->findMovimientosOS();
            
        return $data;
    }
    
    /*envia datos para grabar registro: MovimientosOS*/
    public function postNewMovimientosOS(){
        $data = Obj::run()->movimientosOSModel->newMovimientosOS();
        
        echo json_encode($data);
    }
    
    /*envia datos para editar registro: MovimientosOS*/
    public function postEditMovimientosOS(){
        $data = Obj::run()->movimientosOSModel->editMovimientosOS();
        
        echo json_encode($data);
    }
    
    /*envia datos para eliminar registro: MovimientosOS*/
    public function postDeleteMovimientosOS(){
        $data = Obj::run()->movimientosOSModel->deleteMovimientosOS();
        
        echo json_encode($data);
    }
    
    /*envia datos para eliminar registros: MovimientosOS*/
    public function postDeleteMovimientosOSAll(){
        $data = Obj::run()->movimientosOSModel->deleteMovimientosOSAll();
        
        echo json_encode($data);
    }
    
}

?>