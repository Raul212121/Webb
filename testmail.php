<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'localhost';      // încearcă direct local
    $mail->SMTPAuth   = true;
    $mail->Username   = '_mainaccount@luramt2.ro';
    $mail->Password   = 'PAROLA_TA_CPANEL';
    $mail->SMTPSecure = 'tls';            // încearcă și cu 'ssl' dacă nu merge
    $mail->Port       = 587;              // încearcă și cu 465 pentru SSL

    // Debug
    $mail->SMTPDebug  = 2;                // nivel detaliat
    $mail->Debugoutput = 'html';

    $mail->setFrom('_mainaccount@luramt2.ro', 'LuraMT2 Test');
    $mail->addAddress('adresa_ta_personala@gmail.com', 'Tester');

    $mail->isHTML(true);
    $mail->Subject = 'Test SMTP LuraMT2';
    $mail->Body    = '✅ Dacă vezi acest mesaj, SMTP funcționează!';

    $mail->send();
    echo '✅ Email trimis cu succes!';
} catch (Exception $e) {
    echo "❌ Eroare la trimitere: {$mail->ErrorInfo}";
}
