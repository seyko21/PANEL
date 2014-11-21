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
                /*registros a mostrar*/
                $sOutput .= '["'.($num++).'","'.  Functions::cambiaf_a_normal($aRow['fecha']).'","'.$aRow['numero_doc'].'","'.$aRow['moneda'].'","'.number_format($aRow['monto'],2).'","'.$saldo.'",'.$axion.' ';

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
                    $saldo = '<span class=\"badge bg-color-red\">'.number_format($aRow['monto_saldo'],2).'</span>';
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
                /*registros a mostrar*/
                $sOutput .= '["'.  Functions::cambiaf_a_normal($aRow['fecha']).'","'.$aRow['numero_doc'].'","'.$aRow['moneda'].'","'.number_format($aRow['monto'],2).'","'.$saldo.'" ';

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
        
        $total = 0;
        $acuenta = 0;
        $saldo = 0;
        
        $html ='
        <style>           
           table,h3,h4,p{font-family:Arial;} 
           table, table td, table th, p {font-size:12px;}
           table{width:100%;}
           #td2 th, .totales{background:#901D78; color:#FFF; height:25px;}
           #td2 td{font-size:10px;height:25px;}           
        </style>';
        
        $mon = $data[0]['moneda'].' ';        
        
        $html .='<table width="100%" border="0" cellpadding="5" cellspacing="3">
          <tr bgcolor="#901D78">
            <th colspan="6"><div align="center"><h2 style="color:#FFF;">REPORTE DE VENTAS </h2></div></th>
          </tr>
          <tr>
            <td width="16%"><strong>Fecha:</strong></td>
            <td width="26%"><h3>'.$data[0]['fecha'].'</h3></td>                        
            <td width="16%"><strong>Moneda :</strong></td>
            <td width="30%">'.$data[0]['descripcion_moneda'].'</td>
          </tr>         
        </table> 
        <br />
        <table id="td2" border="1" style="border-collapse:collapse">
            <tr>
                <th style="width:5%">#</th>
                <th style="width:12%">Código Impresión</th>
                <th style="width:40%">Cliente</th>
                <th style="width:12%">Tipo Doc.</th>                
                <th style="width:12%">Total</th>
                <th style="width:12%">A Cuenta</th>
                <th style="width:12%">Saldo</th>
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
        $html .= '<h4>Observación </h4>';
        $html .='<p><b>Generado por: </b> '.Session::get('sys_nombreUsuario').'</p>';                
        $html .='<p><b>Fecha y Hora: </b> '.date("d/m/Y h:i A").'</p>';  
        
        if ($mpdf == 'EXCEL'){
           return $html; 
        }
        
        $mpdf->WriteHTML($html);               
    }  
     
}

?>