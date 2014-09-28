<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 28-09-2014 00:09:01 
* Descripcion : contratosController.php
* ---------------------------------------
*/    

class contratosController extends Controller{

    public function __construct() {
        $this->loadModel("contratos");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexContratos");
    }
    
    public function getGridContratos(){
        $exportarpdf   = Session::getPermiso('MCONEP');
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->contratosModel->getContratos();
        
        $num = Obj::run()->contratosModel->_iDisplayStart;
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
                switch($aRow['estado']){
                    case 'E':
                        $estado = '<span class=\"label label-default\">'.SEGCO_5.'</span>';
                        break;
                    case 'T':
                        $estado = '<span class=\"label label-success\">'.SEGPA_8.'</span>';
                        break;
                    case 'P':
                        $estado = '<span class=\"label label-warning\">'.SEGPA_7.'</span>';
                        break;
                    case 'A':
                        $estado = '<span class=\"label label-danger\">'.SEGPA_9.'</span>';
                        break;
                    case 'F':
                        $estado = '<span class=\"label label-info\">'.SEGPA_29.'</span>';
                        break;
                }
                
                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_ordenservicio']);
                
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $axion = '"<div class=\"btn-group\">';
                 
                if($exportarpdf['permiso']){
                    $axion .= '<button type=\"button\" class=\"'.$exportarpdf['theme'].'\" title=\"'.$exportarpdf['accion'].'\" onclick=\"contratos.postExportarContratoPDF(this,\'' . $encryptReg . '\')\"> ';
                    $axion .= '    <i class=\"'.$exportarpdf['icono'].'\"></i>';
                    $axion .= '</button>';
                }
       
                
                $axion .= ' </div>" ';
                
                $creador = $aRow['creador'];
                
                /*registros a mostrar*/
                $sOutput .= '["'.$aRow['orden_numero'].'","'.$aRow['fecha_contrato'].'","'.$aRow['cliente'].' - '.$aRow['representante'].'","'.$creador.'","S/.'.number_format($aRow['monto_total'],2).'","'.$estado.'",'.$axion.' ';

                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }
    
    /*carga formulario (newContratos.phtml) para nuevo registro: Contratos*/
    public function getFormNewContratos(){
        Obj::run()->View->render("formNewContratos");
    }
    
    /*carga formulario (editContratos.phtml) para editar registro: Contratos*/
    public function getFormEditContratos(){
        Obj::run()->View->render("formEditContratos");
    }
    
    /*busca data para editar registro: Contratos*/
    public static function findContratos(){
        $data = Obj::run()->contratosModel->findContratos();
            
        return $data;
    }
    
    /*envia datos para grabar registro: Contratos*/
    public function postNewContratos(){
        $data = Obj::run()->contratosModel->newContratos();
        
        echo json_encode($data);
    }
    
    /*envia datos para editar registro: Contratos*/
    public function postEditContratos(){
        $data = Obj::run()->contratosModel->editContratos();
        
        echo json_encode($data);
    }
    
    /*envia datos para eliminar registro: Contratos*/
    public function postDeleteContratos(){
        $data = Obj::run()->contratosModel->deleteContratos();
        
        echo json_encode($data);
    }
    
    /*envia datos para eliminar registros: Contratos*/
    public function postDeleteContratosAll(){
        $data = Obj::run()->contratosModel->deleteContratosAll();
        
        echo json_encode($data);
    }
    
}

?>