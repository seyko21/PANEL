<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 30-09-2014 03:09:35 
* Descripcion : liquidacionSocioController.php
* ---------------------------------------
*/    

class liquidacionSocioController extends Controller{

    public function __construct() {
        $this->loadModel("liquidacionSocio");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexLiquidacionSocio");
    }
    
    public function getGridLiquidacionSocio(){
        $exportarpdf   = Session::getPermiso('LISOCEP');     
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->liquidacionSocioModel->getLiquidacionSocio();
        
        $num = Obj::run()->liquidacionSocioModel->_iDisplayStart;
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
                
               /*campo que maneja los estados, para el ejemplo aqui es ACTIVO, coloca tu campo*/
                switch($aRow['estado']){
                    case 'E':
                        $estado = '<span class=\"label label-default\">'.SEGCO_5.'</span>';
                        break;
                    case 'T':
                        $estado = '<span class=\"label label-success\">'.SEGPA_8.'</span>';
                        break;
                    case 'P':
                        $estado = '<span class=\"label label-warning\">'.SEGPA_7.'</span>';
                        break;
                    case 'A':
                        $estado = '<span class=\"label label-danger\">'.SEGPA_9.'</span>';
                        break;
                    case 'F':
                        $estado = '<span class=\"label label-info\">'.SEGPA_29.'</span>';
                        break;
                    case 'R':
                        $estado = '<span class=\"label bg-color-magenta txt-color-white\">'.SEGPA_30.'</span>';
                        break;
                }
                
                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_ordenservicio']);
                $idSocio = Aes::en($aRow['id_persona']); 
                $numOrden = Aes::en($aRow['orden_numero']);

                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $axion = '"<div class=\"btn-group\">';
                 
                if($exportarpdf['permiso']){
                    $axion .= '<button type=\"button\" class=\"'.$exportarpdf['theme'].'\" title=\"'.$exportarpdf['accion'].'\" onclick=\"liquidacionSocio.postPDF(this,\'' . $encryptReg . '\',\'' . $idSocio . '\',\'' . $numOrden . '\')\"> ';
                    $axion .= '    <i class=\"'.$exportarpdf['icono'].'\"></i>';
                    $axion .= '</button>';
                }
       
                $axion .= ' </div>" ';
                
                /*registros a mostrar*/
                $sOutput .= '["'.$aRow['orden_numero'].'","'.$aRow['socio'].'","'.  Functions::cambiaf_a_normal($aRow['fecha_contrato']).'","'.$aRow['cliente'].' - '.$aRow['representante'].'","S/.'.number_format($aRow['ingresos'],2).'","'.$estado.'",'.$axion.' ';

                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }
      
    public function postPDF($n=''){
         
        $c = 'liquidacion_'.Obj::run()->liquidacionSocioModel->_numOrden.'.pdf';
        
        $ar = ROOT.'public'.DS.'files'.DS.$c;
               
        $mpdf = new mPDF('c', 'A4-L');
        
        $mpdf->SetHTMLHeader('<img src="'.ROOT.'public'.DS.'img'.DS.'logotipo.png" width="137" height="68" />','',TRUE);
        $mpdf->SetHTMLFooter('<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold;"><tr>
                                <td width="33%"><span style="font-weight: bold;">{DATE j-m-Y}</span></td>
                                <td width="33%" align="center" style="font-weight: bold;">{PAGENO}/{nbpg}</td>
                                <td width="33%" style="text-align: right; ">'.LB_EMPRESA.'</td>
                             </tr></table>');
    
        $html = $this->getHtmlLiquidacion($mpdf);                 
        
        $mpdf->Output($ar,'F');
        
        if($n != 'N'){
            $data = array('result'=>1,'archivo'=>$c);
            echo json_encode($data);
        }
    }       
        
    public function getHtmlLiquidacion($mpdf){
       $data = Obj::run()->liquidacionSocioModel->getRptDetalleOrden();
       $dataG = Obj::run()->liquidacionSocioModel->getRptGastosOrden();
       $dataP = Obj::run()->liquidacionSocioModel->getRptPagoGanancia(); 
      $html ='
        <style>           
           table,h2,h3,h4{font-family:Arial;} 
           table, table td, table th, p {font-size:12px;}
           table{width:100%;}
           #td2 th, .totales{color:#FFF;background:#901D78; height:25px; font-size:11px;}
           #td2 td{font-size:10px;height:25px;}  
           table.print-friendly tr td, table.print-friendly tr th { page-break-inside: avoid; page-break-after: always;}
        </style>';
      
       switch($data[0]['estado']){
            case 'E': $estado = 'Emitido'; break;
            case 'P': $estado = 'Pago parcial'; break;
            case 'T': $estado = 'Pago total'; break;
            case 'F': $estado = 'Finalizado'; break;
            case 'A': $estado = 'Anulado'; break;
            case 'R': $estado = 'Renovado'; break;
        }
                        
        $html .='<table width="100%" border="0" cellpadding="5" cellspacing="3" class="print-friendly">
          <tr bgcolor="#901D78">
            <th colspan="6"><div align="center"><h2 style="color:#FFF;">Liquidacion de Socio </h2></div></th>
          </tr>
           <tr>
           <td width="5%"><h3><strong>N° OS:</strong></h3></td>
            <td width="20%">'.$data[0]['orden_numero'].'</td>            
            <td style ="width: 8%;text-align:right" ><strong>Fecha Contrato:</strong></td>
            <td style ="width:15%;text-align:left" >'.$data[0]['fecha'].'</td>
            <td style ="width:15%;text-align:right" ><strong>Estado :</strong></td>
            <td style ="width:15%;text-align:left">'.$estado.'</td>                
          </tr>
          <tr>      
            <td><strong>Socio(a):</strong></td>
            <td colspan="5">'.$data[0]['socio'].'</td>
          </tr>          
          <tr>
            <td><strong>Cliente:</strong></td>
            <td colspan="5">'.($data[0]['ruc']==''?'':$data[0]['ruc'].' - ').$data[0]['cliente'].'</td>            
          </tr>
           <tr>
           <td width="13%"><strong>Tiempo :</strong></td>
            <td width="26%">'.Functions::convertirDiaMes($data[0]['cantidad_mes']).' ('. number_format($data[0]['cantidad_mes'],1).')</td>
            <td width="8%" style ="text-align:right"><strong>Oferta :</strong></td>
            <td width="15%" style ="text-align:left">'.$data[0]['dias_oferta'].' días</td>
          </tr>
        </table> ';
         $html .= '<h3>Detalle de liquidación</h3> 
        <table id="td2" border="1" style="border-collapse:collapse" class="print-friendly">
            <tr>
                <th rowspan ="2" style="width:5%" >Código</th>
                <th rowspan ="2" style="width:8%">Elemento</th>
                <th rowspan ="2" style="width:30%">Ubicación</th>                
                <th rowspan ="2" style="width:8%">Alquiler</th>
                <th colspan = "3" style="width:8%">Egresos</th>               
                <th rowspan ="2" style="width:8%">Utilidad </th>                
                <th rowspan ="2" style="width:10%">Ganancia</th>
            </tr>
            <tr>              
                <th style="width:10%">Gastos</th>               
                <th style="width:10%">Impuesto</th>
                <th style="width:10%">Comisión</th>
            </tr>';
        // Listado del servicio 
        $sumaUtilidadSocio = 0;
        $sumaUtilidad = 0;
        $egreso = 0;
        $nroCuota = $data[0]['nrocuotas'];
        $totalContrato = 0;        
        $sumaOI =0;
        $sumaImpuesto =0;
        $sumaComision =0;
        if ($nroCuota == 0) $nroCuota = 1;
        foreach ($data as $value) {
            $alquiler = number_format($value['importe'] ,2);
            $egresos = number_format($value['orden_instalacion'],2);
            $impuesto = number_format($value['impuesto'],2);
            $comision = number_format($value['comision_venta'],2);
            
            $porcentaje = number_format($value['porcentaje_ganacia']*100,1); 
            $porComi = number_format($value['porcentaje_comision']*100,1);                         
            
            $egreso = $egreso + ($value['egresos'] );            
            $totalContrato = $totalContrato + ($value['total_final'] ); 
            
            $sumaOI = $sumaOI + $value['orden_instalacion'];
            $sumaImpuesto = $sumaImpuesto + $value['impuesto'];
            $sumaComision = $sumaComision + $value['comision_venta'];
            $utilidad = $value['utilidad'];
            $sumaUtilidad = $sumaUtilidad + $utilidad;
            
            $utilidadSocio = $utilidad * $value['porcentaje_ganacia'];
            $sumaUtilidadSocio = $sumaUtilidadSocio + $utilidadSocio;
            
            $html .= '<tr>
                <td style="text-align:center">'.$value['codigo'].'</td>
                <td>'.$value['elemento'].'</td>
                <td>'.$value['producto'].' - '.number_format($value['dimension_ancho'],1).' x '.number_format($value['dimension_alto'],1).' mts'.'</td>                
                <td style="text-align:right">S/.'.$alquiler.'</td>
                <td style="text-align:right">S/.'.$egresos.'</td>
                <td style="text-align:right">S/.'.$impuesto.'</td>                
                <td style="text-align:right">S/.'.$comision.' ('.$porComi.'%)</td>                     
                <td style="text-align:right">S/.'.number_format($utilidad,2).'</td>                  
                <td style="text-align:right">S/.'.number_format($utilidadSocio,2).' ('.$porcentaje.'%)</td>                                    
            </tr>';
        }            
        $html .= '<tr>';
        $html .= '<td colspan="4" style="text-align:right;">&nbsp;</td>';        
        $html .= '<td  style="text-align:right; font-weight:bold;">S/.'.number_format($sumaOI,2).'</td>';        
        $html .= '<td style="text-align:right;font-weight:bold;">S/.'.number_format($sumaImpuesto,2).'</td>';        
        $html .= '<td style="text-align:right;font-weight:bold;">S/.'.number_format($sumaComision,2).'</td>';        
        $html .= '<td  style="text-align:right;font-weight:bold;">S/.'.number_format($sumaUtilidad,2).'</td>';        
        $html .= '<td class="totales" style="text-align:right; font-weight:bold; font-size:14px;">S/.'.number_format($sumaUtilidadSocio,2).'</td></tr>';
        
        $html .='</table>';                
        
        $html .= '<h3>Resumen </h3><table id="td2" border="1" style="border-collapse:collapse" class="print-friendly">               
          <tr>
                <th style="width:15%;" >Total Alquiler</th>
                <th style="width:15%;" >Total Egresos</th>
                <th style="width:15%;" >Total Utilidad</th>
                <th style="width:15%;" >Ganancia</th>
                <th style="width:15%;" >Ganancia Mensual ('.$nroCuota.')</th>
         </tr>
         <tr>
                <td align="right">S/.'.number_format($data[0]['total_final'],2).'</td>
                <td align="right">S/.'.number_format($egreso,2).'</td>
                <td align="right">S/.'.number_format($sumaUtilidad,2).'</td>
                <td align="right">S/.'.number_format($sumaUtilidadSocio,2).'</td>
                <td align="right"><b>S/.'.number_format($sumaUtilidadSocio / $nroCuota,5).'</b></td>
         </tr>
        </table>';           
        if ($data[0]['observacion']!= '' ):
            $html .= '<h3>Observación </h3>';
            $html .= '<p>- '.$data[0]['observacion'].'</p>';        
        endif;        
        
        $mpdf->WriteHTML($html);
        $mpdf->AddPage();
        $sumaMonto = 0;
        $sumaPago = 0;
        $sumaSaldo = 0;
        // LISTADO DE PAGOS DEL SOCIO
        $html = '<h3>Detalle de Ingresos</h3> 
        <table id="td2" border="1" style="border-collapse:collapse" class="print-friendly">
            <tr>
                <th style="width:10%" >N° Cuota</th>
                <th style="width:20%">Fecha </th>
                <th style="width:20%">Ultimo Pago</th>
                <th style="width:15%">Monto</th>                
                <th style="width:15%">Pagado</th>   
                <th style="width:15%">Saldo</th> 
            </tr>
           ';
         foreach ($dataP as $value) {        
             $sumaMonto += $value['comision_venta'];
             $sumaSaldo += $value['comision_saldo'];
             $sumaPago += $value['comision_asignado'];
             
             $html .= '<tr>
                <td style="text-align:center">'.$value['nrocuota'].'</td>
                <td style="text-align:center">'.$value['fecha_creacion'].'</td>   
                <td style="text-align:center">'.$value['ultimo_pago'].'</td>  
                <td style="text-align:right">'.number_format($value['comision_venta'],5).'</td>
                <td style="text-align:right">S/.'.number_format($value['comision_asignado'],5).'</td>  
                <td style="text-align:right">S/.'.number_format($value['comision_saldo'],5).'</td> 
            </tr>';
        }
           $html .= '<tr>';
           $html .= '<td colspan="3" style="text-align:right;">&nbsp;</td>';        
           $html .= '<td style="text-align:right;font-weight:bold;">S/.'.number_format($sumaMonto,2).'</td>';   
           $html .= '<td style="text-align:right;font-weight:bold;">S/.'.number_format($sumaPago,2).'</td>';   
           $html .= '<td style="text-align:right;font-weight:bold;">S/.'.number_format($sumaSaldo,2).'</td>';   
           $html .= '</tr>';
           $html .='</table>';
        
        $mpdf->WriteHTML($html);
        $mpdf->AddPage(); 
        
        $html = '<h3>Detalle de Gastos</h3> 
        <table id="td2" border="1" style="border-collapse:collapse" class="print-friendly">
            <tr>
                <th style="width:5%" >N°</th>
                <th style="width:30%">Descripción del Concepto</th>
                <th style="width:10%">Cantidad</th>                
                <th style="width:10%">Importe</th>                
            </tr>
           ';
        $i = 1;
        $sumaGasto = 0;
        foreach ($dataG as $value) { 
            $sumaGasto = $sumaGasto + $value['costo_total'];
             $html .= '<tr>
                <td style="text-align:center">'.$i.'</td>
                <td>'.$value['descripcion'].'</td>                
                <td style="text-align:right">'.number_format($value['cantidad'],2).'</td>
                <td style="text-align:right">S/.'.number_format($value['costo_total'],2).'</td>                
            </tr>';
             $i++;
        }
        $html .= '<tr>';
        $html .= '<td colspan="3" style="text-align:right;">&nbsp;</td>';        
        $html .= '<td style="text-align:right;font-weight:bold;">S/.'.number_format($sumaGasto,2).'</td>';        
        $html .= '</tr>';
        $html .='</table>';  
        $mpdf->WriteHTML($html);  
        //return $html;
    }          
    
}

?>