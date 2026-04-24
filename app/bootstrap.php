<?php
declare(strict_types=1);

session_start();

require BASE_PATH . '/app/autoload.php';
require BASE_PATH . '/config/app.php';

use App\Services\AuthService;
use App\Services\RegisterService;
use App\Services\VerificationService;
use App\Repositories\MarketDepotRepository;
use App\Resolvers\ItemIconResolver;
use App\Resolvers\ItemProtoResolver;
use App\Resolvers\ItemNameResolver;
use App\Resolvers\ItemDescResolver;
use App\Resolvers\ItemAttributeResolver;
use App\Resolvers\ItemFixedBonusResolver;
use App\Services\FleamarketSellService;
use App\Repositories\MarketListingRepository;
use App\Services\FleamarketWithdrawService;
use App\Repositories\AccountRepository;
use App\Services\FleamarketWithdrawListingService;
use App\Services\FleamarketReturnListingToDepotService;
use App\Services\FleamarketBuyService;

$pageTitle = 'LuraMT2';
$bodyClass = 'page-home';
$loginError = '';
$currentUser = null;
$isLoggedIn = false;
$registerError = '';
$registerSuccess = '';
$registerToken = '';
$registerCode = '';
$openVerificationModal = false;
$verifyError = '';
$verifySuccess = '';
$openLoginModal = false;
$loginSuccess = '';
$marketDepotItems = array();
$itemIconResolver = new ItemIconResolver();
$itemProtoResolver = new ItemProtoResolver();
$itemNameResolver = new ItemNameResolver();
$selectedDepotItem = null;
$openFleamarketModal = false;
$itemDescResolver = new ItemDescResolver();
$itemAttributeResolver = new ItemAttributeResolver();
$itemFixedBonusResolver = new ItemFixedBonusResolver();
$fleamarketSellError = '';
$fleamarketSellSuccess = '';
$myMarketListings = array();
$fleamarketWithdrawError = '';
$fleamarketWithdrawSuccess = '';
$fleamarketNoticeMessage = '';
$openFleamarketNotice = false;
$activeListingCount = 0;
$maxListingSlots = 3;
$fleaReputation = 0.00;
$fleamarketListingWithdrawError = '';
$fleamarketListingWithdrawSuccess = '';
$activeFleamarketTab = 'depot';
$publicMarketListings = array();
$fleamarketBuyError = '';
$fleamarketBuySuccess = '';
$activeMarketSearch = '';
$activeMarketBonusFilters = array();
$depotCurrentPage = 1;
$depotPerPage = 44;
$depotTotalItems = 0;
$depotTotalPages = 1;
$marketCurrentPage = 1;
$marketPerPage = 10;
$marketTotalItems = 0;
$marketTotalPages = 1;

if (isset($_SESSION['auth_user']) && is_array($_SESSION['auth_user'])) {
    $currentUser = $_SESSION['auth_user'];
    $isLoggedIn = true;
}

if ($isLoggedIn && !empty($currentUser['login'])) {
    $accountRepository = new \App\Repositories\AccountRepository();
    $freshAccount = $accountRepository->findByLogin($currentUser['login']);

    if (!empty($freshAccount)) {
        $currentUser = $freshAccount;
        $_SESSION['auth_user'] = $freshAccount;
    }
}

if ($isLoggedIn && !empty($currentUser['id'])) {
    $marketDepotRepository = new MarketDepotRepository();
	if (isset($_GET['depot_page'])) {
		$depotCurrentPage = (int) $_GET['depot_page'];
		if ($depotCurrentPage < 1) {
			$depotCurrentPage = 1;
		}
	}

	$depotTotalItems = $marketDepotRepository->countItemsByAccountId((int) $currentUser['id']);
	$depotTotalPages = max(1, (int) ceil($depotTotalItems / $depotPerPage));
	if ($depotCurrentPage > $depotTotalPages) {
		$depotCurrentPage = $depotTotalPages;
	}

	$depotOffset = ($depotCurrentPage - 1) * $depotPerPage;
    $marketDepotItems = $marketDepotRepository->getItemsByAccountId(
		(int) $currentUser['id'],
		$depotPerPage,
		$depotOffset
	);
	$marketListingRepository = new MarketListingRepository();
	$myMarketListings = $marketListingRepository->getActiveListingsByAccountId((int) $currentUser['id']);
	$activeListingCount = count($myMarketListings);
	$accountRepository = new AccountRepository();
	$fleaReputation = $accountRepository->getFleaReputationById((int) $currentUser['id']);
	$maxListingSlots = min(20, 3 + (int) floor($fleaReputation / 5));

	if ($marketCurrentPage > $marketTotalPages) {
		$marketCurrentPage = $marketTotalPages;
	}

	$publicMarketListings = $marketListingRepository->getPublicActiveListings(100000000, 0);

	if (isset($_GET['fleamarket_open']) && $_GET['fleamarket_open'] === '1') {
		$openFleamarketModal = true;
	}

	if (isset($_GET['fleamarket_tab']) && in_array($_GET['fleamarket_tab'], array('depot', 'offers', 'market'), true)) {
		$activeFleamarketTab = $_GET['fleamarket_tab'];
	}

	if (isset($_GET['market_search']) && is_string($_GET['market_search'])) {
		$activeMarketSearch = trim($_GET['market_search']);
	}

	if (isset($_GET['market_bonus']) && is_string($_GET['market_bonus'])) {
		$rawMarketBonusFilters = explode(',', $_GET['market_bonus']);

		foreach ($rawMarketBonusFilters as $bonusFilter) {
			$bonusFilter = trim((string) $bonusFilter);

			if ($bonusFilter === '') {
				continue;
			}

			$activeMarketBonusFilters[] = $bonusFilter;
		}

		$activeMarketBonusFilters = array_values(array_unique($activeMarketBonusFilters));
	}

	if (!function_exists('fleamarket_normalize_text')) {
		function fleamarket_normalize_text($text)
		{
			$text = (string) $text;

			$replaceMap = array(
				'Ă' => 'A', 'Â' => 'A', 'Î' => 'I', 'Ș' => 'S', 'Ş' => 'S', 'Ț' => 'T', 'Ţ' => 'T',
				'ă' => 'a', 'â' => 'a', 'î' => 'i', 'ș' => 's', 'ş' => 's', 'ț' => 't', 'ţ' => 't',
				'Á' => 'A', 'À' => 'A', 'Ä' => 'A', 'Ã' => 'A', 'Å' => 'A',
				'á' => 'a', 'à' => 'a', 'ä' => 'a', 'ã' => 'a', 'å' => 'a',
				'É' => 'E', 'È' => 'E', 'Ê' => 'E', 'Ë' => 'E',
				'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
				'Í' => 'I', 'Ì' => 'I', 'Ï' => 'I',
				'í' => 'i', 'ì' => 'i', 'ï' => 'i',
				'Ó' => 'O', 'Ò' => 'O', 'Ô' => 'O', 'Ö' => 'O', 'Õ' => 'O',
				'ó' => 'o', 'ò' => 'o', 'ô' => 'o', 'ö' => 'o', 'õ' => 'o',
				'Ú' => 'U', 'Ù' => 'U', 'Û' => 'U', 'Ü' => 'U',
				'ú' => 'u', 'ù' => 'u', 'û' => 'u', 'ü' => 'u',
				'Ý' => 'Y', 'ý' => 'y', 'ÿ' => 'y',
				'Ç' => 'C', 'ç' => 'c',
				'Ñ' => 'N', 'ñ' => 'n'
			);

			$text = strtr($text, $replaceMap);
			$text = strtolower($text);

			return trim($text);
		}
	}

	if (!function_exists('fleamarket_bonus_key_matches_text')) {
		function fleamarket_bonus_key_matches_text($bonusKey, $bonusText)
		{
			$bonusText = fleamarket_normalize_text($bonusText);

			$map = array(
				'semi_human' => array('tare impotriva semi-oamenilor'),
				'animal' => array('tare impotriva animalelor'),
				'orc' => array('tare impotriva orcilor'),
				'esoteric' => array('tare impotriva esotericilor'),
				'undead' => array('tare impotriva vampirilor'),
				'devil' => array('tare impotriva diavolului'),
				'monster' => array('tare impotriva monstrilor'),
				'warrior' => array('tare impotriva razboinicilor'),
				'assassin' => array('tare impotriva ninja'),
				'sura' => array('tare impotriva sura'),
				'shaman' => array('tare impotriva samanilor'),
				'critical' => array('lovitura critica'),
				'penetrate' => array('lovitura patrunzatoare'),
				'attack_speed' => array('viteza de atac'),
				'move_speed' => array('viteza de miscare'),
				'cast_speed' => array('viteza farmecului'),
				'max_hp' => array('max. pv'),
				'max_sp' => array('max. pm'),
				'strength' => array('putere'),
				'dexterity' => array('dexteritate'),
				'vitality' => array('vitalitate'),
				'intelligence' => array('inteligenta'),
				'steal_hp' => array('absorbtie pv'),
				'steal_sp' => array('absorbtie pm'),
				'arrow_resist' => array('rezistenta la sageti'),
				'fire_resist' => array('rezistenta la foc'),
				'electric_resist' => array('rezistenta la fulger'),
				'magic_resist' => array('rezistenta la magie'),
				'wind_resist' => array('rezistenta la vant'),
				'average' => array('paguba medie'),
				'average_resist' => array('rezistenta medie la paguba'),
				'skill_damage' => array('paguba competentei'),
				'skill_resist' => array('rezistenta la paguba competentei'),
			);

			if (!isset($map[$bonusKey])) {
				return false;
			}

			foreach ($map[$bonusKey] as $needle) {
				if (mb_stripos($bonusText, fleamarket_normalize_text($needle), 0, 'UTF-8') !== false) {
					return true;
				}
			}

			return false;
		}
	}

	if (!empty($activeMarketSearch) || !empty($activeMarketBonusFilters)) {
		$filteredMarketListings = array();

		foreach ($publicMarketListings as $publicListingItem) {
			$itemName = $itemNameResolver->getNameByVnum($publicListingItem['item_vnum']);
			$itemName = !empty($itemName) ? $itemName : ('VNUM ' . (int) $publicListingItem['item_vnum']);


			if (!empty($activeMarketBonusFilters)) {
				$itemProtoRow = $itemProtoResolver->getItemByVnum($publicListingItem['item_vnum']);
				$fixedBonusLines = array();
				$extraBonusLines = $itemAttributeResolver->getExtraBonusLines($publicListingItem);
				$socketBonusLines = array();

				if (!empty($itemProtoRow)) {
					$fixedBonusLines = $itemFixedBonusResolver->getFixedBonusLines($itemProtoRow);
				}

				for ($socketIndex = 0; $socketIndex <= 2; $socketIndex++) {
					$socketKey = 'socket' . $socketIndex;
					$socketVnum = isset($publicListingItem[$socketKey]) ? (int) $publicListingItem[$socketKey] : 0;

					if ($socketVnum <= 2) {
						continue;
					}

					$socketProtoRow = $itemProtoResolver->getItemByVnum($socketVnum);
					if (!empty($socketProtoRow)) {
						$socketBonusLines = array_merge($socketBonusLines, $itemFixedBonusResolver->getFixedBonusLines($socketProtoRow));
					}
				}

				$allBonusLines = array_merge($fixedBonusLines, $extraBonusLines, $socketBonusLines);

				$matchesAllBonuses = true;

				foreach ($activeMarketBonusFilters as $bonusFilter) {
					$foundBonusMatch = false;

					foreach ($allBonusLines as $bonusLine) {
						if (fleamarket_bonus_key_matches_text($bonusFilter, $bonusLine)) {
							$foundBonusMatch = true;
							break;
						}
					}

					if (!$foundBonusMatch) {
						$matchesAllBonuses = false;
						break;
					}
				}

				if (!$matchesAllBonuses) {
					continue;
				}
			}

			$filteredMarketListings[] = $publicListingItem;
		}

		$publicMarketListings = $filteredMarketListings;
	}

	$marketSellerReputations = array();

	foreach ($publicMarketListings as $publicListingItem) {
		$sellerAccountId = isset($publicListingItem['account_id']) ? (int) $publicListingItem['account_id'] : 0;

		if ($sellerAccountId < 1) {
			continue;
		}

		if (!isset($marketSellerReputations[$sellerAccountId])) {
			$marketSellerReputations[$sellerAccountId] = $accountRepository->getFleaReputationById($sellerAccountId);
		}
	}

    if (isset($_GET['selected_depot_item'])) {
        $selectedDepotItemId = (int) $_GET['selected_depot_item'];

        if ($selectedDepotItemId > 0) {
            $selectedDepotItem = $marketDepotRepository->findByIdAndAccountId(
                $selectedDepotItemId,
                (int) $currentUser['id']
            );
        }

        $openFleamarketModal = true;
    }


	if (isset($_GET['fleamarket_notice'])) {
		if ($_GET['fleamarket_notice'] === 'withdraw_success') {
			$fleamarketNoticeMessage = 'Itemul a fost trimis in depozitul Itemshop.';
			$openFleamarketNotice = true;
		}

		if ($_GET['fleamarket_notice'] === 'sell_success') {
			$fleamarketNoticeMessage = 'Itemul a fost listat cu succes in FleaMarket.';
			$openFleamarketNotice = true;
		}

		if ($_GET['fleamarket_notice'] === 'listing_returned_to_depot') {
			$fleamarketNoticeMessage = 'Itemul a fost mutat inapoi in depozitul FleaMarket.';
			$openFleamarketNotice = true;
		}

		if ($_GET['fleamarket_notice'] === 'buy_success') {
			$fleamarketNoticeMessage = 'Itemul a fost cumparat si trimis in depozitul Itemshop.';
			$openFleamarketNotice = true;
		}

	}

    if ($selectedDepotItem === null && !empty($marketDepotItems)) {
        $selectedDepotItem = $marketDepotItems[0];
    }
}

if ($selectedDepotItem === null && !empty($marketDepotItems)) {
    $selectedDepotItem = $marketDepotItems[0];
}

if (isset($_GET['logout']) && $_GET['logout'] === '1') {
    unset($_SESSION['auth_user']);
    session_regenerate_id(true);

    header('Location: /');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_form'])) {
    $authService = new AuthService();

    $result = $authService->attemptLogin(
        isset($_POST['username']) ? $_POST['username'] : '',
        isset($_POST['password']) ? $_POST['password'] : ''
    );

    if (!empty($result['success'])) {
		$_SESSION['auth_user'] = $result['account'];
		$currentUser = $result['account'];
		$isLoggedIn = true;
	} else {
        $loginError = isset($result['message']) ? $result['message'] : 'Autentificare esuata.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register_form'])) {
    $registerService = new RegisterService();

    $result = $registerService->startRegistration(
        isset($_POST['username']) ? $_POST['username'] : '',
        isset($_POST['email']) ? $_POST['email'] : '',
        isset($_POST['password']) ? $_POST['password'] : '',
        isset($_POST['password_confirm']) ? $_POST['password_confirm'] : '',
        isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : ''
    );

    if (!empty($result['success'])) {
        $registerSuccess = isset($result['message']) ? $result['message'] : 'Inregistrare pending creata.';
        $registerToken = isset($result['token']) ? $result['token'] : '';
        $registerCode = isset($result['code']) ? $result['code'] : '';
		$openVerificationModal = true;
    } else {
        $registerError = isset($result['message']) ? $result['message'] : 'Inregistrarea a esuat.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify_form'])) {
    $verificationService = new VerificationService();

    $result = $verificationService->verifyPendingAccount(
        isset($_POST['verification_token']) ? $_POST['verification_token'] : '',
        isset($_POST['verification_code']) ? $_POST['verification_code'] : ''
    );

    if (!empty($result['success'])) {
		$verifySuccess = '';
		$loginSuccess = isset($result['message']) ? $result['message'] : 'Contul a fost creat cu succes.';
		$openVerificationModal = false;
		$loginError = '';
		$registerError = '';
		$registerSuccess = '';
		$openLoginModal = true;
	} else {
        $verifyError = isset($result['message']) ? $result['message'] : 'Verificarea a esuat.';
        $openVerificationModal = true;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fleamarket_sell_form'])) {
    $sellService = new FleamarketSellService();

    $result = $sellService->sellSelectedDepotItem(
        !empty($currentUser['id']) ? (int) $currentUser['id'] : 0,
        isset($_POST['depot_item_id']) ? (int) $_POST['depot_item_id'] : 0,
        isset($_POST['price_md']) ? (int) $_POST['price_md'] : 0
    );

    $openFleamarketModal = true;

    if (!empty($result['success'])) {
		header('Location: /?fleamarket_open=1&fleamarket_notice=sell_success');
		exit;
	} else {
        $fleamarketSellError = isset($result['message']) ? $result['message'] : 'Listarea a esuat.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fleamarket_withdraw_form'])) {
    $withdrawService = new FleamarketWithdrawService();

    $result = $withdrawService->withdrawDepotItemToMall(
        !empty($currentUser['id']) ? (int) $currentUser['id'] : 0,
        isset($_POST['depot_item_id']) ? (int) $_POST['depot_item_id'] : 0
    );

    $openFleamarketModal = true;

   if (!empty($result['success'])) {
		header('Location: /?fleamarket_open=1&fleamarket_notice=withdraw_success');
		exit;
	} else {
        $fleamarketWithdrawError = isset($result['message']) ? $result['message'] : 'Retragerea a esuat.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fleamarket_listing_withdraw_form'])) {
    $withdrawListingService = new FleamarketWithdrawListingService();

    $result = $withdrawListingService->startWithdrawTimer(
        !empty($currentUser['id']) ? (int) $currentUser['id'] : 0,
        isset($_POST['listing_id']) ? (int) $_POST['listing_id'] : 0
    );

    $openFleamarketModal = true;
	$activeFleamarketTab = 'offers';

   if (!empty($result['success'])) {
		header('Location: /?fleamarket_open=1&fleamarket_tab=offers');
		exit;
	} else {
        $fleamarketListingWithdrawError = isset($result['message']) ? $result['message'] : 'Retragerea nu a putut fi pornita.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fleamarket_listing_finalize_return_form'])) {
    $returnListingService = new FleamarketReturnListingToDepotService();

    $result = $returnListingService->finalizeReturnToDepot(
        !empty($currentUser['id']) ? (int) $currentUser['id'] : 0,
        isset($_POST['listing_id']) ? (int) $_POST['listing_id'] : 0
    );
    $openFleamarketModal = true;
    $activeFleamarketTab = 'offers';

    if (!empty($result['success'])) {
        header('Location: /?fleamarket_open=1&fleamarket_tab=offers&fleamarket_notice=listing_returned_to_depot');
        exit;
    } else {
        $fleamarketListingWithdrawError = isset($result['message']) ? $result['message'] : 'Itemul nu a putut fi returnat in depozit.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fleamarket_buy_form'])) {
    $buyService = new FleamarketBuyService();

    $result = $buyService->buyListing(
        !empty($currentUser['id']) ? (int) $currentUser['id'] : 0,
        isset($_POST['listing_id']) ? (int) $_POST['listing_id'] : 0
    );

    $openFleamarketModal = true;
    $activeFleamarketTab = 'market';

    if (!empty($result['success'])) {
        header('Location: /?fleamarket_open=1&fleamarket_tab=market&fleamarket_notice=buy_success');
        exit;
    } else {
        $fleamarketBuyError = isset($result['message']) ? $result['message'] : 'Cumpararea a esuat.';
    }
}

if (isset($_GET['fleamarket_partial']) && $_GET['fleamarket_partial'] === '1') {
    require BASE_PATH . '/resources/views/partials/fleamarket-content.php';
    exit;
}

require BASE_PATH . '/resources/views/layouts/app.php';