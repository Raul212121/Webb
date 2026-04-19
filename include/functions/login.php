<?php
if (isset($_POST['username']) && isset($_POST['password'])) {

    $username = strip_tags($_POST['username']);
    $password = strip_tags($_POST['password']);

    $captcha_ok = true; // DEFAULT: NU BLOCAM LOGIN-UL

    if (!empty($_POST['g-recaptcha-response'])) {

        if (function_exists('curl_init')) {

            $ch = curl_init("https://www.google.com/recaptcha/api/siteverify");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                'secret'   => $secret_key,
                'response' => $_POST['g-recaptcha-response']
            ]));

            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

            $response = curl_exec($ch);
            curl_close($ch);

            if ($response !== false) {
                $data = json_decode($response, true);
                if (isset($data['success']) && $data['success'] !== true) {
                    $captcha_ok = false;
                }
            }

        }
        // daca cURL nu exista → NU blocam
    }

    if (!$captcha_ok) {
        $login_info = array(6);
    } else {
        $login_info = $database->doLogin($username, $password);
    }
}
?>
