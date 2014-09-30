<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 30-09-2014 03:09:35 
* Descripcion : liquidacionSocioController.php
* ---------------------------------------
*/    

class liquidacionSocioController extends Controller{

    public function __construct() {
        $this->loadModel("liquidacionSocio");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexLiquidacionSocio");
    }
    
    public function getGridLiquidacionSocio(){
        $exportarpdf   = Session::getPermiso('LISOCEP');     
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->liquidacionSocioModel->getLiquidacionSocio();
        
        $num = Obj::run()->liquidacionSocioModel->_iDisplayStart;
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
                switch($aRow['estado']){
                    case 'E':
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
                }
                
                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_ordenservicio']);
                $idSocio = Aes::en($aRow['id_persona']); 
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $axion = '"<div class=\"btn-group\">';
                 
                if($exportarpdf['permiso']){
                    $axion .= '<button type=\"button\" class=\"'.$exportarpdf['theme'].'\" title=\"'.$exportarpdf['accion'].'\" onclick=\"liquidacionSocio.postPDF(this,\'' . $encryptReg . '\',\'' . $idSocio . '\')\"> ';
                    $axion .= '    <i class=\"'.$exportarpdf['icono'].'\"></i>';
                    $axion .= '</button>';
                }
       
                $axion .= ' </div>" ';
                
                /*registros a mostrar*/
                $sOutput .= '["'.$aRow['orden_numero'].'","'.  Functions::cambiaf_a_normal($aRow['fecha_contrato']).'","'.$aRow['cliente'].' - '.$aRow['representante'].'","'.$aRow['socio'].'","S/.'.number_format($aRow['monto_total'],2).'","'.$estado.'",'.$axion.' ';

                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }
    
    /*carga formulario (newLiquidacionSocio.phtml) para nuevo registro: LiquidacionSocio*/
    public function getFormNewLiquidacionSocio(){
        Obj::run()->View->render("formNewLiquidacionSocio");
    }      
    
}

?>