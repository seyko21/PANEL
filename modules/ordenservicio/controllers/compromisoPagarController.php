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
        
        $consultar   = Session::getPermiso('COPAGCC');
        
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
                
                 $encryptReg = Aes::en($aRow['id_ordenservicio']);
                
                /*campo que maneja los estados, para el ejemplo aqui es ACTIVO, coloca tu campo*/
                switch ($aRow['estado']){
                        case 'E': #emitido
                            $estado = '<span class=\"label label-default\">' . CROPA_2 . '</span>';
                            break;
                        case 'P': #pagado
                            $estado = '<span class=\"label label-success\">' . CROPA_3 . '</span>';
                            break;
                        case 'R': #reprogramado
                            $estado = '<span class=\"label label-warning\">' . CROPA_4 . '</span>';
                            break;
                        default:
                            $estado = '';
                            break;
                 }
                 
                                
                $axion = '"<div class=\"btn-group\">';
                 
                if($consultar['permiso']){
                    if ($aRow['estado'] == 'P'){
                        $axion .= '<button type=\"button\" class=\"'.$consultar['theme'].'\" title=\"'.$consultar['accion'].' pagos \" onclick=\"compromisoPagar.postExportarContratoPDF(this,\'' . $encryptReg . '\')\"> ';
                        $axion .= '    <i class=\"'.$consultar['icono'].'\"></i>';
                        $axion .= '</button>';
                    }else{
                        $axion .= '<button type=\"button\" class=\"'.$consultar['theme'].'\" title=\"'.$consultar['accion'].'\" disabled=\"disabled\" > ';
                        $axion .= '    <i class=\"'.$consultar['icono'].'\"></i>';
                        $axion .= '</button>';
                    }
                }
                $axion .= ' </div>" ';
                /*registros a mostrar*/
                $sOutput .= '["'.$aRow['orden_numero'].'","'.$aRow['numero_cuota'].'","'.$aRow['fecha_programada'].'","'.$aRow['cliente'].' - '.$aRow['representante'].'","'.number_format($aRow['mora'],2).'","'.number_format($aRow['monto_pago'],2).'","'.$estado.'",'.$axion.' ';

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