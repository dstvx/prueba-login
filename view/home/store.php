<?php
    require_once("c://xampp/htdocs/login/controller/homeController.php");
    $obj = new homeController();
    $correo = $_POST['correo'];
    $password = $_POST['password'];
    $rut = $_POST['rut'];
    $confirmarContraseña = $_POST['confirmarPassword'];
    $error = "";
    if (empty($correo) && empty($password) && empty($confirmarContraseña)){
        $error .= "<li>Los campos son iguales</li>";
        header("Location:signup.php?error=".$error."&&correo=".$correo."&&password=".$password."&&confirmarPassword=".$confirmarContraseña);
    } else if($correo || $password || $confirmarContraseña){
        if($password == $confirmarContraseña){
            if($obj->guardarUsuario($correo,$password, $rut) == false){
                $error .= "<li>Correo agregado</li>";
                header("Location:signup.php?error=".$error."&&correo=".$correo."&&password=".$password."&&confirmarPassword=".$confirmarContraseña);
            }else{
                header("Location:login.php");
            }
        }else{
            $error .= "<li>Las contraseñas son iguales</li>";
            header("Location:signup.php?error=".$error."&&correo=".$correo."&&password=".$password."&&confirmarPassword=".$confirmarContraseña);
        }
    }
?>