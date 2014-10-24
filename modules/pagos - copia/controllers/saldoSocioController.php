<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 26-09-2014 23:09:38 
* Descripcion : saldoSocioController.php
* ---------------------------------------
*/    

class saldoSocioController extends Controller{

    public function __construct() {
        $this->loadModel("saldoSocio");
        $this->loadController(array('modulo'=>'pagos','controller'=>'saldoVendedor'));                   
    }
    
    public function index(){ 
        Obj::run()->View->render("indexSaldoSocio");
    }    
    public function getConsulta(){ 
        Obj::run()->View->render('consultarPagoSaldo');
    }       
    public function getGridSaldoSocio(){
        $consultar   = Session::getPermiso('SASOCCC');
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->saldoSocioModel->getSaldoSocio();
        
        $num = Obj::run()->saldoSocioModel->_iDisplayStart;
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
                $encryptReg = Aes::en($aRow['id_comision']);
                $idPersona = Aes::en($aRow['id_persona']);
                
                $num = Obj::run()->saldoSocioModel->_iDisplayStart;
                if($num >= 10){
                    $num++;
                }else{
                    $num = 1;
                }
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */

                $c1 = $aRow['id_comision'];
                $c2 = $aRow['orden_numero'];
                $c3 = '<a href=\"javascript:;\" onclick=\"persona.getDatosPersonales(\''.$idPersona.'\');\">'.$aRow['nombrecompleto'].'</a>';
                $c4 = $aRow['fecha'];
                $c5 = (number_format($aRow['porcentaje_comision']*100)).' %';
                $c6 = number_format($aRow['comision_venta'],2);
                $c7 = number_format($aRow['comision_asignado'],2);
                $c8 = number_format($aRow['comision_saldo'],2);
                $c9 = Functions::convertirDiaMes($aRow['meses_contrato']);
                $c10 =$aRow['numero_cuota'];

                $axion = '"<div class=\"btn-group\">';
                if($consultar['permiso']){
                    if ($aRow['comision_asignado'] > 0 ){
                        $axion .= '<button type=\"button\" class=\"'.$consultar['theme'].'\" title=\"'.$consultar['accion'].'\" onclick=\"saldoSocio.getConsulta(this,\''.$encryptReg.'\',\''.$c3.'\')\">';
                        $axion .= '    <i class=\"'.$consultar['icono'].'\"></i>';
                        $axion .= '</button>';
                    }else{
                        $axion .= '<button type=\"button\" class=\"'.$consultar['theme'].'\" title=\"'.$consultar['accion'].'\" disabled >';
                        $axion .= '    <i class=\"'.$consultar['icono'].'\"></i>';
                        $axion .= '</button>';
                    }
                }
                $axion .= ' </div>" ';                
                
                /*registros a mostrar*/
                $sOutput .= '["'.$c10.'","'.$c2.'","'.$c3.'","'.$c4.'","'.$c5.'","'.$c9.'","'.$c6.'","'.$c7.'","'.$c8.'",'.$axion.' ';

                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }
    
    public function getGridPagoSocio(){
        $rResult = Obj::run()->saldoSocioModel->gridPagoSocio();
        return $rResult;
    }
        
    
    public function gridPagoSocio(){
        $exportarpdf   = Session::getPermiso('SASOCEP');
        $eliminar  = Session::getPermiso('GPASODE');
        
        $sEcho  =   $this->post('sEcho');
        
        $rResult = $this->getGridPagoSocio();
        
        if(!isset($rResult['error'])){  
            $iTotal         = isset($rResult[0]['total'])?$rResult[0]['total']:0;
            
            $sOutput = '{';
            $sOutput .= '"sEcho": '.intval($sEcho).', ';
            $sOutput .= '"iTotalRecords": '.$iTotal.', ';
            $sOutput .= '"iTotalDisplayRecords": '.$iTotal.', ';
            $sOutput .= '"aaData": [ ';
            foreach ( $rResult as $key=>$aRow ){
                /*datos de manera manual*/
                
                $encryptReg = Aes::en($aRow['id_boleta']);
                
                $c1 = $aRow['boleta_numero'];
                $c2 = $aRow['fecha'];
                $c3 = $aRow['recibo_numero'];
                $c4 = $aRow['recibo_serie'];
                $c8 = number_format($aRow['monto_neto'],2);
                
                $axion = '"<div class=\"btn-group\">';
                if($exportarpdf['permiso']){
                    $axion .= '<button type=\"button\" class=\"'.$exportarpdf['theme'].'\" title=\"'.$exportarpdf['accion'].' pagos \" onclick=\"saldoSocio.postPDF(this,\'' . $encryptReg . '\',\'' . $c1 . '\')\"> ';
                    $axion .= '    <i class=\"'.$exportarpdf['icono'].'\"></i>';
                    $axion .= '</button>';
                }
                if($eliminar['permiso']){
                    $axion .= '<button type=\"button\" class=\"'.$eliminar['theme'].'\" title=\"'.$eliminar['accion'].'\" onclick=\"pagoSocio.postDeletePago(\''.$encryptReg.'\')\">';
                    $axion .= '    <i class=\"'.$eliminar['icono'].'\"></i>';
                    $axion .= '</button>';
                }
                $axion .= ' </div>" ';
                
                $sOutput .= '["'.$c1.'","'.$c2.'","'.$c3.'","'.$c4.'","'.$c8.'",'.$axion.' ';
                               
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
    
    public function postPDF($n=''){
         
        $c = 'boleta_'.Obj::run()->saldoSocioModel->_numBoleta.'.pdf';
        
        $ar = ROOT.'public'.DS.'files'.DS.$c;
               
        $mpdf = new mPDF('c');
        
        $mpdf->SetHTMLHeader('<img src="'.ROOT.'public'.DS.'img'.DS.'logotipo.png" width="137" height="68" />','',TRUE);
        $mpdf->SetHTMLFooter('<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold;"><tr>
                                <td width="33%"><span style="font-weight: bold;">{DATE j-m-Y}</span></td>
                                <td width="33%" align="center" style="font-weight: bold;">{PAGENO}/{nbpg}</td>
                                <td width="33%" style="text-align: right; ">SEVEND.pe</td>
                             </tr></table>');
                
        $html = $this->getHtmlBoleta();         

        $mpdf->WriteHTML($html);
        $mpdf->Output($ar,'F');
        
        if($n != 'N'){
            $data = array('result'=>1,'archivo'=>$c);
            echo json_encode($data);
        }
    }       
        
    public function getHtmlBoleta(){
      $data = Obj::run()->saldoVendedorController->getBoleta();
        
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
            <th colspan="6"><div align="center"><h2 style="color:#FFF;">Recibo de pago</h2></div></th>
          </tr>
           <tr>
            <td width="13%"><h3><strong>N°:</strong></h3></td>
            <td width="26%">'.$data['boleta_periodo'].'-'.$data['boleta_numero'].'</td>
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
          </tr>        
          <tr>
            <td><strong>Pago recibido:</strong></td>
            <td colspan="5">'.$td.'</td>            
          </tr>   
        </table> ';
        
         $html .= '<h2>Detalle de Pago</h2><table id="td2" border="1" style="border-collapse:collapse">            
            <tr>
                <th style="width:50%;" >Descripción del Concepto</th>                
                <th style="width:25%;" >Total Neto</th>
            </tr>
            <tr>
                <td >Utilidad percibida de la Orden de Servicio N°: <b>'.$data['orden_numero'].'</b></td>
                <td align="right">S/.'.number_format($data['monto_neto'],2).'</td>
            </tr>
            </table>';
                    
        return $html;
    }        
  
}

?>