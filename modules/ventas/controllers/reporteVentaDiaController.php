<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 19-11-2014 22:11:59 
* Descripcion : reporteVentaDiaController.php
* ---------------------------------------
*/    

class reporteVentaDiaController extends Controller{

    public function __construct() {
        $this->loadModel("reporteVentaDia");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexReporteVentaDia");
    }
    
    public function getGraficoVentaDia() {
        $data = Obj::run()->reporteVentaDiaModel->getGraficoVentaDia();
        echo json_encode($data);
        
    }
    public function postPDF(){
        $c = 'documento_'.Obj::run()->reporteVentaDiaModel->_fecha.'.pdf';
        
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
        $c = 'documento_'.Obj::run()->reporteVentaDiaModel->_fecha.'.xls';
        
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
        $data = Obj::run()->reporteVentaDiaModel->getListadoVenta();
        $dataE = Obj::run()->reporteVentaDiaModel->getListadoEgresos();
        
        $totalD = 0;
        $acuentaD = 0;
        $saldoD = 0;
        
        $totalS = 0;
        $acuentaS = 0;
        $saldoS = 0;
        
        $ingresosD = 0;
        $egresosD = 0;
        $utilidadD = 0;
        
        $ingresosS = 0;
        $egresosS = 0;
        $utilidadS = 0;
        
        $moneda_1 = 'Nuevos soles';
        $moneda_2 = 'Dolares';
        
        $html ='
        <style>           
           table,h2,h3,h4,p{font-family:Arial;} 
           table, table td, table th, p {font-size:12px;}
           table{width:100%;}
           #td2 th{background:#901D78; color:#FFF; height:25px;}
           .totales{ font-weight:bold;height:25px;  }
           #td2 td{font-size:10px;height:25px;}           
        </style>';
                     
        $html .='<table width="100%" border="0" cellpadding="5" cellspacing="3">
          <tr bgcolor="#901D78">
            <th colspan="7"><div align="center"><h2 style="color:#FFF;">REPORTE DE CAJA POR DIA</h2></div></th>
          </tr>
          <tr>
            <td width="16%"><strong>Fecha:</strong></td>
            <td width="26%"><h3>'.$data[0]['fecha'].'</h3></td>                        
          </tr>         
        </table> 
        <h2>Ingresos</h2>
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
           $mon = $value['moneda'].' ';   
           switch ($value['tipo_doc']){
                case 'R': $tipoDoc = 'Recibo'; break;
                case 'B': $tipoDoc = 'Boleta'; break;
                case 'F': $tipoDoc = 'Factura'; break;
            }
            
             switch ($value['id_moneda']){
                 case 'SO':
                       $totalS +=   $value['monto_importe'];
                       $acuentaS += $value['monto_asignado'];
                       $saldoS += $value['monto_saldo'];
                       $monS = $value['moneda'];
                       $ingresosS += $value['monto_asignado'];
                       break;
                 case 'DO':
                      $totalD +=   $value['monto_importe'];
                      $acuentaD += $value['monto_asignado'];
                      $saldoD += $value['monto_saldo'];
                      $monD = $value['moneda'];
                      $ingresosD +=$value['monto_asignado'];
                      break;
             }

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
        $html .= '<tr><td colspan="3"></td><td>'.$moneda_1.':</td><td class="totales" style="text-align:right; font-weight:bold;">'.$monS.number_format($totalS,2).'</td>'
                .'<td class="totales" style="text-align:right; font-weight:bold;">'.$monS.number_format($acuentaS,2).'</td>'
                .'<td class="totales" style="text-align:right; font-weight:bold;">'.$monS.number_format($saldoS,2).'</td>'
                . '</tr>';
        
         $html .= '<tr><td colspan="3"></td><td>'.$moneda_2.':</td><td class="totales" style="text-align:right; font-weight:bold;">'.$monD.number_format($totalD,2).'</td>'
                .'<td class="totales" style="text-align:right; font-weight:bold;">'.$monD.number_format($acuentaD,2).'</td>'
                .'<td class="totales" style="text-align:right; font-weight:bold;">'.$monD.number_format($saldoD,2).'</td>'
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
                      
             switch ($value['id_moneda']){
                 case 'SO':
                       $egresosS += $value['monto'];
                       $monS = $value['moneda'];
                       break;
                 case 'DO':
                      $egresosD += $value['monto'];
                      $monD = $value['moneda'];
                      break;
             }

            $html .= '<tr>
                <td style="text-align:center">'.($i++).'</td>
                <td>'.$value['descripcion'].'</td>
                <td style="text-align:right">'.$mon.number_format($value['monto'],2).'</td>
            </tr>';            
        }    
        
         $html .= '<tr><td colspan="1"></td><td style="text-align:right;">'.$moneda_1.':</td><td class="totales" style="text-align:right; font-weight:bold;">'.$monS.number_format($egresosS,2).'</td>'
                . '</tr>';
        
         $html .= '<tr><td colspan="1"></td><td style="text-align:right;">'.$moneda_2.':</td><td class="totales" style="text-align:right; font-weight:bold;">'.$monD.number_format($egresosD,2).'</td>'
                . '</tr>';
        $html .='</table>';
        
        $html .= '<h2>Resumen</h2>';
        $html .= '<table id="td2" border="1" style="border-collapse:collapse">               
          <tr>
                <th style="width:20%;" >Moneda</th>  
                <th style="width:30%;" >Ingresos</th>                                
                <th style="width:30%;" >Egresos</th>
                <th style="width:30%;" >Total de Caja</th>
            </tr>
            <tr>                
                <td>'.$moneda_1.'</td>
                <td align="right" style="font-size:12px;" >'.$monS.number_format($ingresosS,2).'</td>
                <td align="right" style="font-size:12px;" >'.$monS.number_format($egresosS,2).'</td>
                <td align="right" style="font-size:12px;" ><b>'.$monS.number_format(($ingresosS - $egresosS),2).'</b></td>
            </tr>
            <tr>     
                <td>'.$moneda_2.'</td>
                <td align="right"  style="font-size:12px;" >'.$monD.number_format($ingresosD,2).'</td>
                <td align="right"  style="font-size:12px;" >'.$monD.number_format($egresosD,2).'</td>
                <td align="right"  style="font-size:12px;" ><b>'.$monD.number_format(($ingresosD - $egresosD),2).'</b></td>
            </tr>
            </table>';
        
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