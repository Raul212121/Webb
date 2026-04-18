<?php
	if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response']))
	{
		$username = strip_tags($_POST['username']);
		$password = strip_tags($_POST['password']);

		$data = array(
					'secret' => $secret_key,
					'response' => $_POST['g-recaptcha-response']
				);

		$verify = curl_init();
		curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
		curl_setopt($verify, CURLOPT_POST, true);
		curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($verify);
		$responseData = json_decode($response);

        if($responseData->success)
           $login_info = $database->doLogin($username,$password);
		else $login_info = array(6);
	}
?>