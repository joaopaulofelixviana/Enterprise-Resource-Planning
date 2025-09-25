<?php

namespace PHPMailer\PHPMailer;

class PHPMailer
{
    const CHARSET_UTF8 = 'utf-8';
    const CONTENT_TYPE_PLAINTEXT = 'text/plain';
    const CONTENT_TYPE_TEXT_HTML = 'text/html';
    const ENCODING_8BIT = '8bit';
    const ENCODING_BASE64 = 'base64';
    const ENCRYPTION_STARTTLS = 'tls';
    const ENCRYPTION_SMTPS = 'ssl';

    public $Priority;
    public $CharSet = self::CHARSET_UTF8;
    public $ContentType = self::CONTENT_TYPE_PLAINTEXT;
    public $Encoding = self::ENCODING_8BIT;
    public $ErrorInfo = '';
    public $From = '';
    public $FromName = '';
    public $Sender = '';
    public $Subject = '';
    public $Body = '';
    public $AltBody = '';
    protected $MIMEBody = '';
    protected $MIMEHeader = '';
    protected $mailHeader = '';

    protected $to = [];
    protected $cc = [];
    protected $bcc = [];
    protected $ReplyTo = [];
    protected $attachments = [];
    protected $CustomHeader = [];
    
    public $Host = 'localhost';
    public $Port = 25;
    public $SMTPAuth = false;
    public $Username = '';
    public $Password = '';
    public $SMTPSecure = '';
    public $Timeout = 300;
    protected $smtp = null;
    protected $language = [];
    
    public function __construct($exceptions = null)
    {
        $this->CharSet = self::CHARSET_UTF8;
    }

    public function isHTML($isHtml = true)
    {
        if ($isHtml) {
            $this->ContentType = self::CONTENT_TYPE_TEXT_HTML;
        } else {
            $this->ContentType = self::CONTENT_TYPE_PLAINTEXT;
        }
    }

    public function isSMTP()
    {
        $this->Mailer = 'smtp';
    }

    public function addAddress($address, $name = '')
    {
        $this->to[] = [$address, $name];
    }

    public function addCC($address, $name = '')
    {
        $this->cc[] = [$address, $name];
    }

    public function addBCC($address, $name = '')
    {
        $this->bcc[] = [$address, $name];
    }

    public function setFrom($address, $name = '')
    {
        $this->From = $address;
        $this->FromName = $name;
    }

    public function addAttachment($path, $name = '')
    {
        if (file_exists($path)) {
            $this->attachments[] = [
                'path' => $path,
                'name' => $name,
                'encoding' => self::ENCODING_BASE64,
                'type' => mime_content_type($path)
            ];
        }
    }

    public function send()
    {
        try {
            if ($this->Mailer == 'smtp') {
                return $this->smtpSend();
            }
            return $this->mailSend();
        } catch (Exception $e) {
            $this->ErrorInfo = $e->getMessage();
            return false;
        }
    }

    protected function smtpSend()
    {
        if (!$this->smtp) {
            $this->smtp = new SMTP();
        }

        try {
            $this->smtp->connect($this->Host, $this->Port);
            
            if ($this->SMTPAuth) {
                $this->smtp->authenticate($this->Username, $this->Password);
            }

            foreach ($this->to as $recipient) {
                $this->smtp->recipient($recipient[0]);
            }

            $this->smtp->data($this->Body);
            $this->smtp->quit();
            
            return true;
        } catch (Exception $e) {
            $this->ErrorInfo = $e->getMessage();
            return false;
        }
    }

    protected function mailSend()
    {
        $headers = "From: {$this->FromName} <{$this->From}>\r\n";
        $headers .= "Content-Type: {$this->ContentType}; charset={$this->CharSet}\r\n";

        $to = array_map(function($recipient) {
            return $recipient[0];
        }, $this->to);

        return mail(implode(', ', $to), $this->Subject, $this->Body, $headers);
    }

    public function clearAddresses()
    {
        $this->to = [];
        $this->cc = [];
        $this->bcc = [];
    }

    public function clearAttachments()
    {
        $this->attachments = [];
    }
}
