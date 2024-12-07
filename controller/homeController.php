<?php
    class homeController{
        private $MODEL;
        public function __construct()
        {
            require_once("c://xampp/htdocs/login/model/homeModel.php");
            $this->MODEL = new homeModel();
        }
        public function guardarUsuario($correo,$contraseña,$rut){
            $valor = $this->MODEL->agregarNuevoUsuario($this->limpiarcorreo($correo), $this->encriptarcontraseña($this->limpiarcadena($contraseña)), $this->limpiarcadena($rut));
            return $valor;
        }
        public function limpiarcadena($campo){
            $campo = strip_tags($campo);
            $campo = filter_var($campo, FILTER_UNSAFE_RAW);
            $campo = htmlspecialchars($campo);
            return $campo;
        }
        public function limpiarcorreo($campo){
            $campo = strip_tags($campo);
            $campo = filter_var($campo, FILTER_SANITIZE_EMAIL);
            $campo = htmlspecialchars($campo);
            return $campo;
        }
        public function encriptarcontraseña($contraseña){
            return password_hash($contraseña,PASSWORD_DEFAULT);
        }
        public function verificarusuario($correo, $contraseña) {
            $keydb = $this->MODEL->obtenerclave($correo);
            return (password_verify($contraseña, $keydb)) ? true : false;
        }
    }
?>