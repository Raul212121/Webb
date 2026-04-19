<?php
session_start();

/*
  AICI PRESUPUNEM CA UTILIZATORUL ESTE LOGAT
  si ca ai deja un account_id in sesiune
*/

$account_id = $_SESSION['account_id'] ?? 0;

if ($account_id <= 0) {
    die("Trebuie sa fii logat.");
}

// URL-ul API Metin2Vote
$api_url = "https://www.metin2vote.com/API/Vote/1032/a2JYVU56aHBhTHJpNnhqd1VlNWFGZnI5ZTVJRU9hM1drSVVVeUN6YmN1ST0=/" . $account_id;

// Cerere CURL
$ch = curl_init($api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
curl_close($ch);

// DACA A VOTAT
if (trim($response) === "1") {
    echo "Ai votat! Urmeaza sa primesti recompensa.";
} else {
    echo "Nu ai votat inca.";
}
