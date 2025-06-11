<?php
require "../../api/auth/session_guard.php";
require "../../api/auth/verificarpermicao.php";
require "../../api/db/db.php";

if ($logado !== null) { //* padrao para uso de seguran;a 
    http_response_code(403);

    header("Location: ../public/pages/login.php?erronaoatorizado");
    exit();
}
// Verifica se o ID está disponível
if ($roleId > 0) {
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = :id");
    $stmt->execute(['id' => $roleId]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        die("Usuário não encontrado.");
    }
} else {
    die("ID inválido.");
}
if (isset($_GET['sucess'])) {
    echo "<script>Swal.fire({
  icon: 'Sucess',
  title: 'Sucesso',
  text: 'altera;ao feita com sucesso',
});</script";

}else{
    echo "<script>Swal.fire({
        icon: 'error',
        title: 'ERROR',
        text: 'nao deu certo Consulte o administrador para mais informações',
      });</script";
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Editar Perfil</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: #f7f9fc;
        }

        .header {
            background: #004bff;
            padding: 20px 40px;
            color: white;
        }

        .container {
            max-width: 1000px;
            margin: 40px auto;
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.04);
        }

        .tabs {
            display: flex;
            border-bottom: 1px solid #eee;
            margin-bottom: 30px;
        }

        .tab {
            padding: 10px 20px;
            font-weight: 600;
            color: #555;
            border-bottom: 3px solid transparent;
        }

        .tab.active {
            color: #2c3e50;
            border-color: #2c3e50;
        }

        form {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .form-group {
            flex: 1 1 45%;
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }

        input {
            padding: 12px 14px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
            background-color: #f9f9f9;
        }

        input:focus {
            outline: none;
            border-color: #007bff;
            background-color: #fff;
        }

        .btn {
            padding: 12px 20px;
            background-color: #004bff;
            color: white;
            font-size: 16px;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 30px;
        }

        .btn:hover {
            background-color: #0039cc;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>Editar Perfil</h1>
    </div>

    <div class="container">
        <div class="tabs">
            <div class="tab active">Informações Pessoais</div>
        </div>

        <form action="../../api/auth/config.php" method="post">
            <input type="hidden" name="id" value="<?= htmlspecialchars($usuario['id']) ?>">

            <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($usuario['username']) ?>" required>
            </div>

            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>
            </div>

            <div class="form-group" style="flex: 1 1 100%;">
                <label for="senha">Senha</label>
                <input type="password" name="senha" id="senha" placeholder="Deixe em branco para manter a atual">
            </div>

            <button type="submit" class="btn">Salvar Alterações</button>
        </form>
    </div>

</body>

</html>