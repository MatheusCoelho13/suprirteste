<?php
require __DIR__ . "/session_guard.php"; // caminho absoluto relativo ao próprio arquivo

$roleId = $_SESSION['user']['role'] ?? null;
// $nameuser=  $_SESSION['user']['']
$roleName = ($roleId == 1) ? 'admin' : 'user';

