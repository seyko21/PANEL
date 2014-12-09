<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 05-09-2014 23:09:58 
* Descripcion : generarCotizacionController.php
* ---------------------------------------
*/    

class generarCotizacionController extends Controller{

    public function __construct() {
        $this->loadModel(array('modulo'=>'ventas','modelo'=>'generarCotizacion'));
        $this->loadController(array('modulo'=>'ventas','controller'=>'vproducto')); 
        $this->loadController(array('modulo' => 'usuarios', 'controller' => 'configurarUsuarios'));            
    }
    
    public function index(){ 
        Obj::run()->View->render("indexGenerarCotizacion");
    }
    
    public function getFormBuscarCliente(){ 
        Obj::run()->View->render('formBuscarClienteCotizacion');
    }
    
    public function getGridGenerarCotizacion(){
       $exportarpdf   = Session::getPermiso('VCOTIEP');
       $exportarexcel = Session::getPermiso('VCOTIEX');
       $enviaremail   = Session::getPermiso('VCOTIEE');
       $generar   = Session::getPermiso('VCOTIGN');
       
       $sEcho          =   $this->post('sEcho');
        
       $rResult = Obj::run()->generarCotizacionModel->getGridGenerarCotizacion();
        
        if(!isset($rResult['error'])){  
            $iTotal         = isset($rResult[0]['total'])?$rResult[0]['total']:0;
            $idx =1;
            $sOutput = '{';
            $sOutput .= '"sEcho": '.intval($sEcho).', ';
            $sOutput .= '"iTotalRecords": '.$iTotal.', ';
            $sOutput .= '"iTotalDisplayRecords": '.$iTotal.', ';
            $sOutput .= '"aaData": [ ';
            foreach ( $rResult as $key=>$aRow ){
                             
                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_cotizacion']);
                
                switch($aRow['estado']){
                    case 'E':
                         $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" name=\"'.VCOTI.'chk_delete[]\" value=\"'.$encryptReg.'\">';
                        $estado = '<span class=\"label label-default\">'.SEGCO_5.'</span>';
                        break;                  
                    case 'A':
                        $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" name=\"'.VCOTI.'chk_delete[]\" disabled>';       
                        $estado = '<span class=\"label label-danger\">'.SEGPA_9.'</span>';
                        break;        
                    case 'P':
                        $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" name=\"'.VCOTI.'chk_delete[]\" disabled>';       
                        $estado = '<span class=\"label label-info\">'.SEGCO_6.'</span>';
                        break; 
                }
                
                $idPersona = Aes::en($aRow['id_persona']);
                $nombre = '<a href=\"javascript:;\" onclick=\"persona.getDatosPersonales(\''.$idPersona.'\');\">'.$aRow['nombre_descripcion'].'</a>';
                $codVenta = $aRow['codigo_venta'];
                /*datos de manera manual*/
                $sOutput .= '["'.$chk.'","'.$aRow['codigo'].'","'.$nombre.'","'.  Functions::cambiaf_a_normal($aRow['fecha']).'","'.$aRow['moneda'].'","'.number_format($aRow['monto_total'],2).'","'.$codVenta.'","'.$estado.'", ';
                
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';                      
                                       
        
                if($exportarpdf['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"'.$exportarpdf['theme'].'\" title=\"'.$exportarpdf['accion'].'\" onclick=\"vGenerarCotizacion.postPDF(this,\''.$encryptReg.'\',\''.$aRow['codigo'].'\')\">';
                    $sOutput .= '    <i class=\"'.$exportarpdf['icono'].'\"></i>';
                    $sOutput .= '</button>';
                }    
                if($exportarexcel['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"'.$exportarexcel['theme'].'\" title=\"'.$exportarexcel['accion'].'\" onclick=\"vGenerarCotizacion.postExcel(this,\''.$encryptReg.'\',\''.$aRow['codigo'].'\')\">';
                    $sOutput .= '    <i class=\"'.$exportarexcel['icono'].'\"></i>';
                    $sOutput .= '</button>';
                }
                if($enviaremail['permiso'] && $aRow['estado'] == 'E'){
                    $sOutput .= '<button type=\"button\" class=\"'.$enviaremail['theme'].'\" title=\"'.$enviaremail['accion'].'\" onclick=\"vGenerarCotizacion.postEmail(this,\''.$encryptReg.'\', \''.$aRow['codigo'].'\')\">';
                    $sOutput .= '    <i class=\"'.$enviaremail['icono'].'\"></i>';
                    $sOutput .= '</button>';
                }
                if($generar['permiso'] && $aRow['estado'] == 'E'){
                    $sOutput .= '<button type=\"button\" class=\"'.$generar['theme'].'\" title=\"'.$generar['accion'].'\" onclick=\"generarVenta.getFormEditarGenerarVenta(this,\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"'.$generar['icono'].'\"></i>';
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
                
                if ($aRow['email'] !== ''){               
                    $nom = '<a href=\"javascript:;\" onclick=\"simpleScript.setInput({'.$tab.'txt_idpersona:\''.$encryptReg.'\', '.$tab.'txt_cliente:\''.$aRow['nombrecompleto'].'\'},\'#'.VCOTI.'formBuscarCliente\');\" >'.$aRow['nombrecompleto'].'</a>';
                    $email = $aRow['email'];
                }else{
                    $nom = '<span class=\"txt-color-red\">'.$aRow['nombrecompleto'].'</span>';                    
                    $email = '<span class=\"txt-color-red\">- no tiene email -<span>';
                }
                /*datos de manera manual*/
                $sOutput .= '["'.(++$key).'","'.$nom.'", "'.$email.'" ';

                $sOutput .= '],';
            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;
    }    
        
    /*carga formulario (newGenerarCotizacion.phtml) para nuevo registro: GenerarCotizacion*/
    public function getFormNewVGenerarCotizacion(){
        Obj::run()->View->render("formNewGenerarCotizacion");
    }
    
    /*carga formulario (editGenerarCotizacion.phtml) para editar registro: GenerarCotizacion*/
    public function getFormEditVGenerarCotizacion(){
        Obj::run()->View->render("formEditGenerarCotizacion");
    }
    //abrir ventana de busqueda:
    public function getFormBuscarProductos(){
        Obj::run()->View->render("formBuscarProductosCotizacion");
    }
    //Buscar productos al abrir ventana:    
    public function getFindProductos(){
        $data = Obj::run()->generarCotizacionModel->getFindProductos();
        
        return $data;
    }
    
    public function postPDF($n=''){
        $c = 'cotizacion_'.Obj::run()->generarCotizacionModel->_cod.'.pdf';
        
        $ar = ROOT.'public'.DS.'files'.DS.$c;
               
        $mpdf = new mPDF('c');
        
        $dataC = Obj::run()->generarCotizacionModel->getFindCotizacion();
        if($dataC['estado'] == 'A'){
           $mpdf->SetWatermarkText('A N U L A D O');
           $mpdf->showWatermarkText = true;         
        }   
        $mpdf->SetHTMLHeader('<img src="'.ROOT.'public'.DS.'img'.DS.'logotipo.png" width="137" height="68" />','',TRUE);
        $mpdf->SetHTMLFooter('<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold;"><tr>
                                <td width="33%"><span style="font-weight: bold;">{DATE j-m-Y}</span></td>
                                <td width="33%" align="center" style="font-weight: bold;">{PAGENO}/{nbpg}</td>
                                <td width="33%" style="text-align: right; ">'.LB_EMPRESA2.'</td>
                             </tr></table>');
                
        $html = $this->getHtmlGenerarCotizacion($mpdf);         

        //$mpdf->WriteHTML($html);
        $mpdf->Output($ar,'F');
                        
         if($n != 'N'){
            $data = array('result'=>1,'archivo'=>$c);
            echo json_encode($data);
        }
        
    }
    
    public function postExcel(){
        $c = 'cotizacion_'.Obj::run()->generarCotizacionModel->_cod.'.xls';
        
        $ar = ROOT.'public'.DS.'files'.DS.$c;
        
        $html = $this->getHtmlGenerarCotizacion("EXCEL");
        
        $f=fopen($ar,'wb');
        if(!$f){$data = array('result'=>2);}
        fwrite($f,  utf8_decode($html));
        fclose($f);
                        
        $data = array('result'=>1,'archivo'=>$c);
        echo json_encode($data);
    }
    
    private function getHtmlGenerarCotizacion($mpdf){
        $dataC = Obj::run()->generarCotizacionModel->getFindCotizacion();
        $dataD = Obj::run()->generarCotizacionModel->getFindCotizacionD();
        $html ='
        <style>           
           table,h3,h4,p{font-family:Arial;} 
           table, table td, table th, p {font-size:12px;}
           table{width:100%;}
           #td2 th, .totales{background:#901D78; color:#FFF; height:25px;}
           #td2 td{font-size:10px;height:25px;}           
        </style>';
        
        $mon = $dataC['moneda'].' ';
        
        switch ($dataC['estado']){
            case 'E': $estado = 'Emitido'; break;
            case 'A': $estado = 'Anulado'; break;
        }
                
        switch ($dataC['tipo_doc']){
            case 'R': $tipoDoc = 'Recibo'; break;
            case 'B': $tipoDoc = 'Boleta'; break;
            case 'F': $tipoDoc = 'Factura'; break;
        }
        $nom = $dataC['nombre_descripcion'];
        
        $html .='<table width="100%" border="0" cellpadding="5" cellspacing="3">
          <tr bgcolor="#901D78">
            <th colspan="6"><div align="center"><h2 style="color:#FFF;">Cotización de Venta </h2></div></th>
          </tr>
          <tr>
            <td width="10%"><strong>Código:</strong></td>
            <td width="26%"><h3>'.$dataC['codigo'].'</h3></td>
            <td width="15%"></td>
            <td width="15%"></td>
            <td width="20%"><strong>Fecha Emitido :</strong></td>
            <td width="15%">'.$dataC['fecha'].'</td>
          </tr>
          <tr>
            <td><strong>Cliente:</strong></td>
            <td colspan="5">'.strtoupper($dataC['cliente']).'</td>
          </tr>
          <tr>         
            <td ><strong>Moneda:</strong></td>
            <td >'.$dataC['descripcion_moneda'].'</td>
            <td ></td>
            <td ></td>
            <td ><strong>Estado:</strong></td>
            <td >'.$estado.'</td>
          </tr>
        </table> 
        <br />
        <table id="td2" border="1" style="border-collapse:collapse">
            <tr>
                <th style="width:5%">Item</th>
                <th style="width:40%">Descripción del Producto</th>
                <th style="width:12%">Unid. Medid</th>
                <th style="width:12%">Cantidad</th>
                <th style="width:12%">Precio</th>
                <th style="width:12%">Importe</th>
            </tr>';
        $i =1;
        $subtotal = 0;
        $impuesto = 0;
        $total = 0;
        foreach ($dataD as $value) {
            
            if ($value['cantidad_multiple'] == '1'){
                $cantidad = number_format($value['cantidad_1'],2).' x '.number_format($value['cantidad_2'],2);
            }else{
                $cantidad = number_format($value['cantidad_real'],2);
            }
            
            $html .= '<tr>
                <td style="text-align:center">'.($i++).'</td>
                <td>'.$value['descripcion'].'</td>
                <td style="text-align:center">'.$value['sigla'].'</td>                    
                <td style="text-align:center">'.$cantidad.'</td>
                <td style="text-align:right">'.$mon.number_format($value['precio_final'],2).'</td>
                <td style="text-align:right">'.$mon.number_format($value['importe_afectado'],2).'</td>
            </tr>';
            $subtotal +=$value['importe_afectado'];
            $impuesto += $value['impuesto'];
            $total += $value['total_impuesto'];
        }    
        $html .= '<tr><td colspan="4"></td><td>Sub Total:</td><td  style="text-align:right; font-weight:bold;">'.$mon.number_format($subtotal,2).'</td></tr>';
        $html .= '<tr><td colspan="4"></td><td>Impuesto ('.($dataC['porcentaje_igv']*100).'%):</td><td style="text-align:right; font-weight:bold;">'.$mon.number_format($impuesto,2).'</td></tr>';
        $html .= '<tr><td colspan="4"></td><td>Total:</td><td class="totales" style="text-align:right; font-weight:bold;">'.$mon.number_format($total,2).'</td></tr>';
        
        $html .='</table>';
        $html .= '<h4>Observación </h4>';
        if ($dataC['observacion']!= '' ):
            $html .= '<p>- '.$dataC['observacion'].'</p>';
        else:
            $html .= '<p>- NINGUNO.</p>';
        endif;   
        
        if ($mpdf == 'EXCEL'){
           return $html; 
        }
        
        $mpdf->WriteHTML($html);               
    }
    
 public function postEmail(){ 
        $this->postPDF('N');        
        $data = Obj::run()->generarCotizacionModel->getFindCotizacion();
        
        $data0 = Obj::run()->configurarUsuariosController->getParametros('EMAIL');        
        $data1 = Obj::run()->configurarUsuariosController->getParametros('EMCO');        
        $emailEmpresa = $data0['valor'];
        $empresa = $data1['valor'];
        
        $emailCliente = $data['email'];
        $cliente = $data['nombrecompleto'];
        $emailUser = $data['mail_user'];
        $vendedor = $data['vendedor'];
        $numCotizacion = $data['codigo'];
        $numVendedor = $data['telefono_vendedor'];
        
        $archivo = 'cotizacion_'.$numCotizacion.'.pdf';
        //Html de Cotizacion:
         $body = '
            <h3>Estimado: ' . $cliente . '</h3>
            <p>Muchas gracias por confiar en <b>'.LB_EMPRESA2.'</b>, le enviamos nuestra cotizacion acerca de nuestros productos y servicios.
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
        $mail->Subject    = "Cotizacion SEVEND";
        
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
    
    /*envia datos para grabar registro: GenerarCotizacion*/
    public function postNewGenerarCotizacion(){
        $data = Obj::run()->generarCotizacionModel->newGenerarCotizacion();
        
        echo json_encode($data);
    }
    
    /*envia datos para editar registro: GenerarCotizacion*/
    public function postEditGenerarCotizacion(){
        $data = Obj::run()->generarCotizacionModel->editGenerarCotizacion();
        
        echo json_encode($data);
    }
    
    /*envia datos para eliminar registro: GenerarCotizacion*/
    public function postAnularGenerarCotizacionAll(){
        $data = Obj::run()->generarCotizacionModel->anularGenerarCotizacionAll();
        
        echo json_encode($data);
    }
    
    public function getFindCotizacion(){
        $data = Obj::run()->generarCotizacionModel->getFindCotizacion();
        
        return $data;
    }        
    public function getFindCotizacionD(){
        $data = Obj::run()->generarCotizacionModel->getFindCotizacionD();
        
        return $data;
    }
    
}

?>