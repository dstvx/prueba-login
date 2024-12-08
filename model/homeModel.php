<?php
    class homeModel{
        private $PDO;
        public function __construct()
        {
            require_once("c://xampp/htdocs/login/config/db.php");
            $pdo = new db();
            $this->PDO = $pdo->conexion();
        }

        public function agregarNuevoUsuario($correo, $password, $rut){
            $resultadoRut = $this->phpRule_ValidarRut($rut);
            if ($resultadoRut['error']) {
                return $resultadoRut['msj'];
            }
            $checkEmail = $this->PDO->prepare("SELECT COUNT(*) FROM usuarios WHERE correo = :correo");
            $checkEmail->bindParam(":correo", $correo);
            $checkEmail->execute();
            
            $checkRut = $this->PDO->prepare("SELECT COUNT(*) FROM usuarios WHERE rut = :rut");
            $checkRut->bindParam(":rut", $rut);
            $checkRut->execute();
            
            if ($checkEmail->fetchColumn() > 0) {
                return "El correo electrónico ya está registrado.";
            }
        
            if ($checkRut->fetchColumn() > 0) {
                return "El RUT ya está registrado en el sistema.";
            }
            $statement = $this->PDO->prepare("INSERT INTO usuarios (correo, rut, `PASSWORD`) VALUES (:correo, :rut, :password)");
            $statement->bindParam(":correo", $correo);
            $statement->bindParam(":rut", $rut);
            $statement->bindParam(":password", $password);
            
            try {
                $result = $statement->execute();
                
                if ($result) {
                    return "Usuario registrado correctamente.";
                } else {
                    $errorInfo = $statement->errorInfo();
                    error_log("Insertion Error: " . print_r($errorInfo, true));
                    return "Error desconocido al registrar usuario.";
                }
            } catch (PDOException $e) {
                if ($e->getCode() == '23000') {
                    if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                        if (strpos($e->getMessage(), 'rut') !== false) {
                            return "El RUT ya está registrado en el sistema.";
                        }
                        if (strpos($e->getMessage(), 'correo') !== false) {
                            return "El correo electrónico ya está registrado.";
                        }
                    }
                }
                
                error_log("PDO Exception: " . $e->getMessage());
                return "Error en la base de datos: " . $e->getMessage();
            }
        }
        public function obtenerclave($correo){
            $statement = $this->PDO->prepare("SELECT PASSWORD FROM usuarios WHERE correo = :correo");
            $statement->bindParam(":correo",$correo);
            return ($statement->execute()) ? $statement->fetch()['PASSWORD'] : false;
        }
        public function phpRule_ValidarRut($rut) {

            if ((empty($rut)) || strlen($rut) < 3) {
                return array('error' => true, 'msj' => 'RUT vacío o con menos de 3 caracteres.');
            }

            $parteNumerica = str_replace(substr($rut, -2, 2), '', $rut);

            if (!preg_match("/^[0-9]*$/", $parteNumerica)) {
                return array('error' => true, 'msj' => 'La parte numérica del RUT sólo debe contener números.');
            }

            $guionYVerificador = substr($rut, -2, 2);

            if (strlen($guionYVerificador) != 2) {
                return array('error' => true, 'msj' => 'Error en el largo del dígito verificador.');
            }

            if (!preg_match('/(^[-]{1}+[0-9kK]).{0}$/', $guionYVerificador)) {
                return array('error' => true, 'msj' => 'El dígito verificador no cuenta con el patrón requerido');
            }

            if (!preg_match("/^[0-9.]+[-]?+[0-9kK]{1}/", $rut)) {
                return array('error' => true, 'msj' => 'Error al digitar el RUT');
            }

            $rutV = preg_replace('/[\.\-]/i', '', $rut);
            $dv = substr($rutV, -1);
            $numero = substr($rutV, 0, strlen($rutV) - 1); // Número del RUT
            $i = 2;
            $suma = 0;

            foreach (array_reverse(str_split($numero)) as $v) {
                if ($i == 8) {
                    $i = 2;
                }
                $suma += $v * $i;
                ++$i;
            }

            $dvr = 11 - ($suma % 11);
            if ($dvr == 11) {
                $dvr = 0;
            }
            if ($dvr == 10) {
                $dvr = 'K';
            }

            if ($dvr == strtoupper($dv)) {
                return array('error' => false, 'msj' => 'RUT ingresado correctamente.');
            } else {
                return array('error' => true, 'msj' => 'El RUT ingresado no es válido.');
            }
        }
    }

?>