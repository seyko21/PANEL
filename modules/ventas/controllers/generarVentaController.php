<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 05-09-2014 23:09:58 
* Descripcion : generarVentaController.php
* ---------------------------------------
*/    

class generarVentaController extends Controller{

    public function __construct() {
        $this->loadModel(array('modulo'=>'ventas','modelo'=>'generarVenta'));
        $this->loadController(array('modulo'=>'ventas','controller'=>'vproducto')); 
    }
    
    public function index(){ 
        Obj::run()->View->render("indexGenerarVenta");
    }
    
    public function getFormBuscarCliente(){ 
        Obj::run()->View->render('formBuscarCliente');
    }
    
    public function getGridGenerarVenta(){
       $exportarpdf   = Session::getPermiso('VGEVEEP');
       $exportarexcel = Session::getPermiso('VGEVEEX');
       $clonar         = Session::getPermiso('VGEVECL');
       
       $sEcho          =   $this->post('sEcho');
        
       $rResult = Obj::run()->generarVentaModel->getGridGenerarVenta();
        
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
                $encryptReg = Aes::en($aRow['id_docventa']);
                
                switch($aRow['estado']){
                    case 'E':
                         $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" name=\"'.VGEVE.'chk_delete[]\" value=\"'.$encryptReg.'\">';
                        $estado = '<span class=\"label label-default\">'.SEGCO_5.'</span>';
                        break;                  
                    case 'A':
                        $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" name=\"'.VGEVE.'chk_delete[]\" disabled>';       
                        $estado = '<span class=\"label label-danger\">'.SEGPA_9.'</span>';
                        break;                 
                }
                
                switch($aRow['tipo_doc']){
                    case 'F': $tipoDoc = 'Factura';break;                  
                    case 'B': $tipoDoc = 'Boleta';break;                  
                    case 'R': $tipoDoc = 'Recibo';break;                                                                     
                }
                $nombre = $aRow['nombre_descripcion'];
                    
                $saldo = number_format($aRow['monto_saldo'],2);
                /*datos de manera manual*/
                $sOutput .= '["'.$chk.'","'.$aRow['codigo_impresion'].'","'.$nombre.'","'.$tipoDoc.'","'.  Functions::cambiaf_a_normal($aRow['fecha']).'","'.$aRow['moneda'].'","'.number_format($aRow['monto_total'],2).'","'.$saldo.'","'.$estado.'", ';
                
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';                      
                                       
                if($clonar['permiso'] && $aRow['estado'] == 'E'){
                    $sOutput .= '<button type=\"button\" class=\"'.$clonar['theme'].'\" title=\"'.$clonar['accion'].'\" onclick=\"generarVenta.getFormEditarGenerarVenta(this,\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"'.$clonar['icono'].'\"></i>';
                    $sOutput .= '</button>';
                }
                if($exportarpdf['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"'.$exportarpdf['theme'].'\" title=\"'.$exportarpdf['accion'].'\" onclick=\"generarVenta.postPDF(this,\''.$encryptReg.'\',\''.$aRow['codigo_impresion'].'\')\">';
                    $sOutput .= '    <i class=\"'.$exportarpdf['icono'].'\"></i>';
                    $sOutput .= '</button>';
                }
                if($exportarexcel['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"'.$exportarexcel['theme'].'\" title=\"'.$exportarexcel['accion'].'\" onclick=\"generarVenta.postExcel(this,\''.$encryptReg.'\',\''.$aRow['codigo_impresion'].'\')\">';
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
    
    public function getClientes(){ 
        $tab = $this->post('_tab');
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->generarVentaModel->getClientes();
        
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
                
                $nom = '<a href=\"javascript:;\" onclick=\"simpleScript.setInput({'.$tab.'txt_idpersona:\''.$encryptReg.'\', '.$tab.'txt_cliente:\''.$aRow['nombrecompleto'].'\'},\'#'.VGEVE.'formBuscarCliente\');\" >'.$aRow['nombrecompleto'].'</a>';
                
                /*datos de manera manual*/
                $sOutput .= '["'.(++$key).'","'.$nom.'", "'.$aRow['numerodocumento'].'" ';

                $sOutput .= '],';
            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;
    }    
        
    /*carga formulario (newGenerarVenta.phtml) para nuevo registro: GenerarVenta*/
    public function getFormNewGenerarVenta(){
        Obj::run()->View->render("formNewGenerarVenta");
    }
    
    /*carga formulario (editGenerarVenta.phtml) para editar registro: GenerarVenta*/
    public function getFormEditGenerarVenta(){
        Obj::run()->View->render("formEditGenerarVenta");
    }
    //abrir ventana de busqueda:
    public function getFormBuscarProductos(){
        Obj::run()->View->render("formBuscarProductos");
    }
    //Buscar productos al abrir ventana:    
    public function getFindProductos(){
        $data = Obj::run()->generarVentaModel->getFindProductos();
        
        return $data;
    }
    
    public function postPDF(){
        $c = 'venta_'.Obj::run()->generarVentaModel->_cod.'.pdf';
        
        $ar = ROOT.'public'.DS.'files'.DS.$c;
               
        $mpdf = new mPDF('c');
        
        $dataC = Obj::run()->generarVentaModel->getFindVenta();
        if($dataC['estado'] == 'A'){
           $mpdf->SetWatermarkText('A N U L A D O');
           $mpdf->showWatermarkText = true;         
        }   
        $mpdf->SetHTMLHeader('<img src="'.ROOT.'public'.DS.'img'.DS.'logotipo.png" width="137" height="68" />','',TRUE);
        $mpdf->SetHTMLFooter('<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold;"><tr>
                                <td width="33%"><span style="font-weight: bold;">{DATE j-m-Y}</span></td>
                                <td width="33%" align="center" style="font-weight: bold;">{PAGENO}/{nbpg}</td>
                                <td width="33%" style="text-align: right; ">SEVEND.pe</td>
                             </tr></table>');
                
        $html = $this->getHtmlGenerarVenta($mpdf);         

        //$mpdf->WriteHTML($html);
        $mpdf->Output($ar,'F');
        
        $data = array('result'=>1,'archivo'=>$c);
        echo json_encode($data);
        
    }
    
    public function postExcel(){
        $c = 'venta_'.Obj::run()->generarVentaModel->_cod.'.xls';
        
        $ar = ROOT.'public'.DS.'files'.DS.$c;
        
        $html = $this->getHtmlGenerarVenta("EXCEL");
        
        $f=fopen($ar,'wb');
        if(!$f){$data = array('result'=>2);}
        fwrite($f,  utf8_decode($html));
        fclose($f);
                        
        $data = array('result'=>1,'archivo'=>$c);
        echo json_encode($data);
    }
    
    private function getHtmlGenerarVenta($mpdf){
        $dataC = Obj::run()->generarVentaModel->getFindVenta();
        $dataD = Obj::run()->generarVentaModel->getFindVentaD();
        $html ='
        <style>           
           table,h3,h4,p{font-family:Arial;} 
           table, table td, table th, p {font-size:12px;}
           table{width:100%;}
           #td2 th, .totales{background:#901D78; color:#FFF; height:25px;}
           #td2 td{font-size:10px;height:25px;}           
        </style>';
        
        $mon = $dataC['moneda'];
        
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
        if($nom == '') $nom = '- Sin Descripción -';
        
        $html .='<table width="100%" border="0" cellpadding="5" cellspacing="3">
          <tr bgcolor="#901D78">
            <th colspan="6"><div align="center"><h2 style="color:#FFF;">DOCUMENTO DE VENTA </h2></div></th>
          </tr>
          <tr>
            <td width="16%"><strong>N° Impresión:</strong></td>
            <td width="26%"><h3>'.$dataC['periodo'].' - '.$dataC['codigo_impresion'].'</h3></td>
            <td width="15%"></td>
            <td width="15%"></td>
            <td width="20%"><strong>Fecha :</strong></td>
            <td width="15%">'.$dataC['fecha'].'</td>
          </tr>
          <tr>
            <td><strong>Cliente:</strong></td>
            <td colspan="5">'.$dataC['cliente'].'</td>
          </tr>
          <tr>         
            <td width="20%"><strong>Moneda:</strong></td>
            <td width="15%">'.$dataC['descripcion_moneda'].'</td>
            <td width="10%"><strong>Tipo Doc:</strong></td>
            <td width="15%">'.$tipoDoc.'</td>
            <td width="20%"><strong>Estado:</strong></td>
            <td width="15%">'.$estado.'</td>
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
        foreach ($dataD as $value) {
            
            if ($value['cantidad_multiple'] == '1'){
                $cantidad = number_format($value['cantidad_1'],2).' x '.number_format($value['cantidad_2'],2);
            }else{
                $cantidad = number_format($value['cantidad_real'],2);
            }
            
            $html .= '<tr>
                <td style="text-align:center">'.($i++).'</td>
                <td>'.$value['descripcion'].'</td>
                <td>'.$value['sigla'].'</td>                    
                <td style="text-align:center">'.$cantidad.'</td>
                <td style="text-align:right">'.$mon.number_format($value['precio'],2).'</td>
                <td style="text-align:right">'.$mon.number_format($value['importe'],2).'</td>
            </tr>';
        }    
        $html .= '<tr><td colspan="5"></td><td class="totales" style="text-align:right; font-weight:bold;">'.$mon.number_format($dataC['monto_importe'],2).'</td></tr>';
        
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
    
    /*envia datos para grabar registro: GenerarVenta*/
    public function postNewGenerarVenta(){
        $data = Obj::run()->generarVentaModel->newGenerarVenta();
        
        echo json_encode($data);
    }
    
    /*envia datos para editar registro: GenerarVenta*/
    public function postEditGenerarVenta(){
        $data = Obj::run()->generarVentaModel->editGenerarVenta();
        
        echo json_encode($data);
    }
    
    /*envia datos para eliminar registro: GenerarVenta*/
    public function postAnularGenerarVentaAll(){
        $data = Obj::run()->generarVentaModel->anularGenerarVentaAll();
        
        echo json_encode($data);
    }
    
    public function getFindVenta(){
        $data = Obj::run()->generarVentaModel->getFindVenta();
        
        return $data;
    }        
    public function getFindVentaD(){
        $data = Obj::run()->generarVentaModel->getFindVentaD();
        
        return $data;
    }
    
    public static function getCodigo(){ 
        $data = Obj::run()->generarVentaModel->getGenerarCodigo();        
        return $data;
    }       

}

?>