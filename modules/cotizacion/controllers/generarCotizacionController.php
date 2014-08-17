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
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->generarCotizacionModel->getGridCotizacion();
        
        if(!isset($rResult['error'])){  
            $iTotal         = isset($rResult[0]['total'])?$rResult[0]['total']:0;
            
            $sOutput = '{';
            $sOutput .= '"sEcho": '.intval($sEcho).', ';
            $sOutput .= '"iTotalRecords": '.$iTotal.', ';
            $sOutput .= '"iTotalDisplayRecords": '.$iTotal.', ';
            $sOutput .= '"aaData": [ ';
            foreach ( $rResult as $aRow ){
                
                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_cotizacion']);
                
                /*datos de manera manual*/
                $sOutput .= '["'.$aRow['cotizacion_numero'].'","'.$aRow['nombrecompleto'].'","'.$aRow['meses_contrato'].'","'.$aRow['dias_oferta'].'", ';

                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';
                
                if($enviaremail['permiso']){
                    $sOutput .= '<button type=\"button\" class=\"btn btn-primary\" title=\"'.$enviaremail['accion'].'\" onclick=\"generarCotizacion.postEmail(this,\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"fa fa-envelope-o fa-lg\"></i>';
                    $sOutput .= '</button>';
                }
                if($exportarpdf['permiso']){
                    $sOutput .= '<button type=\"button\" class=\"btn btn-primary\" title=\"'.$exportarpdf['accion'].'\" onclick=\"generarCotizacion.postPDF(this,\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"fa fa-file fa-lg\"></i>';
                    $sOutput .= '</button>';
                }
                if($exportarexcel['permiso']){
                    $sOutput .= '<button type=\"button\" class=\"btn btn-primary\" title=\"'.$exportarexcel['accion'].'\" onclick=\"generarCotizacion.postExcel(this,\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"fa fa-file fa-lg\"></i>';
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
    
    public function postGenerarCotizacion(){ 
        $data = Obj::run()->generarCotizacionModel->generarCotizacion();
        
        echo json_encode($data);
    }
    
    public function postEmail(){ 
        $data = Obj::run()->generarCotizacionModel->getCotizacion();
        $email = $data[0]['email'];
        $cad = explode('@',$email);
               
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

        $address = "whoto@gmail.com";
        $mail->AddAddress($address, "John Doe");

        $mail->Subject    = "PHPMailer Test Subject via mail(), basic";

        $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

        $mail->MsgHTML($body);

//        $mail->AddAttachment("public/img/phpmailer.gif");      // attachment
//        $mail->AddAttachment("public/img/phpmailer_mini.gif"); // attachment
        
        $data = array('result'=>2);
        /*validar si dominio de correo existe*/
        if(checkdnsrr($cad[1])){
            if($mail->Send()) {
                $data = array('result'=>1);
            } else {
                $data = array('result'=>2);
            }
        }
        
        echo json_encode($data);
    }
    
    public function postPDF(){ 
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
        
        $mpdf->WriteHTML($html);
        $mpdf->Output(ROOT.'public'.DS.'files'.DS.'cotizacion.pdf','F');
        
        $data = array('result'=>1);
        echo json_encode($data);
    }
    
    public function postExcel(){
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
        
        
        $f=fopen(ROOT.'public'.DS.'files'.DS.'cotizacion.xls','wb');
        if(!$f){$data = array('result'=>2);}
        fwrite($f,  utf8_decode($html));
        fclose($f);
                        
        $data = array('result'=>1);
        echo json_encode($data);
    }
    
}

?>