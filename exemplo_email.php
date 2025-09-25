<?php

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\SimpleMailer;

try {
    // Criar instância do SimpleMailer
    $email = new SimpleMailer();

    // Configurar SMTP (exemplo com Gmail)
    $email->configurarSMTP(
        'smtp.gmail.com',
        587,
        'seu-email@gmail.com',
        'sua-senha-ou-senha-de-app'
    );

    // Configurar remetente
    $email->setRemetente('seu-email@gmail.com', 'Seu Nome');

    // Adicionar destinatário
    $email->addDestinatario('destinatario@exemplo.com', 'Nome do Destinatário');

    // Definir assunto
    $email->setAssunto('Teste de Email');

    // Definir mensagem
    $email->setMensagem('
        <h1>Olá!</h1>
        <p>Este é um teste de email usando a classe SimpleMailer.</p>
        <p>Uma versão simplificada do PHPMailer.</p>
    ');

    // Opcional: adicionar um anexo
    // $email->addAnexo('caminho/para/arquivo.pdf', 'documento.pdf');

    // Enviar o email
    if ($email->enviar()) {
        echo "Email enviado com sucesso!";
    }

    // Limpar destinatários e anexos para enviar para outros
    $email->limpar();

} catch (Exception $e) {
    echo "Erro ao enviar email: " . $e->getMessage();
} 