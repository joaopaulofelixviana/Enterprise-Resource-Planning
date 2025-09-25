<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

class Mailer {
    private static $mailer;
    
    private static function getInstance() {
        if (!self::$mailer) {
            self::$mailer = new PHPMailer(true);
            
            // Configurações do servidor
            self::$mailer->isSMTP();
            self::$mailer->Host = 'smtp.gmail.com'; 
            self::$mailer->SMTPAuth = true;
            self::$mailer->Username = 'seu-email@gmail.com'; // Altere para seu e-mail
            self::$mailer->Password = 'sua-senha'; // Altere para sua senha
            self::$mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            self::$mailer->Port = 587;
            self::$mailer->CharSet = 'UTF-8';
            
            // Configurações padrão
            self::$mailer->setFrom('seu-email@gmail.com', 'Nome da Loja');
        }
        return self::$mailer;
    }
    
    public static function enviarEmailPedido($pedido_id, $endereco, $cep, $itens, $subtotal, $frete, $desconto, $total, $email_cliente) {
        try {
            $mail = self::getInstance();
            
            // Limpa destinatários anteriores
            $mail->clearAddresses();
            
            // Destinatário
            $mail->addAddress($email_cliente);
            
            // Assunto
            $mail->Subject = "Pedido #$pedido_id - Confirmação";
            
            // Corpo do e-mail em HTML
            $html = "
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background: #f8f9fa; padding: 20px; text-align: center; }
                    .content { padding: 20px; }
                    .footer { text-align: center; padding: 20px; font-size: 12px; color: #666; }
                    table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                    th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
                    th { background: #f8f9fa; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h1>Pedido Confirmado!</h1>
                        <p>Obrigado por comprar conosco.</p>
                    </div>
                    <div class='content'>
                        <h2>Detalhes do Pedido #$pedido_id</h2>
                        <p><strong>Endereço de Entrega:</strong><br>$endereco<br>CEP: $cep</p>
                        
                        <h3>Itens do Pedido:</h3>
                        <table>
                            <tr>
                                <th>Produto</th>
                                <th>Variação</th>
                                <th>Quantidade</th>
                                <th>Preço</th>
                            </tr>";
            
            foreach ($itens as $item) {
                $html .= "
                            <tr>
                                <td>{$item['nome']}</td>
                                <td>{$item['variacao']}</td>
                                <td>{$item['quantidade']}</td>
                                <td>R$ " . number_format($item['preco'] * $item['quantidade'], 2, ',', '.') . "</td>
                            </tr>";
            }
            
            $html .= "
                        </table>
                        
                        <div style='text-align: right; margin-top: 20px;'>
                            <p><strong>Subtotal:</strong> R$ " . number_format($subtotal, 2, ',', '.') . "</p>
                            <p><strong>Frete:</strong> R$ " . number_format($frete, 2, ',', '.') . "</p>
                            <p><strong>Desconto:</strong> R$ " . number_format($desconto, 2, ',', '.') . "</p>
                            <p><strong>Total:</strong> R$ " . number_format($total, 2, ',', '.') . "</p>
                        </div>
                    </div>
                    <div class='footer'>
                        <p>Este é um e-mail automático, por favor não responda.</p>
                    </div>
                </div>
            </body>
            </html>";
            
            $mail->isHTML(true);
            $mail->Body = $html;
            $mail->AltBody = "Pedido #$pedido_id\n\n" .
                            "Endereço: $endereco\n" .
                            "CEP: $cep\n\n" .
                            "Itens:\n" .
                            implode("\n", array_map(function($item) {
                                return "- {$item['nome']} ({$item['variacao']}) x {$item['quantidade']} = R$ " . 
                                       number_format($item['preco'] * $item['quantidade'], 2, ',', '.');
                            }, $itens)) .
                            "\n\nSubtotal: R$ " . number_format($subtotal, 2, ',', '.') .
                            "\nFrete: R$ " . number_format($frete, 2, ',', '.') .
                            "\nDesconto: R$ " . number_format($desconto, 2, ',', '.') .
                            "\nTotal: R$ " . number_format($total, 2, ',', '.');
            
            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Erro ao enviar e-mail: " . $e->getMessage());
            return false;
        }
    }
} 