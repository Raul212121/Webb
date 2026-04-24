<?php
declare(strict_types=1);

namespace App\Resolvers;

final class ItemFixedBonusResolver
{
    public function getFixedBonusLines(array $itemProtoRow)
	{
		$lines = array();

		$itemType = isset($itemProtoRow['item_type']) ? $itemProtoRow['item_type'] : '';
		$subType = isset($itemProtoRow['sub_type']) ? $itemProtoRow['sub_type'] : '';

		if ($itemType === 'ITEM_WEAPON') {
			$value1 = isset($itemProtoRow['value1']) ? (int) $itemProtoRow['value1'] : 0;
			$value2 = isset($itemProtoRow['value2']) ? (int) $itemProtoRow['value2'] : 0;
			$value3 = isset($itemProtoRow['value3']) ? (int) $itemProtoRow['value3'] : 0;
			$value4 = isset($itemProtoRow['value4']) ? (int) $itemProtoRow['value4'] : 0;
			$value5 = isset($itemProtoRow['value5']) ? (int) $itemProtoRow['value5'] : 0;

			$attackMin = $value3 + $value5;
			$attackMax = $value4 + $value5;
			$magicMin = $value1;
			$magicMax = $value2;

			$attackLine = null;
			$magicLine = null;

			if ($attackMin > 0 || $attackMax > 0) {
				if ($attackMax > $attackMin) {
					$attackLine = 'Valoarea atacului ' . $attackMin . ' - ' . $attackMax;
				} else {
					$attackLine = 'Valoarea atacului ' . max($attackMin, $attackMax);
				}
			}

			if ($value1 > 0 || $value2 > 0) {
				$magicMin += $value5;
				$magicMax += $value5;

				if ($magicMax > $magicMin) {
					$magicLine = 'Valoarea atacului magic ' . $magicMin . ' - ' . $magicMax;
				} else {
					$magicLine = 'Valoarea atacului magic ' . max($magicMin, $magicMax);
				}
			}

			if ($subType === 'WEAPON_FAN') {
				if ($magicLine !== null) {
					$lines[] = $magicLine;
				}
				if ($attackLine !== null) {
					$lines[] = $attackLine;
				}
			} else {
				if ($attackLine !== null) {
					$lines[] = $attackLine;
				}
				if ($magicLine !== null) {
					$lines[] = $magicLine;
				}
			}
		}

		if ($itemType === 'ITEM_ARMOR') {
			$value0 = isset($itemProtoRow['value0']) ? (int) $itemProtoRow['value0'] : 0;
			$value1 = isset($itemProtoRow['value1']) ? (int) $itemProtoRow['value1'] : 0;
			$value5 = isset($itemProtoRow['value5']) ? (int) $itemProtoRow['value5'] : 0;

			$defense = $value1 + ($value5 * 2);
			$magicDefense = $value0;

			if ($defense > 0) {
				$lines[] = 'Aparare ' . $defense;
			}

			if ($magicDefense > 0) {
				$lines[] = 'Aparare magica ' . $magicDefense;
			}
		}

		$lines = array_merge($lines, $this->getAddonLines($itemProtoRow));

		return $lines;
	}

    private function getAddonLines(array $itemProtoRow)
    {
        $lines = array();

        for ($i = 0; $i <= 2; $i++) {
            $typeKey = 'addon_type' . $i;
            $valueKey = 'addon_value' . $i;

            $addonType = isset($itemProtoRow[$typeKey]) ? trim((string) $itemProtoRow[$typeKey]) : '';
            $addonValue = isset($itemProtoRow[$valueKey]) ? (int) $itemProtoRow[$valueKey] : 0;

            if ($addonType === '' || $addonType === 'APPLY_NONE' || $addonValue === 0) {
                continue;
            }

            $lines[] = $this->formatAddonLine($addonType, $addonValue);
        }

        return $lines;
    }

    private function formatAddonLine($addonType, $addonValue)
    {
        $map = array(
            'APPLY_MAX_HP' => 'Max. PV +%d',
            'APPLY_MAX_SP' => 'Max. PM +%d',
            'APPLY_CON' => 'Vitalitate +%d',
            'APPLY_INT' => 'Inteligenta +%d',
            'APPLY_STR' => 'Putere +%d',
            'APPLY_DEX' => 'Dexteritate +%d',
            'APPLY_ATT_SPEED' => 'Viteza de atac +%d%%',
            'APPLY_MOV_SPEED' => 'Viteza de miscare +%d%%',
            'APPLY_CAST_SPEED' => 'Viteza farmecului +%d%%',
            'APPLY_HP_REGEN' => 'Regenerarea PV +%d%%',
            'APPLY_SP_REGEN' => 'Regenerare PM +%d%%',
            'APPLY_CRITICAL_PCT' => 'Sansa de lovitura critica +%d%%',
            'APPLY_PENETRATE_PCT' => 'Sansa la lovituri patrunzatoare +%d%%',
            'APPLY_ATTBONUS_HUMAN' => 'Tare impotriva semi-oamenilor +%d%%',
            'APPLY_ATTBONUS_ANIMAL' => 'Tare impotriva animalelor +%d%%',
            'APPLY_ATTBONUS_ORC' => 'Tare impotriva orcilor +%d%%',
            'APPLY_ATTBONUS_MILGYO' => 'Tare impotriva esotericilor +%d%%',
            'APPLY_ATTBONUS_UNDEAD' => 'Puternic impotriva vampirilor +%d%%',
            'APPLY_ATTBONUS_DEVIL' => 'Tare impotriva diavolului +%d%%',
			'APPLY_ATTBONUS_MONSTER' => 'Tare impotriva monstrilor +%d%%',
            'APPLY_STEAL_HP' => 'Absorbtie PV +%d%%',
            'APPLY_STEAL_SP' => 'Absorbtie PM +%d%%',
            'APPLY_BLOCK' => 'Sansa de a bloca un atac corporal +%d%%',
            'APPLY_DODGE' => 'Sansa de a evita atacul cu sageti +%d%%',
            'APPLY_RESIST_SWORD' => 'Aparare sabie %d%%',
            'APPLY_RESIST_TWOHAND' => 'Aparare doua maini %d%%',
            'APPLY_RESIST_DAGGER' => 'Aparare pumnal %d%%',
            'APPLY_RESIST_BELL' => 'Aparare clopot %d%%',
            'APPLY_RESIST_FAN' => 'Aparare evantai %d%%',
            'APPLY_RESIST_BOW' => 'Rezistenta la sageti %d%%',
            'APPLY_RESIST_FIRE' => 'Rezistenta la foc %d%%',
            'APPLY_RESIST_ELEC' => 'Rezistenta la fulger %d%%',
            'APPLY_RESIST_MAGIC' => 'Rezistenta la magie %d%%',
            'APPLY_RESIST_WIND' => 'Rezistenta la vant %d%%',
        );

        if (!isset($map[$addonType])) {
            return $addonType . ': ' . $addonValue;
        }

        $text = str_replace('%d%%', $addonValue . '%', $map[$addonType]);
		$text = str_replace('%d', (string) $addonValue, $text);

		return $text;
    }
}