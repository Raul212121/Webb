<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\AccountRepository;
use App\Repositories\PendingAccountRepository;

final class VerificationService
{
    private $accountRepository;
    private $pendingAccountRepository;

    public function __construct()
    {
        $this->accountRepository = new AccountRepository();
        $this->pendingAccountRepository = new PendingAccountRepository();
    }

    public function verifyPendingAccount($token, $code)
    {
        $token = trim((string) $token);
        $code = trim((string) $code);

        if ($token === '' || $code === '') {
            return array(
                'success' => false,
                'message' => 'Completeaza codul de verificare.',
            );
        }

        $pendingAccount = $this->pendingAccountRepository->findActiveByTokenAndCode($token, $code);

        if (!$pendingAccount) {
            return array(
                'success' => false,
                'message' => 'Codul sau tokenul de verificare este invalid.',
            );
        }

        if (!empty($pendingAccount['expires_at']) && strtotime($pendingAccount['expires_at']) < time()) {
            return array(
                'success' => false,
                'message' => 'Codul de verificare a expirat.',
            );
        }

        if ($this->accountRepository->existsByLogin($pendingAccount['login'])) {
            return array(
                'success' => false,
                'message' => 'Exista deja un cont cu acest nume.',
            );
        }

        if ($this->accountRepository->existsByEmail($pendingAccount['email'])) {
            return array(
                'success' => false,
                'message' => 'Exista deja un cont cu acest email.',
            );
        }

        $created = $this->accountRepository->createWebsiteAccount(array(
            'login' => $pendingAccount['login'],
            'password' => $pendingAccount['password_hash'],
            'email' => $pendingAccount['email'],
            'create_time' => date('Y-m-d H:i:s'),
            'status' => 'OK',
            'newsletter' => 0,
            'empire' => 0,
            'name_checked' => 0,
            'availDt' => '0000-00-00 00:00:00',
            'mileage' => 0,
            'cash' => 0,
            'gold_expire' => '0000-00-00 00:00:00',
            'silver_expire' => '0000-00-00 00:00:00',
            'safebox_expire' => '2028-06-21 00:00:00',
            'autoloot_expire' => '2028-06-21 00:00:00',
            'fish_mind_expire' => '0000-00-00 00:00:00',
            'marriage_fast_expire' => '0000-00-00 00:00:00',
            'money_drop_rate_expire' => '0000-00-00 00:00:00',
            'total_cash' => 0,
            'total_mileage' => 0,
            'channel_company' => '',
            'ip' => !empty($pendingAccount['ip_address']) ? $pendingAccount['ip_address'] : '',
            'last_play' => date('Y-m-d H:i:s'),
            'web_admin' => 0,
            'coins' => 0,
            'jcoins' => 0,
            'deletion_token' => '',
            'passlost_token' => '',
            'email_token' => '',
            'new_email' => '',
            'social_id' => '',
        ));

        if (!$created) {
            return array(
                'success' => false,
                'message' => 'Contul nu a putut fi creat.',
            );
        }

        $marked = $this->pendingAccountRepository->markAsVerified($pendingAccount['id']);

        if (!$marked) {
            return array(
                'success' => false,
                'message' => 'Contul a fost creat, dar pending-ul nu a putut fi actualizat.',
            );
        }

        return array(
            'success' => true,
            'message' => 'Contul a fost verificat si creat cu succes.',
        );
    }
}