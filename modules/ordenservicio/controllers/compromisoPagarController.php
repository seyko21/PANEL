<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 24-09-2014 00:09:39 
* Descripcion : compromisoPagarController.php
* ---------------------------------------
*/    

class compromisoPagarController extends Controller{

    public function __construct() {
        $this->loadModel(array('modulo'=>'ordenservicio','modelo'=>'compromisoPagar'));
    }
    
    public function index(){ 
        Obj::run()->View->render("indexCompromisoPagar");
    }
    
    public function getGridCompromisoPagar(){
        
        $exportarpdf   = Session::getPermiso('COPAGEP');
        $exportarexcel= Session::getPermiso('COPAGEX');
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->compromisoPagarModel->getCompromisoPagar();
        
        $num = Obj::run()->compromisoPagarModel->_iDisplayStart;
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
                
                 $encryptReg = Aes::en($aRow['id_compromisopago']);
                $idPersona = Aes::en($aRow['id_persona']);
                /*campo que maneja los estados, para el ejemplo aqui es ACTIVO, coloca tu campo*/
                switch ($aRow['estado']){
                        case 'E': #emitido
                            $estado = '<span class=\"label label-default\">' . CROPA_2 . '</span>';
                            break;
                        case 'P': #pagado
                            $estado = '<span class=\"label label-success\">' . CROPA_3 . '</span>';
                            break;
                        case 'R': #reprogramado
                            $estado = '<span class=\"label label-warning\">' . CROPA_4 . '</span>';
                            break;
                        default:
                            $estado = '';
                            break;
                 }
                 
                                
                $axion = '"<div class=\"btn-group\">';
                 
                if($exportarpdf['permiso']){
                    if ($aRow['estado'] == 'P'){
                        $axion .= '<button type=\"button\" class=\"'.$exportarpdf['theme'].'\" title=\"'.$exportarpdf['accion'].' pagos \" onclick=\"compromisoPagar.postPDF(this,\'' . $encryptReg . '\')\"> ';
                        $axion .= '    <i class=\"'.$exportarpdf['icono'].'\"></i>';
                        $axion .= '</button>';
                    }else{
                        $axion .= '<button type=\"button\" class=\"'.$exportarpdf['theme'].'\" title=\"'.$exportarpdf['accion'].'\" disabled=\"disabled\" > ';
                        $axion .= '    <i class=\"'.$exportarpdf['icono'].'\"></i>';
                        $axion .= '</button>';
                    }
                }
                if($exportarexcel['permiso']){
                    if ($aRow['estado'] == 'P'){
                        $axion .= '<button type=\"button\" class=\"'.$exportarexcel['theme'].'\" title=\"'.$exportarexcel['accion'].' pagos \" onclick=\"compromisoPagar.postExcel(this,\'' . $encryptReg . '\')\"> ';
                        $axion .= '    <i class=\"'.$exportarexcel['icono'].'\"></i>';
                        $axion .= '</button>';
                    }else{
                        $axion .= '<button type=\"button\" class=\"'.$exportarexcel['theme'].'\" title=\"'.$exportarexcel['accion'].'\" disabled=\"disabled\" > ';
                        $axion .= '    <i class=\"'.$exportarexcel['icono'].'\"></i>';
                        $axion .= '</button>';
                    }
                }
                $axion .= ' </div>" ';
                /*registros a mostrar*/
                $sOutput .= '["'.$aRow['orden_numero'].'","'.$aRow['numero_cuota'].'","'.$aRow['fecha_programada'].'","<a href=\"javascript:;\" onclick=\"persona.getDatosPersonales(\''.$idPersona.'\');\">'.$aRow['cliente'].' - '.$aRow['representante'].'</a>","'.number_format($aRow['mora'],2).'","'.number_format($aRow['monto_pago'],2).'","'.$estado.'",'.$axion.' ';

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
         
        $c = 'pagos_'.Obj::run()->compromisoPagarModel->_idCompromiso.'.pdf';
        
        $ar = ROOT.'public'.DS.'files'.DS.$c;
               
        $mpdf = new mPDF('c');

        $mpdf->mirrorMargins = 1;
        $mpdf->defaultheaderfontsize = 10; /* in pts */
        $mpdf->defaultheaderfontstyle = B; /* blank, B, I, or BI */
        $mpdf->defaultheaderline = 1; /* 1 to include line below header/above footer */
        $mpdf->defaultfooterfontsize = 12; /* in pts */
        $mpdf->defaultfooterfontstyle = B; /* blank, B, I, or BI */
        $mpdf->defaultfooterline = 1; /* 1 to include line below header/above footer */
        
        $mpdf->SetHTMLHeader('<img src="'.ROOT.'public'.DS.'img'.DS.'logotipo.png" width="137" height="68" />','',TRUE);
        $mpdf->SetHTMLFooter('<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold;"><tr>
                                <td width="33%"><span style="font-weight: bold;">{DATE j-m-Y}</span></td>
                                <td width="33%" align="center" style="font-weight: bold;">{PAGENO}/{nbpg}</td>
                                <td width="33%" style="text-align: right; ">'.LB_EMPRESA.'</td>
                             </tr></table>');
                
        $html = $this->getHtmlPagos();         

        $mpdf->WriteHTML($html);
        $mpdf->Output($ar,'F');
        
        if($n != 'N'){
            $data = array('result'=>1,'archivo'=>$c);
            echo json_encode($data);
        }
    }
    
    public function postExcel(){
        $c = 'pagos_'.Obj::run()->compromisoPagarModel->_idCompromiso.'.xls';
        
        $ar = ROOT.'public'.DS.'files'.DS.$c;
        
        $html = $this->getHtmlPagos();
        
        $f=fopen($ar,'wb');
        if(!$f){$data = array('result'=>2);}
        fwrite($f,  utf8_decode($html));
        fclose($f);
                        
        $data = array('result'=>1,'archivo'=>$c);
        echo json_encode($data);
    }
        
    public function getHtmlPagos(){
        $data = Obj::run()->compromisoPagarModel->getPagos();
        $html ='
        <style>           
           table,h2,h3,h4{font-family:Arial;} 
           table, table td, table th, p {font-size:12px;}
           table{width:100%;}
           #td2 th, .totales{color:#FFF;background:#901D78; height:25px; font-size:12px;}
           #td2 td{font-size:11px;height:25px;}           
        </style>';
        
        switch($data[0]['estado']){
            case 'P': $estado = 'Pagado'; break;
            case 'A': $estado = 'Anulado'; break;
        }
        
        switch($data[0]['forma_pago']){
            case 'E': $formaPago = 'Efectivo'; break;
            case 'B': $formaPago = 'Cuenta Bancaria'; break;
        }
        switch($data[0]['tipo_documento']){
            case 'F': $tipodoc = 'Factura'; break;
            case 'B': $tipodoc = 'Boleta'; break;
            case 'R': $tipodoc = 'Recibo'; break;
        }
        
        $html .='<table width="100%" border="0" cellpadding="5" cellspacing="3">
          <tr bgcolor="#901D78">
            <th colspan="6"><div align="center"><h2 style="color:#FFF;">Pago de Cliente</h2></div></th>
          </tr>
           <tr>
           <td width="13%"><h3><strong>Cuota N°:</strong></h3></td>
            <td width="26%">'.$data[0]['numero_cuota'].'</td>
            <td width="8%"><strong>Estado :</strong></td>
            <td width="15%">'.$estado.'</td>
            <td width="15%"><strong>Programado :</strong></td>
            <td width="15%;text-align:left" >'.Functions::cambiaf_a_normal($data[0]['fecha_programada']).'</td>
          </tr>
          <tr>
            <td><strong>Cliente:</strong></td>
            <td colspan="5">'.($data[0]['ruccliente']==''?'':$data[0]['ruccliente'].' - ').$data[0]['razonsocial'].'</td>            
          </tr>
          <tr>
            <td><strong>Representante:</strong></td>
            <td colspan="5">'.($data[0]['numerodocumento']==''?'':$data[0]['numerodocumento'].' - ').$data[0]['nombrecompleto'].'</td>
          </tr>
          <tr>
            <td><strong>N° Documento:</strong></td>
            <td>'.$data[0]['numero_documento'].'</td>
            <td><strong>N° Serie:</strong></td>
            <td>'.$data[0]['serie_documento'].'</td>
            <td><strong>Fecha Pago:</strong></td>
            <td>'.Functions::cambiaf_a_normal($data[0]['fecha_pago']).'</td>
          </tr>          
          <tr>
            <td><strong>Forma Pago:</strong></td>
            <td>'.$formaPago.'</td>
            <td><strong>Tipo Doc.:</strong></td>
            <td>'.$tipodoc.'</td>
            <td><strong>Mora:</strong></td>
            <td>'.number_format($data[0]['costo_mora'],2).'</td>                            
          </tr>
        </table> ';
        
         $html .= '<h2>Detalle de Pago</h2><table id="td2" border="1" style="border-collapse:collapse">            
            <tr>
                <th style="width:25%;" >Subtotal</th>
                <th style="width:25%;" >Impuesto '.number_format($data[0]['porcentaje_igv']*100).'%</th>
                <th style="width:25%;" >Total</th>
            </tr>
            <tr>
                <td align="right">S/.'.number_format($data[0]['monto_subtotal'],2).'</td>
                <td align="right">S/.'.number_format($data[0]['monto_impuesto'],2).'</td>
                <td align="right">S/.'.number_format($data[0]['monto_pago'],2).'</td>
            </tr>
            </table>';
                    
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