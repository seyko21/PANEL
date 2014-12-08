<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 06-12-2014 21:12:27 
* Descripcion : cajaAperturaController.php
* ---------------------------------------
*/    

class cajaAperturaController extends Controller{

    public function __construct() {
        $this->loadModel("cajaApertura");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexCajaApertura");
    }
    
    public function getGridCajaApertura(){
        $editar   = Session::getPermiso('CAJAAED');        
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->cajaAperturaModel->getCajaApertura();
        
        $num = Obj::run()->cajaAperturaModel->_iDisplayStart;
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
                $encryptReg = Aes::en($aRow['id_caja']);
                
                /*campo que maneja los estados, para el ejemplo aqui es ACTIVO, coloca tu campo*/
                if($aRow['estado'] == 'A'){
                    $estado = '<span class=\"label label-success\">'.CAJAA_Apertura.'</span>';
                }else{
                    $estado = '<span class=\"label label-danger\">'.CAJAA_Cierre.'</span>';
                }                                                       
                        
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $axion = '"<div class=\"btn-group\">';
                 
                if($editar['permiso'] && $aRow['estado'] == 'A'  ){
                    $axion .= '<button type=\"button\" class=\"'.$editar['theme'].'\" title=\"'.$editar['accion'].'\" onclick=\"cajaApertura.getFormEditCajaApertura(this,\''.$encryptReg.'\',\''.$aRow['descripcion_moneda'].'\')\">';
                    $axion .= '    <i class=\"'.$editar['icono'].'\"></i>';
                    $axion .= '</button>';
                }else{
                    $axion .= '<button type=\"button\" class=\"'.$editar['theme'].'\" title=\"'.$editar['accion'].'\" disabled >';
                    $axion .= '    <i class=\"'.$editar['icono'].'\"></i>';
                    $axion .= '</button>';
                }
                
                $axion .= ' </div>" ';
                 $f = new DateTime($aRow['fecha_creacion']);
		 $c1 = $f->format('d/m/Y h:i A');                                             
                 $c2 =  $aRow['sigla_moneda'];             
                 $c3 =  number_format($aRow['monto_inicial'],2);             
                 $c4 =  number_format($aRow['total_ingresos'],2);             
                 $c5 =  number_format($aRow['total_egresos'],2);             
                 $c6 =  number_format($aRow['total_saldo'],2);             
                /*registros a mostrar*/
                $sOutput .= '["'.$aRow['id_caja'].'","'.$c1.'","'.$c2.'","'.$c3.'","'.$c4.'","'.$c5.'","'.$c6.'","'.$estado.'",'.$axion.' ';

                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }

   public function postGenerarApertura(){
        $data = Obj::run()->cajaAperturaModel->postGenerarApertura();
        echo json_encode($data);
    }    

   public function getValidarCaja(){
        $data = Obj::run()->cajaAperturaModel->getValidarCaja();
        return $data;
    }  
    
    /*carga formulario (editCajaApertura.phtml) para editar registro: CajaApertura*/
    public function getFormEditCajaApertura(){
        Obj::run()->View->render("formEditCajaApertura");
    }
    
    /*busca data para editar registro: CajaApertura*/
    public static function findCajaApertura(){
        $data = Obj::run()->cajaAperturaModel->findCajaApertura();
            
        return $data;
    }
    
    /*envia datos para editar registro: CajaApertura*/
    public function postEditCajaApertura(){
        $data = Obj::run()->cajaAperturaModel->editCajaApertura();
        
        echo json_encode($data);
    }
    
}

?>