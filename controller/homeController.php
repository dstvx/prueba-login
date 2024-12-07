<?php
    class homeController{
        private $MODEL;
        public function __construct()
        {
            require_once("c://xampp/htdocs/login/model/homeModel.php");
            $this->MODEL = new homeModel();
        }
        public function guardarUsuario($correo,$password,$rut){
            if ($this->MODEL->agregarNuevoUsuario($correo, $password, $rut) === false) {
                return "El RUT ingresado no es válido.";
            }
            return "Usuario registrado correctamente.";
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
        public function encriptarcontraseña($password){
            return password_hash($password,PASSWORD_DEFAULT);
        }
        public function verificarusuario($correo, $password) {
            $keydb = $this->MODEL->obtenerclave($correo);
            return (password_verify($password, $keydb)) ? true : false;
        }
    }
?>