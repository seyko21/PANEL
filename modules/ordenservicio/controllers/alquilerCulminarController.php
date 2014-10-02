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
                /*registros a mostrar*/
                
                $sOutput .= '["'.$aRow['codigo'].'","'.$aRow['orden_numero'].'","'.$aRow['ordenin_numero'].'","'.$aRow['cliente'].'","'.$fi.'","'.$ff.'","'.Functions::convertirDiaMes($aRow['meses_contrato']).'","'.$oferta.'" ';

                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }
    
    /*carga formulario (newAlquilerCulminar.phtml) para nuevo registro: AlquilerCulminar*/
    public function getFormNewAlquilerCulminar(){
        Obj::run()->View->render("formNewAlquilerCulminar");
    }
    
    /*carga formulario (editAlquilerCulminar.phtml) para editar registro: AlquilerCulminar*/
    public function getFormEditAlquilerCulminar(){
        Obj::run()->View->render("formEditAlquilerCulminar");
    }
    
    /*busca data para editar registro: AlquilerCulminar*/
    public static function findAlquilerCulminar(){
        $data = Obj::run()->alquilerCulminarModel->findAlquilerCulminar();
            
        return $data;
    }
    
    /*envia datos para grabar registro: AlquilerCulminar*/
    public function postNewAlquilerCulminar(){
        $data = Obj::run()->alquilerCulminarModel->newAlquilerCulminar();
        
        echo json_encode($data);
    }
    
    /*envia datos para editar registro: AlquilerCulminar*/
    public function postEditAlquilerCulminar(){
        $data = Obj::run()->alquilerCulminarModel->editAlquilerCulminar();
        
        echo json_encode($data);
    }
    
    /*envia datos para eliminar registro: AlquilerCulminar*/
    public function postDeleteAlquilerCulminar(){
        $data = Obj::run()->alquilerCulminarModel->deleteAlquilerCulminar();
        
        echo json_encode($data);
    }
    
    /*envia datos para eliminar registros: AlquilerCulminar*/
    public function postDeleteAlquilerCulminarAll(){
        $data = Obj::run()->alquilerCulminarModel->deleteAlquilerCulminarAll();
        
        echo json_encode($data);
    }
    
}

?>