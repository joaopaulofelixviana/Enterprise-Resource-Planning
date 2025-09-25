<?php

namespace PHPMailer\PHPMailer;


class SimpleMailer
{
    private $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);
        $this->mailer->CharSet = 'UTF-8';
    }

    /**
     * Configura o servidor SMTP
     */
    public function configurarSMTP($host, $porta, $usuario, $senha, $seguranca = 'tls')
    {
        $this->mailer->isSMTP();
        $this->mailer->Host = $host;
        $this->mailer->Port = $porta;
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = $usuario;
        $this->mailer->Password = $senha;
        $this->mailer->SMTPSecure = $seguranca;
    }

    /**
     * Define o remetente do email
     */
    public function setRemetente($email, $nome = '')
    {
        $this->mailer->setFrom($email, $nome);
    }

    /**
     * Adiciona um destinatário
     */
    public function addDestinatario($email, $nome = '')
    {
        $this->mailer->addAddress($email, $nome);
    }

    /**
     * Define o assunto do email
     */
    public function setAssunto($assunto)
    {
        $this->mailer->Subject = $assunto;
    }

    /**
     * Define o corpo do email
     */
    public function setMensagem($mensagem, $isHTML = true)
    {
        $this->mailer->isHTML($isHTML);
        $this->mailer->Body = $mensagem;
        
        if ($isHTML) {
            $this->mailer->AltBody = strip_tags($mensagem);
        }
    }

    /**
     * Adiciona um anexo
     */
    public function addAnexo($caminho, $nome = '')
    {
        $this->mailer->addAttachment($caminho, $nome);
    }

    /**
     * Envia o email
     */
    public function enviar()
    {
        try {
            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            throw new Exception('Erro ao enviar email: ' . $this->mailer->ErrorInfo);
        }
    }

    /**
     * Limpa todos os destinatários e anexos
     */
    public function limpar()
    {
        $this->mailer->clearAddresses();
        $this->mailer->clearAttachments();
    }
} 