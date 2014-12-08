<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 20-11-2014 00:11:17 
* Descripcion : reporteVentaFechaController.php
* ---------------------------------------
*/    

class reporteVentaFechaController extends Controller{

    public function __construct() {
        $this->loadModel(array('modulo'=>'ventas','modelo'=>'reporteVentaFecha'));
        $this->loadController(array('modulo'=>'ventas','controller'=>'generarVenta')); 
    }
    
    public function index(){ 
        Obj::run()->View->render("indexReporteVentaFecha");
    }
    
    public function getGridReporteVentaFecha(){
        $consultar   = Session::getPermiso('VRPT2CC');
        $exportarpdf   = Session::getPermiso('VRPT2EP');
        $exportarexcel  = Session::getPermiso('VRPT2EX');
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->reporteVentaFechaModel->getReporteVentaFecha();
        
        $num = Obj::run()->reporteVentaFechaModel->_iDisplayStart;
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
                $encryptReg = Aes::en($aRow['fecha']);
                $encryptReg2 = Aes::en($aRow['id_moneda']);
                
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $axion = '"<div class=\"btn-group\">';
                 
                if($consultar['permiso']){
                    $axion .= '<button type=\"button\" class=\"'.$consultar['theme'].'\" title=\"'.$consultar['accion'].'\" onclick=\"reporteVentaFecha.getFormConsultaVenta(this,\''.$encryptReg.'\',\''.$encryptReg2.'\')\">';
                    $axion .= '    <i class=\"'.$consultar['icono'].'\"></i>';
                    $axion .= '</button>';
                }
                 if($exportarpdf['permiso'] == 1){
                    $axion .= '<button type=\"button\" class=\"'.$exportarpdf['theme'].'\" title=\"'.$exportarpdf['accion'].'\" onclick=\"reporteVentaFecha.postPDF(this,\''.$encryptReg.'\',\''.$encryptReg2.'\')\">';
                    $axion .= '    <i class=\"'.$exportarpdf['icono'].'\"></i>';
                    $axion .= '</button>';
                }
               if($exportarexcel['permiso'] == 1){
                    $axion .= '<button type=\"button\" class=\"'.$exportarexcel['theme'].'\" title=\"'.$exportarexcel['accion'].'\" onclick=\"reporteVentaFecha.postExcel(this,\''.$encryptReg.'\',\''.$encryptReg2.'\')\">';
                    $axion .= '    <i class=\"'.$exportarexcel['icono'].'\"></i>';
                    $axion .= '</button>';
                }
                
                $axion .= ' </div>" ';     
                
                
                 if($aRow['saldo'] > 0){
                    $saldo = '<span class=\"badge bg-color-red\">'.number_format($aRow['saldo'],2).'</span>';
                }else{
                    $saldo = number_format($aRow['saldo'],2);
                }
                
                $egresos = number_format($aRow['egresos'],2);
                $utilidad = number_format($aRow['utilidad'],2);
                $inicial =  number_format($aRow['inicial'],2);
                $ingresos = number_format($aRow['monto'],2);
                /*registros a mostrar*/
                $sOutput .= '["'.($num++).'","'.  Functions::cambiaf_a_normal($aRow['fecha']).'","'.$aRow['numero_doc'].'","'.$aRow['moneda'].'","'.$inicial.'","'.$ingresos.'","'.$egresos.'","'.$utilidad.'",'.$axion.' ';

                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }

    public function getGridConsultaVentaFecha(){        
        $exportarpdf   = Session::getPermiso('VRPT2EP');
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->reporteVentaFechaModel->getConsultaVentaFecha();
        
        $num = Obj::run()->reporteVentaFechaModel->_iDisplayStart;
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
                $encryptReg = Aes::en($aRow['id_docventa']);                               
                
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $axion = '"<div class=\"btn-group\">';
                 
                if($exportarpdf['permiso'] == 1){
                    $axion .= '<button type=\"button\" class=\"'.$exportarpdf['theme'].'\" title=\"'.$exportarpdf['accion'].'\" onclick=\"reporteVentaFecha.postPDFVenta(this,\''.$encryptReg.'\',\''.$aRow['codigo_impresion'].'\')\">';
                    $axion .= '    <i class=\"'.$exportarpdf['icono'].'\"></i>';
                    $axion .= '</button>';
                }
                
                $axion .= ' </div>" ';
                if ($aRow['monto_saldo'] > 0):
                    $saldo = '<span class=\"badge bg-color-red\" style=\"padding:5px;\">'.number_format($aRow['monto_saldo'],2).'</span>';
                else :
                     $saldo = number_format($aRow['monto_saldo'],2);
                endif;
                                
                $nombre = $aRow['nombre_descripcion'];
                $pagado = number_format($aRow['monto_asignado'],2);
                /*registros a mostrar*/
                $sOutput .= '["'.($num++).'","'.$aRow['codigo_impresion'].'","'.$nombre.'","'.$aRow['moneda'].'","'.number_format($aRow['monto_total'],2).'","'.$pagado.'","'.$saldo.'",'.$axion.' ';

                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }    
    public function getConsultaCaja(){                        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->reporteVentaFechaModel->getConsultaCaja();
        
        $num = Obj::run()->reporteVentaFechaModel->_iDisplayStart;
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
                $encryptReg = Aes::en($aRow['id_egreso']);                               
                                                
                $c1 = $aRow['id_caja'];
                $c2 = $aRow['sigla_moneda'];
                $c3 = number_format($aRow['monto_inicial'],2);
                $c4 = number_format($aRow['total_ingresos'],2);
                $c5 = number_format($aRow['total_egresos'],2);
                $c6 = number_format($aRow['total_saldo'],2);
                if ($aRow['estado'] == 'C'){
                    $f = new DateTime($aRow['fecha_cierre']);
                    $c7 = $f->format('d/m/Y h:i A');         
                }else{
                    $c7 = ' - Aperturado - ';
                }
                /*registros a mostrar*/
                $sOutput .= '["'.$c1.'","'.$c2.'","'.$c3.'","'.$c4.'","'.$c5.'","'.$c6.'","'.$c7.'" ';

                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }    
    
      public function getConsultaEgresos(){                        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->reporteVentaFechaModel->getConsultaEgresos();
        
        $num = Obj::run()->reporteVentaFechaModel->_iDisplayStart;
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
                $encryptReg = Aes::en($aRow['id_egreso']);                               
                                                
                                                
                $nombre = $aRow['descripcion'];
                $fecha = Functions::cambiaf_a_normal($aRow['fecha']);
                
                /*registros a mostrar*/
                $sOutput .= '["'.($num++).'","'.$nombre.'","'.$fecha.'","'.$aRow['sigla_moneda'].'","'.number_format($aRow['monto'],2).'" ';

                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }    
    
    public function getGridIndexVentaFecha(){

        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->reporteVentaFechaModel->getIndexVentaFecha();
        
        $num = Obj::run()->reporteVentaFechaModel->_iDisplayStart;
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
                $encryptReg = Aes::en($aRow['fecha']);
                $encryptReg2 = Aes::en($aRow['id_moneda']);                                               
                
                if($aRow['saldo'] > 0){
                    $saldo = '<span class=\"badge bg-color-red\">'.number_format($aRow['saldo'],2).'</span>';
                }else{
                    $saldo = number_format($aRow['saldo'],2);
                }
                $egresos = number_format($aRow['egresos'],2);
                $utilidad = number_format($aRow['utilidad'],2);
                $inicial =  number_format($aRow['inicial'],2);
                $ingresos = number_format($aRow['monto'],2);
                /*registros a mostrar*/
                $sOutput .= '["'.  Functions::cambiaf_a_normal($aRow['fecha']).'","'.$aRow['numero_doc'].'","'.$aRow['moneda'].'","'.$inicial.'","'.$ingresos.'","'.$egresos.'","'.$utilidad.'" ';

                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }    
    
    
    /*carga formulario (newReporteVentaFecha.phtml) para nuevo registro: ReporteVentaFecha*/
    public function getFormConsultaVenta(){
        Obj::run()->View->render("formConsultarVentas");
    }
    //PDF de consulta 
    public function postPDFUnaVenta(){
        $data = Obj::run()->generarVentaController->postPDF();
        
        return $data;
     }   

    public function postPDF(){
        $c = 'documento_'.Obj::run()->reporteVentaFechaModel->_fecha.'.pdf';
        
        $ar = ROOT.'public'.DS.'files'.DS.$c;
               
        $mpdf = new mPDF('c');
              
        $mpdf->SetHTMLHeader('<img src="'.ROOT.'public'.DS.'img'.DS.'logotipo.png" width="137" height="68" />','',TRUE);
        $mpdf->SetHTMLFooter('<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold;"><tr>
                                <td width="33%"><span style="font-weight: bold;">{DATE j-m-Y}</span></td>
                                <td width="33%" align="center" style="font-weight: bold;">{PAGENO}/{nbpg}</td>
                                <td width="33%" style="text-align: right; ">'.LB_EMPRESA2.'</td>
                             </tr></table>');
                
        $html = $this->getHtml($mpdf);         

        //$mpdf->WriteHTML($html);
        $mpdf->Output($ar,'F');
        
        $data = array('result'=>1,'archivo'=>$c);
        echo json_encode($data);
        
    }
    
    public function postExcel(){
        $c = 'documento_'.Obj::run()->reporteVentaFechaModel->_fecha.'.xls';
        
        $ar = ROOT.'public'.DS.'files'.DS.$c;
        
        $html = $this->getHtml("EXCEL");
        
        $f=fopen($ar,'wb');
        if(!$f){$data = array('result'=>2);}
        fwrite($f,  utf8_decode($html));
        fclose($f);
                        
        $data = array('result'=>1,'archivo'=>$c);
        echo json_encode($data);
    }
    
    private function getHtml($mpdf){
        $data = Obj::run()->reporteVentaFechaModel->getListadoVenta();
        $dataE = Obj::run()->reporteVentaFechaModel->getListadoEgresos();  
        $dataR = Obj::run()->reporteVentaFechaModel->getListadoResumen();
        $total = 0;
        $acuenta = 0;
        $saldo = 0;        
        $egresos= 0;        
        
        $sumInicial = 0;
        $sumIngresos = 0;
        $sumEgresos = 0;
        $sumSaldo = 0;
        
        $html ='
        <style>           
            table,h2,h3,h4,p{font-family:Arial;} 
           table, table td, table th, p {font-size:12px;}
           table{width:100%;}
           #td2 th{background:#901D78; color:#FFF; height:25px;}
           .totales{ font-weight:bold;height:25px;  }
           #td2 td{font-size:10px;height:25px;}           
        </style>';
        
        $mon = $data[0]['moneda'].' ';        
        
        $html .='<table width="100%" border="0" cellpadding="5" cellspacing="3">
          <tr bgcolor="#901D78">
            <th colspan="6"><div align="center"><h2 style="color:#FFF;">REPORTE DE CAJA </h2></div></th>
          </tr>
          <tr>
            <td width="16%"><strong>Fecha:</strong></td>
            <td width="26%"><h3>'.$data[0]['fecha'].'</h3></td>                        
            <td width="16%"><strong>Moneda :</strong></td>
            <td width="30%">'.$data[0]['descripcion_moneda'].'</td>
          </tr>         
        </table>';
        
        $html .= '<h2>Resumen</h2>';
        $html .= '<table id="td2" border="1" style="border-collapse:collapse">               
          <tr>
                <th style="width:5%;" >ID Caja</th>  
                <th style="width:8%;" >Fecha / Hora</th>  
                <th style="width:13%;" >Inicial</th>  
                <th style="width:13%;" >Ingresos</th>                                
                <th style="width:13%;" >Egresos</th>
                <th style="width:13%;" >Saldo</th>
                <th style="width:8%;" >Cierre</th>
            </tr>';
         foreach ($dataR as $value) {  
             
            $sumInicial += $value['monto_inicial'];
            $sumIngresos += $value['total_ingresos'];
            $sumEgresos += $value['total_egresos'];
            $sumSaldo += $value['total_saldo'];
            $fechacierre = ($value['fecha_cierre'] == '')?'- Aperturado -':$value['fecha_cierre'];       
            $html .=    '<tr>                
                <td align="center" style="font-size:11px;" >'.$value['id_caja'].'</td>  
                <td align="center" style="font-size:11px;" >'.$value['fecha_creacion'].'</td>          
                <td align="right" style="font-size:12px;" >'.$mon.number_format($value['monto_inicial'],2).'</td>                
                <td align="right" style="font-size:12px;" >'.$mon.number_format($value['total_ingresos'],2).'</td>
                <td align="right" style="font-size:12px;" >'.$mon.number_format($value['total_egresos'],2).'</td>
                <td align="right" style="font-size:12px;" ><b>'.$mon.number_format($value['total_saldo'],2).'</b></td>
                <td align="center" style="font-size:11px;" >'.$fechacierre.'</td>
            </tr>';
         }
         
         $html .=  '<tr><td colspan="7"></td></tr><tr>'
                 . '<td colspan="2"><b>Totales</b></td>'
                 . '<td align="right" ><b>'.$mon.number_format($sumInicial,2).'</b></td>'
                 . '<td align="right" ><b>'.$mon.number_format($sumIngresos,2).'</b></td>'
                 . '<td align="right" ><b>'.$mon.number_format($sumEgresos,2).'</b></td>'
                 . '<td align="right" ><b>'.$mon.number_format($sumSaldo,2).'</b></td>'
                 . '<td></td>'
                  . '</tr>';
         $html .=   '</table>';        
        
        
        $html .= '<h4>Observación </h4>';
        $html .='<p><b>Generado por: </b> '.Session::get('sys_nombreUsuario').'</p>';                
        $html .='<p><b>Fecha y Hora: </b> '.date("d/m/Y h:i A").'</p>'; 
        
        if ($mpdf !== 'EXCEL'){
            $mpdf->WriteHTML($html);
            $mpdf->AddPage();
            $html = '';
        }
        
        $html .= '<table width="100%" border="0" cellpadding="5" cellspacing="3">
          <tr bgcolor="#901D78">
            <th colspan="6"><div align="center"><h2 style="color:#FFF;">REPORTE DETALLADO DE CAJA </h2></div></th>
          </tr></table>
          <h2>Ingresos</h2>
        <table id="td2" border="1" style="border-collapse:collapse">
            <tr>
                <th style="width:5%">#</th>
                <th style="width:12%">Código Impresión</th>
                <th style="width:40%">Cliente</th>
                <th style="width:12%">Tipo Doc.</th>                
                <th style="width:12%">Total</th>
                <th style="width:12%">A Cuenta</th>
                <th style="width:12%">Deuda</th>
            </tr>';
        $i =1;
        foreach ($data as $value) {                    
           switch ($value['tipo_doc']){
                case 'R': $tipoDoc = 'Recibo'; break;
                case 'B': $tipoDoc = 'Boleta'; break;
                case 'F': $tipoDoc = 'Factura'; break;
            }
            $total +=   $value['monto_importe'];
            $acuenta += $value['monto_asignado'];
            $saldo += $value['monto_saldo'];
            $html .= '<tr>
                <td style="text-align:center">'.($i++).'</td>
                <td>'.$value['codigo_impresion'].'</td>
                <td>'.$value['cliente'].'</td>
                <td style="text-align:center">'.$tipoDoc.'</td>                    
                <td style="text-align:right">'.$mon.number_format($value['monto_importe'],2).'</td>
                <td style="text-align:right"><b>'.$mon.number_format($value['monto_asignado'],2).'</b></td>
                <td style="text-align:right">'.$mon.number_format($value['monto_saldo'],2).'</td>
            </tr>';            
        }    
        $html .= '<tr><td colspan="4"></td><td style="text-align:right; font-weight:bold;"><b>'.$mon.number_format($total,2).'</b></td>'
                .'<td style="text-align:right; font-weight:bold;"><b>'.$mon.number_format($acuenta,2).'</b></td>'
                .'<td style="text-align:right; font-weight:bold;"><b>'.$mon.number_format($saldo,2).'</b></td>'
                . '</tr>';                
        $html .='</table>';
        
    $html .= '<h2>Egresos</h2>
        <table id="td2" border="1" style="border-collapse:collapse">
            <tr>
                <th style="width:5%">#</th>
                <th style="width:60%">Descripción de gasto</th>
                <th style="width:20%">Monto</th>                
            </tr>'; 
        
        $i = 1;
        foreach ($dataE as $value) {       
           $mon = $value['moneda'].' ';   
           $egresos += $value['monto'];                        
           $html .= '<tr>
                <td style="text-align:center">'.($i++).'</td>
                <td>'.$value['descripcion'].'</td>
                <td style="text-align:right">'.$mon.number_format($value['monto'],2).'</td>
            </tr>';            
        }    
        
         $html .= '<tr><td colspan="1"></td><td style="text-align:right;"></td><td class="totales" style="text-align:right; font-weight:bold;">'.$mon.number_format($egresos,2).'</td>'
                . '</tr>';       
         
        $html .='</table>';         
        
        if ($mpdf == 'EXCEL'){
           return $html; 
        }
        
        $mpdf->WriteHTML($html);               
    }  
     
}

?>