<?php
require '../../api/db/db.php'; // Inclui a conexão PDO
require "../../api/auth/session_guard.php";
require "../../api/auth/verificarpermicao.php";
//! mudar e colocar o role quando e  logadpo para impedir
//! mudar e colocar o role quando e  logadpo para impedir
if ($logado == 0) {
    http_response_code(403);

    header("Location: ../public/pages/login.php?erronaoatorizado");
    exit();
}
if ($roleId == 1) {
    http_response_code(403);
    echo "<script>Swal.fire({
  title: 'Error!',
  text: 'voce nao tem autorição para acessar essa pagina',
  icon: 'error',
  confirmButtonText: 'Cool'
})</script>";
}
// Verifica se a conexão foi criada
if (!isset($pdo)) {
    die("Erro: conexão com o banco de dados não foi estabelecida.");
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Logs de Ações</title>
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../api/js/menu.js"></script>
    <link rel="stylesheet" href="../css/prefitura.css">
    <link rel="stylesheet" href="../css/global.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #2c2c2c;
            /* Fundo preto */
            color: #f1f1f1;
            /* Texto claro */
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #FFA500;
            /* Laranja */
            margin-bottom: 30px;
        }

        ul {
            list-style-type: none;
            padding: 0;
            max-width: 800px;
            margin: 0 auto;
        }

        li {
            background-color: #333;
            /* Escuro */
            border-left: 5px solid #FFA500;
            /* Laranja */
            margin-bottom: 15px;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        li:hover {
            transform: scale(1.02);
            background-color: #444;
            /* Ligeiramente mais claro no hover */
        }

        li strong {
            color: #FFA500;
            /* Laranja */
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
        }

        li::before {
            content: "📝 ";
            margin-right: 5px;
            color: #FFA500;
            /* Laranja no ícone */
        }

        .btn-voltar {
            display: block;
            width: 200px;
            margin: 30px auto 0;
            padding: 10px;
            background-color: #FFA500;
            /* Laranja */
            color: #fff;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .btn-voltar:hover {
            background-color: #e68a00;
            /* Laranja mais escuro no hover */
        }
    </style>
</head>

<body>

    <h2>Logs de Ações</h2>
    <button class="hamburger"><i class="ph ph-list"></i></button>

    <div class="sidebar" id="nav">
        <img src="./assets/logo-supirir.png" id="img-logo" alt="">
        <button class="close-menuu">
            <i class="ph ph-x" id="icon-x"></i>
        </button>
        <div class="menu-item active">🏠 Dashboard</div>
        <?php
        if ($roleName == 'admin') {
            echo ' <div class="menu-item active"><a href="./logs.php">🏠 Logs </a></div>';
            echo ' <div class="menu-item active"><a href="#">🏠 usuarios </a></div>';

            echo "<script>console.log('voce tem premi;ao')</script>";
        } else {
            echo "<script>console.log('voce nao tem premi;ao')</script>";
        }
        ?>
        <div class="menu-item active">🏠 <a href="#"> Relatorios (nao disponivel)</a> </div>
        <div class="menu-item active">🏠 <a href="./pages/Prefeitura.php">Prefeituras(BETA)</a> </div>
        <div class="menu-item active">🏠 <a href="#">Arquivos(nao disponivel)</a> </div>
        <div class="menu-item active">🏠 <a href="#"> Notificaçoes(nao disponivel)</a></div>
        <div class="menu-item active">🏠 <a href="#"> Configuraçao </a></div>


        <div class="profile">
            <img src="<?php echo isset($_SESSION['user']['photo']) && !empty($_SESSION['user']['photo']) ? $_SESSION['user']['photo'] : 'caminho/para/foto_padrao.jpg'; ?>" alt="User   Photo">
            <div class="profile-info">
                <?php
                echo '<h2>' . htmlspecialchars($_SESSION['user']['username']) . '</h2>';
                echo '<h2>' . htmlspecialchars($roleName) . '</h2>';
                ?>
            </div>
        </div>
    </div>
    <ul>
        <?php
        try {
            $stmt = $pdo->query("SELECT * FROM logs ORDER BY data_hora DESC");

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<li><strong>{$row['data_hora']}</strong> - {$row['usuario']} => {$row['acao']}</li>";
            }
        } catch (PDOException $e) {
            echo "<p>Erro ao buscar logs: " . $e->getMessage() . "</p>";
        }
        ?>
    </ul>

    <!-- Botão de voltar -->
    <a href="dashboard.php