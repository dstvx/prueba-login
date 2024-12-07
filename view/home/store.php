<?php
    require_once("c://xampp/htdocs/login/controller/homeController.php");
    $obj = new homeController();
    $correo = $_POST['correo'];
    $password = $_POST['password'];
    $rut = $_POST['rut'];
    $confirmarPassword = $_POST['confirmarPassword'];
    $error = "";
    if (empty($correo) && empty($password) && empty($confirmarPassword)){
        $error .= "<li>Los campos son iguales</li>";
        header("Location:signup.php?error=".$error."&&correo=".$correo."&&password=".$password."&&confirmarPassword=".$confirmarPassword);
    } else if($correo || $password || $confirmarPassword){
        if($password == $confirmarPassword){
            if($obj->guardarUsuario($correo,$password, $rut) == false){
                $error .= "<li>Correo agregado</li>";
                header("Location:signup.php?error=".$error."&&correo=".$correo."&&password=".$password."&&confirmarPassword=".$confirmarPassword);
            }else{
                header("Location:login.php");
            }
        }else{
            $error .= "<li>Las contraseñas son iguales</li>";
            header("Location:signup.php?error=".$error."&&correo=".$correo."&&password=".$password."&&confirmarPassword=".$confirmarPassword);
        }
    }
?>