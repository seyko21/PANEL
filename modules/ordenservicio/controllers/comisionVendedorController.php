<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 21-09-2014 18:09:08 
* Descripcion : comisionVendedorController.php
* ---------------------------------------
*/    

class comisionVendedorController extends Controller{

    public function __construct() {
        $this->loadModel("comisionVendedor");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexComisionVendedor");
    }
    
    public function getGridComisionVendedor(){
        $generar   = Session::getPermiso('COMVEGN');
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->comisionVendedorModel->getComisionVendedor();
        
        if(!isset($rResult['error'])){  
            $iTotal         = isset($rResult[0]['total'])?$rResult[0]['total']:0;
            
            $sOutput = '{';
            $sOutput .= '"sEcho": '.intval($sEcho).', ';
            $sOutput .= '"iTotalRecords": '.$iTotal.', ';
            $sOutput .= '"iTotalDisplayRecords": '.$iTotal.', ';
            $sOutput .= '"aaData": [ ';     
            
            foreach ( $rResult as $aRow ){
                
                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_ordenserviciod']);
                
                
                
                /*registros a mostrar*/
                $sOutput .= '["'.$aRow['vendedor'].'", ';
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';
                 
                if($generar['permiso']){
                    $sOutput .= '<button type=\"button\" class=\"'.$generar['theme'].'\" title=\"'.COMVE_2.'\" onclick=\"comisionVendedor.getFormComisionVendedor(this,\''.$encryptReg.'\',\''.$aRow['vendedor'].'\')\">';
                    $sOutput .= '    <i class=\"'.$generar['icono'].'\"></i>';
                    $sOutput .= '</button>';
                }
                
                $sOutput .= ' </div>" ';
                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }
    
    public function getFormComisionVendedor(){
        Obj::run()->View->render("formComisionVendedor");
    }
    
    public function getOrdenesServicio(){
        $data = Obj::run()->comisionVendedorModel->getOrdenesServicio();
        return $data;
    }
    
    public function getImagenesConfirmadas($orden){
        $data = Obj::run()->comisionVendedorModel->getImagenesConfirmadas($orden);
        return $data;
    }
    
    public function postGenerarComisionVendedor(){
        $data = Obj::run()->comisionVendedorModel->generarComisionVendedor();
        
        echo json_encode($data);
    }
    
}

?>