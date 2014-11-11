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
    public function getFormAdjuntar() {    
        Obj::run()->View->idCaratula = Formulario::getParam('_idCaratula');
        Obj::run()->View->render('formAdjuntarImg');
    }    
   public function getGridFichaTecnica(){
       $editar = Session::getPermiso('FITECED');
       $eliminar = Session::getPermiso('FITECDE');
       $crearOpcion = Session::getPermiso('FITECCO'); 
       $exportarpdf   = Session::getPermiso('FITECEP');
       $exportarexcel = Session::getPermiso('FITECEX');
       $consultar = Session::getPermiso('FITECVP'); 
       $sEcho          =   $this->post('sEcho');
        
       $rResult = Obj::run()->fichaTecnicaModel->getGridFichaTecnica();
        
        if(!isset($rResult['error'])){  
            $iTotal         = isset($rResult[0]['total'])?$rResult[0]['total']:0;
            $idx =1;
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
                $sOutput .= '["'.$chk.'","'.$aRow['distrito'].'","'.$aRow['ubicacion'].'","'.$aRow['elemento'].'","'.number_format($aRow['precio'],2).'","'.$aRow['nroCaratulas'].'","'.$estado.'", ';
                
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';
                
                //Visualizar Detalle
                if($consultar['permiso'] == 1){
                    $sOutput .= '<button id=\"'.T102.'btnProducto'.$idx.'\" type=\"button\" class=\"'.$consultar['theme'].'\" title=\"'.$consultar['accion'].' Caratula\" onclick=\"fichaTecnica.getGridCaratula(\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"'.$consultar['icono'].'\"></i>';
                    $sOutput .= '</button>'; 
                }
                if($crearOpcion['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"'.$crearOpcion['theme'].'\" title=\"'.$crearOpcion['accion'].' Caratula\" onclick=\"fichaTecnica.getNuevoCaratula(this, \''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"'.$crearOpcion['icono'].'\"></i>';
                    $sOutput .= '</button>';
                }                        
                if($editar['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"'.$editar['theme'].'\" title=\"'.$editar['accion'].' Ficha Técnica\" onclick=\"fichaTecnica.getEditarFichaTecnica(this,\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"'.$editar['icono'].'\"></i>';
                    $sOutput .= '</button>';
                }   
                if($exportarpdf['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"'.$exportarpdf['theme'].'\" title=\"'.$exportarpdf['accion'].'\" onclick=\"fichaTecnica.postPDF(this,\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"'.$exportarpdf['icono'].'\"></i>';
                    $sOutput .= '</button>';
                }
                if($exportarexcel['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"'.$exportarexcel['theme'].'\" title=\"'.$exportarexcel['accion'].'\" onclick=\"fichaTecnica.postExcel(this,\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"'.$exportarexcel['icono'].'\"></i>';
                    $sOutput .= '</button>';
                }
                
                $sOutput .= ' </div>" ';

                $sOutput = substr_replace( $sOutput, "", -1 );
                $sOutput .= '],';
                $idx++;
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
       $adjuntar = Session::getPermiso('FITECAJ'); 
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
                }elseif($aRow['estado'] == 'A'){
                    $estado = '<span class=\"label label-warning\">Alquilado</span>';
                }
                
                if($aRow['iluminado'] == 1){
                    $iluminado = '<span class=\"label label-success\">SI</span>';
                }elseif($aRow['iluminado'] == 0){
                    $iluminado = '<span class=\"label label-danger\">NO</span>';
                }
                if($aRow['imagen'] != '' or $aRow['imagen'] != null){
                    $ruta = BASE_URL.'public/img/uploads/'.$aRow['imagen'];
                    $imagen = '<img border=\"0\" src=\"'.$ruta.'\" style=\"width:70px; height:40px;cursor:pointer;\" onclick=\"registrarVendedor.getFormViewFoto(\''.AesCtr::en($ruta).'\');\" />';
                }else{
                    $imagen = '<img src=\"'.BASE_URL.'public/img/sin_foto.jpg\" style=\"width:70px; height:40px;\" />';
                }
                
                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_caratula']);
                $idProd = Aes::en($aRow['id_producto']);
                                                
                /*datos de manera manual*/
                $sOutput .= '["'.$aRow['codigo'].'","'.$aRow['descripcion'].'","'.number_format($aRow['precio'],2).'","'.$imagen.'","'.$iluminado.'","'.$estado.'", ';
                
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';
                
                //Visualizar Detalle                
                if($editar['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"'.$editar['theme'].'\" title=\"'.$editar['accion'].'\" onclick=\"fichaTecnica.getEditarCaratula(this,\''.$encryptReg.'\',\''.$idProd.'\')\">';
                    $sOutput .= '    <i class=\"'.$editar['icono'].'\"></i>';
                    $sOutput .= '</button>';
                }      
                if($adjuntar['permiso']){
                    $sOutput .= '<button type=\"button\" class=\"'.$adjuntar['theme'].'\" title=\"'.$adjuntar['accion'].' (Imagen)\" onclick=\"fichaTecnica.getFormAdjuntar(this,\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"'.$adjuntar['icono'].'\"></i>';
                    $sOutput .= '</button>';
                }
                 if($eliminar['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"'.$eliminar['theme'].'\" title=\"'.$eliminar['accion'].'\" onclick=\"fichaTecnica.postDeleteCaratula(this,\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"'.$eliminar['icono'].'\"></i>';
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
        $c = 'fichatecnica_'.Obj::run()->fichaTecnicaModel->_idProducto.'.pdf';
        $ar = ROOT.'public'.DS.'files'.DS.$c;
        $mpdf = new mPDF('c');
                        
        $mpdf->SetHTMLHeader('<img src="'.ROOT.'public'.DS.'img'.DS.'logotipo.png" width="137" height="68" />','',TRUE);
        $mpdf->SetHTMLFooter('<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold;"><tr>
                                <td width="33%"><span style="font-weight: bold;">{DATE j-m-Y}</span></td>
                                <td width="33%" align="center" style="font-weight: bold;">{PAGENO}/{nbpg}</td>
                                <td width="33%" style="text-align: right; ">'.LB_EMPRESA.'</td>
                             </tr></table>');
        
        $html = $this->getHtmlReporte();        
        $mpdf->WriteHTML($html);
        $mpdf->Output($ar,'F');
                
        $data = array('result'=>1,'archivo'=>$c);
        echo json_encode($data);
    }
    
    public function postExcel(){
        $c = 'fichatecnica_'.Obj::run()->fichaTecnicaModel->_idProducto.'.xls';
        $ar = ROOT.'public'.DS.'files'.DS.$c;
        $html = $this->getHtmlReporte();
                
        $f=fopen($ar,'wb');
        if(!$f){$data = array('result'=>2);}
        fwrite($f,  utf8_decode($html));
        fclose($f);
                        
        $data = array('result'=>1,'archivo'=>$c);
        echo json_encode($data);
    }
    
    
    public function getHtmlReporte(){
        $data = Obj::run()->fichaTecnicaModel->getRptFichaTecnica();
        $html = '';        
        $html ='
            <style>
            table,h1,h2,h3,h4, p{font-family:Arial;} 
            table, table td, table th{ font-size:12px;}
            table{width:100%;}
            #td2 th, .totales{background:#901D78; color:#FFF; height:25px;}
            #td2 td{font-size:11px;height:25px;}
        </style>
        <table width="100%" border="0" cellpadding="5" cellspacing="3">
            <tr bgcolor="#901D78">
                    <th colspan="6"><div align="center"><h2 style="color:#FFF;">FICHA TECNICA </h2></div></th>
            </tr>
            <tr>
              <td width="16%"><b>DEPARTAMENTO:</b></td><td width="21%">'.$data[0]['departamento'].'</td>
              <td width="10%"><b>PROVINCIA:</b></td><td width="20%">'.$data[0]['provincia'].'</td>
              <td width="8%"><b>DISTRITO:</b></td><td width="26%">'.$data[0]['distrito'].'</td>
            </tr>
            <tr>
              <td><b>UBICACION:</b></td><td colspan="5">'.strtoupper($data[0]['ubicacion']).'</td>
            </tr>
            <tr>
              <td><b>TIPO PANEL:</b></td><td>'.strtoupper($data[0]['tipoPanel']).'</td>
              <td colspan="4"><table width="100%" border="0">
                <tr>
                  <td width="20%"><b>ANCHO:</b></td><td width="21%" >'.$data[0]['dimension_ancho'].'</td>
                  <td width="20%"><b>ALTO:</b></td><td width="21%" >'.$data[0]['dimension_alto'].'</td>
                  <td width="20%"><b>AREA:</b></td><td width="21%" >'.$data[0]['dimesion_area'].' m <sup>2</sup></td>
                </tr>
              </table></td>
            </tr>           
            <tr>
                <td colspan="6"><table width="100%" border="0">
                  <tr>
                    <td width="11%"><b>LATITUD:</b></td><td width="41%">'.$data[0]['google_latitud'].'</td>
                    <td width="7%"><b>LONGITUD:</b></td><td width="41%">'.$data[0]['google_longitud'].'</td>
                  </tr>
                </table></td>
              </tr>
              <tr>
              <td><b>OBSERVACION:</b></td><td colspan="5">'.strtoupper($data[0]['observacion']).'</td>
            </tr>
          </table>              
          <h4>LISTADO DE CARATULAS</h4>
        <table id="td2" style="border-collapse:collapse;" border="1">        
            <tr >
                <th  style="width:20%">CODIGO</th>
                <th style="width:40%">DESCRIPCION</th>
                <th style="width:10%">PRECIO</th>
                <th style="width:10%">ILUMINADO</th>           
                <th style="width:10%">ESTADO</th> 
            </tr>';
        foreach ($data as $value) {
            $iluminado = ($value['iluminado']=='1')?'SI':'NO';
            $estado = ($value['estado']=='D')?'DISPONIBLE':'ALQUILADO';
            $comision = 100*$value['comision_vendedor'];
            $vendedor = ($value['vendedor'] == ''?'No se asigno vendedor':$value['vendedor']);
            $html .= '            
            <tr>
                <td style="text-align:center"><h3>'.$value['codigo'].'</h3></td>
                <td>'.$value['descripcion'].'</td>
                <td style="text-align:right">'.number_format($value['precio'],2).'</td>               
                <td style="text-align:center">'.$iluminado.'</td>                
                <td style="text-align:center">'.$estado.'</td>                                    
            </tr>';
            if($data['multiplecotizacion'] == 'N'):
                $html .= '<tr>
                    <td style="text-align:right"><b>VENDEDOR:</b></td>
                    <td>'.strtoupper($vendedor).'</td>
                    <td style="text-align:right"><b>% COMISION:</b></td>               
                    <td style="text-align:right">'.number_format($comision).'% </td>                
                    <td style="text-align:center">&nbsp;</td>                                    
                </tr>';
            else:
                $dataV = Obj::run()->fichaTecnicaModel->getRptVendedorCuenta($value['id_caratula']);
                  $html .= '<tr>
                    <td>&nbsp;</td>
                    <td><b>VENDEDOR</b></td>
                    <td><b>% COMISION:</b></td>               
                    <td>&nbsp;</td>                
                    <td style="text-align:center">&nbsp;</td>                                    
                </tr>';
                foreach ($dataV as $v):
                    $comision = 100*$v['porcentaje_comision'];
                    $html .= '<tr>
                    <td style="text-align:right"></td>
                    <td>'.strtoupper($v['vendedor']).'</td>
                    <td style="text-align:right">'.number_format($comision).'% </td>                                    
                    <td style="text-align:right">&nbsp;</td>                                  
                    <td style="text-align:center">&nbsp;</td>                                    
                </tr>';
                    
                endforeach;
                                    
            endif;                            
        }
              
        $html .='</table>';                        
        $html .='<h4>PERMISO MUNICIPAL</h4>';
        
        if($data[0]['fecha_inicio'] == ''){
           $html .='<h4><i>No tiene registrado Permiso Municipal.</i></h4>'; 
        }else{        
            $html .= '
                <table width="100%" border="0">
                    <tr>
                      <td width="18%"><strong>FECHA INICIO:</strong></td>
                      <td width="22%">'.Functions::cambiaf_a_normal($data[0]['fecha_inicio']).'</td>
                      <td width="18%"><strong>FECHA FINAL:</strong></td>
                      <td width="49%">'.Functions::cambiaf_a_normal($data[0]['fecha_final']).'</td>
                    </tr>
                    <tr>
                      <td><strong>MONTO PAGADO:</strong></td>
                      <td colspan="2">S/. '.number_format($data[0]['pm_precio'],2).'</td>
                    </tr>
                    <tr>
                      <td><strong>OBSERVACIONES:</strong></td>
                      <td colspan="3">'.strtoupper($data[0]['pm_obs']).'</td>
                    </tr>
                  </table>';
        }
       
        return $html;
        
    }
    
    public function deleteArchivo(){
        $c = Formulario::getParam('_archivo');
        
        $filename = ROOT.'public'.DS.'files'.DS.$c;
        unlink($filename);
        echo $filename;
    }  
   
    public function adjuntarImagen() {
//        header("Access-Control-Allow-Origin: *");
//        header('Content-type: application/json');
        $p = Obj::run()->fichaTecnicaModel->_idCaratula;
        
        if (!empty($_FILES)) {
            $targetPath = ROOT . 'public' . DS .'img' .DS . 'uploads' . DS;
            $tempFile = $_FILES['file']['tmp_name'];                     
            
            $file = $p.'_'.time().rand(0,10).'_'.$_FILES['file']['name'];               
            $targetFile = $targetPath.$file;            
            
            if (move_uploaded_file($tempFile, $targetFile)) {
               $array = array("img" => $targetPath, "thumb" => $targetPath,'archivo'=>$file);
               
               Obj::run()->fichaTecnicaModel->adjuntarImagen($file);
            }
            echo json_encode($array);
        }
    }
    
    public function deleteAdjuntar() {
        $data = Obj::run()->fichaTecnicaModel->deleteAdjuntar();
        
        $file = Formulario::getParam('_img');
        
        $file = str_replace("/","\\", $file);
        
        $targetPath =  $file;
        
        unlink($targetPath);
        
        echo json_encode($data);
    }          
    
}

?>