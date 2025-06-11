<?php
include_once '../api/db/db.php'; // Conexão com o banco de dados
require "../api/auth/verificarpermicao.php";//verificar a permi;ao
require "../api/auth/session_guard.php"; // isso faz a seguran;a do site;

$conn = new mysqli($host, $username, $password, $dbname);

if($logado != 1){ //* padrao para uso de seguran;a
     http_response_code(403);
 
    header("Location: ../public/pages/login.php?erronaoatorizado");
    exit();
}
// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}


// Verificar se o filtro foi definido
$filtro = isset($_GET['filtro']) ? $_GET['filtro'] : 'nao_vistas';
if (isset($_GET['marcar_respondida']) && is_numeric($_GET['marcar_respondida'])) {
    $id = intval($_GET['marcar_respondida']);
    $conn->query("UPDATE contacts SET respondida = TRUE WHERE id = $id");
    header("Location: dashboard.php?filtro=$filtro");
    exit;
}

                    // Limitação de resultados
                    $limit = 5; // Número de resultados por página
                    $page = isset($_GET['page']) ? intval($_GET['page']) : 1; // Página atual
                    $offset = ($page - 1) * $limit; // Cálculo do deslocamento
                      
                        $user = $_SESSION['user']['username'];
// Consultar mensagens com base no filtro
if ($filtro === 'respondida') {
    $sql = "SELECT id, first_name, last_name, phone, email, file_path, complemento, servico, lugar,  created_at FROM contacts WHERE respondida = 1 LIMIT $limit OFFSET $offset";
    $totalResults = $conn->query("SELECT COUNT(*) as total FROM contacts WHERE respondida = 1 ")->fetch_assoc()['total'];
} elseif ($filtro === 'vistas') {
    $sql = "SELECT id, first_name, last_name, phone, email, file_path, complemento, servico, lugar, created_at FROM contacts WHERE vista = TRUE AND respondida = FALSE LIMIT $limit OFFSET $offset";
    $totalResults = $conn->query("SELECT COUNT(*) as total FROM contacts WHERE vista = 1 AND respondida = 0")->fetch_assoc()['total'];
} elseif ($filtro === 'nao_vistas') {
    $sql = "SELECT id, first_name, last_name, phone, email, file_path, servico, lugar, complemento, created_at FROM contacts WHERE vista = FALSE LIMIT $limit OFFSET $offset";
    $totalResults = $conn->query("SELECT COUNT(*) as total FROM contacts WHERE vista = FALSE")->fetch_assoc()['total'];
} elseif ($filtro === 'nome' && isset($_GET['nome'])) {
    $nome = $conn->real_escape_string($_GET['nome']);
    $sql = "SELECT id, first_name, last_name, phone, email, file_path, complemento, servico, lugar, created_at FROM contacts WHERE first_name LIKE '%$nome%' LIMIT $limit OFFSET $offset";
    $totalResults = $conn->query("SELECT COUNT(*) as total FROM contacts WHERE first_name LIKE '%$nome%'")->fetch_assoc()['total'];
} else {
    $sql = "SELECT id, first_name, last_name, phone, email, file_path, complemento, servico, lugar, created_at FROM contacts  WHERE 1 LIMIT $limit OFFSET $offset";
    $totalResults = $conn->query("SELECT COUNT(*) as total FROM contacts")->fetch_assoc()['total'];
}
                        require_once '../api/controller/logs_controller.php';

                        // Atualizar mensagens como vistas
                        if (isset($_GET['marcar_visto']) && is_numeric($_GET['marcar_visto'])) {
    $id = intval($_GET['marcar_visto']);
    $conn->query("UPDATE contacts SET vista = TRUE WHERE id = $id");
   
    registrar_log($user, "Marcou como vista a mensagem ID $id");
    header("Location: dashboard.php?filtro=$filtro");
    exit;
} else {
    echo "<script>console.log('DEU MERDAaaaaaaaaaaaaaaa');</script>";
}
if (isset($_GET['respondido'])) {

    $id = intval($_GET['respondido']);
    if (isset($id)) {
        echo "<script>console.log('DEU certoooooooo');</script>";
        echo "<script>console.log($id);</script>";
        $conn->query("UPDATE contacts SET respondida= TRUE WHERE id = $id");
    } else {
        echo "<script>console.log('DE o cu certoooooooo');</script>";
    }
    $res = " foi aceito";
    $conn->query("UPDATE contacts SET vista = TRUE WHERE id = $id");
  
    registrar_log($_SESSION['user']['username'], "Marcou como respondida a mensagem ID $id e colocou $res ");
    echo "<script>console.log('teSSsteetsetptste');</script>";
} else {
    echo "<script>console.log('DEU MERDAA');</script>";
}
// Consultar total de resultados para a paginação
$totalResults = $conn->query("SELECT COUNT(*) as total FROM contacts WHERE respondida = FALSE")->fetch_assoc()['total'];
$totalPages = ceil($totalResults / $limit);

$result = $conn->query($sql);


?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Suporte</title>
    <link rel="stylesheet" href="./css/dashboard.css">
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/regular/style.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/fill/style.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   
    <style>

    </style>
</head>

<body>

    <div class="search-container">
        <input type="text" id="pesquisa" placeholder="Pesquisar por palavras chaves">
    </div>
    <div id="loading" style="display: none;">Carregando...</div>
    <div class="resultados_div">
        <ul id="resultados"></ul>
    </div>
    <script>
        document.getElementById("pesquisa").addEventListener("input", function() {
            let nome = this.value;
            const loadingIndicator = document.getElementById("loading");

            if (nome.length > 0) {
                loadingIndicator.style.display = "block"; // Mostrar o indicador de carregamento

                fetch("../api/controller/buscar.php?nome=" + encodeURIComponent(nome))
                    .then(response => response.json())
                    .then(data => {
                        let lista = document.getElementById("resultados");
                        lista.innerHTML = "";
                        loadingIndicator.style.display = "none"; // Esconder o indicador de carregamento

                        // Se a resposta não for um array, mostra o erro
                        if (!Array.isArray(data)) {
                            console.error("Erro na API:", data);
                            lista.innerHTML = "<li>Erro na busca</li>";
                            return;
                        }

                        // Renderiza a lista corretamente
                        if (data.length === 0) {
                            lista.innerHTML = "<li>Nenhum resultado encontrado</li>";
                        } else {
                            data.forEach(item => {
                                let li = document.createElement("li");
                                li.innerHTML = `<a href="dashboard.php?filtro=nome&nome=${encodeURIComponent(item.first_name)}">${item.first_name}</a>`;

                                lista.appendChild(li);
                            });
                        }
                    })
                    .catch(error => {
                        console.error("Erro no fetch:", error);
                        loadingIndicator.style.display = "none"; // Esconder o indicador de carregamento em caso de erro
                    });
            } else {
                document.getElementById("resultados").innerHTML = ""; // Limpar resultados se a pesquisa estiver vazia
            }
        });
    </script>

    <i class="ph ph-magnifying-glass"></i>

    <!-- Menu de Filtros -->
    <button class="hamburger"><i class="ph ph-list"></i></button>
    <div class="menu">
        <a href="dashboard.php?filtro=respondida" class="<?= $filtro === 'respondidas' ? 'ativo' : '' ?>">Respondidas</a>
        <a href="dashboard.php?filtro=vistas" class="<?= $filtro === 'vistas' ? 'ativo' : '' ?>">Vistas</a>
        <a href="dashboard.php?filtro=nao_vistas" class="<?= $filtro === 'nao_vistas' ? 'ativo' : '' ?>">Não Vistas</a>
        <a href="../api/controller/exel.php">Exportar dados para Excel</a>
    </div>

    <div class="sidebar" id="nav">
        <img src="../public/assets/logo-supirir.png" id="img-logo" alt="">
        <button class="close-menuu">
            <i class="ph ph-x" id="icon-x"></i>
        </button>
        <div class="menu-item active">🏠 Dashboard</div>
        <?php
        if ($roleName == 'admin') {
            echo ' <div class="menu-item active"><a href="./pages/logs.php">🏠 Logs </a></div>';
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
        <div class="menu-item active">🏠 <a href="./pages/config.php"> Configuraçao </a></div>


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

    <div class="dashboard">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $email = $row["email"];

                echo '<div class="card" id="card">';
                echo '<div class="card-actions">';
                if ($filtro !== 'vistas' && $filtro !== 'respondida') {
                    echo "<script>console.log('Você está logados');</script>";
                    echo '<a href="dashboard.php?marcar_visto=' . $row["id"] . '&filtro=nao_vistas" class="btn"><i class="ph ph-eye"></i></a>';  // Ícone de olho
                }
                echo '<a onclick="abrirjanela(\'bom\'); event.preventDefault();" href="dashboard.php?respondido=' . $row["id"] . '" class="btn"><i class="ph ph-check"></i></a>';

                // echo '<a onclick="abrirjanela(\'bom\') return false;" href="dashboard.php?respondido=' . $row["id"] . '" "  class="btn"><i class="ph ph-check"></i></a>';  // Ícone de olho

                // echo '<a href="#" class="btn1" id="btn" onclick="abrirjanela(\'bom\'); return false;"><i class="ph ph-check"></i></a>';
                echo '<a href="dashboard.php?res" class="btn1" id="btn" onclick="abrirjanela(\'ruim\'); return false;"><i class="ph ph-x"></i></a>';
                echo '</div>';

                echo '<p class="nome">' . htmlspecialchars($logado) . " " . htmlspecialchars($row['last_name']) . '</p>';
                echo '<p class="email">' . htmlspecialchars($row["email"]) . '</p>';
                echo '<p class="data">' . htmlspecialchars($row["created_at"]) . '</p>';
                echo '<p class="bairro">' . htmlspecialchars($row["complemento"]) . '</p>';
                echo '<p class="area_de_interesse">' . htmlspecialchars($row["lugar"]) . '</p>';

                echo '<p class="area_de_interesse">' . htmlspecialchars($row["servico"]) . '</p>';
                if (!empty($row["file_path"])) {
                    echo '<p class="curriculo"><a href="' . htmlspecialchars($row["file_path"]) . '" download>📄 Baixar Currículo</a></p>';
                } else {
                    echo '<p class="curriculo">Sem currículo</p>';
                }
                echo '</div>';
            }
        } else {
            echo "<p>Nenhuma mensagem encontrada.</p>";
        }
        ?>
    </div>

    <div class="pagination">
        <?php
        for ($i = 1; $i <= $totalPages; $i++) {
            echo '<a href="dashboard.php?filtro=' . $filtro . '&page=' . $i . '" class="' . ($i == $page ? 'active' : '') . '">' . $i . '</a>';
        }
        ?>
    </div>
    <script src="../api/js/static.js"></script>
    <script type="text/javascript">
        // Passar o valor da sessão PHP para o JavaScript
        const email = "<?php echo $email ?>";
    </script>
</body>

</html>