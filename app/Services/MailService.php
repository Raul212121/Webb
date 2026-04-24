<?php
declare(strict_types=1);

namespace App\Services;

final class MailService
{
    private $config;

    public function __construct()
    {
        $this->config = require BASE_PATH . '/config/mail.php';
    }

    public function sendVerificationCode($toEmail, $toLogin, $verificationCode)
    {
        $subject = 'Cod verificare cont LuraMT2';
        $message = $this->buildVerificationMessage($toLogin, $verificationCode);
        $headers = $this->buildHeaders();

        return mail($toEmail, $subject, $message, $headers);
    }

    private function buildHeaders()
    {
        $fromAddress = isset($this->config['from_address']) ? $this->config['from_address'] : '';
        $fromName = isset($this->config['from_name']) ? $this->config['from_name'] : 'LuraMT2';

        $headers = array();
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-Type: text/plain; charset=UTF-8';
        $headers[] = 'From: ' . $fromName . ' <' . $fromAddress . '>';

        return implode("\r\n", $headers);
    }

    private function buildVerificationMessage($login, $verificationCode)
    {
        return
            "Salut " . $login . ",\n\n" .
            "Codul tau de verificare pentru contul LuraMT2 este:\n\n" .
            $verificationCode . "\n\n" .
            "Codul expira in 15 minute.\n\n" .
            "Daca nu tu ai cerut acest cod, ignora acest email.\n\n" .
            "LuraMT2";
    }
}