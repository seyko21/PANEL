<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 27-08-2014 02:08:12 
* Descripcion : catalogoPrecioController.php
* ---------------------------------------
*/    

class catalogoPreciosController extends Controller{
           
    public function __construct() {
        $this->loadModel("catalogoPrecios");
        $this->loadController(array('modulo'=>'panel','controller'=>'fichaTecnica'));        
    }    
    public function index(){ 
        Obj::run()->View->render("indexCatalogoPrecios");
    }    
    public function getEditarCaratula(){ 
        Obj::run()->View->render('editarCatalogoPrecio');
    } 
        
    public function getGridProducto(){
       $editar = Session::getPermiso('CATPRED');       
       $exportarpdf   = Session::getPermiso('CATPREP');
       $exportarexcel = Session::getPermiso('CATPREX'); 
       $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->catalogoPreciosModel->getGridProducto();
        
        if(!isset($rResult['error'])){  
            $iTotal         = isset($rResult[0]['total'])?$rResult[0]['total']:0;
            
            $sOutput = '{';
            $sOutput .= '"sEcho": '.intval($sEcho).', ';
            $sOutput .= '"iTotalRecords": '.$iTotal.', ';
            $sOutput .= '"iTotalDisplayRecords": '.$iTotal.', ';
            $sOutput .= '"aaData": [ ';
            foreach ( $rResult as $key=>$aRow ){
                
                if($aRow['estado'] == 'D'){
                    $estado = '<span class=\"label label-success\">Disponible</span>';
                }elseif($aRow['estado'] == 'A'){
                    $estado = '<span class=\"label label-warning\">Alquilado</span>';
                }
                
                if($aRow['iluminado'] == 1){
                    $iluminado = '<span class=\"label label-success\">SI</span>';
                }elseif($aRow['iluminado'] == 0){
                    $iluminado = '<span class=\"label label-danger\">NO</span>';
                }
                
                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_caratula']);
                $idProd = Aes::en($aRow['id_producto']);
                                                
                /*datos de manera manual*/
                $sOutput .= '["'.$aRow['codigo'].'","'.$aRow['distrito'].'","'.$aRow['ubicacion'].'","'.$aRow['elemento'].'","'.$aRow['dimesion_area'].'","'.number_format($aRow['precio'],2).'","'.$iluminado.'","'.$estado.'", ';
                
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';
                
                //Visualizar Detalle                
                if($editar['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"btn btn-primary btn-xs\" title=\"'.$editar['accion'].'\" onclick=\"catalogoPrecios.getEditarCaratula(\''.$encryptReg.'\',\''.$idProd.'\')\">';
                    $sOutput .= '    <i class=\"fa fa-edit fa-lg\"></i>';
                    $sOutput .= '</button>';
                }
                if($exportarpdf['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"btn txt-color-white bg-color-blueDark btn-xs\" title=\"'.$exportarpdf['accion'].'\" onclick=\"catalogoPrecios.postPDF(this,\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"fa fa-file-pdf-o fa-lg\"></i>';
                    $sOutput .= '</button>';
                }
                if($exportarexcel['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"btn btn-success btn-xs\" title=\"'.$exportarexcel['accion'].'\" onclick=\"catalogoPrecios.postExcel(this,\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"fa fa-file-excel-o fa-lg\"></i>';
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
    
    public function postEditarCaratula(){                                 
        $data = Obj::run()->fichaTecnicaController->postEditarCaratula();        
        echo $data;        
    }   
    public static function getListadoTipoPanel(){ 
       $data = Obj::run()->catalogoPreciosModel->getTipoPanel();            
       return $data;
    }
    public function postPDF(){ 
        $c = 'fichatecnicacaratula_'.Obj::run()->catalogoPreciosModel->_idCaratula.'.pdf';
        $ar = ROOT.'public'.DS.'files'.DS.$c;
        $mpdf = new mPDF('c');
        $mpdf->mirrorMargins = 1;
        $mpdf->defaultheaderfontsize = 11; /* in pts */
        $mpdf->defaultheaderfontstyle = B; /* blank, B, I, or BI */
        $mpdf->defaultheaderline = 1; /* 1 to include line below header/above footer */
        $mpdf->defaultfooterfontsize = 11; /* in pts */
        $mpdf->defaultfooterfontstyle = B; /* blank, B, I, or BI */
        $mpdf->defaultfooterline = 1; /* 1 to include line below header/above footer */
                        
        $mpdf->SetHTMLHeader('<img src="'.ROOT.'public'.DS.'img'.DS.'logotipo.png" width="137" height="68" />','',TRUE);
        $mpdf->SetHTMLFooter('<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold;"><tr>
                                <td width="33%"><span style="font-weight: bold;">{DATE j-m-Y}</span></td>
                                <td width="33%" align="center" style="font-weight: bold;">{PAGENO}/{nbpg}</td>
                                <td width="33%" style="text-align: right; ">SEVEND.pe</td>
                             </tr></table>');
        
        $html = $this->getHtmlReporte();        
        $mpdf->WriteHTML($html);
        $mpdf->Output($ar,'F');
                
        $data = array('result'=>1,'archivo'=>$c);
        echo json_encode($data);
    }
    
    public function postExcel(){
        $c = 'fichatecnicacaratula_'.Obj::run()->catalogoPreciosModel->_idCaratula.'.xls';
        $ar = ROOT.'public'.DS.'files'.DS.$c;
        $html = $this->getHtmlReporte();
                
        $f=fopen($ar,'wb');
        if(!$f){$data = array('result'=>2);}
        fwrite($f,  utf8_decode($html));
        fclose($f);
                        
        $data = array('result'=>1,'archivo'=>$c);
        echo json_encode($data);
                
    }
    
    
    public function getHtmlReporte(){
        $data = Obj::run()->catalogoPreciosModel->getRptFichaTecnicaCatalogo();
        $html = '';
        $iluminado = ($data[0]['iluminado']=='1')?'SI':'NO';
        $estado = ($data[0]['estado']=='D')?'DISPONIBLE':'ALQUILADO';
        $comision = number_format(100*$data[0]['comision_vendedor']);
        $vendedor = ($data[0]['vendedor'] == ''?'No se asigno vendedor':$data[0]['vendedor']);
        
        $html .='
        <style>
            table,h2,h3,h4{font-family:Arial;} 
            table, table td, table th{ font-size:12px;}
            table{width:100%;}
            table td {padding:4px 3px 4px 0px}
            h3{font-size:25px;}
        </style>
        <h2>FICHA TECNICA </h2>  
            <table width="100%" border="0" >
                <tr>
                    <td colspan="4">
                        <table width="100%" border="0">
                            <tr>
                              <td width="5%"><strong>CÓDIGO:</strong></td>
                              <td width="25%"><h3>'.$data[0]['codigo'].'</h3></td>                            
                            </tr>                           
                          </table>
                      </td>
                </tr>
                <tr>
                  <td width="16%"><b>DEPARTAMENTO:</b></td><td width="21%">'.$data[0]['departamento'].'</td>
                  <td width="10%"><b>PROVINCIA:</b></td><td width="20%">'.$data[0]['provincia'].'</td>
                  <td width="8%"><b>DISTRITO:</b></td><td width="26%">'.$data[0]['distrito'].'</td>
                </tr>
                <tr>
                  <td><b>UBICACION:</b></td><td colspan="5">'.strtoupper($data[0]['ubicacion']).'</td>
                </tr>
                <tr>
                  <td><b>TIPO PANEL:</b></td><td>'.strtoupper($data[0]['tipoPanel']).'</td>
                  <td colspan="4"><table width="100%" border="0">
                    <tr>
                      <td width="20%"><b>ANCHO:</b></td><td width="21%" >'.$data[0]['dimension_ancho'].'</td>
                      <td width="20%"><b>ALTO:</b></td><td width="21%" >'.$data[0]['dimension_alto'].'</td>
                      <td width="20%"><b>AREA:</b></td><td width="21%" >'.$data[0]['dimesion_area'].' m <sup>2</sup></td>
                    </tr>
                  </table></td>
                </tr>           
                <tr>
                    <td colspan="6"><table width="100%" border="0">
                      <tr>
                        <td width="11%"><b>LATITUD:</b></td><td width="41%">'.$data[0]['google_latitud'].'</td>
                        <td width="7%"><b>LONGITUD:</b></td><td width="41%">'.$data[0]['google_longitud'].'</td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                  <td><b>OBSERVACION:</b></td><td colspan="5">'.strtoupper($data[0]['observacion']).'</td>
                </tr>
                 <tr>
                    <td><strong>PRECIO:</strong></td>
                    <td>'.number_format($data[0]['precio'],2).'</td>
                    <td><strong>ILUMINADO:</strong></td>
                    <td>'.$iluminado.'</td>
                  </tr>
                  <tr>
                    <td><strong>VENDEDOR:</strong></td>
                    <td colspan="3">'.strtoupper($vendedor).'</td>
                  </tr>
                  <tr>
                    <td><strong>COMISION:</strong></td>
                    <td colspan="3">'.$comision.' %</td>
                 </tr>
                 <tr>
                    <td><strong>ESTADO:</strong></td>
                    <td colspan="3">'.$estado.'</td>                 
                </tr>
          </table>';
        
        $html .='<h4>PERMISO MUNICIPAL</h4>';
        
        if($data[0]['fecha_inicio'] == ''){
           $html .='<h4><i>No tiene registrado Permiso Municipal.</i></h4>'; 
        }else{        
            $html .= '
                <table width="100%" border="0">
                    <tr>
                      <td width="18%"><strong>FECHA INICIO:</strong></td>
                      <td width="22%">'.Functions::cambiaf_a_normal($data[0]['fecha_inicio']).'</td>
                      <td width="18%"><strong>FECHA FINAL:</strong></td>
                      <td width="49%">'.Functions::cambiaf_a_normal($data[0]['fecha_final']).'</td>
                    </tr>
                    <tr>
                      <td><strong>MONTO PAGADO:</strong></td>
                      <td colspan="2">S/. '.number_format($data[0]['pm_precio'],2).'</td>
                    </tr>
                    <tr>
                      <td><strong>OBSERVACIONES:</strong></td>
                      <td colspan="3">'.strtoupper($data[0]['pm_obs']).'</td>
                    </tr>
                  </table>';
        }
       
        return $html;
        
    }
        
  
    
}

?>