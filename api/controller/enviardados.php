<?php
// Define o tipo de resposta como JSON
header('Content-Type: application/json');

// Inclui o autoload do Composer (necessário para usar o PHPMailer)
require '../../vendor/autoload.php';

// Usa as classes do PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Verifica se a requisição é do tipo POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Pega os dados enviados pelo formulário via POST
    $nome = $_POST['username'] ?? '';
    $email = $_POST['password'] ?? '';
    $mensagem = $_POST['message'] ?? '';

    // Validação dos campos: se estiverem vazios, retorna erro específico
    if (empty($nome)) {
        echo json_encode(["status" => "error", "msg" => "O campo nome está vazio."]);
        exit;
    }
    if (empty($email)) {
        echo json_encode(["status" => "error", "msg" => "O campo e-mail está vazio."]);
        exit;
    }
    if (empty($mensagem)) {
        echo json_encode(["status" => "error", "msg" => "O campo mensagem está vazio."]);
        exit;
    }

    // Cria uma nova instância do PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Configurações do servidor SMTP da Brevo
        $mail->isSMTP(); // Usa SMTP
        $mail->Host = 'smtp-relay.brevo.com'; // Endereço do servidor SMTP
        $mail->SMTPAuth = true; // Ativa autenticação SMTP
        $mail->Username = '82a04b001@smtp-brevo.com'; // Usuário SMTP (deve estar verificado na Brevo)
        $mail->Password = 'SO3rLk69jPnH2XUs'; // Senha SMTP gerada no painel da Brevo
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Tipo de criptografia
        $mail->Port = 587; // Porta para STARTTLS

        // Define remetente e destinatário
        $mail->setFrom(' matheuscf6@gmail.com', 'teste111111');
        $mail->addAddress('matheuscf6@gmail.com'); // Email que vai receber a mensagem

        // Opcional: adiciona um anexo (comentado neste exemplo)
        $mail->AddAttachment('./logo-supirir.png');

        // Define que o corpo do e-mail é HTML
        $mail->isHTML(true);
        $mail->Subject = "Nova mensagem do formulário"; // Assunto do e-mail

        // Corpo do e-mail em HTML
        $body = '<html><body>';
        $body .= '<h2>Nova mensagem recebida</h2>';
        $body .= '<p><strong>Nome:</strong> ' . htmlspecialchars($nome) . '</p>';
        $body .= '<p><strong>E-mail:</strong> ' . htmlspecialchars($email) . '</p>';
        $body .= '<p><strong>Mensagem:</strong><br>' . nl2br(htmlspecialchars($mensagem)) . '</p>';
        $body .= '<img src="./logo-supirir" width="150" alt="Logo" />'; // Comentado para não usar imagem embutida
        $body .= '</body></html>';

        // Define o corpo do e-mail
        $mail->Body = $body;

        // Opcional: adiciona uma imagem embutida (desnecessária se a linha acima estiver comentada)
        // $mail->addEmbeddedImage('./logo-supirir.png', 'logo_image', './logo-supirir.png');

        // Envia o e-mail
        $mail->send();

        // Retorna JSON de sucesso
        echo json_encode(["status" => "success", "msg" => "E-mail enviado com sucesso!{$mail->ErrorInfo}"]);
    } catch (Exception $e) {
        // Retorna JSON com erro do PHPMailer, se falhar
        echo json_encode(["status" => "error", "msg" => "Erro ao enviar o e-mail: {$mail->ErrorInfo}"]);
    }
} else {
    // Se a requisição não for POST, retorna erro
    echo json_encode(["status" => "error", "msg" => "Método inválido. Use POST."]);
}
