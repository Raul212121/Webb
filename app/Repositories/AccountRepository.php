<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use PDO;

final class AccountRepository
{
    public function findByLogin($login)
    {
        $connection = Database::connection();

        $statement = $connection->prepare('
            SELECT id, login, password, email, status, web_admin, cash
            FROM account
            WHERE login = :login
            LIMIT 1
        ');

        $statement->execute(array(
            'login' => $login,
        ));

        $account = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$account) {
            return null;
        }

        return $account;
    }

	public function findByLoginAndPassword($login, $plainPassword)
	{
		$connection = Database::connection();

		$statement = $connection->prepare('
			SELECT id, login, password, email, status, web_admin, cash
			FROM account
			WHERE login = :login
			  AND password = PASSWORD(:password)
			LIMIT 1
		');

		$statement->execute(array(
			'login' => $login,
			'password' => $plainPassword,
		));

		$account = $statement->fetch(PDO::FETCH_ASSOC);

		if (!$account) {
			return null;
		}

		return $account;
	}

	public function existsByLogin($login)
	{
		$connection = Database::connection();

		$statement = $connection->prepare('
			SELECT id
			FROM account
			WHERE login = :login
			LIMIT 1
		');

		$statement->execute(array(
			'login' => $login,
		));

		return (bool) $statement->fetch(PDO::FETCH_ASSOC);
	}

	public function existsByEmail($email)
	{
		$connection = Database::connection();

		$statement = $connection->prepare('
			SELECT id
			FROM account
			WHERE email = :email
			LIMIT 1
		');

		$statement->execute(array(
			'email' => $email,
		));

		return (bool) $statement->fetch(PDO::FETCH_ASSOC);
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

	public function getFleaReputationById($accountId)
	{
		$connection = Database::connection();

		$statement = $connection->prepare('
			SELECT flea_reputation
			FROM account
			WHERE id = :id
			LIMIT 1
		');

		$statement->execute(array(
			'id' => (int) $accountId,
		));

		$row = $statement->fetch(PDO::FETCH_ASSOC);

		if (!$row || !isset($row['flea_reputation'])) {
			return 0.00;
		}

		return (float) $row['flea_reputation'];
	}

	public function decreaseFleaReputationById($accountId, $amount)
	{
		$connection = Database::connection();

		$statement = $connection->prepare('
			UPDATE account
			SET flea_reputation = GREATEST(0, flea_reputation - :amount)
			WHERE id = :id
			LIMIT 1
		');

		return $statement->execute(array(
			'amount' => (float) $amount,
			'id' => (int) $accountId,
		));
	}

	public function findById($accountId)
	{
		$connection = Database::connection();

		$statement = $connection->prepare('
			SELECT id, login, password, email, status, web_admin, cash, flea_reputation
			FROM account
			WHERE id = :id
			LIMIT 1
		');

		$statement->execute(array(
			'id' => (int) $accountId,
		));

		$account = $statement->fetch(PDO::FETCH_ASSOC);

		if (!$account) {
			return null;
		}

		return $account;
	}

	public function decreaseCashById($accountId, $amount)
	{
		$connection = Database::connection();

		$statement = $connection->prepare('
			UPDATE account
			SET cash = cash - :decrease_amount
			WHERE id = :id
			  AND cash >= :check_amount
			LIMIT 1
		');

		return $statement->execute(array(
			'decrease_amount' => (int) $amount,
			'check_amount' => (int) $amount,
			'id' => (int) $accountId,
		));
	}

	public function increaseFleaReputationById($accountId, $amount)
	{
		$connection = Database::connection();

		$statement = $connection->prepare('
			UPDATE account
			SET flea_reputation = flea_reputation + :amount
			WHERE id = :id
			LIMIT 1
		');

		return $statement->execute(array(
			'amount' => (float) $amount,
			'id' => (int) $accountId,
		));
	}

	public function increaseCashById($accountId, $amount)
	{
		$connection = Database::connection();

		$statement = $connection->prepare('
			UPDATE account
			SET cash = cash + :amount
			WHERE id = :id
			LIMIT 1
		');

		return $statement->execute(array(
			'amount' => (int) $amount,
			'id' => (int) $accountId,
		));
	}

}