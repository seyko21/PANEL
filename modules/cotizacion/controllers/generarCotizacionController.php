<?php
/*
* --------------------------------------
* fecha: 14-08-2014 05:08:10 
* Descripcion : generarCotizacionController.php
* --------------------------------------
*/    

class generarCotizacionController extends Controller{

    public function __construct() {
        $this->loadModel('generarCotizacion');
        $this->loadController(array('modulo' => 'usuarios', 'controller' => 'configurarUsuarios'));    
    }
    
    public function index(){ 
        Obj::run()->View->render('indexGenerarCotizacion');
    }
    
    public function getGridCotizacion(){
        $enviaremail   = Session::getPermiso('GNCOTEE');
        $exportarpdf   = Session::getPermiso('GNCOTEP');
        $exportarexcel = Session::getPermiso('GNCOTEX');
        $clonar         = Session::getPermiso('GNCOTCL');
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->generarCotizacionModel->getGridCotizacion();
        
        if(!isset($rResult['error'])){  
            $iTotal         = isset($rResult[0]['totalg'])?$rResult[0]['totalg']:0;
            
            $sOutput = '{';
            $sOutput .= '"sEcho": '.intval($sEcho).', ';
            $sOutput .= '"iTotalRecords": '.$iTotal.', ';
            $sOutput .= '"iTotalDisplayRecords": '.$iTotal.', ';
            $sOutput .= '"aaData": [ ';
            foreach ( $rResult as $key=>$aRow ){
                
                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_cotizacion']);
                $idPersona = Aes::en($aRow['id_persona']);
                
                $chk = '';
                $estado = '';
                if($aRow['estado'] == 'E'){
                    $estado = '<span class=\"label label-success\">'.SEGCO_5.'</span>';                    
                    $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" name=\"'.T8.'chk_delete[]\" value=\"'.$encryptReg.'\"  >'; 
                }elseif($aRow['estado'] == 'A'){                   
                    $estado = '<span class=\"label label-danger\">'.LABEL_AN.'</span>';
                    $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" name=\"'.T8.'chk_delete[]\" disabled  >';
                }     
                     
                 if($aRow['incluyeigv'] == 1){
                    $incl = '<span class=\"label label-success\">'.LABEL_S.'</span>';                    
                }elseif($aRow['incluyeigv'] == 0){                   
                    $incl = '<span class=\"label label-info\">'.LABEL_N.'</span>';                    
                }     
                
                $total = 'S/. '.number_format($aRow['total'],2);
                
                /*datos de manera manual*/
                $sOutput .= '["'.$chk.'","'.$aRow['cotizacion_numero'].'","<a href=\"javascript:;\" onclick=\"persona.getDatosPersonales(\''.$idPersona.'\');\">'.$aRow['nombrecompleto'].'</a>","'.$aRow['fechacoti'].'","'.Functions::cambiaf_a_normal($aRow['vencimiento']).'","'.$incl.'","'.  $total.'","'.$estado.'", ';

                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar fa-mail-forward
                 */
                $sOutput .= '"<div class=\"btn-group\">';
                
                if($exportarpdf['permiso']){
                    $sOutput .= '<button type=\"button\" class=\"'.$exportarpdf['theme'].'\" title=\"'.$exportarpdf['accion'].'\" onclick=\"generarCotizacion.postPDF(this,\''.$encryptReg.'\', \''.$aRow['cotizacion_numero'].'\')\">';
                    $sOutput .= '    <i class=\"'.$exportarpdf['icono'].'\"></i>';
                    $sOutput .= '</button>';
                }
                if($exportarexcel['permiso']){
                    $sOutput .= '<button type=\"button\" class=\"'.$exportarexcel['theme'].'\" title=\"'.$exportarexcel['accion'].'\" onclick=\"generarCotizacion.postExcel(this,\''.$encryptReg.'\', \''.$aRow['cotizacion_numero'].'\')\">';
                    $sOutput .= '    <i class=\"'.$exportarexcel['icono'].'\"></i>';
                    $sOutput .= '</button>';
                }
                if($clonar['permiso'] && $aRow['estado'] != 'A'){
                    $sOutput .= '<button type=\"button\" class=\"'.$clonar['theme'].'\" title=\"'.$clonar['accion'].'\" onclick=\"generarCotizacion.getClonar(\''.$aRow['cotizacion_numero'].'\',\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"'.$clonar['icono'].'\"></i>';
                    $sOutput .= '</button>';
                }
                if($enviaremail['permiso'] && $aRow['estado'] != 'A'){
                    $sOutput .= '<button type=\"button\" class=\"'.$enviaremail['theme'].'\" title=\"'.$enviaremail['accion'].'\" onclick=\"generarCotizacion.postEmail(this,\''.$encryptReg.'\', \''.$aRow['cotizacion_numero'].'\')\">';
                    $sOutput .= '    <i class=\"'.$enviaremail['icono'].'\"></i>';
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

    public function getNuevoGenerarCotizacion(){
        Obj::run()->View->render('nuevoGenerarCotizacion'); 
    }
    
    public function getFormNewCotizacion(){
        Obj::run()->View->render('formNewCotizacion'); 
    }
    
    public function getFormClonarCotizacion(){
        Obj::run()->View->render('formClonarCotizacion'); 
    }
    
    public function getFormBuscarMisProductos(){ 
        Obj::run()->View->render('formBuscarMisProductos');
    }
    
    public function getTableMisProductos(){ 
        Obj::run()->View->tab = $this->post('_tab');
        Obj::run()->View->render('tableMisProductos');
    }
    
    public static function getMisProductos(){ 
        $data = Obj::run()->generarCotizacionModel->getMisProductos();
        
        return $data;
    }
    
    public function getFormBuscarCliente(){ 
        Obj::run()->View->render('formBuscarCliente');
    }
    
    public function getClientes(){ 
        $tab = $this->post('_tab');
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->generarCotizacionModel->getClientes();
        
        if(!isset($rResult['error'])){  
            $iTotal         = isset($rResult[0]['total'])?$rResult[0]['total']:0;
            
            $sOutput = '{';
            $sOutput .= '"sEcho": '.intval($sEcho).', ';
            $sOutput .= '"iTotalRecords": '.$iTotal.', ';
            $sOutput .= '"iTotalDisplayRecords": '.$iTotal.', ';
            $sOutput .= '"aaData": [ ';
            foreach ( $rResult as $key=>$aRow ){
                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_persona']);
                
                $nom = '<a href=\"javascript:;\" onclick=\"simpleScript.setInput({'.$tab.'txt_idpersona:\''.$encryptReg.'\', '.$tab.'txt_cliente:\''.$aRow['razon_social'].' - '.$aRow['nombrecompleto'].'\'},\'#'.T8.'formBuscarCliente\');\" >'.$aRow['nombrecompleto'].'</a>';
                
                /*datos de manera manual*/
                $sOutput .= '["'.(++$key).'","'.$nom.'", "'.$aRow['razon_social'].'" ';

                $sOutput .= '],';
            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;
    }
    
    public static function getProduccion(){
        $data = Obj::run()->generarCotizacionModel->getProduccion();
        
        return $data;
    }
    
    public static function getProductosCotizados(){
        $data = Obj::run()->generarCotizacionModel->getProductosCotizados();
        
        return $data;
    }
    
    public function postGenerarCotizacion(){ 
        $data = Obj::run()->generarCotizacionModel->generarCotizacion();
        
        echo json_encode($data);
    }
    
    public function postEmail(){ 
        $this->postPDF('N');
        Obj::run()->generarCotizacionModel->postTiempoCotizacion();
        $data = Obj::run()->generarCotizacionModel->getCotizacion();
        $data0 = Obj::run()->configurarUsuariosController->getParametros('EMAIL');        
        $data1 = Obj::run()->configurarUsuariosController->getParametros('EMCO');        
        $emailEmpresa = $data0['valor'];
        $empresa = $data1['valor'];
        
        $emailCliente = $data[0]['email'];
        $cliente = $data[0]['nombrecompleto'];
        $emailUser = $data[0]['mail_user'];
        $vendedor = $data[0]['vendedor'];
        $numCotizacion = $data[0]['cotizacion_numero'];
        $numVendedor = $data[0]['telefono_vendedor'];
        $archivo = 'cotizacion_'.$numCotizacion.'.pdf';
        //Html de Cotizacion:
         $body = '
            <h3>Estimado: ' . $cliente . '</h3>
            <p>Muchas gracias por confiar en <b>'.LB_EMPRESA.'</b>, le enviamos nuestra cotizacion acerca del Servicio.
            <br/>Esperamos su pronta respuesta.</p>
            <hr>
            <p>Atte:<br/>
            <b>'.$vendedor.'</b><br/>
            <i>Ejecutivo de Ventas</i><br/>
            Cel#: '.$numVendedor.'<br/>
            <a href="'.URL_WEBSITE.'">'.URL_WEBSITE.'</a></p>';
         
        //$body = $this->getHtmlCotizacion();        
        
        $mail             = new PHPMailer(); // defaults to using php "mail()"
 
        $mail->SetFrom($emailUser, $vendedor);
        
        $mail->AddAddress($emailCliente, $cliente);
        $mail->AddBCC($emailEmpresa, $empresa);
        $mail->AddBCC($emailUser, $vendedor);
        $mail->Subject    = "Cotizacion SEVEND Marketing ";
        
        $mail->MsgHTML($body);
        $mail->AddAttachment('public/files/'.$archivo);      // attachment
        $mail->CharSet = 'UTF-8';
        
        $data = array('result'=>2);
        if($mail->Send()) {
            $data = array('result'=>1,'archivo'=>$archivo);
        } else {
            $data = array('result'=>2);
        }
               
        echo json_encode($data);
    }
    
    public function postPDF($n=''){
        $data = Obj::run()->generarCotizacionModel->getCotizacion();
         
        $c = 'cotizacion_'.Obj::run()->generarCotizacionModel->_numCoti.'.pdf';
        
        $ar = ROOT.'public'.DS.'files'.DS.$c;
               
        $mpdf = new mPDF('c');     
        
        //pie de página:
        $pie = Obj::run()->generarCotizacionModel->getPiePagina();
        
        if($data[0]['estado'] == 'A'){
           $mpdf->SetWatermarkText('A N U L A D O');
           $mpdf->showWatermarkText = true;         
        }              
        $mpdf->SetHTMLHeader('<img src="'.ROOT.'public'.DS.'img'.DS.'logotipo.png" width="137" height="68" />','',TRUE);

        $mpdf->SetHTMLFooter('<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold;"><tr>
                                <td width="33%"><span style="font-weight: bold;">{DATE j/m/Y}</span></td>
                                <td width="33%" align="center" style="font-weight: bold;">{PAGENO}/{nbpg}</td>
                                <td width="33%" style="text-align: right; ">'.LB_EMPRESA.'</td>
                             </tr></table><p style="text-align:center;"><span style="font-weight: bold; font-size:13px;">'.$pie['valor'].'</span></p>');
                
        $html = $this->getHtmlCotizacion();         

        $mpdf->WriteHTML($html);
        $mpdf->Output($ar,'F');
        
        if($n != 'N'){
            $data = array('result'=>1,'archivo'=>$c);
            echo json_encode($data);
        }
    }
    
    public function postExcel(){
        $c = 'cotizacion_'.Obj::run()->generarCotizacionModel->_numCoti.'.xls';
        
        $ar = ROOT.'public'.DS.'files'.DS.$c;
        
        $html = $this->getHtmlCotizacion();
        
        $f=fopen($ar,'wb');
        if(!$f){$data = array('result'=>2);}
        fwrite($f,  utf8_decode($html));
        fclose($f);
                        
        $data = array('result'=>1,'archivo'=>$c);
        echo json_encode($data);
    }
    
    public function postAnularCotizacionAll(){
        $data = Obj::run()->generarCotizacionModel->anularCotizacion();
        
        echo json_encode($data);
    }  
    
    public static function findCotizacion(){
        $data = Obj::run()->generarCotizacionModel->findCotizacion();
        
        return $data;
    }
    
    public function getHtmlCotizacion(){
        $data = Obj::run()->generarCotizacionModel->getCotizacion();
        $html ='
        <style>           
           table,h3,h4{font-family:Arial;} 
           table, table td, table th, p {font-size:12px;}
           table{width:100%;}
           #td2 th, .totales{background:#901D78; color:#FFF; height:25px;}
           #td2 td{font-size:11px;height:25px;}
        </style>';
        
        switch($data[0]['estado']){
            case 'E': $estado = 'Emitido'; break;
            case 'P': $estado = 'Procesado - Orden Servicio'; break;
            case 'R': $estado = 'Rechazado'; break;
            case 'A': $estado = 'Anulado'; break;
        }
        
        
        $html .='<table width="100%" border="0" cellpadding="5" cellspacing="3">
          <tr bgcolor="#901D78">
            <th colspan="7"><div align="center"><h2 style="color:#FFF;">COTIZACION DE SERVICIO</h2></div></th>
          </tr>
          <tr>
            <td width="13%"><strong>'.LABEL_GNC29.':</strong></td>
            <td width="26%"><h3>'.$data[0]['cotizacion_numero'].'</h3></td>
            <td width="15%"><strong>'.LABEL_GNC30.':</strong></td>
            <td width="15%">'.Functions::cambiaf_a_normal($data[0]['fecha_cotizacion']).'</td>
            <td width="20%"><strong>'.LABEL_GNC31.':</strong></td>
            <td width="15%">'.Functions::cambiaf_a_normal($data[0]['vencimiento']).'</td>
          </tr>
          <tr>
            <td><strong>'.LABEL_GNC3.':</strong></td>
            <td colspan="5">'.($data[0]['ruccliente']==''?'':$data[0]['ruccliente'].' - ').$data[0]['razonsocial'].'</td>           
          </tr>
         <!-- <tr>
            <td><strong>'.LABEL_GNC33.':</strong></td>
            <td colspan="5">'.($data[0]['numerodocumento']==''?'':$data[0]['numerodocumento'].' - ').$data[0]['nombrecompleto'].'</td>
          </tr> -->
          <tr>
            <td><strong>'.LABEL_GNC26.':</strong></td>
            <td colspan="3">'.$data[0]['nombre_campania'].'</td> 
            <td align="right" ><strong>'.LABEL_GNC34.' :</strong></td>
            <td colspan="2">'.  Functions::convertirDiaMes($data[0]['cantidad_mes']).' ('. number_format($data[0]['cantidad_mes'],2).')</td>   
          </tr>
         <tr>
            <td ><strong>'.LABEL_GNC32.':</strong></td>
            <td colspan="2">'.$estado.'</td>
         </tr>
        </table> 
        <br />
        <table id="td2" border="1" style="border-collapse:collapse">
            <tr>
                <th style="width:9%" >'.LABEL_A37.'</th>
                <th style="width:10%">'.LABEL_A27.'</th>
                <th style="width:45%">'.LABEL_A26.'</th>
                <th style="width:8%">'.LABEL_A47.'</th>
                <th style="width:12%">'.GNOSE_30.'</th>
                <th style="width:12%">'.LABEL_A45.'</th>
                <th style="width:12%">'.ORINS_18.'</th>
            </tr>';
        foreach ($data as $value) {
            
            if($value['incluyeigv'] == '1'){
                $icl = '';
                $precio = number_format($value['precio_incigv'] * $value['cantidad_mes'] ,3);
                $produccion = number_format($value['produccion_incigv'],3);
                $importe = number_format($value['importe_incigv'],3);
            }else{
                $icl = 'no';
                $precio = number_format($value['precio'] * $value['cantidad_mes'],2);
                $produccion = number_format($value['costo_produccion'],2);
                $importe = number_format($value['importe'],2);
            }  

            $html .= '<tr>
                <td style="text-align:center">'.$value['codigo'].'</td>
                <td>'.$value['elemento'].'</td>
                <td>'.$value['producto'].' - '.number_format($value['dimension_ancho'],1).' x '.number_format($value['dimension_alto'],1).' mts'.'</td>
                <td style="text-align:center">'.number_format($value['dimesion_area'],2).' m<sup>2</sup></td>
                <td style="text-align:right">S/.'.$precio.'</td>
                <td style="text-align:right">S/.'.$produccion.'</td>
                <td style="text-align:right">S/.'.$importe.'</td>
            </tr>';
        }    
        $html .= '<tr><td colspan="5"></td><td>'.LABEL_13.':</td><td style="text-align:right">S/.'.number_format($data[0]['subtotal'],2).'</td></tr>';
        $html .= '<tr><td colspan="5"></td><td>'.LABEL_GNC6.' '.(number_format($data[0]['pigv']*100)).'%:</td><td style="text-align:right">S/.'.number_format($data[0]['impuesto'],2).'</td></tr>';
        $html .= '<tr><td colspan="5"></td><td>Total:</td><td class="totales" style="text-align:right; font-weight:bold;">S/.'.number_format($data[0]['total'],2).'</td></tr>';
        
        $html .='</table>';
                           
        $html .= '<h4 style="color:#000">'.LABEL_GNC35.':</h4>';
         $html .= '<p style="color:#F00">- Los precios '.$icl.' incluyen IGV.</p>';
        if ($data[0]['valor_produccion'] > 0 ):
            $html .= '<p>- El costo del M<sup>2</sup> de producción es de S/. '.number_format($data[0]['valor_produccion'],2).'</p>';
        endif;        
        if ($data[0]['observaciones']!= '' ):
            $html .= '<p>- '.$data[0]['observaciones'].'</p>';
        endif;        
        return $html;
    }
    
    public function deleteArchivo(){
        $c = Formulario::getParam('_archivo');
        
        $filename = ROOT.'public'.DS.'files'.DS.$c;
        unlink($filename);
        echo $filename;
    }
    
}

?>
