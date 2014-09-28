<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 28-09-2014 00:09:34 
* Descripcion : pagosRecibidosController.php
* ---------------------------------------
*/    

class pagosRecibidosController extends Controller{

    public function __construct() {
        $this->loadModel("pagosRecibidos");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexPagosRecibidos");
    }
    
    public function getGridPagosRecibidos(){
        $consultar   = Session::getPermiso('PAGRECC');
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->pagosRecibidosModel->getPagosRecibidos();
        
        $num = Obj::run()->pagosRecibidosModel->_iDisplayStart;
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
                $encryptReg = Aes::en($aRow['id_comision']);
                
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $axion = '"<div class=\"btn-group\">';
                 
                if($consultar['permiso']){
                    $axion .= '<button type=\"button\" class=\"'.$consultar['theme'].'\" title=\"'.$consultar['accion'].'\" onclick=\"pagosRecibidos.getFormConsulta(this,\''.$encryptReg.'\')\">';
                    $axion .= '    <i class=\"'.$consultar['icono'].'\"></i>';
                    $axion .= '</button>';
                }            
                
                $axion .= ' </div>" ';
                                
                $c2 = $aRow['orden_numero'];
                $c3 = $aRow['nombrecompleto'];
                $c4 = $aRow['fecha'];
                $c5 = (number_format($aRow['porcentaje_comision']*100)).' %';
                $c6 = number_format($aRow['comision_venta'],2);
                $c7 = number_format($aRow['comision_asignado'],2);
                $c8 = number_format($aRow['comision_saldo'],2);
 
                /*registros a mostrar*/
                $sOutput .= '["'.($num++).'","'.$c2.'","'.$c3.'","'.$c4.'","'.$c5.'","'.$c6.'","'.$c7.'","'.$c8.'",'.$axion.' ';                

                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }
    
    /*carga formulario (newPagosRecibidos.phtml) para nuevo registro: PagosRecibidos*/
    public function getFormNewPagosRecibidos(){
        Obj::run()->View->render("formNewPagosRecibidos");
    }
    
    /*carga formulario (editPagosRecibidos.phtml) para editar registro: PagosRecibidos*/
    public function getFormEditPagosRecibidos(){
        Obj::run()->View->render("formEditPagosRecibidos");
    }
    
    /*busca data para editar registro: PagosRecibidos*/
    public static function findPagosRecibidos(){
        $data = Obj::run()->pagosRecibidosModel->findPagosRecibidos();
            
        return $data;
    }
    
    /*envia datos para grabar registro: PagosRecibidos*/
    public function postNewPagosRecibidos(){
        $data = Obj::run()->pagosRecibidosModel->newPagosRecibidos();
        
        echo json_encode($data);
    }
    
    /*envia datos para editar registro: PagosRecibidos*/
    public function postEditPagosRecibidos(){
        $data = Obj::run()->pagosRecibidosModel->editPagosRecibidos();
        
        echo json_encode($data);
    }
    
    /*envia datos para eliminar registro: PagosRecibidos*/
    public function postDeletePagosRecibidos(){
        $data = Obj::run()->pagosRecibidosModel->deletePagosRecibidos();
        
        echo json_encode($data);
    }
    
    /*envia datos para eliminar registros: PagosRecibidos*/
    public function postDeletePagosRecibidosAll(){
        $data = Obj::run()->pagosRecibidosModel->deletePagosRecibidosAll();
        
        echo json_encode($data);
    }
    
}

?>