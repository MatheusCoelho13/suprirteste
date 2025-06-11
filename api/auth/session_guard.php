<?php
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_lifetime', 0);
    session_start();
}

$logado = false;

if (isset($_SESSION["user"])) {
    $logado = true;
    $usuario = $_SESSION["user"];
}
?>