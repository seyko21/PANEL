<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 09-09-2014 06:09:13 
* Descripcion : generarOrdenController.php
* ---------------------------------------
*/    

class generarOrdenController extends Controller{

    public function __construct() {
        $this->loadModel("generarOrden");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexGenerarOrden");
    }
    
    public function getGridGenerarOrden(){
        $editar   = Session::getPermiso('ORSERED');
        $email    = Session::getPermiso('ORSEREE');
        $generar  = Session::getPermiso('ORSERGN');
        $pdf      = Session::getPermiso('ORSEREP');
       
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->generarOrdenModel->getGenerarOrden();
        
        $num = Obj::run()->generarOrdenModel->_iDisplayStart;
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
            
            foreach ( $rResult as $key=>$aRow ){
                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_ordenservicio']);
                $idUser = Aes::en($aRow['id_usuario']);
                $idPersona = Aes::en($aRow['id_persona']);
                
                /*solo se anulara las ordenes que estan en estado E, porque no debde tener ningun pago hecho*/
                $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" disabled=\"disabled\"  >';
                /*campo que maneja los estados, para el ejemplo aqui es ACTIVO, coloca tu campo*/
                switch($aRow['estado']){
                    case 'E':
                        $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" name=\"'.GNOSE.'chk_delete[]\" value=\"'.$encryptReg.'\"  >'; 
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
                
                $cronograma = $aRow['cronogramaTotal'];
                
                $c1 = $aRow['orden_numero'];
                $c2 = $aRow['cotizacion_numero'];
                $c3 = $aRow['cliente'];
                $c4 = $aRow['fecha'];
                $c5 = Functions::convertirDiaMes($aRow['meses_contrato']);
                
                if ($aRow['monto_total'] > $cronograma ):
                    $c6 = '<span class=\"badge bg-color-red\">S/.'.number_format($aRow['monto_total'],2).'</span>';    
                else:
                    $c6 = 'S/.'.number_format($aRow['monto_total'],2);
                endif;
                
                
                
                /*registros a mostrar*/
                $sOutput .= '["'.$chk.'","'.$c1.'","'.$c2.'","<a href=\"javascript:;\" onclick=\"persona.getDatosPersonales(\''.$idPersona.'\');\">'.$c3.'</a>","'.$c4.'","'.$c5.'","'.$c6.'","'.$estado.'",';
                
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';
                 
                if($editar['permiso']){
                    $sOutput .= '<button type=\"button\" class=\"'.$editar['theme'].'\" title=\"'.$editar['accion'].'\" onclick=\"generarOrden.getFormEditOrden(this,\''.$encryptReg.'\',\''.$aRow['estado'].'\')\">';
                    $sOutput .= '    <i class=\"'.$editar['icono'].'\"></i>';
                    $sOutput .= '</button>';
                }
                if($generar['permiso'] && $aRow['estado'] <> 'A'){
                    $sOutput .= '<button type=\"button\" class=\"'.$generar['theme'].'\" title=\"'.$generar['accion'].' '.GNOSE_2.'\" onclick=\"generarOrden.getFormCronograma(this,\''.$encryptReg.'\',\''.$aRow['monto_total'].'\',\''.$aRow['orden_numero'].'\')\">';
                    $sOutput .= '    <i class=\"'.$generar['icono'].'\"></i>';
                    $sOutput .= '</button>';
                }
                /*solo si tiene compromisos se podra exportar contrato*/
                if($aRow['compromisos'] && $aRow['id_contrato'] != '0' && $cronograma >= $aRow['monto_total']){
                    if($email['permiso']){
                        $sOutput .= '<button type=\"button\" class=\"'.$email['theme'].'\" title=\"'.GNOSE_16.'\" onclick=\"registrarVendedor.postAccesoVendedor(this,\'' . $idUser . '\',\'' . $aRow['nombrecompleto'] . '\',\'' . $aRow['email'] . '\')\">';
                        $sOutput .= '    <i class=\"'.$email['icono'].'\"></i>';
                        $sOutput .= '</button>';
                    }
                    if($pdf['permiso']){
                        $sOutput .= '<button type=\"button\" class=\"'.$pdf['theme'].'\" title=\"'.GNOSE_17.'\" onclick=\"generarOrden.postExportarContratoPDF(this,\'' . $encryptReg . '\',\'' . $aRow['orden_numero'] . '\')\">';
                        $sOutput .= '    <i class=\"'.$pdf['icono'].'\"></i>';
                        $sOutput .= '</button>';
                    }
                }
                $sOutput .= '</div>"';
                
                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }
    
    public function getFormCronograma(){
        Obj::run()->View->render("formCronograma");
    }
    
    public function getFormEditOrden(){
        Obj::run()->View->render("formEditOrden");
    }
    
    public function postCuota(){
        $data = Obj::run()->generarOrdenModel->insertCuota();
        
        echo json_encode($data);
    }
    
    public function getGridCuotas(){
        $eliminar   = Session::getPermiso('ORSERDE');
       
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->generarOrdenModel->getGridCuotas();
        
        if(!isset($rResult['error'])){  
            $iTotal         = isset($rResult[0]['total'])?$rResult[0]['total']:0;
            
            $sOutput = '{';
            $sOutput .= '"sEcho": '.intval($sEcho).', ';
            $sOutput .= '"iTotalRecords": '.$iTotal.', ';
            $sOutput .= '"iTotalDisplayRecords": '.$iTotal.', ';
            $sOutput .= '"aaData": [ ';     
            
            $noDelete = 0;
            foreach ( $rResult as $aRow ){
                
                switch($aRow['estado']){
                    case 'E':
                        $estado = '<span class=\"label label-default\" style=\"text-align:center;color:#fff\">'.CROPA_2.'</span>';
                        break;
                    case 'P':
                        $noDelete = 1;
                        $estado = '<span class=\"label label-success\" style=\"text-align:center;color:#fff\">'.CROPA_3.'</span>';
                        break;
                    case 'R':
                        $estado = '<span class=\"label label-warning\" style=\"text-align:center;color:#fff\">'.CROPA_4.'</span>';
                        break;
                    default:
                        $estado = '';
                        break;
                }
                
                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_compromisopago']);
                
                /*registros a mostrar*/
                $sOutput .= '["'.$aRow['numero_cuota'].'","'.number_format($aRow['monto_pago'], 2).'","'.$aRow['fechapago'].'","'.number_format($aRow['costo_mora'],2).'","'.$estado.'",';
                
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';
                 
                if($eliminar['permiso']){
                    if($aRow['estado'] == 'E' && $noDelete == 0){ #solo se eliminan los que estan en estdo E, si hay un solo pago q esta pagado se bloquea eliminar
                        $sOutput .= '<button type=\"button\" class=\"'.$eliminar['theme'].'\" title=\"'.$eliminar['accion'].'\" onclick=\"generarOrden.postDeleteCuota(this,\''.$encryptReg.'\')\">';
                        $sOutput .= '    <i class=\"'.$eliminar['icono'].'\"></i>';
                        $sOutput .= '</button>';
                    }else{
                        $sOutput .= '<button type=\"button\" class=\"'.$eliminar['theme'].'\" title=\"'.$eliminar['accion'].'\" onclick=\"generarOrden.postDeleteCuotaNo()\">';
                        $sOutput .= '    <i class=\"'.$eliminar['icono'].'\"></i>';
                        $sOutput .= '</button>';
                    }
                }
                
                $sOutput .= '</div>"';
                
                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }
    
    public function postEditOrden(){
        $data = Obj::run()->generarOrdenModel->editOrden();
        
        echo json_encode($data);
    }
    
    public function findOrden(){
        $data = Obj::run()->generarOrdenModel->findOrden();
        
        return $data;
    }
    
    public function postDeleteCuota(){
        $data = Obj::run()->generarOrdenModel->postDeleteCuota();
        
        echo json_encode($data);
    }
    
    public function getContratos(){
        $data = Obj::run()->generarOrdenModel->getContratos();
        
        return $data;
    }
    
    public function postExportarContratoPDF(){
        $c = 'contrato_'.Obj::run()->generarOrdenModel->_numOrden.'.pdf';
        
        $ar = ROOT.'public'.DS.'files'.DS.$c;
               
        $mpdf = new mPDF('c');
        
        $mpdf->SetHTMLHeader('<div style="margin:0 auto; width:137px;"><img src="'.ROOT.'public'.DS.'img'.DS.'logotipo.png" width="137" height="68" /></div>','',TRUE);
        $mpdf->SetHTMLFooter('<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold;"><tr>
                                <td width="33%"><span style="font-weight: bold;">Contrato</span></td>
                                <td width="33%" align="center" style="font-weight: bold;">{PAGENO}/{nbpg}</td>
                                <td width="33%" style="text-align: right; ">'.LB_EMPRESA.'</td>
                             </tr></table>');
                
        $html = $this->getHtmlContrato();         

        $mpdf->WriteHTML($html);
        $mpdf->Output($ar,'F');
        
        $data = array('result'=>1,'archivo'=>$c);
        echo json_encode($data);
    }
    
    private function getHtmlContrato(){
        $contrato = Obj::run()->generarOrdenModel->getContrato();        
        $cronograma = Obj::run()->generarOrdenModel->getCronogramaContrato();
        $caratula = Obj::run()->generarOrdenModel->getCaratula();

        $html = str_replace('\\','',$contrato['cuerpo_contrato'] );
        $html = htmlspecialchars_decode($html,ENT_QUOTES);
        //Panel: Caratula
        $panel = '<table border="0" style="width:100%; font-family:Arial; font-size:12px;"> '
                . '<tr>'
                . '<th style="width:7%;border-bottom:solid 1px #000">'.LABEL_A37.'</th>'  
                . '<th style="width:15%;border-bottom:solid 1px #000">'.LABEL_A27.'</th>'
                . '<th style="width:45%;border-bottom:solid 1px #000">'.LABEL_A38.'</th>'                  
                . '<th style="width:12%;border-bottom:solid 1px #000">'.LABEL_A44.'</th>' 
                . '<th style="width:8%;border-bottom:solid 1px #000">'.LABEL_A45.'</th>' 
                . '</tr>';
        
        $impuesto = number_format($caratula[0]['monto_impuesto'],2);
        
        foreach ($caratula as $v) {
            
            if ($value['incluyeigv'] == '1'){
                $precio = number_format($v['precio'],2);    
                $produccion = number_format($v['costo_produccion'],2);    
            }
            else{
                $precio = number_format($v['precio_incigv'],2);     
                $produccion = number_format($v['produccion_incigv'],2);    
            }
            
            if($produccion > 0){
                $produccion = 'S/.'.$produccion;
                $align = 'right';
            }else{
                $produccion = 'NO';
                $align = 'center';
            }
                        
            $panel .= '<tr>';
            $panel .=  '   <td style="text-align:center; font-size:11px;">'.$v['codigo'].'</td>';
            $panel .=  '   <td style="text-align:center; font-size:11px;">'.$v['elemento'].'</td>';
            $panel .= '    <td style="font-size:11px;">'.$v['ubicacion'].' - '.$v['medidas'].' Area: '.$v['dimesion_area'].' m<sup>2</sup></td>';            
            $panel .=  '   <td style="text-align:right; font-size:11px;">S/. '.$precio.'</td>';  
            $panel .=  '   <td style="text-align:'.$align.'; font-size:10px;">'.$produccion.'</td>';
            $panel .=  '</tr>';
        }
        $panel .= '</table>';
        $panel .= '<br />'
                . '<table style="width:80%;font-size:11px;" align="right" >'
                . '<tr>'
                . '<td style="text-align:right;width:10%"><b>Importe:</b></td>'
                . '<td style="text-align:right;width:15%">S/.'.number_format($caratula[0]['monto_venta'],2).'</td>'
                . '<td style="text-align:right;width:10%"><b>IGV '.(number_format($caratula[0]['porcentaje_impuesto']*100)).'%:</b></td>'
                . '<td style="text-align:right;width:15%">S/.'.number_format($caratula[0]['monto_impuesto'],2).'</td>'
                . '</tr>'
                . '</table>';
        
        
        //Cronograma
        $cro = '<table border="0" style="width:70%; font-family:Arial; font-size:12px;"> '
                . '<tr>'
                . '<th style="width:5%;border-bottom:solid 1px #000">'.GNOSE_18.'</th>'                
                . '<th style="border-bottom:solid 1px #000">'.GNOSE_20.'</th>'
                . '<th style="border-bottom:solid 1px #000">'.GNOSE_19.'</th>'
                . '</tr>';
        foreach ($cronograma as $value) {                       
            $cro .= '<tr>';
            $cro .= '    <td style="text-align:center">'.$value['numero_cuota'].'</td>';            
            $cro .= '    <td style="text-align:center">'.$value['fechapro'].'</td>';
            $cro .= '    <td style="text-align:right">S/. '.number_format($value['monto_pago'],2).'</td>';            
            $cro .= '</tr>';
        }
        $cro .= '</table>';
                                                
        $ruc = '';
        if($contrato['numerodocumento'] != ''){ $ruc = ' con RUC '.$contrato['numerodocumento']; }
                
        if ($contrato['flag_impuesto'] == '1'){
            $incluyeIGV = 'No incluye IGV';            
        }
        else{
            $incluyeIGV = 'Incluye IGV';
        }        
                
        $diaoferta = '';
         if ( $contrato['dias_oferta'] > 0){
            $diaoferta = 'La empresa '.LB_EMPRESA.' adiciona un plus promocional de '.$contrato['dias_oferta'].' días calendarios luego de haber finalizado la publicación.';
         }
         
        $html = str_replace('{{CLIENTE_EMPRESA}}',$contrato['cliente'], $html);
        $html = str_replace('{{CLIENTE_DIRECCION}}',$contrato['direccion'], $html);
        $html = str_replace('{{CLIENTE_RUC}}',$ruc, $html);
        $html = str_replace('{{REPRESENTANTE}}',$contrato['representante'], $html);
        $html = str_replace('{{REPRESENTANTE_DNI}}',$contrato['docrepresentante'], $html);
        $html = str_replace('{{CONTRATO_CANTIDADITEM}}',$contrato['npaneles'], $html);
        $html = str_replace('{{CONTRATO_MONTO}}',  number_format($contrato['monto_total'],2), $html);
        $html = str_replace('{{DIA_CONTRATO}}',$contrato['dia'], $html);
        $html = str_replace('{{MES_CONTRATO}}', ucwords(Functions::nombremes($contrato['mes'])), $html);
        $html = str_replace('{{ANIO_CONTRATO}}',$contrato['anio'], $html);
        $html = str_replace('{{INCLUYE_IGV}}',$incluyeIGV, $html);
        $html = str_replace('{{FOR_CARATULAS}}',$panel, $html);
        $html = str_replace('{{FOR_COMPROMISOPAGO}}',$cro, $html);
        $html = str_replace('{{CONTRATO_MESES}}',Functions::convertirDiaMes($contrato['meses_contrato']), $html);
        $html = str_replace('{{DIAS_OFERTA}}',$diaoferta, $html);
        
        $nl = new numerosALetras();
        $html = str_replace('{{CONTRATO_DINERO_EN_LETRAS}}',  $nl->convertir($contrato['monto_total']), $html);
        
        
        return $html;
    }
    
    public function postAnularOrdenAll(){
        $data = Obj::run()->generarOrdenModel->anularOrdenAll();
        
        echo json_encode($data);
    }
    
}

?>