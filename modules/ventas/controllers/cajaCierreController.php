<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 06-12-2014 23:12:35 
* Descripcion : cajaCierreController.php
* ---------------------------------------
*/    

class cajaCierreController extends Controller{

    public function __construct() {
        $this->loadModel("cajaCierre");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexCajaCierre");
    }
    
    public function getGridCajaCierre(){
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->cajaCierreModel->getCajaCierre();
        
        $num = Obj::run()->cajaCierreModel->_iDisplayStart;
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
                
               $encryptReg = Aes::en($aRow['id_caja']);
                
                /*campo que maneja los estados, para el ejemplo aqui es ACTIVO, coloca tu campo*/
                if($aRow['estado'] == 'A'){
                    $estado = '<span class=\"label label-success\">'.CAJAA_Apertura.'</span>';
                }else{
                    $estado = '<span class=\"label label-danger\">'.CAJAA_Cierre.'</span>';
                }                                                       
                                        
                 $f = new DateTime($aRow['fecha_creacion']);
		 $c1 = $f->format('d/m/Y h:i A');                                             
                 $c2 =  $aRow['sigla_moneda'];             
                 $c3 =  number_format($aRow['monto_inicial'],2);             
                 $c4 =  number_format($aRow['total_ingresos'],2);             
                 $c5 =  number_format($aRow['total_egresos'],2);             
                 $c6 =  number_format($aRow['total_saldo'],2);             
                /*registros a mostrar*/
                $sOutput .= '["'.$aRow['id_caja'].'","'.$c1.'","'.$c2.'","'.$c3.'","'.$c4.'","'.$c5.'","'.$c6.'","'.$estado.'" ';
                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }
    
     public function postGenerarCierre(){
        $data = Obj::run()->cajaCierreModel->postGenerarCierre();
        echo json_encode($data);
    }    
    
     public function postGenerarReajuste(){
        $data = Obj::run()->cajaCierreModel->postGenerarReajuste();
        echo json_encode($data);
    }      
    
}

?>