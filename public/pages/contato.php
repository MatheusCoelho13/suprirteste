<?php
session_start();


// try {
//     require "./api/db/db.php";

//     $stmt = $pdo->query("SELECT * FROM prefeituras ORDER BY nome");
//     $prefeituras = $stmt->fetchAll(PDO::FETCH_ASSOC);
// } catch (PDOException $e) {
//     exit("Erro na conexão ou consulta: " . $e->getMessage());
// }
// ?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu de Navegação</title>
    <link rel="stylesheet" href="../css/contato.css">

    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <nav class="navbar">
        <div class="logo">
            <img src="../assets/logo-supirir.png" alt="" id="logo">
        </div>
        <ul>
            <li><a href="./index.php" class="">Home</a></li>
            <li class="dropdown">
                <a href="../../index.php#solucao">Nossos Produtos</a>
                <ul class="dropdown-content">
                    <li><a href="../../index.php#solucao">Cadimov</a></li>
                    <li><a href="../../index.php#solucao">Opinaapp</a></li>
                    <li><a href="../../index.php#solucao">Cidade Online</a></li>
                    <li><a href="../../index.php#solucao">Frota Facil</a></li>

                </ul>
            </li>


            <li><a href="../../index.php#sobrenos">Sobre nos</a></li>
            <li><a href="../../index.php#contato" class="active">Contato</a></li>
        </ul>
        <div class="icons">
            <a href="./login.php">&#128100;</a>
            <a href="./loja">&#128722;</a>
        </div>
</body>
</nav>
<header>
    <h1 id="contato_h1">
        Trabalhe  com a Suprir
    </h1>
    <h2></h2>
    <div class="container">
        <div class="contact-info">
            <h2>Contato</h2>
            <p id="p_contact">Mande mensagem por aqui ou pelas infomaçao a baixo</p>
            <ul>
                <li><span class="icon">&#9742;</span> +55 61 9179-0522</li>
                <li><span class="icon">&#9993;</span> Marcelo@grafor.com.br</li>
                <li><span class="icon">&#127968;</span> endereço</li>
            </ul>
            <div class="social-icons">
                <button onclick="mandar_msg()" class="icon" id="whats"></button>
                <span class="icon">&#128222;</span>
            </div>
        </div>
        <div class="contact-form">
            <form action="../../api/controller/contacts.php" id="formContato" method="post">
                <div class="row">
                    <div class="field">
                        <label for="first-name">Primeiro nome</label>
                        <input type="text" id="first-name" name="first_name" placeholder="First Name">
                    </div>
                    <div class="field">
                        <label for="last-name">Sobrenome</label>
                        <input type="text" id="last-name" name="last_name" placeholder="Last Name">
                    </div>
                </div>
                <div class="row">
                    <div class="field">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Email">
                    </div>
                    <div class="field">
                        <label for="phone">telefone</label>
                        <input type="text" id="phone" name="phone" placeholder="+55(61)9999-9999">
                    </div>
                </div>
                <div class="address-row">
                    <div class="field-small" id="cep-div">
                        <label for="text">cep</label>
                        <input type="text" name="cep" id="cep" maxlength="9">
                    </div>
                    <div class="field-large" id="rua-div">
                        <label for="rua">rua</label>
                        <input type="text" id="rua" name="rua">
                    </div>
                    <div class="field-small" id="numero-div">
                        <label for="numero">Número</label>
                        <input type="text" id="F" name="numero">
                    </div>
                    <div class="field-medium" id="comp-div">
                        <label for="complemento">Complemento</label>
                        <input type="text" id="complemento" name="complemento">
                    </div>
                    <div class="field-medium" id="bairro-div">
                        <label for="bairro">Bairro</label>
                        <input type="text" id="bairro" name="bairro">
                    </div>
                    <div class="field-medium" id="cidade-div">
                        <label for="city">cidade</label>
                        <input type="text" id="cidade" name="cidade">
                    </div>
                    <div class="field-medium" id="estado-div">
                        <label for="estato">Estado</label>
                        <input type="text" id="estado" name="UF">
                    </div>
                </div>

                <div class="row-servicos">
                    <label for="servico">Serviço</label>
                    <select id="servico" name="servico">
                        <option value="Suporte">Suporte</option>
                        <option value="Trabalhe conosco">Trabalhe conosco</option>
                        <option value="Orçamento">Orçamento</option>
                        <option value="Parceria">Parceria</option>
                    </select>
                </div>

                <div class="row-lugar">
                    <label for="local">Local</label>
                    <select id="local" name="lugar">
                        <option selected value="Selecionar a prefeitura">Selecionar a prefeitura</option>
                        <?php foreach ($prefeituras as $p): ?>
                            <option value="<?= htmlspecialchars($p['id']) ?>"><?= htmlspecialchars($p['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="row1">
                    <p id="upload-p">Curriculo</p>
                    <label id="upload-container" for="message">
                        <input type="file" id="file-upload" name="file">
                        <div id="fileDetails" style="display: none; margin-top: 10px;">
                            <span id="fileName">📄 Nenhum arquivo selecionado</span>
                            <button type="remove" id="removeFile">❌ Remover</button>
                        </div>

                    </label>
                </div>
                <div class="row">
                    <button type="submit"><i class="ph ph-chat-text" id="icon-message" style=" font-size: 21px; margin-right: 20px;"></i>Send Message</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.getElementById("file-upload").addEventListener("change", function() {
            let file = this.files[0];
            let label = document.getElementById("upload-container");

            if (file) {
                document.getElementById("fileName").textContent = "📄 " + file.name;
                document.getElementById("fileDetails").style.display = "block";
                label.classList.add("file-selected"); // Remove o texto "Escolher Arquivo"
            }
        });

        document.getElementById("removeFile").addEventListener("click", function(event) {
            event.preventDefault(); // Evita o envio do formulário
            let input = document.getElementById("file-upload");
            let label = document.getElementById("upload-container");

            input.value = ""; // Reseta o input
            document.getElementById("fileName").textContent = "📄 Nenhum arquivo selecionado";
            document.getElementById("fileDetails").style.display = "none";
            label.classList.remove("file-selected"); // Volta a exibir "Escolher Arquivo"
        });
    </script>
    <script>
        // const cepInput = document.getElementById('cep');

        // cepInput.addEventListener('input', function() {
        //     let cep = this.value.replace(/\D/g, ''); // Remove tudo que não for número

        //     if (cep.length > 5) {
        //         cep = cep.substring(0, 5) + '-' + cep.substring(5, 8);
        //     }

        //     this.value = cep;
        // });

        function mandar_msg() {
            const telefone = "5561991857352"; // Substitua pelo número com o código do país e da área
            const mensagem = "Olá, gostaria de mais informações!"; // Mensagem personalizada
            const url = `https://wa.me/${telefone}?text=${encodeURIComponent(mensagem)}`;
        }
    </script>
    <script>
        document.getElementById('cep').addEventListener('input', function() {
            const cep = this.value.replace(/\D/g, '');

            if (cep.length === 8) {
                fetch(`../../api/controller/busca_endereco.php?cep=${cep}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.erro) {
                            alert(data.erro);
                            return;
                        }
                        document.getElementById('rua').value = data.logradouro;
                        document.getElementById('bairro').value = data.bairro;
                        document.getElementById('cidade').value = data.cidade;
                        document.getElementById('estado').value = data.estado;
                        document.getElementById('complemento').value = data.complemento;
                    })
                    .catch(err => {
                        alert("Erro ao buscar endereço.");
                        console.error(err);
                    });
            }
        });
    </script>
    <script src="../../api/js/contact.js"></script>

</header>

</html>