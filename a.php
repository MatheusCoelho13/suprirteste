<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Conheça Nossas Soluções</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 40px 0;
            background: #fff;
        }

        h2 {
            text-align: center;
            margin-bottom: 60px;
        }

        .solucao {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 40px;
            margin: 100px 0;
            opacity: 0;
            transform: translateX(100px);
            transition: all 0.8s ease;
        }

        .solucao.reveal-left {
            transform: translateX(-100px);
        }

        .solucao.visible {
            opacity: 1;
            transform: translateX(0);
        }

        .solucao:nth-child(even) {
            flex-direction: row-reverse;
        }

        .solucao img {
            max-width: 150px;
        }

        .descricao {
            max-width: 500px;
        }
    </style>
</head>

<body>

    <h2>Conheça Nossas Soluções</h2>

    <div class="solucao reveal-right">
        <img src="logo-cadimov.png" alt="Cadimov">
        <div class="descricao">
            <h3>Cadimov</h3>
            <p>Cadimov é uma solução inteligente desenvolvida para...</p>
        </div>
    </div>

    <div class="solucao reveal-left">
        <img src="logo-opinaapp.png" alt="Opinaapp">
        <div class="descricao">
            <h3>Opinaapp</h3>
            <p>Opinaapp é uma ferramenta inovadora que facilita...</p>
        </div>
    </div>

    <div class="solucao reveal-right">
        <img src="logo-cidadeonline.png" alt="Cidade Online">
        <div class="descricao">
            <h3>Cidade Online</h3>
            <p>Bem-vindo à nova era da cidadania digital...</p>
        </div>
    </div>

    <div class="solucao reveal-left">
        <img src="logo-frotafacil.png" alt="Frota Fácil">
        <div class="descricao">
            <h3>Frota Fácil</h3>
            <p>O Frota Fácil é uma solução completa para o controle...</p>
        </div>
    </div>

    <script>
        const elementos = document.querySelectorAll('.solucao');

        function animarAoScroll() {
            const alturaTela = window.innerHeight;
            elementos.forEach(el => {
                const topoElemento = el.getBoundingClientRect().top;
                if (topoElemento < alturaTela - 100) {
                    el.classList.add('visible');
                }
            });
        }

        window.addEventListener('scroll', animarAoScroll);
        window.addEventListener('load', animarAoScroll);
    </script>

</body>

</html>