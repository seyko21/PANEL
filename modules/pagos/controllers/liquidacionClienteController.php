<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 27-09-2014 07:09:56 
* Descripcion : liquidacionClienteController.php
* ---------------------------------------
*/    

class liquidacionClienteController extends Controller{

    public function __construct() {
        $this->loadModel("liquidacionCliente");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexLiquidacionCliente");
    }
    
    public function getGridLiquidacionCliente(){
        $exportarpdf   = Session::getPermiso('LICLEP');
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->liquidacionClienteModel->getLiquidacionCliente();
        
        $num = Obj::run()->liquidacionClienteModel->_iDisplayStart;
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
                $numOrden = Aes::en($aRow['orden_numero']);
                $idPersona = Aes::en($aRow['id_persona']);
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $axion = '"<div class=\"btn-group\">';
                 
                if($exportarpdf['permiso']){
                    $axion .= '<button type=\"button\" class=\"'.$exportarpdf['theme'].'\" title=\"'.$exportarpdf['accion'].'\" onclick=\"liquidacionCliente.postPDF(this,\'' . $encryptReg . '\',\'' . $numOrden . '\')\"> ';
                    $axion .= '    <i class=\"'.$exportarpdf['icono'].'\"></i>';
                    $axion .= '</button>';
                }
       
                $axion .= ' </div>" ';
                
                /*registros a mostrar*/
                $sOutput .= '["'.$aRow['orden_numero'].'","'.  Functions::cambiaf_a_normal($aRow['fecha_contrato']).'","<a href=\"javascript:;\" onclick=\"persona.getDatosPersonales(\''.$idPersona.'\');\">'.$aRow['cliente'].'</a>","S/.'.number_format($aRow['monto_total'],2).'","'.$estado.'",'.$axion.' ';

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
         
        $c = 'liquidacion_'.Obj::run()->liquidacionClienteModel->_numOrden.'.pdf';
        
        $ar = ROOT.'public'.DS.'files'.DS.$c;
               
        $mpdf = new mPDF('c');
        
        $mpdf->SetHTMLHeader('<img src="'.ROOT.'public'.DS.'img'.DS.'logotipo.png" width="137" height="68" />','',TRUE);
        $mpdf->SetHTMLFooter('<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold;"><tr>
                                <td width="33%"><span style="font-weight: bold;">{DATE j-m-Y}</span></td>
                                <td width="33%" align="center" style="font-weight: bold;">{PAGENO}/{nbpg}</td>
                                <td width="33%" style="text-align: right; ">'.LB_EMPRESA.'</td>
                             </tr></table>');
                
        $html = $this->getHtmlLiquidacion();         

        $mpdf->WriteHTML($html);
        $mpdf->Output($ar,'F');
        
        if($n != 'N'){
            $data = array('result'=>1,'archivo'=>$c);
            echo json_encode($data);
        }
    }       
        
    public function getHtmlLiquidacion(){
       $data = Obj::run()->liquidacionClienteModel->getRptDetalleOrden();
       $dataC = Obj::run()->liquidacionClienteModel->getRptDetalleCronograma();
        
      $html ='
        <style>           
           table,h2,h3,h4{font-family:Arial;} 
           table, table td, table th, p {font-size:12px;}
           table{width:100%;}
           #td2 th, .totales{color:#FFF;background:#901D78; height:25px; font-size:11px;}
           #td2 td{font-size:9px;height:25px;}                   
        </style>';
      
       switch($data[0]['estado']){
            case 'E': $estado = 'Emitido'; break;
            case 'P': $estado = 'Pago parcial'; break;
            case 'T': $estado = 'Pago total'; break;
            case 'F': $estado = 'Finalizado'; break;
            case 'A': $estado = 'Anulado'; break;
            case 'R': $estado = 'Renovado'; break;
        }
                        
        $html .='<table width="100%" border="0" cellpadding="5" cellspacing="3">
          <tr bgcolor="#901D78">
            <th colspan="6"><div align="center"><h2 style="color:#FFF;">Liquidacion de Venta </h2></div></th>
          </tr>
           <tr>
           <td width="13%"><h3><strong>N° OS:</strong></h3></td>
            <td width="26%">'.$data[0]['orden_numero'].'</td>            
            <td style ="width: 8%;text-align:right" ><strong>Fecha :</strong></td>
            <td style ="width:15%;text-align:left" >'.$data[0]['fecha'].'</td>
            <td style ="width:15%;text-align:right" ><strong>Estado :</strong></td>
            <td style ="width:15%;text-align:left">'.$estado.'</td>                
          </tr>
          <tr>
            <td><strong>Cliente:</strong></td>
            <td colspan="5">'.($data[0]['ruc']==''?'':$data[0]['ruc'].' - ').$data[0]['cliente'].'</td>            
          </tr>
          <tr>      
            <td><strong>Representante:</strong></td>
            <td colspan="5">'.($data[0]['dni']==''?'':$data[0]['dni'].' - ').$data[0]['representante'].'</td>
          </tr>
          <tr>
            <td><strong>Dirección:</strong></td>
            <td colspan="5">'.$data[0]['direccion'].'</td>            
          </tr> 
           <tr>
           <td width="13%"><strong>Tiempo :</strong></td>
            <td width="26%">'.Functions::convertirDiaMes($data[0]['cantidad_mes']).' ('. number_format($data[0]['cantidad_mes'],1).')</td>
            <td width="8%"><strong>Oferta :</strong></td>
            <td width="15%">'.$data[0]['dias_oferta'].' días</td>
         
          </tr>
        
        </table> ';
        
         $html .= '<h3>Detalle del Servicio</h3> 
        <table id="td2" border="1" style="border-collapse:collapse">
            <tr>
                <th style="width:5%" >Item</th>                
                <th style="width:9%" >Código</th>
                <th style="width:10%">Elemento</th>
                <th style="width:30%">Ubicación</th>
                <th style="width:5%">Instalado</th>
                <th style="width:5%">Retiro</th>
                <th style="width:10%">Alquiler</th>
                <th style="width:10%">Producción</th>
                <th style="width:10%">Total</th>
                
            </tr>';
        // Listado del servicio 
        foreach ($data as $value) {
            
            if($value['incluyeigv'] == '1'){
                $icl = 'Si';
                $precio = number_format($value['precio_incigv'] * $value['cantidad_mes'] ,3);
                $produccion = number_format($value['produccion_incigv'],3);
                $importe = number_format($value['importe_incigv'],3);
            }else{
                $icl = 'No';
                $precio = number_format($value['precio'] * $value['cantidad_mes'],2);
                $produccion = number_format($value['costo_produccion'],2);
                $importe = number_format($value['importe'],2);
            }  

            $html .= '<tr>
                <td style="text-align:center">'.$value['item'].'</td>
                <td style="text-align:center">'.$value['codigo'].'</td>
                <td>'.$value['elemento'].'</td>
                <td>'.$value['producto'].' - '.number_format($value['dimension_ancho'],1).' x '.number_format($value['dimension_alto'],1).' mts'.'</td>
                <td style="text-align:center">'.$value['fecha_inicio'].'</td>
                <td style="text-align:center">'.$value['fecha_termino'].'</td>
                <td style="text-align:right">S/.'.$precio.'</td>
                <td style="text-align:right">S/.'.$produccion.'</td>
                <td style="text-align:right">S/.'.$importe.'</td>                
            </tr>';
        }    
        $html .= '<tr><td colspan="7"></td><td>Importe:</td><td style="text-align:right">S/.'.number_format($data[0]['monto_venta'],2).'</td></tr>';
        $html .= '<tr><td colspan="7"></td><td>IGV '.(number_format($data[0]['porcentaje_impuesto']*100)).'%:</td><td style="text-align:right">S/.'.number_format($data[0]['monto_impuesto'],2).'</td></tr>';
        $html .= '<tr><td colspan="8"></td><td class="totales" style="text-align:right; font-weight:bold;">S/.'.number_format($data[0]['total'],2).'</td></tr>';
        
        $html .='</table>';
        
        // Listado de Cronograma de Pagos
        $html .= '<h3>Cronograma de Pago</h3> 
        <table id="td2" border="1" style="border-collapse:collapse">
            <tr>
                <th style="width:10%">N° Cuota</th>                
                <th style="width:12%">Fch. Programada</th>
                <th style="width:12%">Fch. Pago</th>
                <th style="width:10%">Mora</th>
                <th style="width:15%">Monto</th>
                <th style="width:10%">Estado</th>
                <th style="width:20%">Observación</th>                                
            </tr>';
        // Listado del servicio 
        $sumaPagoSuspendido = 0;
        foreach ($dataC as $value) {
             switch($value['estado']){
                case 'E': $est = 'Emitido'; break;
                case 'P': $est = 'Pagado'; break;
              
             }
             $obs = '-';
             $pago = 'S/.'.number_format($value['monto_pago'],2);
             
             if ($data[0]['estado'] == 'F'):
                 if ($value['fecha_pagoreal'] == ''):
                    $obs = 'DEUDA SUSPENDIDA';    
                    $pago = '<del>S/.'.number_format($value['monto_pago'],2).'</del>';
                    $sumaPagoSuspendido += $value['monto_pago'];
                 endif;
             else:
                 $obs = $value['observacion'];
             endif;
             //------
             if ($value['fecha_pagoreal']!= ''):
                $fpago = $value['fecha_pagoreal'];
             else:
                $fpago = '-';
             endif;
            $html .= '<tr>
                <td style="text-align:center">'.$value['numero_cuota'].'</td>
                <td style="text-align:center">'.$value['fecha_programada'].'</td>            
                <td style="text-align:center">'.$fpago.'</td>
                <td style="text-align:right">S/.'.number_format($value['costo_mora'],2).'</td>
                <td style="text-align:right">'.$pago.'</td> 
                <td style="text-align:center">'.$est.'</td>
                <td style="text-align:center">'.$obs.'</td>
            </tr>';
        }                    
        $html .='</table>';
        
        $html .= '<br /><h3>Resumen</h3><table id="td2" border="1" style="border-collapse:collapse">               
          <tr>
                <th style="width:30%;" >Valor Contrato</th>                                
                <th style="width:30%;" >A cuenta</th>
                <th style="width:30%;" >Saldo por pagar</th>
            </tr>
            <tr>                
                <td align="right" style="font-size:13px;" >S/.'.number_format($data[0]['total'],2).'</td>
                <td align="right" style="font-size:13px;" >S/.'.number_format($data[0]['pagado'],2).'</td>
                <td align="right" style="font-size:13px;" ><b>S/.'.number_format($data[0]['deuda'] - $sumaPagoSuspendido,2).'</b></td>
            </tr>
            </table>';
        
        if ($data[0]['observacion']!= '' ):
            $html .= '<h3>Observación </h3>';
            $html .= '<p>- '.$data[0]['observacion'].'</p>';        
        endif;  
        
        return $html;
    }    
    
}

?>