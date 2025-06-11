<?php

require "../../api/auth/session_guard.php"; // isso faz a seguran;a do site;

// echo '<<?php
$mensagemErro = '';

if (isset($_GET['erronaoatorizado'])) {
    $mensagemErro = 'Acesso não autorizado. Faça login para continuar.';
}
// print_r($_SESSION['user']); // Exibe todos os dados armazenados na sessão
// echo '</pre>';
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/login.css">
    <!-- Adicionando SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>
    <div class="login-container">
        <h2>Login</h2>
        <form id="login-form">
            <input type="text" id="username" placeholder="Usuário" required>
            <input type="password" id="password" placeholder="Senha" required>
            <button type="submit">Entrar</button>
        </form>
        <?php if (!empty($mensagemErro)): ?>
            <div style="color: red; background-color: #ffe0e0; padding: 10px; margin-bottom: 15px; border: 1px solid red;">
                <?= htmlspecialchars($mensagemErro) ?>
            </div>
        <?php endif; ?>

    </div>
    <!-- <?php
            if (isset($_SESSION['user'])) {
                echo "<script>
        Swal.fire({
            title: 'Info!',
            text: 'Você está logado. Você será redirecionado para o dashboard.',
            icon: 'success',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redireciona para o dashboard após o clique no botão OK
                window.location.href = '../../admin/dashboard.php';
            }
        });
    </script>";
            }
            ?> -->

    <script>
        document.getElementById('login-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;

            const response = await fetch('../../api/auth/logar.php', {
                method: 'POST',
                body: new URLSearchParams({
                    username: username,
                    password: password
                })
            });

            const data = await response.json();

            if (data.status === "success") {
                // Redireciona para o dashboard e salva o usuário na sessão
                localStorage.setItem('user', JSON.stringify(data.user));
                window.location.href = '../../admin/dashboard.php';
            } else {
                // Exibe a mensagem de erro
                document.getElementById('error-message').style.display = 'block';
                document.getElementById('error-message').innerText = data.message;
            }
        });
    </script>
</body>

</html>