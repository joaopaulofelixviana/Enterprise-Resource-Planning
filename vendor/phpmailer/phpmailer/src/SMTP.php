<?php

namespace PHPMailer\PHPMailer;


class SMTP
{
    const VERSION = '6.10.0';
    const LE = "\r\n";
    const DEFAULT_PORT = 25;
    const DEFAULT_SECURE_PORT = 465;

    protected $smtp_conn;
    protected $error = ['error' => '', 'detail' => ''];
    protected $last_reply = '';

    public $Host = '';
    public $Port = 25;
    public $Timeout = 300;
    public $Timelimit = 300;

    /**
     * Conecta ao servidor SMTP
     */
    public function connect($host, $port = null, $timeout = 30, $options = [])
    {
        $this->Host = $host;
        $this->Port = $port ?? self::DEFAULT_PORT;
        
        $errno = 0;
        $errstr = '';
        $socket_context = stream_context_create($options);
        
        $this->smtp_conn = @stream_socket_client(
            $this->Host . ':' . $this->Port,
            $errno,
            $errstr,
            $timeout,
            STREAM_CLIENT_CONNECT,
            $socket_context
        );

        if (empty($this->smtp_conn)) {
            $this->setError('Falha na conexão: ' . $errstr);
            return false;
        }

        stream_set_timeout($this->smtp_conn, $this->Timeout);
        $this->last_reply = $this->get_lines();
        
        return true;
    }

    /**
     * Autentica no servidor SMTP
     */
    public function authenticate($username, $password)
    {
        $this->client_send('AUTH LOGIN');
        $rply = $this->get_lines();
        
        if (substr($rply, 0, 3) != '334') {
            return false;
        }

        $this->client_send(base64_encode($username));
        $rply = $this->get_lines();
        
        if (substr($rply, 0, 3) != '334') {
            return false;
        }

        $this->client_send(base64_encode($password));
        $rply = $this->get_lines();
        
        return substr($rply, 0, 3) == '235';
    }


    public function data($msg_data)
    {
        $this->client_send('DATA');
        $rply = $this->get_lines();
        
        if (substr($rply, 0, 3) != '354') {
            return false;
        }

        $lines = explode("\n", str_replace(["\r\n", "\r"], "\n", $msg_data));
        
        foreach ($lines as $line) {
            if (!empty($line) && $line[0] == '.') {
                $line = '.' . $line;
            }
            $this->client_send($line . self::LE);
        }

        $this->client_send('.' . self::LE);
        $rply = $this->get_lines();
        
        return substr($rply, 0, 3) == '250';
    }

 
    public function hello($host = '')
    {
        $this->client_send('EHLO ' . ($host ?: 'localhost'));
        $rply = $this->get_lines();
        
        return substr($rply, 0, 3) == '250';
    }

    public function mail($from)
    {
        $this->client_send('MAIL FROM:<' . $from . '>');
        $rply = $this->get_lines();
        
        return substr($rply, 0, 3) == '250';
    }


    public function recipient($address)
    {
        $this->client_send('RCPT TO:<' . $address . '>');
        $rply = $this->get_lines();
        
        return substr($rply, 0, 3) == '250';
    }


    public function quit()
    {
        $this->client_send('QUIT');
        $this->close();
        return true;
    }

  
    public function close()
    {
        if (is_resource($this->smtp_conn)) {
            fclose($this->smtp_conn);
            $this->smtp_conn = null;
        }
    }


    protected function client_send($data)
    {
        if (!is_resource($this->smtp_conn)) {
            return false;
        }
        return fwrite($this->smtp_conn, $data . self::LE);
    }


    protected function get_lines()
    {
        if (!is_resource($this->smtp_conn)) {
            return '';
        }

        $data = '';
        $endtime = time() + $this->Timelimit;
        
        while (is_resource($this->smtp_conn) && !feof($this->smtp_conn)) {
            $str = @fgets($this->smtp_conn, 515);
            $data .= $str;
            
            if (!empty($str) && $str[strlen($str) - 1] == "\n") {
                break;
            }
            
            if (time() > $endtime) {
                break;
            }
        }
        
        return $data;
    }

    protected function setError($message, $detail = '')
    {
        $this->error = [
            'error' => $message,
            'detail' => $detail
        ];
    }

  
    public function getError()
    {
        return $this->error;
    }

   
    public function getLastReply()
    {
        return $this->last_reply;
    }
} 