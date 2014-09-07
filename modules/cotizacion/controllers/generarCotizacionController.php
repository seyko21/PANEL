<?php
/*
* --------------------------------------
* fecha: 14-08-2014 05:08:10 
* Descripcion : generarCotizacionController.php
* --------------------------------------
*/    

class generarCotizacionController extends Controller{

    public function __construct() {
        $this->loadModel('generarCotizacion');
    }
    
    public function index(){ 
        Obj::run()->View->render('indexGenerarCotizacion');
    }
    
    public function getGridCotizacion(){
        $enviaremail   = Session::getPermiso('GNCOTEE');
        $exportarpdf   = Session::getPermiso('GNCOTEP');
        $exportarexcel = Session::getPermiso('GNCOTEX');
        $clonar         = Session::getPermiso('GNCOTCL');
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->generarCotizacionModel->getGridCotizacion();
        
        if(!isset($rResult['error'])){  
            $iTotal         = isset($rResult[0]['totalg'])?$rResult[0]['totalg']:0;
            
            $sOutput = '{';
            $sOutput .= '"sEcho": '.intval($sEcho).', ';
            $sOutput .= '"iTotalRecords": '.$iTotal.', ';
            $sOutput .= '"iTotalDisplayRecords": '.$iTotal.', ';
            $sOutput .= '"aaData": [ ';
            foreach ( $rResult as $key=>$aRow ){
                
                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_cotizacion']);
                
                $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" name=\"'.T8.'chk_delete[]\" value=\"'.$encryptReg.'\">';
                /*datos de manera manual*/
                $sOutput .= '["'.$chk.'","'.$aRow['cotizacion_numero'].'","'.$aRow['nombrecompleto'].'","'.$aRow['fechacoti'].'","'.$aRow['meses_contrato'].'","'.Functions::cambiaf_a_normal($aRow['vencimiento']).'","'.  number_format($aRow['total'],2).'", ';

                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar fa-mail-forward
                 */
                $sOutput .= '"<div class=\"btn-group\">';
                
                if($exportarpdf['permiso']){
                    $sOutput .= '<button type=\"button\" class=\"btn txt-color-white bg-color-blueDark btn-xs\" title=\"'.$exportarpdf['accion'].'\" onclick=\"generarCotizacion.postPDF(this,\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"fa fa-file-pdf-o fa-lg\"></i>';
                    $sOutput .= '</button>';
                }
                if($exportarexcel['permiso']){
                    $sOutput .= '<button type=\"button\" class=\"btn btn-success btn-xs\" title=\"'.$exportarexcel['accion'].'\" onclick=\"generarCotizacion.postExcel(this,\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"fa fa-file-excel-o fa-lg\"></i>';
                    $sOutput .= '</button>';
                }
                if($clonar['permiso']){
                    $sOutput .= '<button type=\"button\" class=\"btn bg-color-purple txt-color-white btn-xs\" title=\"'.$clonar['accion'].'\" onclick=\"generarCotizacion.getClonar(\''.$aRow['cotizacion_numero'].'\',\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"fa fa-copy fa-lg\"></i>';
                    $sOutput .= '</button>';
                }
                if($enviaremail['permiso'] && $aRow['estado'] == 'E'){
                    $sOutput .= '<button type=\"button\" class=\"btn btn-primary btn-xs\" title=\"'.$enviaremail['accion'].'\" onclick=\"generarCotizacion.postEmail(this,\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"fa fa-envelope-o fa-lg\"></i>';
                    $sOutput .= '</button>';
                }
                
                $sOutput .= ' </div>" ';

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

    public function getNuevoGenerarCotizacion(){
        Obj::run()->View->render('nuevoGenerarCotizacion'); 
    }
    
    public function getFormNewCotizacion(){
        Obj::run()->View->render('formNewCotizacion'); 
    }
    
    public function getFormClonarCotizacion(){
        Obj::run()->View->render('formClonarCotizacion'); 
    }
    
    public function getFormBuscarMisProductos(){ 
        Obj::run()->View->render('formBuscarMisProductos');
    }
    
    public function getTableMisProductos(){ 
        Obj::run()->View->tab = $this->post('_tab');
        Obj::run()->View->render('tableMisProductos');
    }
    
    public static function getMisProductos(){ 
        $data = Obj::run()->generarCotizacionModel->getMisProductos();
        
        return $data;
    }
    
    public function getFormBuscarCliente(){ 
        Obj::run()->View->render('formBuscarCliente');
    }
    
    public function getClientes(){ 
        $tab = $this->post('_tab');
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->generarCotizacionModel->getClientes();
        
        if(!isset($rResult['error'])){  
            $iTotal         = isset($rResult[0]['total'])?$rResult[0]['total']:0;
            
            $sOutput = '{';
            $sOutput .= '"sEcho": '.intval($sEcho).', ';
            $sOutput .= '"iTotalRecords": '.$iTotal.', ';
            $sOutput .= '"iTotalDisplayRecords": '.$iTotal.', ';
            $sOutput .= '"aaData": [ ';
            foreach ( $rResult as $key=>$aRow ){
                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_persona']);
                
                $nom = '<a href=\"javascript:;\" onclick=\"simpleScript.setInput({'.$tab.'txt_idpersona:\''.$encryptReg.'\', '.$tab.'txt_cliente:\''.$aRow['nombrecompleto'].'\'},\'#'.T8.'formBuscarCliente\');\" >'.$aRow['nombrecompleto'].'</a>';
                
                /*datos de manera manual*/
                $sOutput .= '["'.(++$key).'","'.$nom.'" ';

                $sOutput .= '],';
            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;
    }
    
    public static function getProduccion(){
        $data = Obj::run()->generarCotizacionModel->getProduccion();
        
        return $data;
    }
    
    public static function getProductosCotizados(){
        $data = Obj::run()->generarCotizacionModel->getProductosCotizados();
        
        return $data;
    }
    
    public function postGenerarCotizacion(){ 
        $data = Obj::run()->generarCotizacionModel->generarCotizacion();
        
        echo json_encode($data);
    }
    
    public function postEmail(){ 
        $this->postPDF('N');
        Obj::run()->generarCotizacionModel->postTiempoCotizacion();
        
        $data = Obj::run()->generarCotizacionModel->getCotizacion();
        
        $emailCliente = $data[0]['email'];
        $cliente = $data[0]['nombrecompleto'];
        $emailUser = $data[0]['mail_user'];
        
        $body ='
            <h3>Cotización N° '.$data[0]['cotizacion_numero'].'</h3>
            <h3>Cliente: '.$data[0]['nombrecompleto'].'</h3>
        <table border="1" style="border-collapse:collapse">
            <tr>
                <th style="width:10%">Código</th>
                <th style="width:40%">Producto</th>
                <th style="width:10%">Precio</th>
                <th style="width:10%">Meses</th>
                <th style="width:10%">Producción</th>
                <th style="width:10%">Importe</th>
            </tr>';
        foreach ($data as $value) {
            $body .= '<tr>
                <td style="text-align:center">'.$value['codigo'].'</td>
                <td>'.$value['producto'].'</td>
                <td style="text-align:right">'.number_format($value['precio'],2).'</td>
                <td style="text-align:center">'.$value['cantidad_mes'].'</td>
                <td style="text-align:right">'.number_format($value['costo_produccion'],2).'</td>
                <td style="text-align:right">'.number_format($value['importe'],2).'</td>
            </tr>';
        }    
        $body .='</table>';
        
        
        $mail             = new PHPMailer(); // defaults to using php "mail()"
 
//        $body             = file_get_contents('contents.html');
//        $body             = eregi_replace("[\]",'',$html);

        $mail->AddReplyTo("name@gmail.com","First Last");

        $mail->SetFrom('name@gmail.com', 'First Last');

        $mail->AddReplyTo("name@gmail.com","First Last");

        $mail->AddAddress($emailCliente, $cliente);
        $mail->AddAddress($emailUser, $emailUser);

        $mail->Subject    = "PHPMailer Test Subject via mail(), basic";

        $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

        $mail->MsgHTML($body);

        $mail->AddAttachment('public/files/cotizacion.pdf');      // attachment
//        $mail->AddAttachment("public/img/phpmailer_mini.gif"); // attachment
        
        $data = array('result'=>2);
        /*validar si dominio de correo existe*/
        if($mail->Send()) {
            $data = array('result'=>1);
        } else {
            $data = array('result'=>2);
        }
        
        echo json_encode($data);
    }
    
    public function postPDF($n){
        $ar = ROOT.'public'.DS.'files'.DS.'cotizacion.pdf';
        /*se elimina el archivo*/
        unlink($ar);
        
        $data = Obj::run()->generarCotizacionModel->getCotizacion();
        
        $mpdf = new mPDF('c');

        $mpdf->mirrorMargins = 1;
        $mpdf->defaultheaderfontsize = 10; /* in pts */
        $mpdf->defaultheaderfontstyle = B; /* blank, B, I, or BI */
        $mpdf->defaultheaderline = 1; /* 1 to include line below header/above footer */
        $mpdf->defaultfooterfontsize = 12; /* in pts */
        $mpdf->defaultfooterfontstyle = B; /* blank, B, I, or BI */
        $mpdf->defaultfooterline = 1; /* 1 to include line below header/above footer */
        
        $html ='
        <style>
            table tr td, table tr th{
                font-size: 11px;
            }
        </style>
        <h3>Cotización N° '.$data[0]['cotizacion_numero'].'</h3>
        <h4>Cliente: '.$data[0]['nombrecompleto'].'</h4>
        <table border="1" style="border-collapse:collapse">
            <tr>
                <th style="width:10%">Código</th>
                <th style="width:20%">Tipo</th>
                <th style="width:30%">Ubicación</th>
                <th style="width:30%">Medidas</th>
                <th style="width:10%">Meses</th>
                <th style="width:10%">Precio</th>
                <th style="width:10%">Producción</th>
                <th style="width:10%">Total</th>
            </tr>';
        foreach ($data as $value) {
            $html .= '<tr>
                <td style="text-align:center">'.$value['codigo'].'</td>
                <td>'.$value['elemento'].'</td>
                <td>'.$value['producto'].'</td>
                <td>'.$value['dimension_ancho'].' x '.$value['dimension_alto'].' mts</td>
                <td style="text-align:right">'.$value['cantidad_mes'].'</td>
                <td style="text-align:center">'.number_format($value['precio'],2).'</td>
                <td style="text-align:right">'.number_format($value['costo_produccion'],2).'</td>
                <td style="text-align:right">'.number_format($value['importe'],2).'</td>
            </tr>';
        }    
        $html .='</table>';
        
        $mpdf->WriteHTML($html);
        $mpdf->Output($ar,'F');
        
        if($n != 'N'){
            $data = array('result'=>1);
            echo json_encode($data);
        }
    }
    
    public function postExcel(){
        $ar = ROOT.'public'.DS.'files'.DS.'cotizacion.xls';
        /*se elimina el archivo*/
        unlink($ar);
        
        $data = Obj::run()->generarCotizacionModel->getCotizacion();
        
        $html ='
        <h3>Cotización N° '.$data[0]['cotizacion_numero'].'</h3>
        <h4>Cliente: '.$data[0]['nombrecompleto'].'</h4>
        <table border="1" style="border-collapse:collapse">
            <tr>
                <th style="width:10%">Código</th>
                <th style="width:40%">Producto</th>
                <th style="width:10%">Precio</th>
                <th style="width:10%">Meses</th>
                <th style="width:10%">Producción</th>
                <th style="width:10%">Importe</th>
            </tr>';
        foreach ($data as $value) {
            $html .= '<tr>
                <td style="text-align:center">'.$value['codigo'].'</td>
                <td>'.$value['producto'].'</td>
                <td style="text-align:right">'.number_format($value['precio'],2).'</td>
                <td style="text-align:center">'.$value['cantidad_mes'].'</td>
                <td style="text-align:right">'.number_format($value['costo_produccion'],2).'</td>
                <td style="text-align:right">'.number_format($value['importe'],2).'</td>
            </tr>';
        }    
        $html .='</table>';
        
        
        $f=fopen($ar,'wb');
        if(!$f){$data = array('result'=>2);}
        fwrite($f,  utf8_decode($html));
        fclose($f);
                        
        $data = array('result'=>1);
        echo json_encode($data);
    }
    
    public function postAnularCotizacionAll(){
        $data = Obj::run()->generarCotizacionModel->anularCotizacion();
        
        echo json_encode($data);
    }  
    
    public static function findCotizacion(){
        $data = Obj::run()->generarCotizacionModel->findCotizacion();
        
        return $data;
    }
    
}

?>