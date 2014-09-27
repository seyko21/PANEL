<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 27-09-2014 07:09:56 
* Descripcion : liquidacionClienteController.php
* ---------------------------------------
*/    

class liquidacionClienteController extends Controller{

    public function __construct() {
        $this->loadModel("liquidacionCliente");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexLiquidacionCliente");
    }
    
    public function getGridLiquidacionCliente(){
        $exportarpdf   = Session::getPermiso('LICLEP');
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->liquidacionClienteModel->getLiquidacionCliente();
        
        $num = Obj::run()->liquidacionClienteModel->_iDisplayStart;
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
                    $axion .= '<button type=\"button\" class=\"'.$exportarpdf['theme'].'\" title=\"'.$exportarpdf['accion'].'\" onclick=\"liquidacionCliente.postPDF(this,\'' . $encryptReg . '\')\"> ';
                    $axion .= '    <i class=\"'.$exportarpdf['icono'].'\"></i>';
                    $axion .= '</button>';
                }
       
                $axion .= ' </div>" ';
                
                /*registros a mostrar*/
                $sOutput .= '["'.$aRow['orden_numero'].'","'.  Functions::cambiaf_a_normal($aRow['fecha_contrato']).'","'.$aRow['cliente'].' - '.$aRow['representante'].'","S/.'.number_format($aRow['monto_total'],2).'","'.$estado.'",'.$axion.' ';

                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }
    
    /*carga formulario (newLiquidacionCliente.phtml) para nuevo registro: LiquidacionCliente*/
    public function getFormNewLiquidacionCliente(){
        Obj::run()->View->render("formNewLiquidacionCliente");
    }
    
    /*carga formulario (editLiquidacionCliente.phtml) para editar registro: LiquidacionCliente*/
    public function getFormEditLiquidacionCliente(){
        Obj::run()->View->render("formEditLiquidacionCliente");
    }
    
    /*busca data para editar registro: LiquidacionCliente*/
    public static function findLiquidacionCliente(){
        $data = Obj::run()->liquidacionClienteModel->findLiquidacionCliente();
            
        return $data;
    }
    
    /*envia datos para grabar registro: LiquidacionCliente*/
    public function postNewLiquidacionCliente(){
        $data = Obj::run()->liquidacionClienteModel->newLiquidacionCliente();
        
        echo json_encode($data);
    }
    
    /*envia datos para editar registro: LiquidacionCliente*/
    public function postEditLiquidacionCliente(){
        $data = Obj::run()->liquidacionClienteModel->editLiquidacionCliente();
        
        echo json_encode($data);
    }
    
    /*envia datos para eliminar registro: LiquidacionCliente*/
    public function postDeleteLiquidacionCliente(){
        $data = Obj::run()->liquidacionClienteModel->deleteLiquidacionCliente();
        
        echo json_encode($data);
    }
    
    /*envia datos para eliminar registros: LiquidacionCliente*/
    public function postDeleteLiquidacionClienteAll(){
        $data = Obj::run()->liquidacionClienteModel->deleteLiquidacionClienteAll();
        
        echo json_encode($data);
    }
    
}

?>