<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 09-12-2014 17:12:42 
* Descripcion : mensajesPlantillaModel.php
* ---------------------------------------
*/ 

class mensajesPlantillaModel extends Model{

    private $_flag;
    private $_idMensajes;
    private $_asunto;
    private $_alias;    
    private $_cuerpo;
    private $_chkdel;
    private $_usuario;
    
    /*para el grid*/
    public  $_iDisplayStart;
    private $_iDisplayLength;
    private $_iSortingCols;
    private $_sSearch;
    
    public function __construct() {
        parent::__construct();
        $this->_set();
    }
    
    private function _set(){
        $this->_flag        = Formulario::getParam("_flag");
        $this->_idMensajes   = Aes::de(Formulario::getParam("_idMensajes"));    /*se decifra*/
        $this->_usuario     = Session::get("sys_idUsuario");
        
        $this->_asunto     = Formulario::getParam(PMSJ.'txt_asunto');
        $this->_alias     = Formulario::getParam(PMSJ.'txt_alias');        
        $this->_cuerpo     = Formulario::getParam(PMSJ.'txt_mensaje');
        $this->_chkdel  = Formulario::getParam(PMSJ.'chk_delete');        
        
        $this->_iDisplayStart  = Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength = Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   = Formulario::getParam("iSortingCols");
        $this->_sSearch        = Formulario::getParam("sSearch");
    }
    
    /*data para el grid: Mensajes*/
    public function getMensajes(){
        $aColumns       =   array("","asunto","alias","estado" ); //para la ordenacion y pintado en html
        /*
	 * Ordenando, se verifica por que columna se ordenara
	 */
        $sOrder = "";
        for ( $i=0 ; $i<intval( $this->_iSortingCols ) ; $i++ ){
                if ( $this->post( "bSortable_".intval($this->post("iSortCol_".$i)) ) == "true" ){
                        $sOrder .= " ".$aColumns[ intval( $this->post("iSortCol_".$i) ) ]." ".
                                ($this->post("sSortDir_".$i)==="asc" ? "asc" : "desc") .",";
                }
        }
        $sOrder = substr_replace( $sOrder, "", -1 );
        
        $query = "call sp_configMensajeGrid(:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ":iDisplayStart" => $this->_iDisplayStart,
            ":iDisplayLength" => $this->_iDisplayLength,
            ":sOrder" => $sOrder,
            ":sSearch" => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    /*grabar nuevo registro: Mensajes*/
    public function newMensajes(){
        $query = "call sp_configMensajeMantenimiento(:flag,:key,:asunto,:alias,:cuerpo,:usuario);";
        $parms = array(
            ':flag' => 1,
            ':key' => $this->_idMensajes,
            ':asunto' => $this->_asunto,
            ':alias' => $this->_alias,       
            ':cuerpo' => $this->_cuerpo,          
            ':usuario' => $this->_usuario
        );
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
    /*seleccionar registro a editar: Mensajes*/
    public function findMensajes(){
       $query = " select
                    `id_mensaje`,
                    `asunto`,
                    `cuerpo`,
                    `alias`,
                    `usuario_creacion`,
                    `fecha_creacion`,
                    `estado`
                  from `pub_mensaje`
                  where id_mensaje = :idd  ";
       
        $parms = array(
            ':idd' => $this->_idMensajes
        );
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
    /*editar registro: Mensajes*/
    public function editMensajes(){
       $query = "call sp_configMensajeMantenimiento(:flag,:key,:asunto,:alias,:cuerpo,:usuario);";
        $parms = array(
            ':flag' => 2,
            ':key' => $this->_idMensajes,
            ':asunto' => $this->_asunto,
            ':alias' => $this->_alias,       
            ':cuerpo' => $this->_cuerpo,          
            ':usuario' => $this->_usuario
        );
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
    /*eliminar varios registros: Mensajes*/
    public function deleteMensajesAll(){
      foreach ($this->_chkdel as $value) {
           $query = "call sp_configMensajeMantenimiento(:flag,:key,:asunto,:alias,:cuerpo,:usuario);";
            $parms = array(
                ':flag' => 3,
                ':key' => Aes::de($value),
                ':asunto' => '',
                ':alias' => '',       
                ':cuerpo' => '',   
                ':usuario' => $this->_usuario
            );
            $this->execute($query,$parms);
            
        }
        $data = array('result'=>1);
        return $data;
    }
    
    public function postDesactivar(){
        $query = "UPDATE pub_mensaje SET
                    `estado` = 'I'
                WHERE `id_mensaje` = :id;";
        $parms = array(
            ':id' => $this->_idMensajes
        );
        $this->execute($query,$parms);
        $data = array('result'=>1);    
        
        return $data;
    }
    
    public function postActivar(){
        $query = "UPDATE `pub_mensaje` SET
                    `estado` = 'A'
                WHERE `id_mensaje` = :id;";
        $parms = array(
            ':id' => $this->_idMensajes
        );
        $this->execute($query,$parms);
        $data = array('result'=>1);
        return $data;
    }     
    
    public function getPlantillaMensaje($alias){
       $query = " select
                    `id_mensaje`,
                    `asunto`,
                    `cuerpo`,
                    `alias`,
                    `estado`
                  from `pub_mensaje`
                  where alias = :alias  ";
       
        $parms = array(
            ':alias' => $alias
        );
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
}

?>