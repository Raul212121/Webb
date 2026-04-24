<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\AccountRepository;

final class AuthService
{
    private $accountRepository;

    public function __construct()
    {
        $this->accountRepository = new AccountRepository();
    }

    public function attemptLogin($login, $password)
    {
        $login = trim((string) $login);
        $password = (string) $password;

        if ($login === '' || $password === '') {
            return array(
                'success' => false,
                'message' => 'Completeaza toate campurile.',
                'account' => null,
            );
        }

        $account = $this->accountRepository->findByLoginAndPassword($login, $password);

        if (!$account) {
            return array(
                'success' => false,
                'message' => 'Datele de autentificare sunt incorecte.',
                'account' => null,
            );
        }

        if (!isset($account['status']) || $account['status'] !== 'OK') {
            return array(
                'success' => false,
                'message' => 'Contul nu este activ.',
                'account' => null,
            );
        }

        return array(
            'success' => true,
            'message' => 'Autentificare reusita.',
            'account' => $account,
        );
    }
}