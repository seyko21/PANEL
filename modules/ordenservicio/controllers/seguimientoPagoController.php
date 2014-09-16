<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 16-09-2014 22:09:43 
* Descripcion : seguimientoPagoController.php
* ---------------------------------------
*/    

class seguimientoPagoController extends Controller{

    public function __construct() {
        $this->loadModel("seguimientoPago");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexSeguimientoPago");
    }
    
    public function getGridSeguimientoPago(){
        $editar   = Session::getPermiso('SEGPAED');
        $eliminar = Session::getPermiso('SEGPADE');
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->seguimientoPagoModel->getSeguimientoPago();
        
        $num = Obj::run()->seguimientoPagoModel->_iDisplayStart;
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
                    $axion .= '<button type=\"button\" class=\"'.$editar['theme'].'\" title=\"'.$editar['accion'].'\" onclick=\"seguimientoPago.getFormEditSeguimientoPago(this,\''.$encryptReg.'\')\">';
                    $axion .= '    <i class=\"'.$editar['icono'].'\"></i>';
                    $axion .= '</button>';
                }
                if($eliminar['permiso']){
                    $axion .= '<button type=\"button\" class=\"'.$eliminar['theme'].'\" title=\"'.$eliminar['accion'].'\" onclick=\"seguimientoPago.postDeleteSeguimientoPago(this,\''.$encryptReg.'\')\">';
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
    
    /*carga formulario (newSeguimientoPago.phtml) para nuevo registro: SeguimientoPago*/
    public function getFormNewSeguimientoPago(){
        Obj::run()->View->render("formNewSeguimientoPago");
    }
    
    /*carga formulario (editSeguimientoPago.phtml) para editar registro: SeguimientoPago*/
    public function getFormEditSeguimientoPago(){
        Obj::run()->View->render("formEditSeguimientoPago");
    }
    
    /*busca data para editar registro: SeguimientoPago*/
    public static function findSeguimientoPago(){
        $data = Obj::run()->seguimientoPagoModel->findSeguimientoPago();
            
        return $data;
    }
    
    /*envia datos para grabar registro: SeguimientoPago*/
    public function postNewSeguimientoPago(){
        $data = Obj::run()->seguimientoPagoModel->newSeguimientoPago();
        
        echo json_encode($data);
    }
    
    /*envia datos para editar registro: SeguimientoPago*/
    public function postEditSeguimientoPago(){
        $data = Obj::run()->seguimientoPagoModel->editSeguimientoPago();
        
        echo json_encode($data);
    }
    
    /*envia datos para eliminar registro: SeguimientoPago*/
    public function postDeleteSeguimientoPago(){
        $data = Obj::run()->seguimientoPagoModel->deleteSeguimientoPago();
        
        echo json_encode($data);
    }
    
    /*envia datos para eliminar registros: SeguimientoPago*/
    public function postDeleteSeguimientoPagoAll(){
        $data = Obj::run()->seguimientoPagoModel->deleteSeguimientoPagoAll();
        
        echo json_encode($data);
    }
    
}

?>