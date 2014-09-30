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
    
    public function getGridPanelcliente(){
        $editar   = Session::getPermiso('MIPALED');
        $eliminar = Session::getPermiso('MIPALDE');
        
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
               $c7 = Functions::cambiaf_a_normal($aRow['fecha_termino']);
            
               if($aRow['imagen'] != '' or $aRow['imagen'] != null){
                   $imagen = '<a href=\"'.BASE_URL.'public/img/confirmacion/'.$aRow['imagen'].'\" target=\"_blank\" ><img border=\"0\" src=\"'.BASE_URL.'public/img/confirmacion/'.$aRow['imagen'].'\" style=\"width:70px; height:40px;\" /></a>';
               }else{
                   $imagen = '<img src=\"'.BASE_URL.'public/img/sin_foto.jpg\" style=\"width:70px; height:40px;\" />';
               }
                /*registros a mostrar*/
                $sOutput .= '["'.$c1.'","'.$c2.'","'.$c3.'","'.$c4.'","'.$c5.'","'.$c6.'","'.$c7.'","'.$imagen.'" ';

                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }      
    
}

?>