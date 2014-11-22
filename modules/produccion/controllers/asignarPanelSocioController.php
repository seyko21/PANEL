<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 11-10-2014 16:10:11 
* Descripcion : asignarPanelSocioController.php
* ---------------------------------------
*/    

class asignarPanelSocioController extends Controller{

    public function __construct() {
        $this->loadModel("asignarPanelSocio");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexAsignarPanelSocio");
    }
    
    public function getGridAsignarPanelSocio(){
        $editar   = Session::getPermiso('APASOED');
        $eliminar = Session::getPermiso('APASODE');
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->asignarPanelSocioModel->getAsignarPanelSocio();
        
        $num = Obj::run()->asignarPanelSocioModel->_iDisplayStart;
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
                
                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_asignacionpanel']);
                
                
                
                /*registros a mostrar*/
                $sOutput .= '["'.$aRow['nombrecompleto'].'","'.$aRow['ubicacion'].'","'.number_format($aRow['total_invertido'],2).'","'.number_format($aRow['porcentaje_ganacia']*100,2).' %", ';
                
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';
                 
                if($eliminar['permiso']){
                    $sOutput .= '<button type=\"button\" class=\"'.$eliminar['theme'].'\" title=\"'.$eliminar['accion'].'\" onclick=\"asignarPanelSocio.postDeleteAsignarPanelSocio(this,\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"'.$eliminar['icono'].'\"></i>';
                    $sOutput .= '</button>';
                }
                
                $sOutput .= ' </div>" ';
                
                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }
    
    public function getGridSocios(){ 
        $tab = $this->post('_tab');
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->asignarPanelSocioModel->getSocios();
        
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
                
                $nom = '<a href=\"javascript:;\" onclick=\"simpleScript.setInput({'.$tab.'txt_idpersona:\''.$encryptReg.'\', '.$tab.'txt_socio:\''.$aRow['nombrecompleto'].'\'},\'#'.APASO.'formBuscarSocio\');\" >'.$aRow['nombrecompleto'].'</a>';
                
                /*datos de manera manual*/
                $sOutput .= '["'.(++$key).'","'.$nom.'","'.  number_format($aRow['monto_invertido'],2).'","'.  number_format($aRow['monto_saldo'],2).'" ';

                $sOutput .= '],';
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
        
        $rResult = Obj::run()->asignarPanelSocioModel->getProductos();
        
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
                
                $nom = '<a href=\"javascript:;\" onclick=\"simpleScript.setInput({'.$tab.'txt_idproducto:\''.$encryptReg.'\', '.$tab.'txt_producto:\''.$aRow['ubicacion'].'\','.$tab.'txt_saldo:\''.$aRow['total_saldo'].'\','.$tab.'txt_gananciaasig:\''.($aRow['porcentaje']*100).'\'},\'#'.$tab.'formBuscarProductoPanelSocio\');\" >'.$aRow['ubicacion'].'</a>';
                
                /*datos de manera manual*/
                $sOutput .= '["'.(++$key).'","'.$nom.'","'.number_format($aRow['total_produccion'],2).'","'.number_format($aRow['total_asignado'],2).'","'.number_format($aRow['total_saldo'],2).'" ';

                $sOutput .= '],';
            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;
    }
    
    /*carga formulario (newAsignarPanelSocio.phtml) para nuevo registro: AsignarPanelSocio*/
    public function getFormNewAsignarPanelSocio(){
        Obj::run()->View->render("formNewAsignarPanelSocio");
    }
    
    public function getFormBuscarSocio(){ 
        Obj::run()->View->render('formBuscarSocio');
    }
    
    public function getTableInversiones(){ 
        Obj::run()->View->render('formTableInversiones');
    }
    
    public function formBuscarProductoPanelSocio(){
        Obj::run()->View->render("formBuscarProductoPanelSocio");
    }
    
    public function getInversiones(){
        $data = Obj::run()->asignarPanelSocioModel->getInversiones();
            
        return $data;
    }
    
    /*envia datos para grabar registro: AsignarPanelSocio*/
    public function postNewAsignarPanelSocio(){
        $data = Obj::run()->asignarPanelSocioModel->newAsignarPanelSocio();
        
        echo json_encode($data);
    }
    
    /*envia datos para eliminar registros: AsignarPanelSocio*/
    public function postDeleteAsignarPanelSocio(){
        $data = Obj::run()->asignarPanelSocioModel->postDeleteAsignarPanelSocio();
        
        echo json_encode($data);
    }
    
}

?>