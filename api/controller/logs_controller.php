<?php



function registrar_log($usuario, $acao)
{
    global $conn;
    if (!is_string($usuario)) {
        $usuario = json_encode($usuario); // ou trate como necessário
    }
    $usuario = $conn->real_escape_string($usuario);

    // Protege contra injeção de SQL
    $usuario = $conn->real_escape_string($usuario);
    $acao = $conn->real_escape_string($acao);

    // Insere o log na tabela
    $sql = "INSERT INTO logs (usuario, acao) VALUES ('$usuario', '$acao')";

    if (!$conn->query($sql)) {
        // Opcional: registra erro se falhar
        error_log("Erro ao registrar log: " . $conn->error);
    }
}
