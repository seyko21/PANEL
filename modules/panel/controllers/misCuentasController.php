<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 09-09-2014 04:09:19 
* Descripcion : misCuentasController.php
* ---------------------------------------
*/    

class misCuentasController extends Controller{

    public function __construct() {
        $this->loadModel("misCuentas");        
    }
    
    public function index(){ 
        Obj::run()->View->render("indexMisCuentas");
    }
    
public function getGridProducto(){
//       $editar = Session::getPermiso('CATPRED');       
//       $exportarpdf   = Session::getPermiso('CATPREP');
//       $exportarexcel = Session::getPermiso('CATPREX'); 
       $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->misCuentasModel->getGridProducto();
        
        if(!isset($rResult['error'])){  
            $iTotal         = isset($rResult[0]['total'])?$rResult[0]['total']:0;
            
            $sOutput = '{';
            $sOutput .= '"sEcho": '.intval($sEcho).', ';
            $sOutput .= '"iTotalRecords": '.$iTotal.', ';
            $sOutput .= '"iTotalDisplayRecords": '.$iTotal.', ';
            $sOutput .= '"aaData": [ ';
            foreach ( $rResult as $key=>$aRow ){
                
                if($aRow['estado'] == 'D'){
                    $estado = '<span class=\"label label-success\">Disponible</span>';
                }elseif($aRow['estado'] == 'A'){
                    $estado = '<span class=\"label label-warning\">Alquilado</span>';
                }
                
                if($aRow['iluminado'] == 1){
                    $iluminado = '<span class=\"label label-success\">SI</span>';
                }elseif($aRow['iluminado'] == 0){
                    $iluminado = '<span class=\"label label-danger\">NO</span>';
                }
                
                /*antes de enviar id se encrypta*/
                //$encryptReg = Aes::en($aRow['id_caratula']);
                //$idProd = Aes::en($aRow['id_producto']);
                                                
                /*datos de manera manual*/
                $sOutput .= '["'.$aRow['codigo'].'","'.$aRow['distrito'].'","'.$aRow['ubicacion'].'","'.$aRow['elemento'].'","'.$aRow['dimesion_area'].'","'.number_format($aRow['precio'],2).'","'.$iluminado.'","'.$estado.'" ';
                                                                                          
                $sOutput = substr_replace( $sOutput, "", -1 );
                $sOutput .= '],';
            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;
    }          
    public static function getListadoTipoPanelCuenta(){ 
       $data = Obj::run()->misCuentasModel->getTipoPanelCuenta();            
       return $data;
    }
       
    
}

?>