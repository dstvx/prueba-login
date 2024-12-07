<?php
    class homeModel{
        private $PDO;
        public function __construct()
        {
            require_once("c://xampp/htdocs/login/config/db.php");
            $pdo = new db();
            $this->PDO = $pdo->conexion();
        }

        public function agregarNuevoUsuario($correo,$password,$rut){
            $statement = $this->PDO->prepare("INSERT INTO usuarios (correo, rut, `PASSWORD`) VALUES (:correo, :rut, :password)");
            $statement->bindParam(":correo",$correo);
            $statement->bindParam(":rut",$rut);
            $statement->bindParam(":password",$password);
            
            if (!$this->validarRut($rut)) {
                return false;
            }
            try {
                $statement->execute();
                return true;
            } catch (PDOException $e) {
                return false;
            }
        }
        public function obtenerclave($correo){
            $statement = $this->PDO->prepare("SELECT PASSWORD FROM usuarios WHERE correo = :correo");
            $statement->bindParam(":correo",$correo);
            return ($statement->execute()) ? $statement->fetch()['PASSWORD'] : false;
        }
        public function validarRut($rut) {
            $rut = str_replace([".", "-"], "", $rut);
            if (preg_match("/^[0-9]{7,8}[0-9Kk]{1}$/", $rut)) {
                return $this->validarRutChileno($rut);
            }
            return false;
        }
        
        private function validarRutChileno($rut) {
            $rutSinDV = substr($rut, 0, -1);
            $dv = strtoupper(substr($rut, -1));
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