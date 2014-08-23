<?php
/*
* --------------------------------------
* fecha: 15-08-2014 02:08:24 
* Descripcion : fichaTecnicaController.php
* --------------------------------------
*/    

class fichaTecnicaController extends Controller{

    public function __construct() {
        $this->loadModel('fichaTecnica');
    }
    
   public function index(){ 
        Obj::run()->View->render('indexFichaTecnica');
    }
    public function getListaCaratulas(){
        Obj::run()->View->render('indexCaratula');
    }
    public function getNuevoFichaTecnica(){ 
        Obj::run()->View->render('nuevoFichaTecnica');
    }    
    public function getEditarFichaTecnica(){ 
        Obj::run()->View->render('editarFichaTecnica');
    }
    public function getNuevoCaratula(){ 
        Obj::run()->View->render('nuevoCaratula');
    }    
    public function getEditarCaratula(){ 
        Obj::run()->View->render('editarCaratula');
    }          
   public function getGridFichaTecnica(){
       $editar = Session::getPermiso('FITECED');
       $eliminar = Session::getPermiso('FITECDE');
       $agregar = Session::getPermiso('FITECAG'); 
       $exportarpdf   = Session::getPermiso('FITECEP');
       $exportarexcel = Session::getPermiso('FITECEX');
       $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->fichaTecnicaModel->getGridFichaTecnica();
        
        if(!isset($rResult['error'])){  
            $iTotal         = isset($rResult[0]['total'])?$rResult[0]['total']:0;
            
            $sOutput = '{';
            $sOutput .= '"sEcho": '.intval($sEcho).', ';
            $sOutput .= '"iTotalRecords": '.$iTotal.', ';
            $sOutput .= '"iTotalDisplayRecords": '.$iTotal.', ';
            $sOutput .= '"aaData": [ ';
            foreach ( $rResult as $key=>$aRow ){
                
                if($aRow['estado'] == 'A'){
                    $estado = '<span class=\"label label-success\">Activo</span>';
                }elseif($aRow['estado'] == 'B'){
                    $estado = '<span class=\"label label-danger\">Baja</span>';
                }
            
                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_producto']);
                
                $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" name=\"'.T102.'chk_delete[]\" value=\"'.$encryptReg.'\">';
                
                /*datos de manera manual*/
                $sOutput .= '["'.$chk.'","'.$aRow['ubicacion'].'","'.$aRow['dimesion_area'].'","'.number_format($aRow['precio'],2).'","'.$aRow['nroCaratulas'].'","'.$estado.'", ';
                

                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';
                
                //Visualizar Detalle
                $sOutput .= '<button type=\"button\" class=\"btn bg-color-blue txt-color-white btn-xs\" title=\"Listar Caratula\" onclick=\"fichaTecnica.getGridCaratula(\''.$encryptReg.'\')\">';
                $sOutput .= '    <i class=\"fa fa-search-plus fa-lg\"></i>';
                $sOutput .= '</button>';          
                if($agregar['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"btn bg-color-pink txt-color-white btn-xs\" title=\"'.$agregar['accion'].' Caratula\" onclick=\"fichaTecnica.getNuevoCaratula(this, \''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"fa fa-plus-circle fa-lg\"></i>';
                    $sOutput .= '</button>';
                }                        
                if($editar['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"btn btn-primary btn-xs\" title=\"'.$editar['accion'].' Ficha Técnica\" onclick=\"fichaTecnica.getEditarFichaTecnica(\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"fa fa-edit fa-lg\"></i>';
                    $sOutput .= '</button>';
                }   
                if($exportarpdf['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"btn txt-color-white bg-color-blueDark btn-xs\" title=\"'.$exportarpdf['accion'].'\" onclick=\"fichaTecnica.postPDF(this,\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"fa fa-file-pdf-o fa-lg\"></i>';
                    $sOutput .= '</button>';
                }
                if($exportarexcel['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"btn btn-success btn-xs\" title=\"'.$exportarexcel['accion'].'\" onclick=\"fichaTecnica.postExcel(this,\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"fa fa-file-excel-o fa-lg\"></i>';
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
    
    public function getGridCaratula(){
       $editar = Session::getPermiso('FITECED');
       $eliminar = Session::getPermiso('FITECDE');
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->fichaTecnicaModel->getGridCaratula();
        
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
                }elseif($aRow['estado'] == 'N'){
                    $estado = '<span class=\"label label-danger\">Alquilado</span>';
                }
                
                if($aRow['iluminado'] == 1){
                    $iluminado = '<span class=\"label label-success\">SI</span>';
                }elseif($aRow['iluminado'] == 0){
                    $iluminado = '<span class=\"label label-danger\">NO</span>';
                }
                
                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_caratula']);
                $idProd = Aes::en($aRow['id_producto']);
                                                
                /*datos de manera manual*/
                $sOutput .= '["'.$aRow['codigo'].'","'.$aRow['descripcion'].'","'.number_format($aRow['precio'],2).'","'.$iluminado.'","'.$estado.'", ';
                
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';
                
                //Visualizar Detalle                
                if($editar['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"btn btn-primary btn-xs\" title=\"'.$editar['accion'].'\" onclick=\"fichaTecnica.getEditarCaratula(\''.$encryptReg.'\',\''.$idProd.'\')\">';
                    $sOutput .= '    <i class=\"fa fa-edit fa-lg\"></i>';
                    $sOutput .= '</button>';
                }      
                 if($eliminar['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"btn btn-danger btn-xs\" title=\"'.$eliminar['accion'].'\" onclick=\"fichaTecnica.postDeleteCaratula(this,\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"fa fa-ban fa-lg\"></i>';
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
        
    public static function getDepartamentos(){ 
        $data = Obj::run()->fichaTecnicaModel->getDepartamentos();        
        return $data;
    }
    
    public function getProvincias(){
        $data = Obj::run()->fichaTecnicaModel->getProvincias();        
        echo json_encode($data);
    }
    
    public static function getProvinciasEst($dep=''){
        $data = Obj::run()->fichaTecnicaModel->getProvincias($dep);        
        return $data;
    }
    
    public function getUbigeo(){
        $data = Obj::run()->fichaTecnicaModel->getUbigeo();        
        echo json_encode($data);
    }
    
    public static function getUbigeoEst($pro=''){
        $data = Obj::run()->fichaTecnicaModel->getUbigeo($pro);        
        return $data;
    }
    
    public static function getTPanelFichaTecnica(){ 
        $data = Obj::run()->fichaTecnicaModel->getTPanelFichaTecnica();            
        return $data;
    }           
    public static function getFichaTecnica(){ 
        $data = Obj::run()->fichaTecnicaModel->getFichaTecnica();        
        return $data;
    }
    public static function getCaratula(){ 
        $data = Obj::run()->fichaTecnicaModel->getCaratula();        
        return $data;
    }
    public static function getUbicacion(){ 
        $data = Obj::run()->fichaTecnicaModel->getUbicacion();   
        $retorno = LABEL_A23;
        if(strlen($data['ubicacion']) > 0 or strlen($data['dimension_alto']) > 0 or strlen($data['dimension_ancho']) > 0 ){
            $retorno .= ' : '. $data['ubicacion'] . ' - '.$data['dimension_alto'].' x '.$data['dimension_ancho'].' m' ;
        }        
        echo $retorno;        
    }
    
    public function postNuevoFichaTecnica(){ 
        $data = Obj::run()->fichaTecnicaModel->mantenimientoFichaTecnica();
        
        echo json_encode($data);
    }
    
    public function postEditarFichaTecnica(){ 
        $data = Obj::run()->fichaTecnicaModel->mantenimientoFichaTecnica();
        
        echo json_encode($data);
    }
    
    public function postDeleteFichaTecnica(){ 
        $data = Obj::run()->fichaTecnicaModel->mantenimientoFichaTecnica();
        
        echo json_encode($data);
    }
    
    public function postDeleteFichaTecnicaAll(){ 
        $data = Obj::run()->fichaTecnicaModel->mantenimientoFichaTecnicaAll();
        
        echo json_encode($data);
    }  
    
    public function postNuevoCaratula(){ 
        $data = Obj::run()->fichaTecnicaModel->mantenimientoCaratula();
        
        echo json_encode($data);
    }
    
    public function postEditarCaratula(){ 
        $data = Obj::run()->fichaTecnicaModel->mantenimientoCaratula();
        
        echo json_encode($data);
    }  
    public function postDeleteCaratula(){ 
        $data = Obj::run()->fichaTecnicaModel->mantenimientoCaratula();
        
        echo json_encode($data);
    }    
    public function postPDF(){ 
        $data = Obj::run()->fichaTecnicaModel->getRptFichaTecnica();
        
        $mpdf = new mPDF('c');

        $mpdf->mirrorMargins = 1;
        $mpdf->defaultheaderfontsize = 9; /* in pts */
        $mpdf->defaultheaderfontstyle = B; /* blank, B, I, or BI */
        $mpdf->defaultheaderline = 1; /* 1 to include line below header/above footer */
        $mpdf->defaultfooterfontsize = 10; /* in pts */
        $mpdf->defaultfooterfontstyle = B; /* blank, B, I, or BI */
        $mpdf->defaultfooterline = 1; /* 1 to include line below header/above footer */
        
        $html ='
        <h3>Ubicación: '.$data[0]['ubicacion'].'</h3>        
        <table border="1" style="border-collapse:collapse">        
            <tr>
                <th style="width:20%">Código</th>
                <th style="width:40%">Descripción</th>
                <th style="width:10%">Precio</th>
                <th style="width:10%">Iluminado</th>           
                <th style="width:10%">Estado</th> 
            </tr>';
        foreach ($data as $value) {
            $html .= '<tr>
                <td style="text-align:center">'.$value['codigo'].'</td>
                <td>'.$value['descripcion'].'</td>
                <td style="text-align:right">'.number_format($value['precio'],2).'</td>               
                <td style="text-align:center">'.$value['iluminado'].'</td>                
                <td style="text-align:center">'.$value['estado'].'</td>                                    
            </tr>';
        }    
        $html .='</table>';
        
        $mpdf->WriteHTML($html);
        $mpdf->Output(ROOT.'public'.DS.'files'.DS.'fichatecnica.pdf','F');
        
        $data = array('result'=>1);
        echo json_encode($data);
    }
    
    public function postExcel(){
        $data = Obj::run()->generarCotizacionModel->getCotizacion();
        
        $html ='
        <h3>Cotización N° '.$data[0]['cotizacion_numero'].'</h3>
        <h4>Cliente: '.$data[0]['nombrecompleto'].'</h4>
        <table border="1" style="border-collapse:collapse">
            <tr>
                <th style="width:10%">Código</th>
                <th style="width:40%">Producto</th>
                <th style="width:10%">Precio</th>
                <th style="width:10%">Meses</th>
                <th style="width:10%">Producción</th>
                <th style="width:10%">Importe</th>
            </tr>';
        foreach ($data as $value) {
            $html .= '<tr>
                <td style="text-align:center">'.$value['codigo'].'</td>
                <td>'.$value['producto'].'</td>
                <td style="text-align:right">'.number_format($value['precio'],2).'</td>
                <td style="text-align:center">'.$value['cantidad_mes'].'</td>
                <td style="text-align:right">'.number_format($value['costo_produccion'],2).'</td>
                <td style="text-align:right">'.number_format($value['importe'],2).'</td>
            </tr>';
        }    
        $html .='</table>';
        
        
        $f=fopen(ROOT.'public'.DS.'files'.DS.'cotizacion.xls','wb');
        if(!$f){$data = array('result'=>2);}
        fwrite($f,  utf8_decode($html));
        fclose($f);
                        
        $data = array('result'=>1);
        echo json_encode($data);
    }
        
    
}

?>