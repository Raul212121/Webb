<?php
if (php_sapi_name() !== 'cli') {
    exit;
}

// CONFIG
$STRIPE_KEY = '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';
$DB_HOST = '81.180.202.241';
$DB_USER = 'lura_srv';
$DB_PASS = 'LuraSrv_2026!Ultra#Strong';
$DB_NAME = 'account';

// DB
$db = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($db->connect_errno) exit;

// Stripe API – Checkout Sessions
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => 'https://api.stripe.com/v1/checkout/sessions?limit=100',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_USERPWD => $STRIPE_KEY . ':',
]);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
if (!isset($data['data'])) exit;

foreach ($data['data'] as $session) {

    if (
        $session['status'] !== 'complete' ||
        $session['payment_status'] !== 'paid'
    ) {
        continue;
    }

    $meta = $session['metadata'] ?? [];
    $login = $meta['account_id'] ?? null;
    $coins = intval($meta['amount_coins'] ?? 0);

    if (!$login || $coins <= 0) continue;

    $sid = $session['id'];

    // idempotenta
    $check = $db->prepare("SELECT session_id FROM stripe_payments WHERE session_id=?");
    $check->bind_param("s", $sid);
    $check->execute();
    $check->store_result();
    if ($check->num_rows > 0) {
        $check->close();
        continue;
    }
    $check->close();

    // coins
    $stmt = $db->prepare("UPDATE account SET coins = coins + ? WHERE login=?");
    $stmt->bind_param("is", $coins, $login);
    $stmt->execute();
    $stmt->close();

    // log payment
    $ins = $db->prepare(
        "INSERT INTO stripe_payments (session_id, account, coins)
         VALUES (?, ?, ?)"
    );
    $ins->bind_param("ssi", $sid, $login, $coins);
    $ins->execute();
    $ins->close();
}

$db->close();
