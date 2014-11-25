<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 21-11-2014 23:11:45 
* Descripcion : panelDisponibleController.php
* ---------------------------------------
*/    

class panelDisponibleController extends Controller{

    public function __construct() {
        $this->loadModel("panelDisponible");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexPanelDisponible");
    }
    
    public function getGridPanelDisponible(){

        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->panelDisponibleModel->getPanelDisponible();
        
        $num = Obj::run()->panelDisponibleModel->_iDisplayStart;
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
                              
                 $col1 = $aRow['ubicacion'];
                 $col2 = $aRow['dimesion_area'].' m<sup>2</sup>';
                 $col3 = $aRow['distrito'];
                 $col4 = $aRow['elemento'];
                 $col5 = $aRow['codigos'];
                
                /*registros a mostrar*/
                $sOutput .= '["'.($num++).'","'.$col1.'","'.$col2.'","'.$col3.'","'.$col4.'","'.$col5.'" ';

                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }    
    
    public static function getListadoCiudad(){ 
       $data = Obj::run()->panelDisponibleModel->getCiudad();            
       return $data;
    }    

  public function postPDF(){
        $c = 'paneles_disponible_'.date('d_m_Y').'.pdf';
        
        $ar = ROOT.'public'.DS.'files'.DS.$c;
               
        $mpdf = new mPDF('c', 'A4-L'); 
        //pie de página:
        $pie = Obj::run()->panelDisponibleModel->getPiePagina();              
        
        $mpdf->SetHTMLHeader('<img src="'.ROOT.'public'.DS.'img'.DS.'logotipo.png" width="137" height="68" />','',TRUE);
        $mpdf->SetHTMLFooter('<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold;"><tr>                              
                                <td width="70%" align="left" style="font-weight: bold; ">'.$pie['valor'].'</td>
                                <td width="10%" style="text-align: right; ">{PAGENO}/{nbpg}</td>
                             </tr></table>');
                
        $html = $this->getHtml($mpdf);         

        //$mpdf->WriteHTML($html);
        $mpdf->Output($ar,'F');
        
        $data = array('result'=>1,'archivo'=>$c);
        echo json_encode($data);
        
    }        
    
    private function getHtml($mpdf){
        $data = Obj::run()->panelDisponibleModel->getRptPaneles();
        $dataC = Obj::run()->panelDisponibleModel->getAgenteVenta();
        $html ='
        <style>           
           table,h1,h2,h3,h4,p{font-family:Arial;} 
           table p, p {font-size:12px; }
           table{width:100%;}           
           .fondo{ background:#901D78; color:#FFF;  }
        </style>';
                     
        $i =1;
        
        $ciudad = Obj::run()->panelDisponibleModel->_ciudad;
        
        if($ciudad == 'ALL'){
            $descripcion_ciudad = 'Todas las ciudades';           
        }else{
            $descripcion_ciudad = $data[0]['distrito'];
        }
        
        $html .= '<table width="100%" border="0" cellpadding="5" cellspacing="3" style="margin-top:-30px;">
                <tr><td style="text-align:center"><img src="'.ROOT.'public'.DS.'img'.DS.'sevend_grande.jpg"  width="700" height="380"  /></td></tr>
                <tr bgcolor="#901D78">
                  <td style="padding:30px;text-align:center"><h1 style="color:#FFF;">Ubicaciones Disponibles al '.date("d/m/Y").'</h1>
                  <b style="color:#FFF; font-size:17px;">- '.$descripcion_ciudad.' -</b></td>
                </tr></table>'; 
                
        $mpdf->AddPage();                
        
        $html .= '<table width="100%" border="0" cellpadding="3" cellspacing="3">';
        foreach ($data as $value) {       
            $rutaImagen = BASE_URL.'public/img/uploads/'.$value['imagen'];
            
            $html .= '<tr>
                <td valign="top" class="fondo" style="width:30%;">
                    <table width="100%" border="0" cellpadding="5" cellspacing="3">
                        <tr><td class="fondo" style="text-align:center"><h2 style="text-align:center">'.$value['elemento'].'</h2></td></tr>
                        <tr><td class="fondo"><b>Dimensiones:</b> '.$value['dimension_alto'].' x '.$value['dimension_ancho'].' Mts.</td></tr>
                        <tr><td class="fondo"><b>Ubicación:</b> '.$value['ubicacion'].'</td></tr>
                        <tr><td class="fondo"><b>Ciudad:</b> '.$value['distrito'].'</td></tr>
                        <tr><td class="fondo"><b>Observación:</b> '.$value['referencia'].'</td></tr>
                        <tr><td class="fondo"><b>Códigos:</b> '.$value['codigos'].'</td></tr>
                        <tr><td class="fondo"><br/><b>Tarifa:</b> S/.'.number_format($value['precio'],2).' + IGV <br>(Incluye Ambas Caras)</td></tr>  
                        <tr><td>
                        <p><img src="http://maps.googleapis.com/maps/api/staticmap?center='.$value['googlemap_latitud'].','.$value['googlemap_longitud'].'&zoom=16&size=300x300&maptype=roadmap&markers=color:purple%7Clabel:7%7C'.$value['googlemap_latitud'].','.$value['googlemap_longitud'].'&sensor=false"></p>
                        </td></tr>
                    </table>
                </td>
                <td valign="top" style="width:70%; border:solid 1px #CCC" ><img src="'.$rutaImagen.'" width="700" height="600"  /></td>            
            </tr>'; 
            
        }    
         
        $html .='</table>';
        
        $html .= '<table width="100%" border="0" cellpadding="5" cellspacing="3" style="margin-top:-30px;">
                <tr><td style="text-align:center"><img src="'.ROOT.'public'.DS.'img'.DS.'sevend_grande.jpg"  width="700" height="380"  /></td></tr>
                <tr bgcolor="#901D78">
                  <td style="padding:30px;text-align:center"><h1 style="color:#FFF;">Agente de Ventas</h1>
                  <b style="color:#FFF; font-size:17px;">'.$dataC['contacto'].'</b></td>
                </tr></table>'; 
        
        $mpdf->WriteHTML($html);               
    }      
        
    
}

?>