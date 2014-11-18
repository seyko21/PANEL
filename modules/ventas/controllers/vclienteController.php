<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 17-11-2014 17:11:18 
* Descripcion : vclienteController.php
* ---------------------------------------
*/    

class vclienteController extends Controller{

    public function __construct() {
        $this->loadModel(array('modulo'=>'ventas','modelo'=>'vcliente'));
        $this->loadController(array('modulo'=>'personal','controller'=>'persona')); 

    }
    
    public function index(){ 
        Obj::run()->View->render("indexVcliente");        
    }
    
    public function getGridVcliente(){
        $editar   = Session::getPermiso('VRECLED');
        $eliminar = Session::getPermiso('VRECLDE');
         
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->vclienteModel->getVcliente();
        
        $num = Obj::run()->vclienteModel->_iDisplayStart;
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
            
            foreach ( $rResult as $key=>$aRow  ){
                /*antes de enviar id se encrypta*/
                $encryptReg = Aes::en($aRow['id_persona']);
                
                /*campo que maneja los estados, para el ejemplo aqui es ACTIVO, coloca tu campo*/
                if($aRow['estado'] == 'A'){
                    $estado = '<button type=\"button\" class=\"btn btn-success btn-xs\" title=\"Desactivar\" onclick=\"vcliente.postDesactivar(this,\''.$encryptReg.'\')\">Activo</button>';
                    $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" name=\"'.VRECL.'chk_delete[]\" value=\"'.$encryptReg.'\">';
                }elseif($aRow['estado'] == 'I'){
                    $estado = '<button type=\"button\" class=\"btn btn-danger btn-xs\" title=\"Activar\" onclick=\"vcliente.postActivar(this,\''.$encryptReg.'\')\">Inactivo</button>';
                    $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" name=\"'.VRECL.'chk_delete[]\" disabled >';
                }
                                                
                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $axion = '"<div class=\"btn-group\">';
                 
                if($editar['permiso']){
                    $axion .= '<button type=\"button\" class=\"'.$editar['theme'].'\" title=\"'.$editar['accion'].'\" onclick=\"vcliente.getFormEditVcliente(this,\''.$encryptReg.'\')\">';
                    $axion .= '    <i class=\"'.$editar['icono'].'\"></i>';
                    $axion .= '</button>';
                }
                if($eliminar['permiso']){
                    $axion .= '<button type=\"button\" class=\"'.$eliminar['theme'].'\" title=\"'.$eliminar['accion'].'\" onclick=\"vcliente.postDeleteVcliente(this,\''.$encryptReg.'\')\">';
                    $axion .= '    <i class=\"'.$eliminar['icono'].'\"></i>';
                    $axion .= '</button>';
                }
                $axion .= ' </div>" ';
                
                /*registros a mostrar*/                
                
                $tipoP = ($aRow['tipo_persona'] == 'N')?  'P. Natural':'P. Juridica' ;
                
                $sOutput .= '["'.($chk).'","'.$aRow['nombrecompleto'].'","'.$tipoP.'","'.$aRow['telefono'].'","'.$aRow['numerodocumento'].'","'.$aRow['ciudad'].'","'.$estado.'",'.$axion.' ';

                $sOutput .= '],';

            }
            $sOutput = substr_replace( $sOutput, "", -1 );
            $sOutput .= '] }';
        }else{
            $sOutput = $rResult['error'];
        }
        
        echo $sOutput;

    }
    
    /*carga formulario (newVcliente.phtml) para nuevo registro: Vcliente*/
    public function getFormNewVcliente(){
        Obj::run()->View->render("formNewVcliente");
    }
    
    /*carga formulario (editVcliente.phtml) para editar registro: Vcliente*/
    public function getFormEditVcliente(){
        Obj::run()->View->render("formEditVcliente");
    }
    
    /*busca data para editar registro: Vcliente*/
    public static function findVcliente(){
        $data = Obj::run()->vclienteModel->findVcliente();
            
        return $data;
    }
    
    /*envia datos para grabar registro: Vcliente*/
    public function postNewVcliente(){
        $data = Obj::run()->vclienteModel->newVcliente();
        
        echo json_encode($data);
    }
    
    /*envia datos para editar registro: Vcliente*/
    public function postEditVcliente(){
        $data = Obj::run()->vclienteModel->editVcliente();
        
        echo json_encode($data);
    }
    
    /*envia datos para eliminar registro: Vcliente*/
    public function postDeleteVcliente(){
        $data = Obj::run()->vclienteModel->deleteVcliente();
        
        echo json_encode($data);
    }
    
    /*envia datos para eliminar registros: Vcliente*/
    public function postDeleteVclienteAll(){
        $data = Obj::run()->vclienteModel->deleteVclienteAll();
        
        echo json_encode($data);
    }

    public function postDesactivar(){
        $data = Obj::run()->vclienteModel->postDesactivar();
        
        echo json_encode($data);
    }
    
    public function postActivar(){
        $data = Obj::run()->vclienteModel->postActivar();
        
        echo json_encode($data);
    }      
}

?>