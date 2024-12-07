<?php
    require_once("c://xampp/htdocs/login/controller/homeController.php");
    session_start();
    $obj = new homeController();
    $correo = $obj->limpiarcorreo($_POST['correo']);
    $password = $obj->limpiarcadena($_POST['password']);
    $rut = $obj->limpiarcadena($_POST['rut']);
    $bandera = $obj->verificarusuario($correo,$password);
    if($bandera){
        $_SESSION['usuario'] = $correo;
        header("Location:panel_control.php");
    }else{
        $error = "<li>Las claves son incorrectas</li>";
        header("Location:login.php?error=".$error);
    }
?>