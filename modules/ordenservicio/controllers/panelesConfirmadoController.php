<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 27-09-2014 18:09:14 
* Descripcion : panelesConfirmadoController.php
* ---------------------------------------
*/    

class panelesConfirmadoController extends Controller{

    public function __construct() {
        $this->loadModel("panelesConfirmado");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexPanelesConfirmado");
    }
    
    public function getGridPanelesConfirmado(){
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->panelesConfirmadoModel->getPanelesConfirmado();
        
        $num = Obj::run()->panelesConfirmadoModel->_iDisplayStart;
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

             $c1 = $aRow['orden_numero'];
             $c2 = $aRow['vendedor'];
             $c3 = $aRow['codigo'];
             $c4 = $aRow['ubicacion'].' '.$aRow['descripcion'];
             $c5 = Functions::cambiaf_a_normal($aRow['fecha_instalacion']);
             $c6 = $aRow['fecha_imagen'];
             
             if($aRow['imagen'] != '' or $aRow['imagen'] != null){
                $imagen = '<a href=\"'.BASE_URL.'public/img/confirmacion/'.$aRow['imagen'].'\" target=\"_blank\" ><img border=\"0\" src=\"'.BASE_URL.'public/img/confirmacion/'.$aRow['imagen'].'\" style=\"width:70px; height:40px;\" /></a>';
             }else{
                $imagen = '<img src=\"'.BASE_URL.'public/img/sin_foto.jpg\" style=\"width:70px; height:40px;\" />';
             }
                                 
             /*registros a mostrar*/
              $sOutput .= '["'.$c1.'","'.$c2.'","'.$c3.'","'.$c4.'","'.$c5.'","'.$c6.'","'.$imagen.'" ';
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