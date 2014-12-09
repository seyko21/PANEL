<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 07-11-2014 00:11:47 
* Descripcion : vproductoModel.php
* ---------------------------------------
*/ 

class vproductoModel extends Model{

    private $_idVproducto;
    private $_chkdel;
    private $_nombre;
    private $_precio;
    private $_idUM;
    private $_idMoneda;
    private $_usuario;
    private $_incigv;
    
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
        $this->_idVproducto   = Aes::de(Formulario::getParam("_idVproducto"));    /*se decifra*/
        $this->_usuario     = Session::get("sys_idUsuario");
        
        $this->_chkdel  = Formulario::getParam(VPROD.'chk_delete');
        $this->_nombre     = Formulario::getParam(VPROD.'txt_descripcion');
        $this->_precio     = str_replace(',','',Formulario::getParam(VPROD.'txt_precio')); 
        $this->_idUM     = Formulario::getParam(VPROD.'lst_unidadMedida');
        $this->_idMoneda     = Formulario::getParam(VPROD.'lst_moneda');
        $this->_incigv         = Formulario::getParam(VPROD.'lst_igv');
        
        $this->_iDisplayStart  = Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength = Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   = Formulario::getParam("iSortingCols");
        $this->_sSearch        = Formulario::getParam("sSearch");
    }
    
    /*data para el grid: Vproducto*/
    public function getVproducto(){
        $aColumns       =   array("","descripcion","5","incligv","7","precio","estado" ); //para la ordenacion y pintado en html
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
        
        $query = "call sp_ventaProductoGrid(:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ":iDisplayStart" => $this->_iDisplayStart,
            ":iDisplayLength" => $this->_iDisplayLength,
            ":sOrder" => $sOrder,
            ":sSearch" => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    /*grabar nuevo registro: Vproducto*/
    public function newVproducto(){
        $query = "call sp_ventaProductoMantenimiento(:flag,:key,:nombre,:precio,:idum,:moneda,:incligv,:usuario);";
        $parms = array(
            ':flag' => 1,
            ':key' => $this->_idVproducto,
            ':nombre' => $this->_nombre,
            ':precio' => $this->_precio,            
            ':idum' => $this->_idUM, 
            ':moneda' => $this->_idMoneda, 
            ':incligv' => $this->_incigv, 
            ':usuario' => $this->_usuario
        );
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
    /*seleccionar registro a editar: Vproducto*/
    public function findVproducto(){
       $query = "SELECT
        `id_producto`,
        `descripcion`,
        `precio`,
        `estado`,
        `id_unidadmedida`,
        `moneda`,
        incligv
      FROM `ven_producto` WHERE id_producto = :idd; ";
        
        $parms = array(
            ':idd' => $this->_idVproducto
        );
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
    /*editar registro: Vproducto*/
    public function editVproducto(){
        $query = "call sp_ventaProductoMantenimiento(:flag,:key,:nombre,:precio,:idum,:moneda,:incligv,:usuario);";
        $parms = array(
            ':flag' => 2,
            ':key' => $this->_idVproducto,
            ':nombre' => $this->_nombre,
            ':precio' => $this->_precio,            
            ':idum' => $this->_idUM, 
            ':moneda' => $this->_idMoneda, 
             ':incligv' => $this->_incigv, 
            ':usuario' => $this->_usuario
        );
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
    /*eliminar varios registros: Vproducto*/
    public function deleteVproductoAll(){
        foreach ($this->_chkdel as $value) {
            $query = "call sp_ventaProductoMantenimiento(:flag,:key,:nombre,:precio,:idum,:moneda,:incligv,:usuario);";
            $parms = array(
                ':flag' => 3,
                ':key' => Aes::de($value),
                ':nombre' => '',
                ':precio' => '',   
                ':idum' => '',
                ':moneda' => '', 
                ':incligv' => '', 
                ':usuario' => $this->_usuario
            );
            $this->execute($query,$parms);
        }
        $data = array('result'=>1);
        return $data;
    }
    
    public function getUnidadMedida(){
        $query = "SELECT id_unidadmedida,CONCAT(TRIM(nombre),' - ', TRIM(sigla)) AS nombre FROM ven_unidadmedida WHERE estado = :estado; ";
        
        $parms = array(
            ':estado' => 'A'
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }    
    
    public function postDesactivar(){
        $query = "UPDATE ven_producto SET
                    `estado` = 'I'
                WHERE `id_producto` = :id;";
        $parms = array(
            ':id' => $this->_idVproducto
        );
        $this->execute($query,$parms);
        $data = array('result'=>1);    
        
        return $data;
    }
    
    public function postActivar(){
        $query = "UPDATE `ven_producto` SET
                    `estado` = 'A'
                WHERE `id_producto` = :id;";
        $parms = array(
            ':id' => $this->_idVproducto
        );
        $this->execute($query,$parms);
        $data = array('result'=>1);
        return $data;
    }         

    public function getMoneda(){
        $query = "select
                `id_moneda` as id,
                CONCAT(`sigla`,' - ',`descripcion`) as descripcion
              from `pub_moneda`
              where estado = :estado; ";
        
        $parms = array(
            ':estado' => 'A'
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }    
        
    
}

?>