<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use PDO;
use Throwable;

final class FleamarketBuyService
{
    public function buyListing($buyerAccountId, $listingId)
    {
        $buyerAccountId = (int) $buyerAccountId;
        $listingId = (int) $listingId;

        if ($buyerAccountId < 1 || $listingId < 1) {
            return array(
                'success' => false,
                'message' => 'Date invalide pentru cumparare.',
            );
        }

        $connection = Database::connection();

        try {
            $connection->beginTransaction();

            $listingStatement = $connection->prepare('
                SELECT
                    id,
                    account_id,
                    player_id,
                    owner_name,
                    item_vnum,
                    item_count,
                    socket0,
                    socket1,
                    socket2,
                    attrtype0,
                    attrvalue0,
                    attrtype1,
                    attrvalue1,
                    attrtype2,
                    attrvalue2,
                    attrtype3,
                    attrvalue3,
                    attrtype4,
                    attrvalue4,
                    attrtype5,
                    attrvalue5,
                    attrtype6,
                    attrvalue6,
                    price_md,
                    price_jd,
                    status,
                    withdraw_status,
                    withdraw_available_at,
                    created_at,
                    updated_at
                FROM player.market_listings_test
                WHERE id = :id
                  AND status = :status
                  AND (
                        withdraw_status = \'none\'
                        OR (
                            withdraw_status = \'pending\'
                            AND withdraw_available_at > NOW()
                        )
                      )
                LIMIT 1
                FOR UPDATE
            ');

            $listingStatement->bindValue(':id', $listingId, PDO::PARAM_INT);
            $listingStatement->bindValue(':status', 'active', PDO::PARAM_STR);
            $listingStatement->execute();

            $listing = $listingStatement->fetch(PDO::FETCH_ASSOC);

            if (!$listing) {
                $connection->rollBack();

                return array(
                    'success' => false,
                    'message' => 'Oferta nu mai este disponibila.',
                );
            }

            $sellerAccountId = (int) $listing['account_id'];
            $priceMd = (int) $listing['price_md'];

            if ($sellerAccountId === $buyerAccountId) {
                $connection->rollBack();

                return array(
                    'success' => false,
                    'message' => 'Nu iti poti cumpara propriul item.',
                );
            }

            if ($priceMd < 1) {
                $connection->rollBack();

                return array(
                    'success' => false,
                    'message' => 'Pret invalid.',
                );
            }

            $buyerStatement = $connection->prepare('
                SELECT id, cash
                FROM account.account
                WHERE id = :id
                LIMIT 1
                FOR UPDATE
            ');

            $buyerStatement->bindValue(':id', $buyerAccountId, PDO::PARAM_INT);
            $buyerStatement->execute();

            $buyerAccount = $buyerStatement->fetch(PDO::FETCH_ASSOC);

            if (!$buyerAccount) {
                $connection->rollBack();

                return array(
                    'success' => false,
                    'message' => 'Contul cumparatorului nu exista.',
                );
            }

            if ((int) $buyerAccount['cash'] < $priceMd) {
                $connection->rollBack();

                return array(
                    'success' => false,
                    'message' => 'Nu ai suficienti MD.',
                );
            }

            $mallPosStatement = $connection->prepare('
                SELECT pos
                FROM player.item
                WHERE owner_id = :owner_id
                  AND window = :window
                ORDER BY pos ASC
                FOR UPDATE
            ');

            $mallPosStatement->bindValue(':owner_id', $buyerAccountId, PDO::PARAM_INT);
            $mallPosStatement->bindValue(':window', 'MALL', PDO::PARAM_STR);
            $mallPosStatement->execute();

            $usedPositions = $mallPosStatement->fetchAll(PDO::FETCH_COLUMN);

            $usedMap = array();
            foreach ($usedPositions as $pos) {
                $usedMap[(int) $pos] = true;
            }

            $mallPos = null;
            for ($pos = 0; $pos < 45; $pos++) {
                if (!isset($usedMap[$pos])) {
                    $mallPos = $pos;
                    break;
                }
            }

            if ($mallPos === null) {
                $connection->rollBack();

                return array(
                    'success' => false,
                    'message' => 'Depozitul Mall este plin.',
                );
            }

            $decreaseCashStatement = $connection->prepare('
                UPDATE account.account
                SET cash = cash - :price_md
                WHERE id = :buyer_id
                  AND cash >= :check_price_md
                LIMIT 1
            ');

            $decreaseCashStatement->bindValue(':price_md', $priceMd, PDO::PARAM_INT);
            $decreaseCashStatement->bindValue(':check_price_md', $priceMd, PDO::PARAM_INT);
            $decreaseCashStatement->bindValue(':buyer_id', $buyerAccountId, PDO::PARAM_INT);
            $decreaseCashStatement->execute();

            if ($decreaseCashStatement->rowCount() !== 1) {
                $connection->rollBack();

                return array(
                    'success' => false,
                    'message' => 'Nu s-au putut retrage MD-urile.',
                );
            }

            if ($priceMd >= 10) {
				$sellerReceiveMd = (int) floor($priceMd * 95 / 100);
			} else {
				$sellerReceiveMd = $priceMd;
			}

            $increaseSellerCashStatement = $connection->prepare('
                UPDATE account.account
                SET cash = cash + :amount
                WHERE id = :seller_id
                LIMIT 1
            ');

            $increaseSellerCashStatement->bindValue(':amount', $sellerReceiveMd, PDO::PARAM_INT);
            $increaseSellerCashStatement->bindValue(':seller_id', $sellerAccountId, PDO::PARAM_INT);
            $increaseSellerCashStatement->execute();

            if ($increaseSellerCashStatement->rowCount() !== 1) {
                $connection->rollBack();

                return array(
                    'success' => false,
                    'message' => 'MD-urile vanzatorului nu au putut fi actualizate.',
                );
            }

            $reputationGain = (int) floor($priceMd * 2 / 100);

            if ($reputationGain > 0) {
                $increaseReputationStatement = $connection->prepare('
                    UPDATE account.account
                    SET flea_reputation = flea_reputation + :amount
                    WHERE id = :seller_id
                    LIMIT 1
                ');

                $increaseReputationStatement->bindValue(':amount', $reputationGain, PDO::PARAM_INT);
                $increaseReputationStatement->bindValue(':seller_id', $sellerAccountId, PDO::PARAM_INT);
                $increaseReputationStatement->execute();
            }

			$sellerName = isset($listing['owner_name']) ? (string) $listing['owner_name'] : '';

			$buyerNameStatement = $connection->prepare('
				SELECT name
				FROM player.player
				WHERE account_id = :account_id
				ORDER BY id ASC
				LIMIT 1
			');

			$buyerNameStatement->bindValue(':account_id', $buyerAccountId, PDO::PARAM_INT);
			$buyerNameStatement->execute();

			$buyerNameRow = $buyerNameStatement->fetch(PDO::FETCH_ASSOC);
			$buyerName = ($buyerNameRow && isset($buyerNameRow['name'])) ? (string) $buyerNameRow['name'] : '';

			$insertSaleLogStatement = $connection->prepare('
				INSERT INTO player.market_sales_test (
					listing_id,
					seller_account_id,
					seller_name,
					buyer_account_id,
					buyer_name,
					item_vnum,
					item_count,
					price_md,
					price_jd,
					created_at
				) VALUES (
					:listing_id,
					:seller_account_id,
					:seller_name,
					:buyer_account_id,
					:buyer_name,
					:item_vnum,
					:item_count,
					:price_md,
					:price_jd,
					NOW()
				)
			');

			$insertSaleLogStatement->bindValue(':listing_id', (int) $listing['id'], PDO::PARAM_INT);
			$insertSaleLogStatement->bindValue(':seller_account_id', $sellerAccountId, PDO::PARAM_INT);
			$insertSaleLogStatement->bindValue(':seller_name', $sellerName, PDO::PARAM_STR);
			$insertSaleLogStatement->bindValue(':buyer_account_id', $buyerAccountId, PDO::PARAM_INT);
			$insertSaleLogStatement->bindValue(':buyer_name', $buyerName, PDO::PARAM_STR);
			$insertSaleLogStatement->bindValue(':item_vnum', (int) $listing['item_vnum'], PDO::PARAM_INT);
			$insertSaleLogStatement->bindValue(':item_count', (int) $listing['item_count'], PDO::PARAM_INT);
			$insertSaleLogStatement->bindValue(':price_md', $priceMd, PDO::PARAM_INT);
			$insertSaleLogStatement->bindValue(':price_jd', (int) $listing['price_jd'], PDO::PARAM_INT);
			$insertSaleLogStatement->execute();

			if ($insertSaleLogStatement->rowCount() !== 1) {
				$connection->rollBack();

				return array(
					'success' => false,
					'message' => 'Vanzarea nu a putut fi salvata in istoric.',
				);
			}

            $insertItemStatement = $connection->prepare('
                INSERT INTO player.item (
                    owner_id,
                    window,
                    pos,
                    count,
                    vnum,
                    socket0,
                    socket1,
                    socket2,
                    socket3,
                    socket4,
                    socket5,
                    attrtype0,
                    attrvalue0,
                    attrtype1,
                    attrvalue1,
                    attrtype2,
                    attrvalue2,
                    attrtype3,
                    attrvalue3,
                    attrtype4,
                    attrvalue4,
                    attrtype5,
                    attrvalue5,
                    attrtype6,
                    attrvalue6
                ) VALUES (
                    :owner_id,
                    :window,
                    :pos,
                    :count,
                    :vnum,
                    :socket0,
                    :socket1,
                    :socket2,
                    0,
                    0,
                    0,
                    :attrtype0,
                    :attrvalue0,
                    :attrtype1,
                    :attrvalue1,
                    :attrtype2,
                    :attrvalue2,
                    :attrtype3,
                    :attrvalue3,
                    :attrtype4,
                    :attrvalue4,
                    :attrtype5,
                    :attrvalue5,
                    :attrtype6,
                    :attrvalue6
                )
            ');

            $insertItemStatement->bindValue(':owner_id', $buyerAccountId, PDO::PARAM_INT);
            $insertItemStatement->bindValue(':window', 'MALL', PDO::PARAM_STR);
            $insertItemStatement->bindValue(':pos', $mallPos, PDO::PARAM_INT);
            $insertItemStatement->bindValue(':count', (int) $listing['item_count'], PDO::PARAM_INT);
            $insertItemStatement->bindValue(':vnum', (int) $listing['item_vnum'], PDO::PARAM_INT);
            $insertItemStatement->bindValue(':socket0', (int) $listing['socket0'], PDO::PARAM_INT);
            $insertItemStatement->bindValue(':socket1', (int) $listing['socket1'], PDO::PARAM_INT);
            $insertItemStatement->bindValue(':socket2', (int) $listing['socket2'], PDO::PARAM_INT);
            $insertItemStatement->bindValue(':attrtype0', (int) $listing['attrtype0'], PDO::PARAM_INT);
            $insertItemStatement->bindValue(':attrvalue0', (int) $listing['attrvalue0'], PDO::PARAM_INT);
            $insertItemStatement->bindValue(':attrtype1', (int) $listing['attrtype1'], PDO::PARAM_INT);
            $insertItemStatement->bindValue(':attrvalue1', (int) $listing['attrvalue1'], PDO::PARAM_INT);
            $insertItemStatement->bindValue(':attrtype2', (int) $listing['attrtype2'], PDO::PARAM_INT);
            $insertItemStatement->bindValue(':attrvalue2', (int) $listing['attrvalue2'], PDO::PARAM_INT);
            $insertItemStatement->bindValue(':attrtype3', (int) $listing['attrtype3'], PDO::PARAM_INT);
            $insertItemStatement->bindValue(':attrvalue3', (int) $listing['attrvalue3'], PDO::PARAM_INT);
            $insertItemStatement->bindValue(':attrtype4', (int) $listing['attrtype4'], PDO::PARAM_INT);
            $insertItemStatement->bindValue(':attrvalue4', (int) $listing['attrvalue4'], PDO::PARAM_INT);
            $insertItemStatement->bindValue(':attrtype5', (int) $listing['attrtype5'], PDO::PARAM_INT);
            $insertItemStatement->bindValue(':attrvalue5', (int) $listing['attrvalue5'], PDO::PARAM_INT);
            $insertItemStatement->bindValue(':attrtype6', (int) $listing['attrtype6'], PDO::PARAM_INT);
            $insertItemStatement->bindValue(':attrvalue6', (int) $listing['attrvalue6'], PDO::PARAM_INT);
            $insertItemStatement->execute();

            if ($insertItemStatement->rowCount() !== 1) {
                $connection->rollBack();

                return array(
                    'success' => false,
                    'message' => 'Itemul nu a putut fi trimis in Mall.',
                );
            }

            $deleteListingStatement = $connection->prepare('
                DELETE FROM player.market_listings_test
                WHERE id = :id
                  AND account_id = :seller_id
                LIMIT 1
            ');

            $deleteListingStatement->bindValue(':id', (int) $listing['id'], PDO::PARAM_INT);
            $deleteListingStatement->bindValue(':seller_id', $sellerAccountId, PDO::PARAM_INT);
            $deleteListingStatement->execute();

            if ($deleteListingStatement->rowCount() !== 1) {
                $connection->rollBack();

                return array(
                    'success' => false,
                    'message' => 'Itemul a fost trimis in Mall, dar listingul nu a putut fi sters.',
                );
            }

            $connection->commit();

            return array(
                'success' => true,
                'message' => 'Itemul a fost cumparat si trimis in depozitul Itemshop.',
            );
        } catch (Throwable $exception) {
            if ($connection->inTransaction()) {
                $connection->rollBack();
            }

            return array(
                'success' => false,
                'message' => 'Cumpararea a esuat. Incearca din nou.',
            );
        }
    }
}