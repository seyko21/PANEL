<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 05-09-2014 23:09:58 
* Descripcion : regProduccionController.php
* ---------------------------------------
*/    

class regProduccionController extends Controller{

    public function __construct() {
        $this->loadModel("regProduccion");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexRegProduccion");
    }
    
    public function getGridRegProduccion(){
       $editar = Session::getPermiso('REPROED');
       $eliminar = Session::getPermiso('REPRODE');
       $exportarpdf   = Session::getPermiso('REPROEP');
       $exportarexcel = Session::getPermiso('REPROEX');
       $sEcho          =   $this->post('sEcho');
        
       $rResult = Obj::run()->regProduccionModel->getGridProduccion();
        
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
                
                /*datos de manera manual*/
                $sOutput .= '["'.$chk.'","'.$aRow['distrito'].'","'.  Functions::cambiaf_a_normal($aRow['fecha']).'","'.$aRow['ubicacion'].'","'.$aRow['elemento'].'","'.number_format($aRow['total_produccion'],2).'","'.number_format($aRow['total_asignado'],2).'","'.number_format($aRow['total_saldo'],2).'", ';
                
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';                      
                       
                if($editar['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"'.$editar['theme'].'\" title=\"'.$editar['accion'].' Ficha Técnica\" onclick=\"fichaTecnica.getEditarFichaTecnica(\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"'.$editar['icono'].'\"></i>';
                    $sOutput .= '</button>';
                }   
                if($exportarpdf['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"'.$exportarpdf['theme'].'\" title=\"'.$exportarpdf['accion'].'\" onclick=\"fichaTecnica.postPDF(this,\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"'.$exportarpdf['icono'].'\"></i>';
                    $sOutput .= '</button>';
                }
                if($exportarexcel['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"'.$exportarexcel['theme'].'\" title=\"'.$exportarexcel['accion'].'\" onclick=\"fichaTecnica.postExcel(this,\''.$encryptReg.'\')\">';
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
    
    /*carga formulario (newRegProduccion.phtml) para nuevo registro: RegProduccion*/
    public function getFormNewRegProduccion(){
        Obj::run()->View->render("formNewRegProduccion");
    }
    
    /*carga formulario (editRegProduccion.phtml) para editar registro: RegProduccion*/
    public function getFormEditRegProduccion(){
        Obj::run()->View->render("formEditRegProduccion");
    }
    
    /*envia datos para grabar registro: RegProduccion*/
    public function postNewRegProduccion(){
        $data = Obj::run()->regProduccionModel->newRegProduccion();
        
        echo json_encode($data);
    }
    
    /*envia datos para editar registro: RegProduccion*/
    public function postEditRegProduccion(){
        $data = Obj::run()->regProduccionModel->editRegProduccion();
        
        echo json_encode($data);
    }
    
    /*envia datos para eliminar registro: RegProduccion*/
    public function postDeleteRegProduccionAll(){
        $data = Obj::run()->regProduccionModel->deleteRegProduccionAll();
        
        echo json_encode($data);
    }
    
}

?>