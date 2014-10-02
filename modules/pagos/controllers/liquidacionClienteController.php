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
                }
                
                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_ordenservicio']);
                
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $axion = '"<div class=\"btn-group\">';
                 
                if($exportarpdf['permiso']){
                    $axion .= '<button type=\"button\" class=\"'.$exportarpdf['theme'].'\" title=\"'.$exportarpdf['accion'].'\" onclick=\"liquidacionCliente.postPDF(this,\'' . $encryptReg . '\')\"> ';
                    $axion .= '    <i class=\"'.$exportarpdf['icono'].'\"></i>';
                    $axion .= '</button>';
                }
       
                $axion .= ' </div>" ';
                
                /*registros a mostrar*/
                $sOutput .= '["'.$aRow['orden_numero'].'","'.  Functions::cambiaf_a_normal($aRow['fecha_contrato']).'","'.$aRow['cliente'].' - '.$aRow['representante'].'","S/.'.number_format($aRow['monto_total'],2).'","'.$estado.'",'.$axion.' ';

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
         
        $c = 'liquidacion_'.Obj::run()->liquidacionClienteModel->_idOrden.'.pdf';
        
        $ar = ROOT.'public'.DS.'files'.DS.$c;
               
        $mpdf = new mPDF('c', 'A4-L');
        
        $mpdf->SetHTMLHeader('<img src="'.ROOT.'public'.DS.'img'.DS.'logotipo.png" width="137" height="68" />','',TRUE);
        $mpdf->SetHTMLFooter('<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold;"><tr>
                                <td width="33%"><span style="font-weight: bold;">{DATE j-m-Y}</span></td>
                                <td width="33%" align="center" style="font-weight: bold;">{PAGENO}/{nbpg}</td>
                                <td width="33%" style="text-align: right; ">SEVEND.pe</td>
                             </tr></table>');
                
       // $html = $this->getHtmlLiquidacion();         

        $mpdf->WriteHTML($html);
        $mpdf->Output($ar,'F');
        
        if($n != 'N'){
            $data = array('result'=>1,'archivo'=>$c);
            echo json_encode($data);
        }
    }       
        
    public function getHtmlLiquidacion(){
        $data = Obj::run()->saldoVendedorModel->getBoleta();
        
      $html ='
        <style>           
           table,h2,h3,h4{font-family:Arial;} 
           table, table td, table th, p {font-size:12px;}
           table{width:100%;}
           #td2 th, .totales{color:#FFF;background:#901D78; height:25px; font-size:12px;}
           #td2 td{font-size:11px;height:25px;}           
        </style>';
        
        switch($data['estado']){
            case 'R': $estado = 'Pagado'; break;
            case 'A': $estado = 'Anulado'; break;
        }
         $nl = new numerosALetras();
         $td = $nl->convertir($data['monto_neto']);
                
        $html .='<table width="100%" border="0" cellpadding="5" cellspacing="3">
          <tr bgcolor="#901D78">
            <th colspan="6"><div align="center"><h2 style="color:#FFF;">Boleta de pago</h2></div></th>
          </tr>
           <tr>
           <td width="13%"><h3><strong>N°:</strong></h3></td>
            <td width="26%">'.$data['boleta_numero'].'</td>
            <td width="8%"><strong>Estado :</strong></td>
            <td width="15%">'.$estado.'</td>
            <td width="15%"><strong>Fecha :</strong></td>
            <td width="15%;text-align:left" >'.$data['fecha'].'</td>
          </tr>
          <tr>
            <td><strong>Beneficiario:</strong></td>
            <td colspan="5">'.($data['ruc']==''?'':$data['ruc'].' - ').$data['benefactor'].'</td>            
          </tr>   
          <tr>
            <td><strong>Dirección:</strong></td>
            <td colspan="5">'.$data['direccion'].'</td>            
          </tr>   
          <tr>
            <td><strong>N° Documento:</strong></td>
            <td>'.$data['recibo_numero'].'</td>
            <td><strong>N° Serie:</strong></td>
            <td>'.$data['recibo_serie'].'</td>
            <td><strong>Exonerado:</strong></td>
            <td>'.$data['exonerado'].'</td>
          </tr>        
          <tr>
            <td><strong>Pago recibido:</strong></td>
            <td colspan="5">'.$td.'</td>            
          </tr>   
        </table> ';
        
         $html .= '<h2>Detalle de Pago</h2><table id="td2" border="1" style="border-collapse:collapse">            
            <tr>
                <th style="width:25%;" >Total Honorario</th>
                <th style="width:25%;" >Retencion ('.number_format($data['impuesto_ir']*100).'%) IR</th>
                <th style="width:25%;" >Total Neto</th>
            </tr>
            <tr>
                <td align="right">S/.'.number_format($data['monto_total'],2).'</td>
                <td align="right">S/.'.number_format($data['monto_retencion'],2).'</td>
                <td align="right">S/.'.number_format($data['monto_neto'],2).'</td>
            </tr>
            </table>';
                    
        return $html;
    }    
    
}

?>