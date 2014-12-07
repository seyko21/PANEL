<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 22-11-2014 20:11:18 
* Descripcion : vegresosController.php
* ---------------------------------------
*/    

class vegresosController extends Controller{

    public function __construct() {
        $this->loadModel(array('modulo'=>'ventas','modelo'=>'vegresos'));
        $this->loadController(array('modulo'=>'ventas','controller'=>'vproducto')); 
        $this->loadController(array('modulo'=>'ventas','controller'=>'cajaApertura'));         
    }
    
    public function index(){ 
        Obj::run()->View->render("indexVegresos");
    }
    
    public function getGridVegresos(){
        $editar   = Session::getPermiso('VEGREED');
        $eliminar = Session::getPermiso('VEGREDE');
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->vegresosModel->getVegresos();
        
        $num = Obj::run()->vegresosModel->_iDisplayStart;
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
                               
                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_egreso']);
                
                switch($aRow['estado']){
                    case 'E':
                         $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" name=\"'.VEGRE.'chk_delete[]\" value=\"'.$encryptReg.'\">';
                        $estado = '<span class=\"label label-default\">'.SEGCO_5.'</span>';
                        break;                  
                    case 'A':
                        $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" name=\"'.VEGRE.'chk_delete[]\" disabled>';       
                        $estado = '<span class=\"label label-danger\">'.SEGPA_9.'</span>';
                        break;                 
                }
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $axion = '"<div class=\"btn-group\">';
                 
                if($editar['permiso']){
                    $axion .= '<button type=\"button\" class=\"'.$editar['theme'].'\" title=\"'.$editar['accion'].'\" onclick=\"vegresos.getFormEditVegresos(this,\''.$encryptReg.'\')\">';
                    $axion .= '    <i class=\"'.$editar['icono'].'\"></i>';
                    $axion .= '</button>';
                }
                
                $axion .= ' </div>" ';

                /*registros a mostrar*/
                $sOutput .= '["'.$chk.'","'.$aRow['descripcion'].'","'.Functions::cambiaf_a_normal($aRow['fecha']).'","'.$aRow['moneda'].'","'.number_format($aRow['monto'],2).'","'.$estado.'" ';

                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }
    
    /*carga formulario (newVegresos.phtml) para nuevo registro: Vegresos*/
    public function getFormNewVegresos(){
        Obj::run()->View->render("formNewVegresos");
    }
    
    /*carga formulario (editVegresos.phtml) para editar registro: Vegresos*/
    public function getFormEditVegresos(){
        Obj::run()->View->render("formEditVegresos");
    }
    
    /*busca data para editar registro: Vegresos*/
    public static function findVegresos(){
        $data = Obj::run()->vegresosModel->findVegresos();
            
        return $data;
    }
    
    /*envia datos para grabar registro: Vegresos*/
    public function postNewVegresos(){
        $data = Obj::run()->vegresosModel->newVegresos();
        
        echo json_encode($data);
    }
    
    /*envia datos para editar registro: Vegresos*/
    public function postEditVegresos(){
        $data = Obj::run()->vegresosModel->editVegresos();
        
        echo json_encode($data);
    }
    
    /*envia datos para eliminar registro: Vegresos*/
    public function postDeleteVegresos(){
        $data = Obj::run()->vegresosModel->deleteVegresos();
        
        echo json_encode($data);
    }
    
    /*envia datos para eliminar registros: Vegresos*/
    public function postDeleteVegresosAll(){
        $data = Obj::run()->vegresosModel->deleteVegresosAll();
        
        echo json_encode($data);
    }
    
    public static function getValidarCaja(){ 
        $data = Obj::run()->cajaAperturaController->getValidarCaja();        
        return $data;
    }      
    
}

?>