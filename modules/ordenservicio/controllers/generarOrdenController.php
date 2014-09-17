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
            
            foreach ( $rResult as $aRow ){
                
                /*campo que maneja los estados, para el ejemplo aqui es ACTIVO, coloca tu campo*/
//                if($aRow['estado'] == 1){
//                    $estado = '<span class=\"label label-success\">'.LABEL_ACT.'</span>';
//                }else{
//                    $estado = '<span class=\"label label-danger\">'.LABEL_DES.'</span>';
//                }
                
                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_ordenservicio']);
                $idUser = Aes::en($aRow['id_usuario']);
                
                
                /*registros a mostrar*/
                $sOutput .= '["'.($num++).'","'.$aRow['orden_numero'].'","'.$aRow['cotizacion_numero'].'","'.$aRow['nombrecompleto'].'","'.$aRow['cliente'].'","'.$aRow['fecha'].'","'.number_format($aRow['monto_total'],2).'",';
                
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';
                 
                if($editar['permiso']){
                    $sOutput .= '<button type=\"button\" class=\"'.$editar['theme'].'\" title=\"'.$editar['accion'].'\" onclick=\"generarOrden.getFormEditOrden(this,\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"'.$editar['icono'].'\"></i>';
                    $sOutput .= '</button>';
                }
                if($generar['permiso']){
                    $sOutput .= '<button type=\"button\" class=\"'.$generar['theme'].'\" title=\"'.$generar['accion'].' '.GNOSE_2.'\" onclick=\"generarOrden.getFormCronograma(this,\''.$encryptReg.'\',\''.$aRow['monto_total'].'\',\''.$aRow['orden_numero'].'\')\">';
                    $sOutput .= '    <i class=\"'.$generar['icono'].'\"></i>';
                    $sOutput .= '</button>';
                }
                if($email['permiso']){
                    $sOutput .= '<button type=\"button\" class=\"'.$email['theme'].'\" title=\"'.GNOSE_16.'\" onclick=\"registrarVendedor.postAccesoVendedor(this,\'' . $idUser . '\',\'' . $aRow['nombrecompleto'] . '\',\'' . $aRow['email'] . '\')\">';
                    $sOutput .= '    <i class=\"'.$email['icono'].'\"></i>';
                    $sOutput .= '</button>';
                }
                /*solo si tiene compromisos se podra exportar contrato*/
                if($aRow['compromisos'] && $aRow['id_contrato'] != '0'){
                    if($pdf['permiso']){
                        $sOutput .= '<button type=\"button\" class=\"'.$pdf['theme'].'\" title=\"'.GNOSE_17.'\" onclick=\"generarOrden.postExportarContratoPDF(this,\'' . $encryptReg . '\')\">';
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
            
            foreach ( $rResult as $aRow ){
                
                
                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_compromisopago']);
                
                /*registros a mostrar*/
                $sOutput .= '["'.$aRow['numero_cuota'].'","'.number_format($aRow['monto_pago'], 2).'","'.$aRow['fechapago'].'",';
                
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';
                 
                if($eliminar['permiso']){
                    if($aRow['estado'] == 'E'){ #solo se eliminan los que estan en estdo E
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
        $c = 'contrato_'.Obj::run()->generarOrdenModel->_idOrden.'.pdf';
        
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
                                <td width="33%" style="text-align: right; ">SEVEND.pe</td>
                             </tr></table>');
                
        $html = $this->getHtmlContrato();         

        $mpdf->WriteHTML($html);
        $mpdf->Output($ar,'F');
        
        $data = array('result'=>1,'archivo'=>$c);
        echo json_encode($data);
    }
    
    private function getHtmlContrato(){
        $contrato = Obj::run()->generarOrdenModel->getContrato();
        
        $cronograma = Obj::run()->generarOrdenModel->getCronograma();
        $html = $contrato['cuerpo_contrato'];
        
        $cro = '<table style="width:100%" border="1">'
                . '<tr>'
                . '<th style="width:3%">'.GNOSE_18.'</th>'
                . '<th>'.GNOSE_18.'</th>'
                . '<th>'.GNOSE_18.'</th>'
                . '</tr>';
        foreach ($cronograma as $value) {
            $cro .= '<tr>';
            $cro .= '    <td style="text-align:center">'.$value['numero_cuota'].'</td>';
            $cro .= '    <td style="text-align:right">'.number_format($value['monto_pago'],2).'</td>';
            $cro .= '    <td style="text-align:center">'.$value['fechapro'].'</td>';
            $cro .= '</tr>';
        }
        $cro .= '</table>';
    
        $html = str_replace('{{CLIENTE_EMPRESA}}',$contrato['cliente'], $html);
        $html = str_replace('{{CLIENTE_DIRECCION}}',$contrato['direccion'], $html);
        $html = str_replace('{{CLIENTE_RUC}}','RUC '.$contrato['numerodocumento'], $html);
        $html = str_replace('{{REPRESENTANTE}}',$contrato['representante'], $html);
        $html = str_replace('{{REPRESENTANTE_DNI}}',$contrato['docrepresentante'], $html);
        $html = str_replace('{{CONTRATO_CANTIDADITEM}}',$contrato['npaneles'], $html);
        $html = str_replace('{{CONTRATO_MONTO}}',  number_format($contrato['monto_venta'],2), $html);
        $html = str_replace('{{FOR_COMPROMISOPAGO}}',$cro, $html);
        
        $nl = new numerosALetras();
        $html = str_replace('{{CONTRATO_DINERO_EN_LETRAS}}',  $nl->convertir($contrato['monto_venta']), $html);
        
        
        return html_entity_decode($html);
    }
    
}

?>