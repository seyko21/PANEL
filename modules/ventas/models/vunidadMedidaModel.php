<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 06-11-2014 16:11:31 
* Descripcion : vunidadMedidaModel.php
* ---------------------------------------
*/ 

class vunidadMedidaModel extends Model{
   
    private $_idVunidadMedida;
    private $_chkdel;
    private $_nombre;
    private $_sigla;
    private $_cantMultiple;
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
        $this->_idVunidadMedida   = Aes::de(Formulario::getParam("_idVunidadMedida"));    /*se decifra*/
        $this->_usuario     = Session::get("sys_idUsuario");
        $this->_chkdel  = Formulario::getParam(VUNID.'chk_delete');
        $this->_nombre     = Formulario::getParam(VUNID.'txt_descripcion');
        $this->_sigla     = Formulario::getParam(VUNID.'txt_sigla');
        
        $this->_cantMultiple = Formulario::getParam(VUNID.'chk_multi');  
        
        $this->_iDisplayStart  = Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength = Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   = Formulario::getParam("iSortingCols");
        $this->_sSearch        = Formulario::getParam("sSearch");
    }
    
    /*data para el grid: VunidadMedida*/
    public function getVunidadMedida(){
        $aColumns       =   array("","nombre","sigla","estado" ); //para la ordenacion y pintado en html
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
        $query = "call sp_ventaUnidadMedidaGrid(:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ":iDisplayStart" => $this->_iDisplayStart,
            ":iDisplayLength" => $this->_iDisplayLength,
            ":sOrder" => $sOrder,
            ":sSearch" => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    /*grabar nuevo registro: VunidadMedida*/
    public function newVunidadMedida(){
        $query = "call sp_ventaUnidadMedidaMantenimiento(:flag,:key,:nombre,:sigla,:cmulti, :usuario);";
        $parms = array(
            ':flag' => 1,
            ':key' => $this->_idVunidadMedida,
            ':nombre' => $this->_nombre,
            ':sigla' => $this->_sigla,        
            ':cmulti' => $this->_cantMultiple,
            ':usuario' => $this->_usuario
        );
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
    /*seleccionar registro a editar: VunidadMedida*/
    public function findVunidadMedida(){
       $query = "select
                `id_unidadmedida`,
                `nombre`,
                `sigla`,
                `estado`,
                cantidad_multiple
              from `ven_unidadmedida` WHERE id_unidadmedida = :idd; ";
        
        $parms = array(
            ':idd' => $this->_idVunidadMedida
        );
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
    /*editar registro: VunidadMedida*/
    public function editVunidadMedida(){
        $query = "call sp_ventaUnidadMedidaMantenimiento(:flag,:key,:nombre,:sigla,:cmulti,:usuario);";
        $parms = array(
            ':flag' => 2,
            ':key' => $this->_idVunidadMedida,
            ':nombre' => $this->_nombre,
            ':sigla' => $this->_sigla,         
            ':cmulti' => $this->_cantMultiple,
            ':usuario' => $this->_usuario
        );
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
    /*eliminar varios registros: VunidadMedida*/
    public function deleteVunidadMedidaAll(){
        foreach ($this->_chkdel as $value) {
            $query = "call sp_ventaUnidadMedidaMantenimiento(:flag,:key,:nombre,:sigla,:cmulti,:usuario);";
            $parms = array(
                ':flag' => 3,
                ':key' => Aes::de($value),
                ':nombre' => '',
                ':sigla' => '',     
                ':cmulti' => '',
                ':usuario' => $this->_usuario
            );
            $this->execute($query,$parms);
        }
        $data = array('result'=>1);
        return $data;
    }
    
    public function postDesactivar(){
        $query = "UPDATE ven_unidadmedida SET
                    `estado` = 'I'
                WHERE `id_unidadmedida` = :id;";
        $parms = array(
            ':id' => $this->_idVunidadMedida
        );
        $this->execute($query,$parms);
        $data = array('result'=>1);    
        
        return $data;
    }
    
    public function postActivar(){
        $query = "UPDATE `ven_unidadmedida` SET
                    `estado` = 'A'
                WHERE `id_unidadmedida` = :id;";
        $parms = array(
            ':id' => $this->_idVunidadMedida
        );
        $this->execute($query,$parms);
        $data = array('result'=>1);
        return $data;
    }        
    
     public function getLastUnidadMedida(){
        $query = " SELECT id_unidadmedida,CONCAT(TRIM(nombre),' - ', TRIM(sigla)) AS nombre "
                . " FROM `ven_unidadmedida` WHERE estado = :estado ORDER BY 1 DESC LIMIT 1 ";
        $parms = array(
            ':estado' => 'A',
        );
        $data = $this->queryOne($query,$parms);
        return $data;
    }    
    
}

?>