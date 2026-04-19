<?php

// LOG Webhook pentru debugging (optional)
file_put_contents("stripe_log.txt", date("Y-m-d H:i:s") . " - Webhook called\n", FILE_APPEND);

require_once __DIR__ . '/stripe-php/init.php';

\Stripe\Stripe::setApiKey('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');

// Secretul webhook-ului Stripe (IL SCHIMBI CU AL TAU)
$endpoint_secret = 'whsec_mJVuJxAwLZgysu1crlneE2sRZnePvS2Q';

$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? null;
if (!$sig_header) {
    http_response_code(400);
    exit;
}

$event = null;

try {
    $event = \Stripe\Webhook::constructEvent(
        $payload, $sig_header, $endpoint_secret
    );
} catch(\Stripe\Exception\SignatureVerificationException $e) {
    http_response_code(400);
    exit;
}

// Procesăm doar evenimentul corect
if ($event->type === 'checkout.session.completed') {

    $session = $event->data->object;

    $login = $session->metadata->account_id;
    $coins = intval($session->metadata->amount_coins);

    // LOG
    file_put_contents("stripe_log.txt", "Account: $login | Coins: $coins\n", FILE_APPEND);

    // CONECTARE LA BAZA DE DATE METIN2 (account.account)
    $db = new mysqli("81.180.202.241", "lura_srv", "LuraSrv_2026!Ultra#Strong", "account");

    if ($db->connect_errno) {
        file_put_contents("stripe_log.txt", "DB ERROR\n", FILE_APPEND);
        exit;
    }

    // ADAUGĂM COINS
    $stmt = $db->prepare("UPDATE account SET coins = coins + ? WHERE login = ?");
    $stmt->bind_param("is", $coins, $login);
    $stmt->execute();
    $stmt->close();
    $db->close();
}

http_response_code(200);
