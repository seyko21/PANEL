<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 24-09-2014 00:09:39 
* Descripcion : compromisoPagarController.php
* ---------------------------------------
*/    

class compromisoPagarController extends Controller{

    public function __construct() {
        $this->loadModel("compromisoPagar");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexCompromisoPagar");
    }
    
    public function getGridCompromisoPagar(){
        $editar   = Session::getPermiso('COPAGED');
        $eliminar = Session::getPermiso('COPAGDE');
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->compromisoPagarModel->getCompromisoPagar();
        
        $num = Obj::run()->compromisoPagarModel->_iDisplayStart;
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
                    $axion .= '<button type=\"button\" class=\"'.$editar['theme'].'\" title=\"'.$editar['accion'].'\" onclick=\"compromisoPagar.getFormEditCompromisoPagar(this,\''.$encryptReg.'\')\">';
                    $axion .= '    <i class=\"'.$editar['icono'].'\"></i>';
                    $axion .= '</button>';
                }
                if($eliminar['permiso']){
                    $axion .= '<button type=\"button\" class=\"'.$eliminar['theme'].'\" title=\"'.$eliminar['accion'].'\" onclick=\"compromisoPagar.postDeleteCompromisoPagar(this,\''.$encryptReg.'\')\">';
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
    
    /*carga formulario (newCompromisoPagar.phtml) para nuevo registro: CompromisoPagar*/
    public function getFormNewCompromisoPagar(){
        Obj::run()->View->render("formNewCompromisoPagar");
    }
    
    /*carga formulario (editCompromisoPagar.phtml) para editar registro: CompromisoPagar*/
    public function getFormEditCompromisoPagar(){
        Obj::run()->View->render("formEditCompromisoPagar");
    }
    
    /*busca data para editar registro: CompromisoPagar*/
    public static function findCompromisoPagar(){
        $data = Obj::run()->compromisoPagarModel->findCompromisoPagar();
            
        return $data;
    }
    
    /*envia datos para grabar registro: CompromisoPagar*/
    public function postNewCompromisoPagar(){
        $data = Obj::run()->compromisoPagarModel->newCompromisoPagar();
        
        echo json_encode($data);
    }
    
    /*envia datos para editar registro: CompromisoPagar*/
    public function postEditCompromisoPagar(){
        $data = Obj::run()->compromisoPagarModel->editCompromisoPagar();
        
        echo json_encode($data);
    }
    
    /*envia datos para eliminar registro: CompromisoPagar*/
    public function postDeleteCompromisoPagar(){
        $data = Obj::run()->compromisoPagarModel->deleteCompromisoPagar();
        
        echo json_encode($data);
    }
    
    /*envia datos para eliminar registros: CompromisoPagar*/
    public function postDeleteCompromisoPagarAll(){
        $data = Obj::run()->compromisoPagarModel->deleteCompromisoPagarAll();
        
        echo json_encode($data);
    }
    
}

?>