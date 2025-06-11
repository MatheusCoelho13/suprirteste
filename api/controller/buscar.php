<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
require_once "../db/db.php";
try {

    if (isset($_GET['nome']) && !empty($_GET['nome'])) {
        $nome = "%" . $_GET['nome'] . "%";

        $stmt = $pdo->prepare("
            SELECT id, first_name, last_name, complemento 
            FROM contacts 
            WHERE 
                CONCAT(first_name, ' ', last_name) LIKE ?
                OR first_name LIKE ?
                OR last_name LIKE ?
                OR complemento LIKE ?
        ");
        $stmt->execute([$nome, $nome, $nome, $nome]);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($resultados ?: []);
        exit;
    } else {
        echo json_encode([]); // Nenhum parâmetro fornecido
        exit;
    }
} catch (Exception $e) {
    echo json_encode(["erro" => $e->getMessage()]);
    exit;
}
