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
    }

?>