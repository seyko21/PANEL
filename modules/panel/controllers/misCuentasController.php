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

       $consultar   = Session::getPermiso('MISPACC');
       
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
                
                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_producto']);
                
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
              
                if($aRow['imagen'] != '' or $aRow['imagen'] != null){
                    $imagen = '<a href=\"'.BASE_URL.'public/img/uploads/'.$aRow['imagen'].'\" target=\"_blank\" ><img border=\"0\" src=\"'.BASE_URL.'public/img/uploads/'.$aRow['imagen'].'\" style=\"width:70px; height:40px;\" /></a>';
                }else{
                    $imagen = '<img src=\"'.BASE_URL.'public/img/sin_foto.jpg\" style=\"width:70px; height:40px;\" />';
                }
                
                $axion = '"<div class=\"btn-group\">';
                 
                if($consultar['permiso']){
                    $axion .= '<button type=\"button\" class=\"'.$consultar['theme'].'\" title=\"'.$consultar['accion'].'\" onclick=\"panelesInvertido.getConsulta(this,\''.$encryptReg.'\')\">';
                    $axion .= '    <i class=\"'.$consultar['icono'].'\"></i>';
                    $axion .= '</button>';
                }              
                $axion .= ' </div>" ';
                
                /*datos de manera manual*/
                $sOutput .= '["'.$aRow['codigo'].'","'.$aRow['ubicacion'].'","'.$aRow['elemento'].'","'.$aRow['dimesion_area'].'","'.number_format($aRow['precio'],2).'","'.$iluminado.'","'.$estado.'","'.$imagen.'",'.$axion.' ';
                                                                                          
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