<?php
    require_once("c://xampp/htdocs/login/controller/homeController.php");
    $obj = new homeController();
    $correo = $_POST['correo'];
    $password = $_POST['password'];
    $rut = $_POST['rut'];
    $confirmarPassword = $_POST['confirmarPassword'];
    
    $error = "";
    if (empty($correo) || empty($password) || empty($confirmarPassword)){
        $error = "Todos los campos son obligatorios";
        header("Location:signup.php?error=".urlencode($error)."&correo=".$correo."&rut=".$rut);
        exit();
    }
    
    if($password != $confirmarPassword){
        $error = "Las contraseñas no coinciden";
        header("Location:signup.php?error=".urlencode($error)."&correo=".$correo."&rut=".$rut);
        exit();
    }
    
    $resultado = $obj->guardarUsuario($correo, $password, $rut);
    
    if (strpos($resultado, "correctamente") !== false) {
        header("Location:login.php");
    } else {
        header("Location:signup.php?error=".urlencode($resultado)."&correo=".$correo."&rut=".$rut);
    }
    exit();
?>