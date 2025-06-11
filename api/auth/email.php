<?php
header('Content-Type: application/json');

// Carrega o autoload do Composer (caso tenha usado o Composer)
require '../vendor/autoload.php';
// require '';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Verifica se a requisição é POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém os dados do formulário
    $to = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL); // Definindo o e-mail do destinatário
    $message = filter_input(INPUT_POST, 'justificativa', FILTER_SANITIZE_STRING);
    $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);

    // Validação dos dados
    if (!$to || !$subject || !$message) {
        echo json_encode(["error" => "Todos os campos são obrigatórios."]);
        exit;
        // } else {

        //     echo json_encode(["mensagem" => $message,]);
        //     exit;
    }

    // Cria uma instância do PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Configuração do servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp-relay.brevo.com';  // Defina o servidor SMTP
        $mail->SMTPAuth = true;
        $mail->Username = '82a04b001@smtp-brevo.com';  // Seu e-mail
        $mail->Password = 'SO3rLk69jPnH2XUs';  // Senha do aplicativo
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Definição do remetente e destinatário
        $mail->setFrom($to, $subject);
        $mail->addAddress($to);  // Destinatário

        // Definindo o assunto e o corpo do e-mail
        $mail->Subject = $subject;
        $mail->AddAttachment('../assets/logo-supirir.png'); // Se você tiver um anexo, adicione
        // Definindo o corpo do e-mail com logo embutido
        $body = '<html><body>';
        $body .= '<img src="cid:logo_image" width="150" alt="Logo" /><br>';  // A imagem será embutida aqui
        $body .= '<p>' . nl2br($message) . '</p>';
        $body .= '</body></html>';

        $mail->isHTML(true);  // Define o corpo como HTML
        $mail->Body = $body;

        // Adiciona o logo como anexo para embutir no e-mail
        $mail->addEmbeddedImage('../assets/logo-supirir.png', 'logo_image', 'logo-supirir.png');



        // Envia o e-mail
        $mail->send();
        echo json_encode(["success" => "E-mail enviado com sucesso!"]);
    } catch (Exception $e) {
        echo json_encode(["error" => "Erro ao enviar o e-mail. Erro: {$mail->ErrorInfo}"]);
    }
} else {
    echo json_encode(["error" => "Método inválido. Use POST."]);
}
