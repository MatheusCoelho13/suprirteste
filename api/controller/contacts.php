<?php

header("Content-Type: application/json");

// Conexão com o banco de dados
require_once "../db/db.php";


// Função para lidar com o upload do arquivo
function uploadFile($file)
{
    $uploadDir = 'uploads/';

    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = uniqid() . '-' . basename($file["name"]);
    $filePath = $uploadDir . $fileName;

    return move_uploaded_file($file["tmp_name"], $filePath) ? $filePath : false;
}

// Verifica se é uma requisição POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lista de campos esperados
    $expectedFields = ['first_name', 'last_name', 'email', 'phone', 'cep', 'rua', 'bairro', 'cidade', 'UF', 'numero', 'complemento', 'servico', 'lugar'];
    $formData = [];

    // Verifica se todos os campos foram preenchidos
    foreach ($expectedFields as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            echo json_encode([
                'status' => false,
                'msg' => "O campo {$field} é obrigatório."
            ]);
            exit;
        }
        $formData[$field] = trim($_POST[$field]);
    }

    // Verifica se o e-mail já existe no banco de dados
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM contacts WHERE email = :email");
    $stmt->execute([':email' => $formData['email']]);
    if ($stmt->fetchColumn()) {
        echo json_encode([
            'status' => false,
            'msg' => "Este e-mail já está registrado. Espere um pouco que você será notificado."
        ]);
        exit;
    }

    // Verifica se o arquivo foi enviado
    $filePath = null;
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $filePath = uploadFile($_FILES['file']);
        if (!$filePath) {
            echo json_encode([
                'status' => false,
                'msg' => 'Erro ao fazer upload do arquivo.'
            ]);
            exit;
        }
    }

    // Insere os dados no banco de dados
    try {
        $stmt = $pdo->prepare("INSERT INTO contacts (first_name, last_name, email, phone, cep, rua, bairro, cidade, UF, numero, complemento, servico, lugar,
         file_path) VALUES (:first_name, :last_name, :email, :phone, :cep, :rua, :numero, :complemento, :bairro, :cidade, :UF, :servico, :lugar, :file_path)");
        $stmt->execute([
            ':first_name' => $formData['first_name'],
            ':last_name' => $formData['last_name'],
            ':email' => $formData['email'],
            ':phone' => $formData['phone'],
            ':cep' => $formData['cep'],
            ':rua' => $formData['rua'],
            ':numero' => $formData['numero'],
            ':complemento' => $formData['complemento'],
            ':bairro' => $formData['bairro'],
            ':cidade' => $formData['cidade'],
            ':UF' => $formData['UF'],
            ':servico' => $formData['servico'],
            ':lugar' => $formData['lugar'],

            ':file_path' => $filePath
        ]);

        echo json_encode([
            'status' => true,
            'msg' => "Dados enviados com sucesso, fique atento ao email!"
        ]);
    } catch (PDOException $e) {
        echo json_encode([
            'message' => 'Erro ao salvar os dados no banco de dados.',
            'error' => $e->getMessage()
        ]);
    }
} else {
    echo json_encode(["message" => "Método inválido. Use POST."]);
}
