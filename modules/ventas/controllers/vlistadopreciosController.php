<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 22-11-2014 19:11:31 
* Descripcion : vlistadopreciosController.php
* ---------------------------------------
*/    

class vlistadopreciosController extends Controller{

    public function __construct() {
        $this->loadModel("vlistadoprecios");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexVlistadoprecios");
    }
    
    public function getGridVlistadoprecios(){
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->vlistadopreciosModel->getVlistadoprecios();
        
        $num = Obj::run()->vlistadopreciosModel->_iDisplayStart;
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
                
                if($aRow['incligv'] == 'S'){                
                    $incl = '<span class=\"label label-success\">'.LABEL_S.'</span>';                
                }elseif($aRow['incligv'] == 'N'){
                    $incl = '<span class=\"label label-info\">'.LABEL_N.'</span>';
                }   
                /*registros a mostrar*/
                $sOutput .= '["'.($num++).'","'.$aRow['descripcion'].'","'.$aRow['unidad_medida'].'","'.$incl.'","'.$aRow['descripcion_moneda'].'","'.number_format($aRow['precio'],2).'" ';

                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }

    public function postPDF(){
        $c = 'Listado_Productos_Precio.pdf';
        
        $ar = ROOT.'public'.DS.'files'.DS.$c;
               
        $mpdf = new mPDF('c');
              
        $mpdf->SetHTMLHeader('<img src="'.ROOT.'public'.DS.'img'.DS.'logotipo.png" width="137" height="68" />','',TRUE);
        $mpdf->SetHTMLFooter('<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold;"><tr>
                                <td width="33%"><span style="font-weight: bold;">{DATE j-m-Y}</span></td>
                                <td width="33%" align="center" style="font-weight: bold;">{PAGENO}/{nbpg}</td>
                                <td width="33%" style="text-align: right; ">'.LB_EMPRESA2.'</td>
                             </tr></table>');
                
        $html = $this->getHtml($mpdf);         

        //$mpdf->WriteHTML($html);
        $mpdf->Output($ar,'F');
        
        $data = array('result'=>1,'archivo'=>$c);
        echo json_encode($data);
        
    }
    
    public function postExcel(){
        $c = 'Listado_Productos_Precio.xls';
        
        $ar = ROOT.'public'.DS.'files'.DS.$c;
        
        $html = $this->getHtml("EXCEL");
        
        $f=fopen($ar,'wb');
        if(!$f){$data = array('result'=>2);}
        fwrite($f,  utf8_decode($html));
        fclose($f);
                        
        $data = array('result'=>1,'archivo'=>$c);
        echo json_encode($data);
    }
    
    private function getHtml($mpdf){
        $data = Obj::run()->vlistadopreciosModel->getListadoPrecios();
                
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
            <th colspan="4"><div align="center"><h2 style="color:#FFF;">LISTADO DE PRODUCTOS</h2></div></th>
          </tr>
          <tr>
            <td width="16%"><strong>Fecha:</strong></td>
            <td width="26%"><h3>'.date("d/m/Y").'</h3></td>                        
          </tr>         
        </table> 
        <br />';
       
        $html .= '<table id="td2" border="1" style="border-collapse:collapse">
                    <tr>
                        <th style="width:5%">#</th>
                        <th style="width:40%">Descripción del producto</th>
                        <th style="width:20%">Unidad Medida</th>
                        <th style="width:10%">Incl. IGV</th>
                        <th style="width:20%">Precio</th>
                    </tr>'; 		        
       
        foreach ($data as $value) {   
             
            
            if($value['id_moneda'] != $moneda ){
              $html .= '<tr><td colspan="4"><h3>'.$value['sigla_moneda'].' - '.$value['descripcion_moneda'].'</h3></td></tr>';  
              $i =1;
            }   
            $moneda = $value['id_moneda'];    
            $mon = $value['sigla_moneda'].' ';  
            
            if($value['incligv'] == 'S'){                
                $incl = LABEL_S;  
            }elseif($value['incligv'] == 'N'){
                $incl = LABEL_N;
            }   
            
            $html .= '<tr>
                <td style="text-align:center">'.($i++).'</td>
                <td>'.$value['descripcion'].'</td>
                <td style="text-align:center">'.$value['unidad_medida'].'</td>
                <td style="text-align:center">'.$incl.'</td>
                <td style="text-align:right"><b>'.$mon.number_format($value['precio'],2).'</b></td>
            </tr>';    

        }    
         $html .='</table>';         
        
        $html .='<p><b>Generado por: </b> '.Session::get('sys_nombreUsuario').'</p>';                
        $html .='<p><b>Fecha y Hora: </b> '.date("d/m/Y h:i A").'</p>';  
        
        if ($mpdf == 'EXCEL'){
           return $html; 
        }
        
        
        $mpdf->WriteHTML($html);               
    }          
    
}

?>