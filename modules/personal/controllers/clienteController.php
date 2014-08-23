<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 21-08-2014 07:08:16 
* Descripcion : clienteController.php
* ---------------------------------------
*/    

class clienteController extends Controller{

    public function __construct() {
        $this->loadModel("cliente");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexCliente");
    }
    
    public function getGridCliente(){
        $editar = Session::getPermiso('REGCLED');
        
        $sEcho          =   $this->post('sEcho');
        
        $rResult = Obj::run()->clienteModel->getClientes();
        
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
                
                if($aRow['estado'] == 'A'){
                    $estado = '<button type=\"button\" class=\"btn btn-success btn-xs\" title=\"Desactivar\" onclick=\"cliente.postDesactivarCliente(this,\''.$encryptReg.'\')\">Activo</button>';
                }elseif($aRow['estado'] == 'I'){
                    $estado = '<button type=\"button\" class=\"btn btn-danger btn-xs\" title=\"Activar\" onclick=\"cliente.postActivarCliente(this,\''.$encryptReg.'\')\">Inactivo</button>';
                }
                
                $chk = '<input id=\"c_'.(++$key).'\" type=\"checkbox\" name=\"'.REGCL.'chk_delete[]\" value=\"'.$encryptReg.'\">';
                
                /*datos de manera manual*/
                $sOutput .= '["'.$chk.'","'.$aRow['numerodocumento'].'","'.$aRow['nombrecompleto'].'","'.$estado.'", ';
                

                /*
                 * configurando botones (add/edit/delete etc)
                 * se verifica si tiene permisos para editar
                 */
                $sOutput .= '"<div class=\"btn-group\">';
                
                if($editar['permiso'] == 1){
                    $sOutput .= '<button type=\"button\" class=\"btn btn-primary btn-xs\" title=\"'.$editar['accion'].'\" onclick=\"cliente.getEditarCliente(this,\''.$encryptReg.'\')\">';
                    $sOutput .= '    <i class=\"fa fa-edit fa-lg\"></i>';
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
    
    /*carga formulario (newCliente.phtml) para nuevo registro: Cliente*/
    public function getFormNewCliente(){
        Obj::run()->View->render("formNewCliente");
    }
    
    /*carga formulario (editCliente.phtml) para editar registro: Cliente*/
    public function getFormEditCliente(){
        Obj::run()->View->render("formEditCliente");
    }
    
    public static function getDepartamentos(){ 
        $data = Obj::run()->clienteModel->getDepartamentos();
        
        return $data;
    }
    
    public static function getProvinciasEst($dep=''){
        $data = Obj::run()->clienteModel->getProvincias($dep);
        
        return $data;
    }
    
    public static function getUbigeoEst($pro=''){
        $data = Obj::run()->clienteModel->getUbigeo($pro);
        
        return $data;
    }
    
    public static function findCliente(){
        $data = Obj::run()->clienteModel->findCliente();
        
        return $data;
    }
    
    /*envia datos para grabar registro: Cliente*/
    public function postNewCliente(){
        $data = Obj::run()->clienteModel->newCliente();
        
        echo json_encode($data);
    }
    
    /*envia datos para editar registro: Cliente*/
    public function postEditCliente(){
        $data = Obj::run()->clienteModel->editCliente();
        
        echo json_encode($data);
    }
    
    public function postDesactivarCliente(){
        $data = Obj::run()->clienteModel->postDesactivarCliente();
        
        echo json_encode($data);
    }
    
    public function postActivarCliente(){
        $data = Obj::run()->clienteModel->postActivarCliente();
        
        echo json_encode($data);
    }
    
    /*envia datos para eliminar registro: Cliente*/
    public function postDeleteClienteAll(){
        $data = Obj::run()->clienteModel->deleteClienteAll();
        
        echo json_encode($data);
    }
    
}

?>