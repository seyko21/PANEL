<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 21-11-2014 23:11:45 
* Descripcion : panelDisponibleController.php
* ---------------------------------------
*/    

class panelDisponibleController extends Controller{

    public function __construct() {
        $this->loadModel("panelDisponible");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexPanelDisponible");
    }
    
    public function getGridPanelDisponible(){
        $editar   = Session::getPermiso('PANEDED');
        $eliminar = Session::getPermiso('PANEDDE');
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->panelDisponibleModel->getPanelDisponible();
        
        $num = Obj::run()->panelDisponibleModel->_iDisplayStart;
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
                    $axion .= '<button type=\"button\" class=\"'.$editar['theme'].'\" title=\"'.$editar['accion'].'\" onclick=\"panelDisponible.getFormEditPanelDisponible(this,\''.$encryptReg.'\')\">';
                    $axion .= '    <i class=\"'.$editar['icono'].'\"></i>';
                    $axion .= '</button>';
                }
                if($eliminar['permiso']){
                    $axion .= '<button type=\"button\" class=\"'.$eliminar['theme'].'\" title=\"'.$eliminar['accion'].'\" onclick=\"panelDisponible.postDeletePanelDisponible(this,\''.$encryptReg.'\')\">';
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
    
    /*carga formulario (newPanelDisponible.phtml) para nuevo registro: PanelDisponible*/
    public function getFormNewPanelDisponible(){
        Obj::run()->View->render("formNewPanelDisponible");
    }
    
    /*carga formulario (editPanelDisponible.phtml) para editar registro: PanelDisponible*/
    public function getFormEditPanelDisponible(){
        Obj::run()->View->render("formEditPanelDisponible");
    }
    
    /*busca data para editar registro: PanelDisponible*/
    public static function findPanelDisponible(){
        $data = Obj::run()->panelDisponibleModel->findPanelDisponible();
            
        return $data;
    }
    
    /*envia datos para grabar registro: PanelDisponible*/
    public function postNewPanelDisponible(){
        $data = Obj::run()->panelDisponibleModel->newPanelDisponible();
        
        echo json_encode($data);
    }
    
    /*envia datos para editar registro: PanelDisponible*/
    public function postEditPanelDisponible(){
        $data = Obj::run()->panelDisponibleModel->editPanelDisponible();
        
        echo json_encode($data);
    }
    
    /*envia datos para eliminar registro: PanelDisponible*/
    public function postDeletePanelDisponible(){
        $data = Obj::run()->panelDisponibleModel->deletePanelDisponible();
        
        echo json_encode($data);
    }
    
    /*envia datos para eliminar registros: PanelDisponible*/
    public function postDeletePanelDisponibleAll(){
        $data = Obj::run()->panelDisponibleModel->deletePanelDisponibleAll();
        
        echo json_encode($data);
    }
    
}

?>