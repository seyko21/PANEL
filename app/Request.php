<?php
/*
 * --------------------------------------
 * creado por:  RDCC
 * fecha: 03.01.2014
 * Request.php
 * --------------------------------------
 */
class Request{
    
    private $_modulo;
    private $_controlador;
    private $_metodo;
    private $_argumantos;
    private static $_instancias = array();
    
    public function __construct() {
        self::$_instancias[] = $this;
        if(count(self::$_instancias) == 1){
            if(isset($_GET['url'])){
                $url = filter_input(INPUT_GET,'url',FILTER_SANITIZE_URL);
                $url = explode('/',$url);
                $url = array_filter($url);

                $this->_modulo      = strtolower(array_shift($url));
                $this->_controlador = array_shift($url);
                $this->_metodo      = array_shift($url);
                $this->_argumantos  = $url;
            }



            if(!$this->_controlador){
                $this->_controlador = DEFAULT_CONTROLLER;
            }

            if(!$this->_metodo){
                $this->_metodo = 'index';
            }

            if(!isset($this->_argumantos)){
                $this->_argumantos = array(); #array vacio
            }
        }else{
            throw new Exception('Error: class Request ya se instancio; para acceder a la instancia ejecutar: Obj::run()->NOMBRE_REGISTRO');
        }
    }
    
    public function getModulo(){
        if($this->_modulo == ''){
            return 'index';
        }else{
            return $this->_modulo;
        }
    }
    
    public function getControlador(){
        return $this->_controlador;
    }
    
    public function getMetodo(){
        return $this->_metodo;
    }
    
    public function getArgumentos(){
        return $this->_argumantos;
    }
    
}
?>
