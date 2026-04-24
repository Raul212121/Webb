<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\AccountRepository;
use App\Repositories\PendingAccountRepository;
use App\Services\MailService;

final class RegisterService
{
    private $accountRepository;
    private $pendingAccountRepository;

    public function __construct()
    {
        $this->accountRepository = new AccountRepository();
        $this->pendingAccountRepository = new PendingAccountRepository();
    }

    public function startRegistration($login, $email, $password, $passwordConfirm, $ipAddress)
    {
        $login = trim((string) $login);
        $email = trim((string) $email);
        $password = (string) $password;
        $passwordConfirm = (string) $passwordConfirm;
        $ipAddress = trim((string) $ipAddress);

        if ($login === '' || $email === '' || $password === '' || $passwordConfirm === '') {
            return array(
                'success' => false,
                'message' => 'Completeaza toate campurile.',
                'token' => null,
            );
        }

        if (!preg_match('/^[A-Za-z0-9_]{4,30}$/', $login)) {
            return array(
                'success' => false,
                'message' => 'Numele contului este invalid.',
                'token' => null,
            );
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return array(
                'success' => false,
                'message' => 'Adresa de email este invalida.',
                'token' => null,
            );
        }

        if (strlen($password) < 6) {
            return array(
                'success' => false,
                'message' => 'Parola trebuie sa aiba minim 6 caractere.',
                'token' => null,
            );
        }

        if ($password !== $passwordConfirm) {
            return array(
                'success' => false,
                'message' => 'Parolele nu coincid.',
                'token' => null,
            );
        }

        if ($this->accountRepository->existsByLogin($login)) {
            return array(
                'success' => false,
                'message' => 'Exista deja un cont cu acest nume.',
                'token' => null,
            );
        }

        if ($this->accountRepository->existsByEmail($email)) {
            return array(
                'success' => false,
                'message' => 'Exista deja un cont cu acest email.',
                'token' => null,
            );
        }

        if ($this->pendingAccountRepository->findByLogin($login)) {
            return array(
                'success' => false,
                'message' => 'Exista deja o inregistrare in asteptare pentru acest cont.',
                'token' => null,
            );
        }

        if ($this->pendingAccountRepository->findByEmail($email)) {
            return array(
                'success' => false,
                'message' => 'Exista deja o inregistrare in asteptare pentru acest email.',
                'token' => null,
            );
        }

        $verificationCode = (string) random_int(100000, 999999);
        $verificationToken = bin2hex(random_bytes(32));

        $expiresAt = date('Y-m-d H:i:s', time() + 15 * 60);
        $createdAt = date('Y-m-d H:i:s');
        $passwordHash = $this->makeMysqlPasswordHash($password);

        $created = $this->pendingAccountRepository->create(array(
            'login' => $login,
            'email' => $email,
            'password_hash' => $passwordHash,
            'verification_code' => $verificationCode,
            'verification_token' => $verificationToken,
            'ip_address' => $ipAddress,
            'expires_at' => $expiresAt,
            'created_at' => $createdAt,
            'resend_count' => 0,
            'last_sent_at' => $createdAt,
        ));

        if (!$created) {
            return array(
                'success' => false,
                'message' => 'Nu s-a putut crea cererea de inregistrare.',
                'token' => null,
            );
        }

		$mailService = new MailService();

		$mailSent = $mailService->sendVerificationCode(
			$email,
			$login,
			$verificationCode
		);

		if (!$mailSent) {
			return array(
				'success' => false,
				'message' => 'Codul a fost generat, dar emailul nu a putut fi trimis.',
				'token' => null,
			);
		}

        return array(
            'success' => true,
            'message' => 'Codul de verificare a fost generat.',
            'token' => $verificationToken,
            'code' => $verificationCode,
        );
    }

    private function makeMysqlPasswordHash($plainPassword)
    {
        return '*' . strtoupper(sha1(sha1($plainPassword, true)));
    }
}