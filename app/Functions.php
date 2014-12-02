<?php
/*
 * Documento   : Functions
 * Creado      : 07-jul-2014
 * Autor       : ..... .....
 * Descripcion : 
 */
class Functions{
    
    public static function widgetOpen($obj){
        if(is_array($obj)){
            $id     = (isset($obj['id']))?$obj['id']:'';
            $title  = (isset($obj['title']))?$obj['title']:'';
            $width  = (isset($obj['width']))?' width:'.$obj['width'].'; ':'';
            $height = (isset($obj['height']))?' height:'.$obj['height'].';overflow:auto; ':'';
            $padding= (isset($obj['padding']))? '':'no-padding';
            $actions= (isset($obj['actions']))? $obj['actions']:'';
        }else{
            $id     = $obj;
            $title  = $obj;
            $width  = '';
            $height = '';
            $padding= 'no-padding';
            $actions= '';
        }
        $bottom = '';
        $border = '';
        if(empty($padding)){
            $bottom = 'margin-bottom:15px;';
            $border = 'border: #dddddd solid 1px;';
        }
        $toolButton = '';
        if(!empty($actions) && is_array($actions)){
            $toolButton = '
            <div class="widget-toolbar" role="menu">
                <div class="btn-group">
                    <button class="btn dropdown-toggle btn-xs btn-warning" data-toggle="dropdown">
                            Acciones <i class="fa fa-caret-down"></i>
                    </button>
                    <ul class="dropdown-menu pull-right">';
            foreach ($actions as $btn) {
                $t='';
                if(isset($btn['title'])) $t= $btn['title'];
                $toolButton .= '
                <li>
                    <a href="javascript:void(0);" onclick="'.$btn['click'].'" title="'.$t.'" >'.$btn['label'].'</a>
                </li>';
            }
            $toolButton .='  
                    </ul>
                </div>
            </div>';
        }
        $html = '
        <div id="widget_'.$id.'" class="jarviswidget jarviswidget-color-darken jarviswidget-sortable" data-widget-editbutton="false" style="'.$width.'" role="widget">
            <header role="heading">
                <div class="jarviswidget-ctrls" role="menu">
                     <!-- <a style="display: block;" data-original-title="Collapse" href="#" class="button-icon jarviswidget-toggle-btn" rel="tooltip" title="" data-placement="bottom"><i class="fa fa-minus"></i></a> 
                    <a data-original-title="Fullscreen" href="javascript:void(0);" class="button-icon jarviswidget-fullscreen-btn" rel="tooltip" title="" data-placement="bottom"><i class="fa fa-resize-full"></i></a>
                    <div style="top: 33px; left: 1311px; display: block;" class="tooltip fade bottom in">
                        <div class="tooltip-arrow"></div>
                        <div class="tooltip-inner">Fullscreen</div>
                    </div> -->
                    <!--<a style="display: block;" data-original-title="Eliminar" href="javascript:void(0);" class="button-icon jarviswidget-delete-btn" rel="tooltip" title="" data-placement="bottom"><i class="fa fa-times"></i></a>-->
                </div>
               <!-- <div class="widget-toolbar" role="menu">
                    <a data-toggle="dropdown" class="dropdown-toggle color-box selector" href="javascript:void(0);"></a>
                        <ul class="dropdown-menu arrow-box-up-right color-select pull-right">
                            <li><span class="bg-color-green" data-widget-setstyle="jarviswidget-color-green" rel="tooltip" data-placement="left" data-original-title="Green Grass"></span></li>
                            <li><span class="bg-color-greenDark" data-widget-setstyle="jarviswidget-color-greenDark" rel="tooltip" data-placement="top" data-original-title="Dark Green"></span></li>
                            <li><span class="bg-color-greenLight" data-widget-setstyle="jarviswidget-color-greenLight" rel="tooltip" data-placement="top" data-original-title="Light Green"></span></li>
                            <li><span class="bg-color-purple" data-widget-setstyle="jarviswidget-color-purple" rel="tooltip" data-placement="top" data-original-title="Purple"></span></li><li><span class="bg-color-magenta" data-widget-setstyle="jarviswidget-color-magenta" rel="tooltip" data-placement="top" data-original-title="Magenta"></span></li>
                            <li><span class="bg-color-pink" data-widget-setstyle="jarviswidget-color-pink" rel="tooltip" data-placement="right" data-original-title="Pink"></span></li>
                            <li><span class="bg-color-pinkDark" data-widget-setstyle="jarviswidget-color-pinkDark" rel="tooltip" data-placement="left" data-original-title="Fade Pink"></span></li><li><span class="bg-color-blueLight" data-widget-setstyle="jarviswidget-color-blueLight" rel="tooltip" data-placement="top" data-original-title="Light Blue"></span></li><li><span class="bg-color-teal" data-widget-setstyle="jarviswidget-color-teal" rel="tooltip" data-placement="top" data-original-title="Teal"></span></li><li><span class="bg-color-blue" data-widget-setstyle="jarviswidget-color-blue" rel="tooltip" data-placement="top" data-original-title="Ocean Blue"></span></li><li><span class="bg-color-blueDark" data-widget-setstyle="jarviswidget-color-blueDark" rel="tooltip" data-placement="top" data-original-title="Night Sky"></span></li><li><span class="bg-color-darken" data-widget-setstyle="jarviswidget-color-darken" rel="tooltip" data-placement="right" data-original-title="Night"></span></li><li><span class="bg-color-yellow" data-widget-setstyle="jarviswidget-color-yellow" rel="tooltip" data-placement="left" data-original-title="Day Light"></span></li><li><span class="bg-color-orange" data-widget-setstyle="jarviswidget-color-orange" rel="tooltip" data-placement="bottom" data-original-title="Orange"></span></li><li><span class="bg-color-orangeDark" data-widget-setstyle="jarviswidget-color-orangeDark" rel="tooltip" data-placement="bottom" data-original-title="Dark Orange"></span></li><li><span class="bg-color-red" data-widget-setstyle="jarviswidget-color-red" rel="tooltip" data-placement="bottom" data-original-title="Red Rose"></span></li><li><span class="bg-color-redLight" data-widget-setstyle="jarviswidget-color-redLight" rel="tooltip" data-placement="bottom" data-original-title="Light Red"></span></li><li><span class="bg-color-white" data-widget-setstyle="jarviswidget-color-white" rel="tooltip" data-placement="right" data-original-title="Purity"></span></li><li><a href="javascript:void(0);" class="jarviswidget-remove-colors" data-widget-setstyle="" rel="tooltip" data-placement="bottom" data-original-title="Reset widget color to default">Remove</a></li></ul>
                </div> -->
                <span class="widget-icon"><i class="fa fa-table"></i></span>
                <h2>'.$title.'</h2>
                <span class="jarviswidget-loader"><i class="fa fa-refresh fa-spin"></i></span>
                '.$toolButton.'
            </header>
            <div role="content">
                <!-- widget edit box -->
                <div class="jarviswidget-editbox">
                        <!-- This area used as dropdown edit box -->

                </div>
                <!-- end widget edit box -->

                <!-- widget content -->
                <div class="widget-body '.$padding.'" style="'.$height.$bottom.$border.'">';
        return $html;
    }
    
    public static function widgetClose(){
        $html = '</div>
            </div>
        </div>';
        return $html;
    }
    
    public static function help($c){
        $html = '<img src="'.BASE_URL.'public/img/h1.png" class="xhelp pointer" title="'.$c.'" onmousemove="$(\'.tooltip\').css({opacity: 1,background: \'transparent\'});$(\'.tooltip-inner\').css({\'margin-left\':\'-3px\'});" style="margin-top:7px;">';
        return $html;
    }
    
    
    /*
     * echo Functions::selectHtml(array(
                                        'data'=>$data,
                                        'atributes'=>array(
                                            'id'=>T6.'lst_tipoconcento',
                                            'name'=>T6.'lst_tipoconcento'
                                        ),
                                        'etiqueta'=>'descripcion',
                                        'value'=>'id_tipo',
                                        'defaultEtiqueta'=>'',
                                        'txtAll'=>true,
                                        'txtSelect'=>true
                                    ))
     */
    public static function selectHtml($obj) {
        $data = isset($obj['data'])?$obj['data']:array();
        $attr = isset($obj['atributes'])?$obj['atributes']:array();
        $all  = isset($obj['txtAll'])?$obj['txtAll']:false;
        $sel  = isset($obj['txtSelect'])?$obj['txtSelect']:false;
        $etiq = isset($obj['etiqueta'])?$obj['etiqueta']:'';
        $valo = isset($obj['value'])?$obj['value']:'';
        $etid = isset($obj['defaultEtiqueta'])?$obj['defaultEtiqueta']:'';
        
        $html = '<select ';
        foreach ($attr as $key => $value) {
            $html .= $key . '="' . $value . '" ';
        }
        $html .= '>';
        
        if (count($data) > 0) {
            if ($sel){
                $html .= '<option value="">Seleccionar</option>';
            }
            if ($all){
                $html .= '<option value="ALL">Todo(s)</option>';
            }


            foreach ($data as $item) {
                
                /*las etiquetas*/
                if(is_array($etiq)){
                    $desc = '';
                    foreach ($etiq as $val) {
                        $desc .= $item[$val].'-';
                    }
                    $desc = substr_replace($desc, "", -1);
                }else{
                    $desc = $item[$etiq];
                }
                
                /*los valores*/
                if(is_array($valo)){
                    $key = '';
                    foreach ($valo as $vall) {
                        $key .= $item[$vall].'-';
                    }
                    $key = substr_replace($key, "", -1);
                }else{
                    $key = $item[$valo];
                }
                
                $selected = "";
                if ($key == $etid) {
                    $selected = '  selected="selected"';
                }

                $html .= '<option title="' . $desc . '" value="' . $key . '" ' . $selected . '>' . $desc . '</option>';
            }

            $html .= '</select>';
        }
        else{
            $html .= '<option value=""> - Sin datos - </option></select>';
        }
        return $html;
    }

    public static function createCell($obj){
        $t = '';
        for($i=0;$i<$obj['row'];$i++){
           $t.= '<tr>'; 
           for($j=0;$j<$obj['cols'];$j++){
               $t.='<td>&nbsp;</td>';
           }
           $t.= '</tr>';
        }
        return $t;
    }
    
    public static function cambiaf_a_mysql($fecha){
        if(!empty($fecha)){
            $mifecha = explode("/",$fecha);
            $lafecha=$mifecha[2]."-".$mifecha[1]."-".$mifecha[0];
            return $lafecha; 
        }
    } 	
    
    public static function cambiaf_a_normal($fecha){
        if(!empty($fecha)){
            $mifecha = explode("-",$fecha);
            $lafecha=$mifecha[2]."/".$mifecha[1]."/".$mifecha[0];
            return $lafecha; 
        }
    } 
    
    public static function nombremes($mes){
        setlocale(LC_TIME, 'spanish');
        $nombre=strftime("%B",mktime(0, 0, 0, $mes, 1, 2000));
        return $nombre;
    } 
    
    public static function deleteComa($val){
        return str_replace(',', '', $val);
    }
    
    public static function convertirDiaMes($valor){
        //Entrada: 0.5, 0.6, 1, 1.5, 3.5
        if ($valor < 1 ){
           $dia = round(30 * $valor);
           $resultado = number_format($dia,0).' dias';              
        }else{
            $decimal = explode(".",$valor);
            $nmes = $decimal[0];
            $ndia = '0.'.$decimal[1];                        
            $xdia = round(30 * $ndia);
            
            if($xdia <= 0){        
               if($valor == 1):
                  $resultado = number_format($valor,0).' mes';            
                 else:
                  $resultado = number_format($valor,0).' meses';                                
               endif;               
            }else{
                if($valor < 2):
                    $resultado = number_format($nmes,0).' mes y '.$xdia.' dias';                        
                else:
                    $resultado = number_format($nmes,0).' meses y '.$xdia.' dias';                        
                endif;
                
            }
            
             
        }                    
        return $resultado;
    }

}
?>
