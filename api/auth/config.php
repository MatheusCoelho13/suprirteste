<?php
include_once '../db/db.php';

// Dados do formulário
$id    = isset($_POST['id']) ? (int) $_POST['id'] : 0;
$nome  = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

// Validação básica
if ($id <= 0 || empty($nome) || empty($email)) {
    die("Dados inválidos.");
}

// Criptografa senha apenas se foi fornecida
$senhaCriptografada = !empty($senha) ? password_hash($senha, PASSWORD_DEFAULT) : null;

try {
    if ($senhaCriptografada) {
        $stmt = $pdo->prepare("UPDATE usuarios SET username = :nome, email = :email, senha = :senha WHERE id = :id");
        $stmt->execute([
            ':nome'  => $nome,
            ':email' => $email,
            ':senha' => $senhaCriptografada,
            ':id'    => $id
        ]);
    } else {
        $stmt = $pdo->prepare("UPDATE usuarios SET username = :nome, email = :email WHERE id = :id");
        $stmt->execute([
            ':nome'  => $nome,
            ':email' => $email,
            ':id'    => $id
        ]);
    }

    header("Location: ../../admin/pages/config.php?sucess"); // ajuste conforme necessário
    exit();
} catch (PDOException $e) {
    echo "Erro ao atualizar: " . $e->getMessage();
}
