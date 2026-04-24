<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use PDO;

final class PendingAccountRepository
{
    public function findByLogin($login)
    {
        $connection = Database::connection();

        $statement = $connection->prepare('
            SELECT id, login, email, verification_token, verification_code, expires_at, verified_at
            FROM website_pending_accounts
            WHERE login = :login
            LIMIT 1
        ');

        $statement->execute(array(
            'login' => $login,
        ));

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return $row;
    }

    public function findByEmail($email)
    {
        $connection = Database::connection();

        $statement = $connection->prepare('
            SELECT id, login, email, verification_token, verification_code, expires_at, verified_at
            FROM website_pending_accounts
            WHERE email = :email
            LIMIT 1
        ');

        $statement->execute(array(
            'email' => $email,
        ));

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return $row;
    }

    public function findActiveByToken($token)
    {
        $connection = Database::connection();

        $statement = $connection->prepare('
            SELECT id, login, email, password_hash, verification_code, verification_token, expires_at, verified_at, resend_count, last_sent_at, created_at, ip_address
            FROM website_pending_accounts
            WHERE verification_token = :token
              AND verified_at IS NULL
            LIMIT 1
        ');

        $statement->execute(array(
            'token' => $token,
        ));

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return $row;
    }

    public function create($data)
    {
        $connection = Database::connection();

        $statement = $connection->prepare('
            INSERT INTO website_pending_accounts (
                login,
                email,
                password_hash,
                verification_code,
                verification_token,
                ip_address,
                expires_at,
                created_at,
                verified_at,
                resend_count,
                last_sent_at
            ) VALUES (
                :login,
                :email,
                :password_hash,
                :verification_code,
                :verification_token,
                :ip_address,
                :expires_at,
                :created_at,
                NULL,
                :resend_count,
                :last_sent_at
            )
        ');

        return $statement->execute(array(
            'login' => $data['login'],
            'email' => $data['email'],
            'password_hash' => $data['password_hash'],
            'verification_code' => $data['verification_code'],
            'verification_token' => $data['verification_token'],
            'ip_address' => $data['ip_address'],
            'expires_at' => $data['expires_at'],
            'created_at' => $data['created_at'],
            'resend_count' => $data['resend_count'],
            'last_sent_at' => $data['last_sent_at'],
        ));
    }

	public function findActiveByTokenAndCode($token, $code)
	{
		$connection = Database::connection();

		$statement = $connection->prepare('
			SELECT id, login, email, password_hash, verification_code, verification_token, expires_at, verified_at, resend_count, last_sent_at, created_at, ip_address
			FROM website_pending_accounts
			WHERE verification_token = :token
			  AND verification_code = :code
			  AND verified_at IS NULL
			LIMIT 1
		');

		$statement->execute(array(
			'token' => $token,
			'code' => $code,
		));

		$row = $statement->fetch(PDO::FETCH_ASSOC);

		if (!$row) {
			return null;
		}

		return $row;
	}

	public function markAsVerified($id)
	{
		$connection = Database::connection();

		$statement = $connection->prepare('
			UPDATE website_pending_accounts
			SET verified_at = :verified_at
			WHERE id = :id
			LIMIT 1
		');

		return $statement->execute(array(
			'verified_at' => date('Y-m-d H:i:s'),
			'id' => $id,
		));
	}

	public function createWebsiteAccount($data)
	{
		$connection = Database::connection();

		$statement = $connection->prepare('
			INSERT INTO account (
				login,
				password,
				social_id,
				email,
				create_time,
				status,
				newsletter,
				empire,
				name_checked,
				availDt,
				mileage,
				cash,
				gold_expire,
				silver_expire,
				safebox_expire,
				autoloot_expire,
				fish_mind_expire,
				marriage_fast_expire,
				money_drop_rate_expire,
				total_cash,
				total_mileage,
				channel_company,
				ip,
				last_play,
				web_admin,
				coins,
				jcoins,
				deletion_token,
				passlost_token,
				email_token,
				new_email
			) VALUES (
				:login,
				:password,
				:social_id,
				:email,
				:create_time,
				:status,
				:newsletter,
				:empire,
				:name_checked,
				:availDt,
				:mileage,
				:cash,
				:gold_expire,
				:silver_expire,
				:safebox_expire,
				:autoloot_expire,
				:fish_mind_expire,
				:marriage_fast_expire,
				:money_drop_rate_expire,
				:total_cash,
				:total_mileage,
				:channel_company,
				:ip,
				:last_play,
				:web_admin,
				:coins,
				:jcoins,
				:deletion_token,
				:passlost_token,
				:email_token,
				:new_email
			)
		');

		return $statement->execute(array(
			'login' => $data['login'],
			'password' => $data['password'],
			'social_id' => isset($data['social_id']) ? $data['social_id'] : '',
			'email' => $data['email'],
			'create_time' => isset($data['create_time']) ? $data['create_time'] : date('Y-m-d H:i:s'),
			'status' => isset($data['status']) ? $data['status'] : 'OK',
			'newsletter' => isset($data['newsletter']) ? $data['newsletter'] : 0,
			'empire' => isset($data['empire']) ? $data['empire'] : 0,
			'name_checked' => isset($data['name_checked']) ? $data['name_checked'] : 0,
			'availDt' => isset($data['availDt']) ? $data['availDt'] : '0000-00-00 00:00:00',
			'mileage' => isset($data['mileage']) ? $data['mileage'] : 0,
			'cash' => isset($data['cash']) ? $data['cash'] : 0,
			'gold_expire' => isset($data['gold_expire']) ? $data['gold_expire'] : '0000-00-00 00:00:00',
			'silver_expire' => isset($data['silver_expire']) ? $data['silver_expire'] : '0000-00-00 00:00:00',
			'safebox_expire' => isset($data['safebox_expire']) ? $data['safebox_expire'] : '2028-06-21 00:00:00',
			'autoloot_expire' => isset($data['autoloot_expire']) ? $data['autoloot_expire'] : '2028-06-21 00:00:00',
			'fish_mind_expire' => isset($data['fish_mind_expire']) ? $data['fish_mind_expire'] : '0000-00-00 00:00:00',
			'marriage_fast_expire' => isset($data['marriage_fast_expire']) ? $data['marriage_fast_expire'] : '0000-00-00 00:00:00',
			'money_drop_rate_expire' => isset($data['money_drop_rate_expire']) ? $data['money_drop_rate_expire'] : '0000-00-00 00:00:00',
			'total_cash' => isset($data['total_cash']) ? $data['total_cash'] : 0,
			'total_mileage' => isset($data['total_mileage']) ? $data['total_mileage'] : 0,
			'channel_company' => isset($data['channel_company']) ? $data['channel_company'] : '',
			'ip' => isset($data['ip']) ? $data['ip'] : '',
			'last_play' => isset($data['last_play']) ? $data['last_play'] : date('Y-m-d H:i:s'),
			'web_admin' => isset($data['web_admin']) ? $data['web_admin'] : 0,
			'coins' => isset($data['coins']) ? $data['coins'] : 0,
			'jcoins' => isset($data['jcoins']) ? $data['jcoins'] : 0,
			'deletion_token' => isset($data['deletion_token']) ? $data['deletion_token'] : '',
			'passlost_token' => isset($data['passlost_token']) ? $data['passlost_token'] : '',
			'email_token' => isset($data['email_token']) ? $data['email_token'] : '',
			'new_email' => isset($data['new_email']) ? $data['new_email'] : '',
		));
	}

}