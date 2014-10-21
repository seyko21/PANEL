<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 10-09-2014 14:09:03 
* Descripcion : panelesAlquiladosController.php
* ---------------------------------------
*/    

class panelesAlquiladosController extends Controller{

    public function __construct() {
        $this->loadModel("panelesAlquilados");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexPanelesAlquilados");
    }
    public function getConsulta(){ 
        Obj::run()->View->render('consultarPanelAlquilado');
    }  
   public function getGridProducto(){
      $consultar = Session::getPermiso('PAALCC');       
       $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->panelesAlquiladosModel->getGridProducto();
        
        if(!isset($rResult['error'])){  
            $iTotal         = isset($rResult[0]['total'])?$rResult[0]['total']:0;
            
            $sOutput = '{';
            $sOutput .= '"sEcho": '.intval($sEcho).', ';
            $sOutput .= '"iTotalRecords": '.$iTotal.', ';
            $sOutput .= '"iTotalDisplayRecords": '.$iTotal.', ';
            $sOutput .= '"aaData": [ ';
            foreach ( $rResult as $key=>$aRow ){
                
                if($aRow['estado'] == 'D'){
                    $estado = '<span class=\"label label-success\">Disponible</span>';
                }elseif($aRow['estado'] == 'A'){
                    $estado = '<span class=\"label label-warning\">Alquilado</span>';
                }
                
                if($aRow['iluminado'] == 1){
                    $iluminado = '<span class=\"label label-success\">SI</span>';
                }elseif($aRow['iluminado'] == 0){
                    $iluminado = '<span class=\"label label-danger\">NO</span>';
                }
                
                if($aRow['imagen'] != '' or $aRow['imagen'] != null){
                    $imagen = '<a href=\"'.BASE_URL.'public/img/uploads/'.$aRow['imagen'].'\" target=\"_blank\" ><img border=\"0\" src=\"'.BASE_URL.'public/img/uploads/'.$aRow['imagen'].'\" style=\"width:70px; height:40px;\" /></a>';
                }else{
                    $imagen = '<img src=\"'.BASE_URL.'public/img/sin_foto.jpg\" style=\"width:70px; height:40px;\" />';
                }
                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_caratula']);
                                                                        
                /*datos de manera manual*/
                $sOutput .= '["'.$aRow['codigo'].'","'.$aRow['distrito'].'","'.$aRow['ubicacion'].'","'.$aRow['elemento'].'","'.$aRow['dimesion_area'].'","'.$iluminado.'","'.$estado.'","'.$imagen.'", ';
                 
                 //Visualizar Detalle
                $sOutput .= '"<div class=\"btn-group\">';   
                if($consultar['permiso'] == 1){                   
                    $sOutput .= '<button type=\"button\" class=\"'.$consultar['theme'].'\" title=\"'.$consultar['accion'].'\" onclick=\"panelesAlquilados.getConsulta(this,\''.$encryptReg.'\',\''.$aRow['codigo'].'\')\">';
                    $sOutput .= '    <i class=\"'.$consultar['icono'].'\"></i>';                    
                    $sOutput .= '</button>';                        
                }
                $sOutput .= ' </div>" ';
                
                
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
    public static function getListadoTipoPanelCuenta(){ 
       $data = Obj::run()->panelesAlquiladosModel->getTipoPanelCuenta();            
       return $data;
    }
    
    public function getGridOrdenServicio(){
      
       $sEcho  =   $this->post('sEcho');
        
        $rResult = Obj::run()->panelesAlquiladosModel->getGridOrdenServicio();
        
        if(!isset($rResult['error'])){  
            $iTotal         = isset($rResult[0]['total'])?$rResult[0]['total']:0;
            
            $sOutput = '{';
            $sOutput .= '"sEcho": '.intval($sEcho).', ';
            $sOutput .= '"iTotalRecords": '.$iTotal.', ';
            $sOutput .= '"iTotalDisplayRecords": '.$iTotal.', ';
            $sOutput .= '"aaData": [ ';
            foreach ( $rResult as $key=>$aRow ){
                /*datos de manera manual*/
                                
                $c1 = $aRow['orden_numero'];
                $c2 = $aRow['cliente'].' - '.$aRow['responsable'];
                $c3 = $aRow['creador'];
                $c4 = number_format($aRow['precio'],2);
                $c5 = Functions::cambiaf_a_normal($aRow['fecha_inicio']);
                $c6 = Functions::cambiaf_a_normal($aRow['fecha_termino']);
                if($aRow['imagen'] != '' or $aRow['imagen'] != null){
                    $imagen = '<a href=\"'.BASE_URL.'public/img/confirmacion/'.$aRow['imagen'].'\" target=\"_blank\" ><img border=\"0\" src=\"'.BASE_URL.'public/img/confirmacion/'.$aRow['imagen'].'\" style=\"width:70px; height:40px;\" /></a>';
                }else{
                    $imagen = '<img src=\"'.BASE_URL.'public/img/sin_foto.jpg\" style=\"width:70px; height:40px;\" />';
                }
                $sOutput .= '["'.$c1.'","'.$c2.'","'.$c4.'","'.$c5.'","'.$c6.'","'.$imagen.'" ';
                               
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
    
    public function getGridIndexPanelAlquilado(){
      
       $sEcho  =   $this->post('sEcho');
        
        $rResult = Obj::run()->panelesAlquiladosModel->getIndexPanelAlquilado();
        
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
                $c3 = $aRow['fecha_inicio'];
                $c4 = $aRow['fecha_termino'];
                
                $deuda = $aRow['cuotas_deuda'];
                $oi = $aRow['ordeninstal'];
                if($c3 == '') $c3 = '-';
                if($c4 == '') $c4 = '-';
                
                $sOutput .= '["'.$c1.'","'.$c2.'","'.$c3.'","'.$c4.'" ';
                               
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
    
    public function getGridIndexPanelAlquiladoSocio(){
      
       $sEcho  =   $this->post('sEcho');
        
        $rResult = Obj::run()->panelesAlquiladosModel->getIndexPanelAlquiladoSocio();
        
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
                $c3 = $aRow['fecha_inicio'];
                $c4 = $aRow['fecha_termino'];
                
                $deuda = $aRow['cuotas_deuda'];
                if($c3 == '') $c3 = '-';
                if($c4 == '') $c4 = '-';
                
                $sOutput .= '["'.$c1.'","'.$c2.'","'.$c3.'","'.$c4.'" ';
                               
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