<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 05-09-2014 23:09:58 
* Descripcion : generarVentaController.php
* ---------------------------------------
*/    

class generarVentaController extends Controller{

    public function __construct() {
        $this->loadModel("generarVenta");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexGenerarVenta");
    }
    
    public function getGridGenerarVenta(){
       $editar = Session::getPermiso('REPROED');
       $eliminar = Session::getPermiso('REPRODE');
       $exportarpdf   = Session::getPermiso('REPROEP');
       $exportarexcel = Session::getPermiso('REPROEX');
       $adjuntar = Session::getPermiso('REPROAJ');
       
       $sEcho          =   $this->post('sEcho');
        
       $rResult = Obj::run()->generarVentaModel->getGridProduccion();
        
        if(!isset($rResult['error'])){  
            $iTotal         = isset($rResult[0]['total'])?$rResult[0]['total']:0;
            $idx =1;
            $sOutput = '{';
            $sOutput .= '"sEcho": '.intval($sEcho).', ';
            $sOutput .= '"iTotalRecords": '.$iTotal.', ';
            $sOutput .= '"iTotalDisplayRecords": '.$iTotal.', ';
            $sOutput .= '"aaData": [ ';
            foreach ( $rResult as $key=>$aRow ){
                             
                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_produccion']);
                
                $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" name=\"'.REPRO.'chk_delete[]\" value=\"'.$encryptReg.'\">';
                if($aRow['total_asignado'] > 0){
                    $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" name=\"'.REPRO.'chk_delete[]\" disabled>';
                }
                
                
                /*datos de manera manual*/
                $sOutput .= '["'.$chk.'","'.$aRow['numero_produccion'].'","'.$aRow['distrito'].'","'.  Functions::cambiaf_a_normal($aRow['fecha']).'","'.$aRow['ubicacion'].'","'.$aRow['elemento'].'","'.number_format($aRow['total_produccion'],2).'","'.number_format($aRow['total_asignado'],2).'","'.number_format($aRow['total_saldo'],2).'", ';
                
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';                      
                       
                if($editar['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"'.$editar['theme'].'\" title=\"'.$editar['accion'].'\" onclick=\"generarVenta.getFormEditarProduccion(this,\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"'.$editar['icono'].'\"></i>';
                    $sOutput .= '</button>';
                }   
                if($adjuntar['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"'.$adjuntar['theme'].'\" title=\"'.$adjuntar['accion'].'\" onclick=\"generarVenta.getFormImagen(this,\''.$encryptReg.'\',\''.$aRow['numero_produccion'].'\')\">';
                    $sOutput .= '    <i class=\"'.$adjuntar['icono'].'\"></i>';
                    $sOutput .= '</button>';
                }   
                if($exportarpdf['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"'.$exportarpdf['theme'].'\" title=\"'.$exportarpdf['accion'].'\" onclick=\"generarVenta.postPDF(this,\''.$encryptReg.'\',\''.$aRow['numero_produccion'].'\')\">';
                    $sOutput .= '    <i class=\"'.$exportarpdf['icono'].'\"></i>';
                    $sOutput .= '</button>';
                }
                if($exportarexcel['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"'.$exportarexcel['theme'].'\" title=\"'.$exportarexcel['accion'].'\" onclick=\"generarVenta.postExcel(this,\''.$encryptReg.'\',\''.$aRow['numero_produccion'].'\')\">';
                    $sOutput .= '    <i class=\"'.$exportarexcel['icono'].'\"></i>';
                    $sOutput .= '</button>';
                }
                
                $sOutput .= ' </div>" ';

                $sOutput = substr_replace( $sOutput, "", -1 );
                $sOutput .= '],';
                $idx++;
            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;
    }
    
    public function getProductos(){
        $tab = $this->post('_tab');
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->generarVentaModel->getProductos();
        
        if(!isset($rResult['error'])){  
            $iTotal         = isset($rResult[0]['total'])?$rResult[0]['total']:0;
            
            $sOutput = '{';
            $sOutput .= '"sEcho": '.intval($sEcho).', ';
            $sOutput .= '"iTotalRecords": '.$iTotal.', ';
            $sOutput .= '"iTotalDisplayRecords": '.$iTotal.', ';
            $sOutput .= '"aaData": [ ';
            foreach ( $rResult as $key=>$aRow ){
                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_producto']);
                
                $nom = '<a href=\"javascript:;\" onclick=\"simpleScript.setInput({'.$tab.'txt_idproducto:\''.$encryptReg.'\', '.$tab.'txt_producto:\''.$aRow['ubicacion'].'\'},\'#'.REPRO.'formBuscarProducto\');\" >'.$aRow['ubicacion'].'</a>';
                
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
    
    /*carga formulario (newGenerarVenta.phtml) para nuevo registro: GenerarVenta*/
    public function getFormNewProduccion(){
        Obj::run()->View->render("formNewGenerarVenta");
    }
    
    /*carga formulario (editGenerarVenta.phtml) para editar registro: GenerarVenta*/
    public function getFormEditProduccion(){
        Obj::run()->View->render("formEditGenerarVenta");
    }
    
    public function getFormBuscarProducto(){
        Obj::run()->View->render("formBuscarProducto");
    }
    
    public function getFormImagen(){
        Obj::run()->View->render("formImagen");
    }
    
    public function getConceptos(){
        $data = Obj::run()->generarVentaModel->getConceptos();
        
        return $data;
    }
    
    public function postPDF(){
        $c = 'produccion_'.Obj::run()->generarVentaModel->_cod.'.pdf';
        
        $ar = ROOT.'public'.DS.'files'.DS.$c;
               
        $mpdf = new mPDF('c');

        $mpdf->SetHTMLHeader('<img src="'.ROOT.'public'.DS.'img'.DS.'logotipo.png" width="137" height="68" />','',TRUE);
        $mpdf->SetHTMLFooter('<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold;"><tr>
                                <td width="33%"><span style="font-weight: bold;">{DATE j-m-Y}</span></td>
                                <td width="33%" align="center" style="font-weight: bold;">{PAGENO}/{nbpg}</td>
                                <td width="33%" style="text-align: right; ">SEVEND.pe</td>
                             </tr></table>');
                
        $html = $this->getHtmlProduccion($mpdf);         

        //$mpdf->WriteHTML($html);
        $mpdf->Output($ar,'F');
        
        $data = array('result'=>1,'archivo'=>$c);
        echo json_encode($data);
        
    }
    
    public function postExcel(){
        $c = 'produccion_'.Obj::run()->generarVentaModel->_cod.'.xls';
        
        $ar = ROOT.'public'.DS.'files'.DS.$c;
        
        $html = $this->getHtmlProduccion("EXCEL");
        
        $f=fopen($ar,'wb');
        if(!$f){$data = array('result'=>2);}
        fwrite($f,  utf8_decode($html));
        fclose($f);
                        
        $data = array('result'=>1,'archivo'=>$c);
        echo json_encode($data);
    }
    
    private function getHtmlProduccion($mpdf){
        $data = Obj::run()->generarVentaModel->getProduccion();
        $html ='
        <style>           
           table,h3,h4,p{font-family:Arial;} 
           table, table td, table th, p {font-size:12px;}
           table{width:100%;}
           #td2 th, .totales{background:#901D78; color:#FFF; height:25px;}
           #td2 td{font-size:10px;height:25px;}           
        </style>';
        
        $html .='<table width="100%" border="0" cellpadding="5" cellspacing="3">
          <tr bgcolor="#901D78">
            <th colspan="6"><div align="center"><h2 style="color:#FFF;">PRODUCCIÓN</h2></div></th>
          </tr>
          <tr>
            <td width="16%"><strong>N° Producción:</strong></td>
            <td width="26%"><h3>'.$data[0]['numero_produccion'].'</h3></td>
            <td width="15%"></td>
            <td width="15%"></td>
            <td width="20%"><strong>Fecha Producción:</strong></td>
            <td width="15%">'.$data[0]['fecha'].'</td>
          </tr>
          <tr>
            <td><strong>Producto:</strong></td>
            <td colspan="5">'.$data[0]['ubicacion'].' - '.$data[0]['dimension_alto'].' x '.$data[0]['dimension_ancho'].' mts</td>
          </tr>
          <tr>         
            <td width="20%"><strong>Ciudad:</strong></td>
            <td width="15%">'.$data[0]['ciudad'].'</td>
          </tr>
        </table> 
        <br />
        <table id="td2" border="1" style="border-collapse:collapse">
            <tr>
                <th style="width:5%">Item</th>
                <th style="width:40%">Descripción del Concepto</th>
                <th style="width:12%">Cantidad</th>
                <th style="width:12%">Precio</th>
                <th style="width:12%">Total</th>
            </tr>';
        $i =1;
        foreach ($data as $value) {
            $html .= '<tr>
                <td style="text-align:center">'.($i++).'</td>
                <td>'.$value['concepto'].'</td>
                <td style="text-align:right">'.number_format($value['cantidad'],2).'</td>
                <td style="text-align:right">S/.'.number_format($value['precio'],2).'</td>
                <td style="text-align:right">S/.'.number_format($value['costo_importe'],2).'</td>
            </tr>';
        }    
        $html .= '<tr><td colspan="4"></td><td class="totales" style="text-align:right; font-weight:bold;">S/.'.number_format($data[0]['total_produccion'],2).'</td></tr>';
        
        $html .='</table>';
        $html .= '<h4>Observación </h4>';
        if ($data[0]['observacion']!= '' ):
            $html .= '<p>- '.$data[0]['observacion'].'</p>';
        else:
            $html .= '<p>- NINGUNO.</p>';
        endif;   
        
        if ($mpdf == 'EXCEL'){
           return $html; 
        }
        
        $mpdf->WriteHTML($html);
        
        $img = $data[0]['imagen'];
        
        if ($img != ''):
            $mpdf->AddPage();
            $html = '<h3>Vista Previa </h3>';                
            $html .= '<img src="'.BASE_URL.'public/img/produccion/'.$img.'" />';
            $mpdf->WriteHTML($html);   
        endif;

    }
    
    /*envia datos para grabar registro: GenerarVenta*/
    public function postNewGenerarVenta(){
        $data = Obj::run()->generarVentaModel->newGenerarVenta();
        
        echo json_encode($data);
    }
    
    /*envia datos para editar registro: GenerarVenta*/
    public function postEditGenerarVenta(){
        $data = Obj::run()->generarVentaModel->editGenerarVenta();
        
        echo json_encode($data);
    }
    
    /*envia datos para eliminar registro: GenerarVenta*/
    public function postAnularGenerarVentaAll(){
        $data = Obj::run()->generarVentaModel->anularGenerarVentaAll();
        
        echo json_encode($data);
    }
    
    public function findProduccion(){
        $data = Obj::run()->generarVentaModel->findProduccion();
        
        return $data;
    }        
    
}

?>