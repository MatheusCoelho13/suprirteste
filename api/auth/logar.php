<?php
session_start();
include_once '../db/db.php';
header("Content-Type: application/json");

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    echo json_encode([
        "status" => "error",
        "message" => "Por favor, forneça o usuário e a senha"
    ]);
    exit;
}

// Consulta SEM filtrar por role
$query = "SELECT * FROM usuarios WHERE username = :username";
$stmt = $pdo->prepare($query);
$stmt->execute([
    ':username' => $username
]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    if (md5($password) === $user['password']) {
        $_SESSION['user'] = $user;
        echo json_encode([
            "status" => "success",
            "message" => "Login realizado com sucesso",
            "user" => [
                "id" => $user['id'],
                "username" => $user['username'],
                "role" => $user['role'] // vem do banco
            ]
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Usuário ou senha inválidos"
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Usuário ou senha inválidos"
    ]);
}
