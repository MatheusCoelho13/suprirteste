<?php
require "../../api/auth/session_guard.php";
require "../../api/auth/verificarpermicao.php";
require "../../api/db/db.php";

// Redireciona se não estiver logado
if ($logado == 0) {
    header("Location: ../public/pages/login.php?erronaoatorizado");
    exit();
}

// Exibe SweetAlert se o usuário não tem permissão
if ($roleId == 0) {
?>
    <!DOCTYPE html>
    <html lang="pt-BR">

    <head>
        <meta charset="UTF-8" />
        <title>Acesso Negado</title>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>

    <body>
        <script>
            Swal.fire({
                title: 'Erro!',
                text: 'Você não tem autorização para acessar essa página',
                icon: 'error',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = '../../admin/dashboard.php';
            });
        </script>
    </body>

    </html>
<?php
    exit();
}

// Inserir prefeitura
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nova_prefeitura'])) {
    $nome = trim($_POST['nova_prefeitura']);
    if ($nome !== '') {
        $stmt = $pdo->prepare("INSERT INTO prefeituras (nome) VALUES (?)");
        $stmt->execute([$nome]);
    }
    header("Location: prefeitura.php");
    exit;
}

// Remover prefeitura
if (isset($_GET['remover'])) {
    $id = intval($_GET['remover']);
    $stmt = $pdo->prepare("DELETE FROM prefeituras WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: prefeitura.php");
    exit;
}

// Buscar todas as prefeituras
$stmt = $pdo->query("SELECT * FROM prefeituras ORDER BY nome");
$prefeituras = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <title>Administração de Prefeituras</title>
    <link rel="stylesheet" href="../css/prefitura.css">
    <link rel="stylesheet" href="../css/global.css">
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <button class="hamburger"><i class="ph ph-list"></i></button>

    <div class="sidebar" id="nav">
        <img src="./assets/logo-supirir.png" id="img-logo" alt="Logo Supirir">
        <button class="close-menuu">
            <i class="ph ph-x" id="icon-x"></i>
        </button>

        <div class="menu-item">🏠 <a href="../../admin/dashboard.php">Dashboard</a></div>

        <?php if ($roleName === 'admin'): ?>
            <div class="menu-item"><a href="#">📋 Logs</a></div>
            <div class="menu-item"><a href="#">👤 Usuários</a></div>
        <?php endif; ?>

        <div class="menu-item">📈 <a href="#">Relatórios (em breve)</a></div>
        <div class="menu-item"><a href="./Prefeitura.php">🏛️ Prefeituras (BETA)</a></div>
        <div class="menu-item"><a href="#">📂 Arquivos</a></div>
        <div class="menu-item"><a href="#">🔔 Notificações</a></div>

        <div class="profile">
            <img src="<?= isset($_SESSION['user']['photo']) && !empty($_SESSION['user']['photo']) ? $_SESSION['user']['photo'] : 'caminho/para/foto_padrao.jpg' ?>" alt="Foto do usuário">
            <div class="profile-info">
                <h2><?= htmlspecialchars($_SESSION['user']['username']) ?></h2>
                <h3><?= htmlspecialchars($roleName) ?></h3>
            </div>
        </div>
    </div>

    <main class="main-content">
        <h1>Prefeituras Cadastradas</h1>

        <form method="POST">
            <input type="text" name="nova_prefeitura" placeholder="Nova prefeitura" required>
            <button type="submit">Adicionar</button>
        </form>

        <ul>
            <?php foreach ($prefeituras as $p): ?>
                <li>
                    <?= htmlspecialchars($p['nome']) ?>
                    <a href="?remover=<?= $p['id'] ?>" class="remover" onclick="return confirm('Remover esta prefeitura?')">[remover]</a>
                </li>
            <?php endforeach; ?>
        </ul>
    </main>

    <script src="../../api/js/menu.js"></script>
</body>

</html>