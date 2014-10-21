<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 02-10-2014 20:10:49 
* Descripcion : panelesInvertidoController.php
* ---------------------------------------
*/    

class panelesInvertidoController extends Controller{

    public function __construct() {
        $this->loadModel("panelesInvertido");
        $this->loadController(array('modulo' => 'produccion', 'controller' => 'regProduccion')); 
    }
    
    public function index(){ 
        Obj::run()->View->render("indexPanelesInvertido");
    }
    
    public function getConsulta(){ 
        Obj::run()->View->render('consultarPaneSocio');
    }              
    
    public function getGridPanelesInvertido(){
        $consultar   = Session::getPermiso('PAINVCC');
        $exportarpdf   = Session::getPermiso('PAINVEP');
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->panelesInvertidoModel->getPanelesInvertido();
        
        $num = Obj::run()->panelesInvertidoModel->_iDisplayStart;
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
                $encryptReg = Aes::en($aRow['id_producto']);
                $idProduccion = Aes::en($aRow['id_produccion']);
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $axion = '"<div class=\"btn-group\">';
                 
                if($consultar['permiso']){
                    $axion .= '<button type=\"button\" class=\"'.$consultar['theme'].'\" title=\"'.$consultar['accion'].'\" onclick=\"panelesInvertido.getConsulta(this,\''.$encryptReg.'\')\">';
                    $axion .= '    <i class=\"'.$consultar['icono'].'\"></i>';
                    $axion .= '</button>';
                }              
                if($exportarpdf['permiso'] == 1){
                    $axion .= '<button type=\"button\" class=\"'.$exportarpdf['theme'].'\" title=\"'.$exportarpdf['accion'].'\" onclick=\"panelesInvertido.postPDF(this,\''.$idProduccion.'\',\''.$aRow['numero_produccion'].'\')\">';
                    $axion .= '    <i class=\"'.$exportarpdf['icono'].'\"></i>';
                    $axion .= '</button>';
                }
                $axion .= ' </div>" ';
                
                $c1 = $aRow['socio'];
                $c2 = $aRow['ubicacion'];
                $c3 = $aRow['codigos'];
                $c4 = $aRow['fecha'];
                $c5 = number_format($aRow['porcentaje_ganacia']*100,2).'%';
                $c6 = number_format($aRow['total_invertido'],2);
                $c7 = $aRow['numero_produccion'];       
                /*registros a mostrar*/
                $sOutput .= '["'.($num++).'","'.$c1.'","'.$c7.'","'.$c2.'","'.$c3.'","'.$c4.'","'.$c5.'","'.$c6.'",'.$axion.' ';

                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }
  
    public static function getGeografico(){ 
        $data = Obj::run()->panelesInvertidoModel->getGeografico();
        
        return $data;        
    }   
    
    public function postPDF($n=''){
         $data = Obj::run()->regProduccionController->postPDF($n);
         return $data;
    } 
       
    
}

?>