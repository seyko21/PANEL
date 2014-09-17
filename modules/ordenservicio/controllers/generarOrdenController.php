<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 09-09-2014 06:09:13 
* Descripcion : generarOrdenController.php
* ---------------------------------------
*/    

class generarOrdenController extends Controller{

    public function __construct() {
        $this->loadModel("generarOrden");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexGenerarOrden");
    }
    
    public function getGridGenerarOrden(){
        $editar   = Session::getPermiso('ORSERED');
        $email    = Session::getPermiso('ORSEREE');
        $generar  = Session::getPermiso('ORSERGN');
       
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->generarOrdenModel->getGenerarOrden();
        
        $num = Obj::run()->generarOrdenModel->_iDisplayStart;
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
//                if($aRow['estado'] == 1){
//                    $estado = '<span class=\"label label-success\">'.LABEL_ACT.'</span>';
//                }else{
//                    $estado = '<span class=\"label label-danger\">'.LABEL_DES.'</span>';
//                }
                
                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_ordenservicio']);
                $idUser = Aes::en($aRow['id_usuario']);
                
                
                /*registros a mostrar*/
                $sOutput .= '["'.($num++).'","'.$aRow['orden_numero'].'","'.$aRow['cotizacion_numero'].'","'.$aRow['nombrecompleto'].'","'.$aRow['cliente'].'","'.number_format($aRow['monto_total'],2).'",';
                
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';
                 
                if($editar['permiso']){
                    $sOutput .= '<button type=\"button\" class=\"'.$editar['theme'].'\" title=\"'.$editar['accion'].'\" onclick=\"generarOrden.getFormEditOrden(this,\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"'.$editar['icono'].'\"></i>';
                    $sOutput .= '</button>';
                }
                if($generar['permiso']){
                    $sOutput .= '<button type=\"button\" class=\"'.$generar['theme'].'\" title=\"'.$generar['accion'].' '.GNOSE_2.'\" onclick=\"generarOrden.getFormCronograma(this,\''.$encryptReg.'\',\''.$aRow['monto_total'].'\',\''.$aRow['orden_numero'].'\')\">';
                    $sOutput .= '    <i class=\"'.$generar['icono'].'\"></i>';
                    $sOutput .= '</button>';
                }
                if($email['permiso']){
                    $sOutput .= '<button type=\"button\" class=\"'.$email['theme'].'\" title=\"Enviar accesos a email\" onclick=\"registrarVendedor.postAccesoVendedor(this,\'' . $idUser . '\',\'' . $aRow['nombrecompleto'] . '\',\'' . $aRow['email'] . '\')\">';
                    $sOutput .= '    <i class=\"'.$email['icono'].'\"></i>';
                    $sOutput .= '</button>';
                }
                
                $sOutput .= '</div>"';
                
                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }
    
    public function getFormCronograma(){
        Obj::run()->View->render("formCronograma");
    }
    
    public function getFormEditOrden(){
        Obj::run()->View->render("formEditOrden");
    }
    
    public function postCuota(){
        $data = Obj::run()->generarOrdenModel->insertCuota();
        
        echo json_encode($data);
    }
    
    public function getGridCuotas(){
        $eliminar   = Session::getPermiso('ORSERDE');
       
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->generarOrdenModel->getGridCuotas();
        
        if(!isset($rResult['error'])){  
            $iTotal         = isset($rResult[0]['total'])?$rResult[0]['total']:0;
            
            $sOutput = '{';
            $sOutput .= '"sEcho": '.intval($sEcho).', ';
            $sOutput .= '"iTotalRecords": '.$iTotal.', ';
            $sOutput .= '"iTotalDisplayRecords": '.$iTotal.', ';
            $sOutput .= '"aaData": [ ';     
            
            foreach ( $rResult as $aRow ){
                
                
                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_compromisopago']);
                
                /*registros a mostrar*/
                $sOutput .= '["'.$aRow['numero_cuota'].'","'.number_format($aRow['monto_pago'], 2).'","'.$aRow['fechapago'].'",';
                
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';
                 
                if($eliminar['permiso']){
                    if($aRow['estado'] == 'E'){ #solo se eliminan los que estan en estdo E
                        $sOutput .= '<button type=\"button\" class=\"'.$eliminar['theme'].'\" title=\"'.$eliminar['accion'].'\" onclick=\"generarOrden.postDeleteCuota(this,\''.$encryptReg.'\')\">';
                        $sOutput .= '    <i class=\"'.$eliminar['icono'].'\"></i>';
                        $sOutput .= '</button>';
                    }else{
                        $sOutput .= '<button type=\"button\" class=\"'.$eliminar['theme'].'\" title=\"'.$eliminar['accion'].'\" onclick=\"generarOrden.postDeleteCuotaNo()\">';
                        $sOutput .= '    <i class=\"'.$eliminar['icono'].'\"></i>';
                        $sOutput .= '</button>';
                    }
                }
                
                $sOutput .= '</div>"';
                
                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }
    
    public function postEditOrden(){
        $data = Obj::run()->generarOrdenModel->editOrden();
        
        echo json_encode($data);
    }
    
    public function findOrden(){
        $data = Obj::run()->generarOrdenModel->findOrden();
        
        return $data;
    }
    
    public function postDeleteCuota(){
        $data = Obj::run()->generarOrdenModel->postDeleteCuota();
        
        echo json_encode($data);
    }
    
}

?>