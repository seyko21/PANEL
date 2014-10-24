<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 27-09-2014 05:09:34 
* Descripcion : movimientosController.php
* ---------------------------------------
*/    

class movimientosController extends Controller{

    public function __construct() {
        $this->loadModel("movimientos");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexMovimientos");
    }    
    public function getGridMovimientos(){
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->movimientosModel->getMovimientos();
        
        $num = Obj::run()->movimientosModel->_iDisplayStart;
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
                
                /*campo que maneja los estados, para el ejemplo aqui es ACTIVO, coloca tu campo*/
                if($aRow['estado'] == 'E'){
                    $estado = '<span class=\"label label-success\">'.COPAG_3.'</span>';
                }else{
                    $estado = '<span class=\"label label-danger\">'.LABEL_AN.'</span>';
                }
                              
                
                $c1 = $aRow['id_movimiento'];
                $c2 = $aRow['observacion'];
                $c3 = Functions::cambiaf_a_normal($aRow['fecha']);
                $c4 = $aRow['orden_numero'];                
                $c5 = $aRow['codigo'];
                
                $c6 = ($aRow['tipo']=='I'?'Ingreso':'Salida');
                $c7 = ($aRow['moneda']=='SO')?'S/.':'$US';
                $c8 = number_format($aRow['monto'],2);
                
                /*registros a mostrar*/
                $sOutput .= '["'.$c1.'","'.$c3.'","'.$c4.'","'.$c5.'","'.$c6.'","'.$c7.'","'.$c8.'","'.$estado.'","'.$c2.'" ';

                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }
    
    /*carga formulario (newMovimientos.phtml) para nuevo registro: Movimientos*/
    public function getFormNewMovimientos(){
        Obj::run()->View->render("formNewMovimientos");
    }
    
    /*carga formulario (editMovimientos.phtml) para editar registro: Movimientos*/
    public function getFormEditMovimientos(){
        Obj::run()->View->render("formEditMovimientos");
    }
    
    /*busca data para editar registro: Movimientos*/
    public static function findMovimientos(){
        $data = Obj::run()->movimientosModel->findMovimientos();
            
        return $data;
    }
    
    /*envia datos para grabar registro: Movimientos*/
    public function postNewMovimientos(){
        $data = Obj::run()->movimientosModel->newMovimientos();
        
        echo json_encode($data);
    }
    
    /*envia datos para editar registro: Movimientos*/
    public function postEditMovimientos(){
        $data = Obj::run()->movimientosModel->editMovimientos();
        
        echo json_encode($data);
    }
    
    /*envia datos para eliminar registro: Movimientos*/
    public function postDeleteMovimientos(){
        $data = Obj::run()->movimientosModel->deleteMovimientos();
        
        echo json_encode($data);
    }
    
    /*envia datos para eliminar registros: Movimientos*/
    public function postDeleteMovimientosAll(){
        $data = Obj::run()->movimientosModel->deleteMovimientosAll();
        
        echo json_encode($data);
    }
    
}

?>