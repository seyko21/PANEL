<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 27-08-2014 02:08:12 
* Descripcion : catalogoPrecioController.php
* ---------------------------------------
*/    

class catalogoPreciosController extends Controller{
           
    public function __construct() {
        $this->loadModel("catalogoPrecios");
        $this->loadController(array('modulo'=>'panel','controller'=>'fichaTecnica'));        
    }    
    public function index(){ 
        Obj::run()->View->render("indexCatalogoPrecios");
    }    
    public function getEditarCaratula(){ 
        Obj::run()->View->render('editarCatalogoPrecio');
    } 
        
    public function getGridProducto(){
       $editar = Session::getPermiso('CATPRED');       
       $exportarpdf   = Session::getPermiso('CATPREP');
       $exportarexcel = Session::getPermiso('CATPREX'); 
       $adjuntar = Session::getPermiso('CATPRAJ'); 
       $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->catalogoPreciosModel->getGridProducto();
        
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
                    $imagen = '<img border=\"0\" src=\"'.$ruta.'\" style=\"width:70px; height:40px;cursor:pointer\" onclick=\"registrarVendedor.getFormViewFoto(\''.AesCtr::en($ruta).'\');\"/>';
                }else{
                    $imagen = '<img src=\"'.BASE_URL.'public/img/sin_foto.jpg\" style=\"width:70px; height:40px;\" />';
                }
                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_caratula']);
                $idProd = Aes::en($aRow['id_producto']);
                                                
                /*datos de manera manual*/
                $sOutput .= '["'.$aRow['codigo'].'","'.$aRow['distrito'].'","'.$aRow['ubicacion'].'","'.$aRow['elemento'].'","'.$aRow['dimesion_area'].'","'.number_format($aRow['precio'],2).'","'.$iluminado.'","'.$estado.'","'.$imagen.'", ';
                
                $codigo = $aRow['codigo'];
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';
                
                //Visualizar Detalle                
                if($editar['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"'.$editar['theme'].'\" title=\"'.$editar['accion'].'\" onclick=\"catalogoPrecios.getEditarCaratula(this,\''.$encryptReg.'\',\''.$idProd.'\')\">';
                    $sOutput .= '    <i class=\"'.$editar['icono'].'\"></i>';
                    $sOutput .= '</button>';
                }
                 if($adjuntar['permiso']){
                    $sOutput .= '<button type=\"button\" class=\"'.$adjuntar['theme'].'\" title=\"'.$adjuntar['accion'].' (Imagen)\" onclick=\"catalogoPrecios.getFormAdjuntar(this,\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"'.$adjuntar['icono'].'\"></i>';
                    $sOutput .= '</button>';
                }
                if($exportarpdf['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"'.$exportarpdf['theme'].'\" title=\"'.$exportarpdf['accion'].'\" onclick=\"catalogoPrecios.postPDF(this,\''.$encryptReg.'\',\''.$codigo.'\')\">';
                    $sOutput .= '    <i class=\"'.$exportarpdf['icono'].'\"></i>';
                    $sOutput .= '</button>';
                }
                if($exportarexcel['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"'.$exportarexcel['theme'].'\" title=\"'.$exportarexcel['accion'].'\" onclick=\"catalogoPrecios.postExcel(this,\''.$encryptReg.'\',\''.$codigo.'\')\">';
                    $sOutput .= '    <i class=\"'.$exportarexcel['icono'].'\"></i>';
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
    
    public function postEditarCaratula(){                                 
        $data = Obj::run()->fichaTecnicaController->postEditarCaratula();        
        echo $data;        
    }   
    public static function getListadoTipoPanel(){ 
       $data = Obj::run()->catalogoPreciosModel->getTipoPanel();            
       return $data;
    }
    public function postPDF(){ 
        $c = 'fichatecnica_'.Obj::run()->catalogoPreciosModel->_codigo.'.pdf';
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
        $c = 'fichatecnica_'.Obj::run()->catalogoPreciosModel->_codigo.'.xls';
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
        $data = Obj::run()->catalogoPreciosModel->getRptFichaTecnicaCatalogo();
        $dataOS = Obj::run()->catalogoPreciosModel->getRptOrdenServicio();
        $dataV  = Obj::run()->catalogoPreciosModel->getRptVendedorCuenta();
        $html = '';
        $iluminado = ($data[0]['iluminado']=='1')?'SI':'NO';
        $estado = ($data[0]['estado']=='D')?'DISPONIBLE':'ALQUILADO';        
        
        $html .='
        <style>
            table,h2,h3,h4{font-family:Arial;} 
            table, table td, table th{ font-size:12px;}
            table{width:100%;}
            table td {padding:4px 3px 4px 0px}
            h3{font-size:25px;}
            #td2 th, .totales{background:#901D78; color:#FFF; height:25px;}
            #td2 td{font-size:11px;height:25px;}
        </style>        
            <table width="100%" border="0" cellpadding="5" cellspacing="3">
                <tr bgcolor="#901D78">
                    <th colspan="6"><div align="center"><h2 style="color:#FFF;">FICHA TECNICA </h2></div></th>
                 </tr>
                <tr>
                    <td colspan="4">
                        <table width="100%" border="0">
                            <tr>
                              <td width="5%"><strong>'.LABEL_A37.':</strong></td>
                              <td width="25%"><h3 style="color:#901D78">'.$data[0]['codigo'].'</h3></td>                            
                            </tr>                           
                        </table>
                    </td>
                </tr>
                <tr>
                  <td width="16%"><b>'.LABEL_A91.':</b></td><td width="21%">'.$data[0]['departamento'].'</td>
                  <td width="10%"><b>'.LABEL_A92.':</b></td><td width="20%">'.$data[0]['provincia'].'</td>
                  <td width="8%"><b>'.LABEL_A93.':</b></td><td width="26%">'.$data[0]['distrito'].'</td>
                </tr>
                <tr>
                  <td><b>'.LABEL_A26.':</b></td><td colspan="5">'.strtoupper($data[0]['ubicacion']).'</td>
                </tr>
                <tr>
                  <td><b>'.LABEL_A27.':</b></td><td>'.strtoupper($data[0]['tipoPanel']).'</td>
                  <td colspan="4"><table width="100%" border="0">
                    <tr>
                       <td width="20%"><b>'.LABEL_A28.':</b></td><td width="21%" >'.$data[0]['dimension_ancho'].'</td>
                        <td width="20%"><b>'.LABEL_A29.':</b></td><td width="21%" >'.$data[0]['dimension_alto'].'</td>
                        <td width="20%"><b>'.LABEL_A47.':</b></td><td width="21%" >'.$data[0]['dimesion_area'].' m <sup>2</sup></td>
                    </tr>
                  </table></td>
                </tr>           
                <tr>
                    <td colspan="6"><table width="100%" border="0">
                      <tr>
                        <td width="11%"><b>'.LABEL_A32.':</b></td><td width="41%">'.$data[0]['google_latitud'].'</td>
                        <td width="7%"><b>'.LABEL_A33.':</b></td><td width="41%">'.$data[0]['google_longitud'].'</td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                  <td><b>'.LABEL_A30.':</b></td><td colspan="5">'.strtoupper($data[0]['observacion']).'</td>
                </tr>
                 <tr>
                    <td><strong>'.LABEL_A39.':</strong></td>
                    <td>'.number_format($data[0]['precio'],2).'</td>
                    <td><strong>'.LABEL_A40.':</strong></td>
                    <td>'.$iluminado.'</td>
                  </tr>                 
                 <tr>
                    <td><strong>'.LABEL_A48.':</strong></td>
                    <td colspan="3">'.$estado.'</td>                 
                </tr>
          </table>';
        $html .='<h4 style="color:#901D78">RESPONSABLE DE CUENTA</h4>';
                    
            $multiple = $data[0]['multiplecotizacion'];
            if ($multiple == 'S'):
                $html .= '<table id="td2" style="border-collapse:collapse;" border="1">        
                    <tr >
                        <th  style="width:60%">'.LABEL_A112.'</th>
                        <th style="width:20%">'.LABEL_A111.'</th>                
                    </tr>';
                    $i=0;
                    foreach ($dataV as $value) {            
                        $comision = 100*$value['porcentaje_comision'];
                        $vendedor = $value['vendedor'];
                        $dni = ($value['dni']== ''?'':$value['dni'].' - ');
                        $html .= '            
                        <tr>                
                            <td>'.$dni.strtoupper($vendedor).'</td>
                            <td style="text-align:right">'.$comision.' %</td>                               
                        </tr>';   
                        $i++;
                    }     
                    if($i == 0){
                        $html .= '            
                        <tr>                
                            <td><h4><i>No se asigno vendedor</i></h4></td>
                            <td style="text-align:right"> - </td>                               
                        </tr>';   
                    }
                    $html .='</table>';          
            else:
                $comision = number_format(100*$data[0]['comision_vendedor']);
                $vendedor = ($data[0]['vendedor'] == ''?'No se asigno vendedor':$data[0]['vendedor']);
                $html .='<table width="70%" border="0" cellpadding="5" cellspacing="3">
                            <tr>
                                <td width="10%"><strong>'.LABEL_A112.':</strong></td>
                                <td width="50%">'.strtoupper($vendedor).'</td>
                          </tr>
                          <tr>
                                <td><strong>'.LABEL_A111.':</strong></td>
                                <td >'.$comision.' %</td>
                          </tr>
                        </table>';                
            endif;
                        
        
        $html .='<h4 style="color:#901D78">PERMISO MUNICIPAL</h4>';
        
        if($data[0]['fecha_inicio'] == ''){
           $html .='<h4><i>No tiene registrado Permiso Municipal.</i></h4>'; 
        }else{        
            $html .= '
                <table width="100%" border="0" cellpadding="5" cellspacing="3">
                    <tr>
                      <td width="18%"><strong>'.LABEL_A54.':</strong></td>
                      <td width="22%">'.Functions::cambiaf_a_normal($data[0]['fecha_inicio']).'</td>
                      <td width="18%"><strong>'.LABEL_A55.':</strong></td>
                      <td width="49%">'.Functions::cambiaf_a_normal($data[0]['fecha_final']).'</td>
                    </tr>
                    <tr>
                      <td><strong>'.LABEL_A56.':</strong></td>
                      <td colspan="2">S/. '.number_format($data[0]['pm_precio'],2).'</td>
                    </tr>
                    <tr>
                      <td><strong>'.LABEL_A57.':</strong></td>
                      <td colspan="3">'.strtoupper($data[0]['pm_obs']).'</td>
                    </tr>
                  </table>';
        }
        $html .='<h4 style="color:#901D78">ULTIMO CONTRATO</h4>';
        if($estado == 'ALQUILADO'){
            $ruc ='';  
            if($dataOS['ruc'] != '') $ruc = $dataOS['ruc'].' - ';
            switch ($dataOS['estado']){
                case 'E': $estado = SEGPA_6; break;
                case 'P': $estado = SEGPA_7; break;
                case 'T': $estado = SEGPA_8; break;
                case 'F': $estado = SEGPA_29; break;
                case 'A': $estado = SEGPA_9; break;
            }
            $html .= '
                <table width="100%" border="0" >
                   <tr>
                        <td width="16%"><strong>'.GNOSE_25.':</strong></td>
                        <td width="84%">'.$ruc.strtoupper($dataOS['cliente']).'</td>                       
                    </tr>
                    <tr>
                        <td width="16%"><strong>'.GNOSE_26.':</strong></td>
                        <td width="84%">'.strtoupper($dataOS['representante']).'</td>                       
                    </tr>
                    <tr>
                        <td colspan="2" >
                            <table width="100%" border="0" >
                                <tr>
                                    <td width="5%" style="text-align:right"><strong>'.GNOSE_27.':</strong></td>
                                    <td width="10%">'.($dataOS['fecha_contrato']).'</td>
                                    <td width="5%" style="text-align:right"><strong>'.GNOSE_28.':</strong></td>
                                    <td width="10%">'.($dataOS['fecha_inicio']).'</td>
                                    <td width="5%" style="text-align:right"><strong>'.GNOSE_29.':</strong></td>
                                    <td width="10%">'.($dataOS['fecha_termino']).'</td>
                                </tr>
                                <tr>
                                    <td width="5%" align="right"><strong>'.SEGCO_7.':</strong></td>
                                    <td width="10%" align="left">'.($dataOS['orden_numero']).'</td>
                                    <td width="5%" align="right"><strong>'.GNOSE_30.':</strong></td>
                                    <td width="10%" align="left">'.Functions::convertirDiaMes($dataOS['cantidad_mes']).'</td>
                                    <td width="5%" align="right" ><strong>'.LABEL_A48.':</strong></td>
                                    <td width="10%" align="left">'.$estado.'</td>                                    
                                </tr>
                            </table>
                        </td>                       
                    </tr>                 

                  </table>';
             
        }else{
            $html .='<h4><i>No tiene registrado Contratos.</i></h4>'; 
        }
        
       
        return $html;
        
    }
    public function getFormAdjuntar() {    
        Obj::run()->View->idCaratula = Formulario::getParam('_idCaratula');
        Obj::run()->View->render('formAdjuntarImgCP');
    }       
    public function adjuntarImagen() {
       echo Obj::run()->fichaTecnicaController->adjuntarImagen();
    }
    public function deleteAdjuntar() {
        echo Obj::run()->fichaTecnicaController->deleteAdjuntar();     
    }           
    
}

?>