<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 25-09-2014 23:09:26 
* Descripcion : alquilerCulminarController.php
* ---------------------------------------
*/    

class alquilerCulminarController extends Controller{

    public function __construct() {
        $this->loadModel("alquilerCulminar");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexAlquilerCulminar");
    }
    
    public function getGridAlquilerCulminar(){

        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->alquilerCulminarModel->getAlquilerCulminar();
        
        $num = Obj::run()->alquilerCulminarModel->_iDisplayStart;
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
                $fi = Functions::cambiaf_a_normal($aRow['fecha_inicio']);
                $ff = '<span class=\"label label-danger\">'.Functions::cambiaf_a_normal($aRow['fecha_termino']).'</span>'; 
                $oferta = $aRow['dias_oferta'].'d';
                $idPersona = Aes::en($aRow['id_persona']);
                /*registros a mostrar*/
                
                $sOutput .= '["'.$aRow['codigo'].'","'.$aRow['orden_numero'].'","'.$aRow['ordenin_numero'].'","<a href=\"javascript:;\" onclick=\"persona.getDatosPersonales(\''.$idPersona.'\');\">'.$aRow['cliente'].'</a>","'.$fi.'","'.$ff.'","'.Functions::convertirDiaMes($aRow['meses_contrato']).'","'.$oferta.'" ';

                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }
    
    public function getGridIndexAlquilerCulminar(){
      
       $sEcho  =   $this->post('sEcho');
        
        $rResult = Obj::run()->alquilerCulminarModel->getIndexAlquilerCulminar();
        
        if(!isset($rResult['error'])){  
            $iTotal         = isset($rResult[0]['total'])?$rResult[0]['total']:0;
            
            $sOutput = '{';
            $sOutput .= '"sEcho": '.intval($sEcho).', ';
            $sOutput .= '"iTotalRecords": '.$iTotal.', ';
            $sOutput .= '"iTotalDisplayRecords": '.$iTotal.', ';
            $sOutput .= '"aaData": [ ';
            foreach ( $rResult as $key=>$aRow ){
                /*datos de manera manual*/
                $c1 = $aRow['codigo'];
                $c2 = $aRow['orden_numero'];
                $c3 = $aRow['cliente'];
                $c4 = Functions::cambiaf_a_normal($aRow['fecha_inicio']);
                $c5 = Functions::cambiaf_a_normal($aRow['fecha_termino']);
                $c6 = Functions::convertirDiaMes($aRow['meses_contrato']);
                
                $ffd = '<span class=\"label label-danger\">'.$c5.'</span>';  
                
                $sOutput .= '["'.$c1.'","'.$c2.'","'.$c3.'","'.$ffd.'","'.$c6.'" ';
                               
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
  
}

?>