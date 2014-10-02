<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 29-09-2014 22:09:59 
* Descripcion : panelclienteController.php
* ---------------------------------------
*/    

class panelclienteController extends Controller{

    public function __construct() {
        $this->loadModel("panelcliente");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexPanelcliente");
    }
    
    public function getConsulta(){ 
        Obj::run()->View->render('consultarPaneCliente');
    }      
    
    public function getGridPanelcliente(){
        
        $consultar = Session::getPermiso('MIPALCC');
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->panelclienteModel->getPanelcliente();
        
        $num = Obj::run()->panelclienteModel->_iDisplayStart;
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
                $encryptReg = Aes::en($aRow['id_caratula']); 
                
               $c1 = $aRow['codigo'];
               $c2 = $aRow['orden_numero'];
               $c3 = $aRow['ubicacion'];
               $c4 = $aRow['elemento'];
               
               if($aRow['iluminado']="SI"){
                    $c5 = '<span class=\"label label-success\">SI</span>';
                }else{
                    $c5 = '<span class=\"label label-danger\">NO</span>';
                }
                               
               $c6 = Functions::cambiaf_a_normal($aRow['fecha_inicio']);
               $c7 = "<b>".Functions::cambiaf_a_normal($aRow['fecha_termino'])."</b>";
            

               if($aRow['imagen'] != '' or $aRow['imagen'] != null){
                   $imagen = '<a href=\"'.BASE_URL.'public/img/confirmacion/'.$aRow['imagen'].'\" target=\"_blank\" ><img border=\"0\" src=\"'.BASE_URL.'public/img/confirmacion/'.$aRow['imagen'].'\" style=\"width:70px; height:40px;\" /></a>';
               }else{
                   $imagen = '<img src=\"'.BASE_URL.'public/img/sin_foto.jpg\" style=\"width:70px; height:40px;\" />';
               }
               
                          
               $axion = '"<div class=\"btn-group\">';
                 
                if($consultar['permiso']){
                    $axion .= '<button type=\"button\" class=\"'.$consultar['theme'].'\" title=\"'.$consultar['accion'].'\" onclick=\"panelcliente.getConsulta(this,\''.$encryptReg.'\',\''.$aRow['imagen'].'\')\">';
                    $axion .= '    <i class=\"'.$consultar['icono'].'\"></i>';
                    $axion .= '</button>';                
                }               
                
                $axion .= ' </div>" ';
               
                /*registros a mostrar*/
                $sOutput .= '["'.$c1.'","'.$c2.'","'.$c3.'","'.$c4.'","'.$c5.'","'.$c6.'","'.$c7.'",'.$axion.' ';

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
        $data = Obj::run()->panelclienteModel->getGeografico();
        
        return $data;
        
    }
            
    
}

?>