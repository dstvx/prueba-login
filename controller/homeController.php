<?php
    class homeController{
        private $MODEL;
        public function __construct()
        {
            require_once("c://xampp/htdocs/login/model/homeModel.php");
            $this->MODEL = new homeModel();
        }
        public function guardarUsuario($correo,$password,$rut){
            if (!$this->validarRut($rut)) {
                $error = "<li>El RUT ingresado no es válido.</li>";
                
                header("Location:signup.php?error=".$error."&rut=".$rut."&correo=".$correo."&password=".$password);
            }
            $valor = $this->MODEL->agregarNuevoUsuario($this->limpiarcorreo($correo), $this->encriptarcontraseña($this->limpiarcadena($password)), $this->limpiarcadena($rut));
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
        public function encriptarcontraseña($password){
            return password_hash($password,PASSWORD_DEFAULT);
        }
        public function verificarusuario($correo, $password) {
            $keydb = $this->MODEL->obtenerclave($correo);
            return (password_verify($password, $keydb)) ? true : false;
        }
        public function validarRut($rut) {
            // Eliminar puntos y guiones
            $rut = str_replace([".", "-"], "", $rut);
        
            // Verificar que el RUT contenga solo números y un carácter de verificación
            if (preg_match("/^[0-9]{7,8}[0-9Kk]{1}$/", $rut)) {
                // Llamar a la función que valida el RUT completo (algoritmo de validación de RUT chileno)
                return $this->validarRutChileno($rut);
            }
            return false;
        }
        
        private function validarRutChileno($rut) {
            $rutSinDV = substr($rut, 0, -1);  // Extrae el RUT sin el dígito verificador
            $dv = strtoupper(substr($rut, -1));  // Extrae el dígito verificador
        
            // Algoritmo de validación del dígito verificador
            $suma = 0;
            $factor = 2;
        
            for ($i = strlen($rutSinDV) - 1; $i >= 0; $i--) {
                $suma += $rutSinDV[$i] * $factor;
                $factor = ($factor == 7) ? 2 : $factor + 1;
            }
        
            $dvCalculado = 11 - ($suma % 11);
            if ($dvCalculado == 11) {
                $dvCalculado = '0';
            } elseif ($dvCalculado == 10) {
                $dvCalculado = 'K';
            }
        
            return $dv == $dvCalculado;
        }
        
    }
?>