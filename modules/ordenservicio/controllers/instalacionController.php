<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 18-09-2014 22:09:09 
* Descripcion : instalacionController.php
* ---------------------------------------
*/    

class instalacionController extends Controller{

    public function __construct() {
        $this->loadModel("instalacion");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexInstalacion");
    }
    
    public function getGridInstalacion(){
        $clonar   = Session::getPermiso('ORINSCL');
        $pdf      = Session::getPermiso('ORINSEP');
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->instalacionModel->getInstalaciones();
        
        $num = Obj::run()->instalacionModel->_iDisplayStart;
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
                $encryptReg = Aes::en($aRow['id_ordeninstalacion']);
                
                /*campo que maneja los estados, para el ejemplo aqui es ACTIVO, coloca tu campo*/
                if($aRow['estado'] == 'E'){
                    $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" name=\"'.ORINS.'chk_delete[]\" value=\"'.$encryptReg.'\"  >'; 
                    $estado = '<span class=\"label label-success\">'.SEGCO_5.'</span>';
                }else{
                    $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" disabled=\"disabled\"  >'; 
                    $estado = '<span class=\"label label-danger\">'.SEGPA_9.'</span>';
                }
                
                /*registros a mostrar*/
                $sOutput .= '["'.$chk.'",'.$aRow['ordenin_numero'].',"'.$aRow['orden_numero'].'","'.$aRow['codigo'].'","'.$aRow['ubicacion'].' - '.$aRow['descripcion'].'","'.$aRow['fecha_instalacion'].'","'.number_format($aRow['monto_total'],2).'","'.$estado.'", ';
                
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';
                 
                if($pdf['permiso']){
                    $sOutput .= '<button type=\"button\" class=\"'.$pdf['theme'].'\" title=\"'.$pdf['accion'].'\" onclick=\"instalacion.postPDF(this,\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"'.$pdf['icono'].'\"></i>';
                    $sOutput .= '</button>';
                }
                if($clonar['permiso']){
                    $sOutput .= '<button type=\"button\" class=\"'.$clonar['theme'].'\" title=\"'.$clonar['accion'].'\" onclick=\"instalacion.getClonar(\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"'.$clonar['icono'].'\"></i>';
                    $sOutput .= '</button>';
                }
                
                $sOutput .= ' </div>"';
                
                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }
    
    public function getCaratulas(){
        $tab = $this->post('_tab');
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->instalacionModel->getCaratulas();
        
        if(!isset($rResult['error'])){  
            $iTotal         = isset($rResult[0]['total'])?$rResult[0]['total']:0;
            
            $sOutput = '{';
            $sOutput .= '"sEcho": '.intval($sEcho).', ';
            $sOutput .= '"iTotalRecords": '.$iTotal.', ';
            $sOutput .= '"iTotalDisplayRecords": '.$iTotal.', ';
            $sOutput .= '"aaData": [ ';
            foreach ( $rResult as $key=>$aRow ){
                $carat = $aRow['ubicacion'].' - '.$aRow['descripcion'];
                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_ordenserviciod']);
                
                $nom = '<a href=\"javascript:;\" onclick=\"simpleScript.setInput({'.$tab.'txt_idcaratula:\''.$encryptReg.'\', '.$tab.'txt_caratula:\''.$carat.'\'},\'#'.ORINS.'formBuscarCaratula\');\" >'.$aRow['codigo'].'</a>';
                
                /*datos de manera manual*/
                $sOutput .= '["'.(++$key).'","'.$nom.'","'.$carat.'","'.$aRow['orden_numero'].'" ';

                $sOutput .= '],';
            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;
    }

    /*carga formulario (newInstalacion.phtml) para nuevo registro: Instalacion*/
    public function getFormNewInstalacion(){
        Obj::run()->View->render("formNewInstalacion");
    }
    
    public function getFormBuscarCaratulta(){
        Obj::run()->View->render("formBuscarCaratula");
    }
    
    public function getFormBuscarConceptos(){ 
        Obj::run()->View->render('formBuscarConceptos');
    }
    
    public function getFormClonarInstalacion(){ 
        Obj::run()->View->render('formClonarInstalacion');
    }
    
    public function findInstalacionDetalle(){ 
        $data = Obj::run()->instalacionModel->findInstalacionDetalle();
        
        return $data;
    }
    
    public function getConceptos(){ 
        $data = Obj::run()->instalacionModel->getConceptos();
        
        return $data;
    }
    
    /*envia datos para grabar registro: Instalacion*/
    public function postNewInstalacion(){
        $data = Obj::run()->instalacionModel->newInstalacion();
        
        echo json_encode($data);
    }
    
    public function postPDF(){
        $c = 'ordenInstalacion_'.Obj::run()->instalacionModel->_idInstalacion.'.pdf';
        
        $ar = ROOT.'public'.DS.'files'.DS.$c;
               
        $mpdf = new mPDF('c');

        $mpdf->SetHTMLHeader('<img src="'.ROOT.'public'.DS.'img'.DS.'logotipo.png" width="137" height="68" />','',TRUE);
        $mpdf->SetHTMLFooter('<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold;"><tr>
                                <td width="33%"><span style="font-weight: bold;">{DATE j-m-Y}</span></td>
                                <td width="33%" align="center" style="font-weight: bold;">{PAGENO}/{nbpg}</td>
                                <td width="33%" style="text-align: right; ">SEVEND.pe</td>
                             </tr></table>');
                
        $html = $this->getHtmlOI();         

        $mpdf->WriteHTML($html);
        $mpdf->Output($ar,'F');
        
        $data = array('result'=>1,'archivo'=>$c);
        echo json_encode($data);
        
    }
    
    private function getHtmlOI(){
        $data = Obj::run()->instalacionModel->getOrdenInstalacion();
        $html ='
        <style>           
           table,h3,h4{font-family:Arial;} 
           table, table td, table th, p {font-size:12px;}
           table{width:100%;}
           #td2 th, .totales{background:#901D78; color:#FFF; height:25px;}
           #td2 td{font-size:11px;height:25px;}
           #anulado{            
            font-size:30px; font-family:verdana; color:#F00;  }
        </style>';
        
        $html .='<table width="100%" border="0" cellpadding="5" cellspacing="3">
          <tr bgcolor="#901D78">
            <th colspan="6"><div align="center"><h2 style="color:#FFF;">ORDEN DE INSTALACIÓN</h2></div></th>
          </tr>
          <tr>
            <td width="13%"><strong>N° OI:</strong></td>
            <td width="26%"><h3>'.$data[0]['ordenin_numero'].'</h3></td>
            <td width="15%"></td>
            <td width="15%"></td>
            <td width="20%"><strong>Fecha Vencimiento:</strong></td>
            <td width="15%">'.$data[0]['fechains'].'</td>
          </tr>
          <tr>
            <td><strong>Cliente:</strong></td>
            <td colspan="5">'.($data[0]['numerocliente']==''?'':$data[0]['numerocliente'].' - ').$data[0]['cliente'].'</td>
          </tr>
          <tr>
            <td><strong>Representante:</strong></td>
            <td colspan="5">'.($data[0]['nrorepresentante']==''?'':$data[0]['nrorepresentante'].' - ').$data[0]['representante'].'</td>
          </tr>
        </table> 
        <br />
        <table id="td2" border="1" style="border-collapse:collapse">
            <tr>
                <th style="width:40%">Concepto</th>
                <th style="width:8%">Cantidad</th>
                <th style="width:7%">Precio</th>
                <th style="width:12%">Total</th>
            </tr>';
        foreach ($data as $value) {
            $html .= '<tr>
                <td>'.$value['concepto'].'</td>
                <td style="text-align:center">'.number_format($value['cantidad']).'</td>
                <td style="text-align:center">'.number_format($value['precio'],2).'</td>
                <td style="text-align:right">S/.'.number_format($value['costo_total'],2).'</td>
            </tr>';
        }    
        $html .= '<tr><td colspan="3"></td><td class="totales" style="text-align:right; font-weight:bold;">S/.'.number_format($data[0]['monto_total'],2).'</td></tr>';
        
        $html .='</table>';
        
        if ($data[0]['observacion']!= '' ):
            $html .= '<p>- '.$data[0]['observacion'].'</p>';
        endif;        
        return $html;
    }
    
    
    /*envia datos para anular registros: Instalacion*/
    public function postAnularInstalacionAll(){
        $data = Obj::run()->instalacionModel->anularInstalacionAll();
        
        echo json_encode($data);
    }
    
}

?>