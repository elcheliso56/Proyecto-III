<?php
session_start();

if(!isset($_SESSION['usuario_id']) && (!isset($_GET['pagina']) || ($_GET['pagina'] != 'login' && $_GET['pagina'] != 'publicidad'))){
    header("Location: ?pagina=publicidad");
    exit;
}

if(isset($_GET['pagina']) && $_GET['pagina'] == 'logout'){
    session_destroy();
    header("Location: ?pagina=login");
    exit;
}

$pagina = "principal"; 

if (!empty($_GET['pagina'])){ 
    $pagina = basename($_GET['pagina']);  
}

if(is_file("controlador/".$pagina.".php")){ 
    require_once("controlador/".$pagina.".php");
} else {
    require_once("vista/404.php");
}

?>