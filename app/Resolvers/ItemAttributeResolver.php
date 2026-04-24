<?php
declare(strict_types=1);

namespace App\Resolvers;

final class ItemAttributeResolver
{
    private $applyTypeMap = null;
    private $tooltipTextMap = null;

    public function getExtraBonusLines(array $itemRow)
    {
        $lines = array();

        for ($i = 0; $i <= 6; $i++) {
            $typeKey = 'attrtype' . $i;
            $valueKey = 'attrvalue' . $i;

            $attrType = isset($itemRow[$typeKey]) ? (int) $itemRow[$typeKey] : 0;
            $attrValue = isset($itemRow[$valueKey]) ? (int) $itemRow[$valueKey] : 0;

            if ($attrType < 1 || $attrValue === 0) {
                continue;
            }

            $applyName = $this->getApplyNameByType($attrType);

            if (!$applyName) {
                $lines[] = 'Bonus necunoscut (' . $attrType . '): ' . $attrValue;
                continue;
            }

            $tooltipKey = 'TOOLTIP_' . $applyName;
            $template = $this->getTooltipTextByKey($tooltipKey);

            if (!$template) {
                $lines[] = $applyName . ': ' . $attrValue;
                continue;
            }

            $lines[] = $this->formatTooltipText($template, $attrValue);
        }

        return $lines;
    }

    private function getApplyNameByType($attrType)
    {
        $map = $this->getApplyTypeMap();

        return isset($map[$attrType]) ? $map[$attrType] : null;
    }

    private function getTooltipTextByKey($tooltipKey)
    {
        $map = $this->getTooltipTextMap();

        return isset($map[$tooltipKey]) ? $map[$tooltipKey] : null;
    }

    private function getApplyTypeMap()
	{
		if (is_array($this->applyTypeMap)) {
			return $this->applyTypeMap;
		}

		$this->applyTypeMap = array(
			1 => 'APPLY_MAX_HP',
			2 => 'APPLY_MAX_SP',
			3 => 'APPLY_CON',
			4 => 'APPLY_INT',
			5 => 'APPLY_STR',
			6 => 'APPLY_DEX',
			7 => 'APPLY_ATT_SPEED',
			8 => 'APPLY_MOV_SPEED',
			9 => 'APPLY_CAST_SPEED',
			10 => 'APPLY_HP_REGEN',
			11 => 'APPLY_SP_REGEN',
			12 => 'APPLY_POISON_PCT',
			13 => 'APPLY_STUN_PCT',
			14 => 'APPLY_SLOW_PCT',
			15 => 'APPLY_CRITICAL_PCT',
			16 => 'APPLY_PENETRATE_PCT',
			17 => 'APPLY_ATTBONUS_HUMAN',
			18 => 'APPLY_ATTBONUS_ANIMAL',
			19 => 'APPLY_ATTBONUS_ORC',
			20 => 'APPLY_ATTBONUS_MILGYO',
			21 => 'APPLY_ATTBONUS_UNDEAD',
			22 => 'APPLY_ATTBONUS_DEVIL',
			23 => 'APPLY_STEAL_HP',
			24 => 'APPLY_STEAL_SP',
			25 => 'APPLY_MANA_BURN_PCT',
			26 => 'APPLY_DAMAGE_SP_RECOVER',
			27 => 'APPLY_BLOCK',
			28 => 'APPLY_DODGE',
			29 => 'APPLY_RESIST_SWORD',
			30 => 'APPLY_RESIST_TWOHAND',
			31 => 'APPLY_RESIST_DAGGER',
			32 => 'APPLY_RESIST_BELL',
			33 => 'APPLY_RESIST_FAN',
			34 => 'APPLY_RESIST_BOW',
			35 => 'APPLY_RESIST_FIRE',
			36 => 'APPLY_RESIST_ELEC',
			37 => 'APPLY_RESIST_MAGIC',
			38 => 'APPLY_RESIST_WIND',
			39 => 'APPLY_REFLECT_MELEE',
			40 => 'APPLY_REFLECT_CURSE',
			41 => 'APPLY_POISON_REDUCE',
			42 => 'APPLY_KILL_SP_RECOVER',
			43 => 'APPLY_EXP_DOUBLE_BONUS',
			44 => 'APPLY_GOLD_DOUBLE_BONUS',
			45 => 'APPLY_ITEM_DROP_BONUS',
			46 => 'APPLY_POTION_BONUS',
			47 => 'APPLY_KILL_HP_RECOVER',
			48 => 'APPLY_IMMUNE_STUN',
			49 => 'APPLY_IMMUNE_SLOW',
			50 => 'APPLY_IMMUNE_FALL',
			51 => 'APPLY_SKILL',
			52 => 'APPLY_BOW_DISTANCE',
			53 => 'APPLY_ATT_GRADE_BONUS',
			54 => 'APPLY_DEF_GRADE_BONUS',
			55 => 'APPLY_MAGIC_ATT_GRADE',
			56 => 'APPLY_MAGIC_DEF_GRADE',
			57 => 'APPLY_CURSE_PCT',
			58 => 'APPLY_MAX_STAMINA',
			59 => 'APPLY_ATTBONUS_WARRIOR',
			60 => 'APPLY_ATTBONUS_ASSASSIN',
			61 => 'APPLY_ATTBONUS_SURA',
			62 => 'APPLY_ATTBONUS_SHAMAN',
			63 => 'APPLY_ATTBONUS_MONSTER',
			64 => 'APPLY_MALL_ATTBONUS',
			65 => 'APPLY_MALL_DEFBONUS',
			66 => 'APPLY_MALL_EXPBONUS',
			67 => 'APPLY_MALL_ITEMBONUS',
			68 => 'APPLY_MALL_GOLDBONUS',
			69 => 'APPLY_MAX_HP_PCT',
			70 => 'APPLY_MAX_SP_PCT',
			71 => 'APPLY_SKILL_DAMAGE_BONUS',
			72 => 'APPLY_NORMAL_HIT_DAMAGE_BONUS',
			73 => 'APPLY_SKILL_DEFEND_BONUS',
			74 => 'APPLY_NORMAL_HIT_DEFEND_BONUS',
			75 => 'APPLY_PC_BANG_EXP_BONUS',
			76 => 'APPLY_PC_BANG_DROP_BONUS',
			77 => 'APPLY_EXTRACT_HP_PCT',
			78 => 'APPLY_RESIST_WARRIOR',
			79 => 'APPLY_RESIST_ASSASSIN',
			80 => 'APPLY_RESIST_SURA',
			81 => 'APPLY_RESIST_SHAMAN',
			82 => 'APPLY_ENERGY',
			83 => 'APPLY_DEF_GRADE',
			84 => 'APPLY_COSTUME_ATTR_BONUS',
			85 => 'APPLY_MAGIC_ATTBONUS_PER',
		);

		return $this->applyTypeMap;
	}

	private function getTooltipTextMap()
	{
		if (is_array($this->tooltipTextMap)) {
			return $this->tooltipTextMap;
		}

		$this->tooltipTextMap = array(
			'TOOLTIP_APPLY_ATTBONUS_ANIMAL' => 'Tare impotriva animalelor +%d%%',
			'TOOLTIP_APPLY_ATTBONUS_ASSASSIN' => 'Tare impotriva ninja +%d%%',
			'TOOLTIP_APPLY_ATTBONUS_DEVIL' => 'Tare impotriva diavolului +%d%%',
			'TOOLTIP_APPLY_ATTBONUS_HUMAN' => 'Tare impotriva semi-oamenilor +%d%%',
			'TOOLTIP_APPLY_ATTBONUS_MILGYO' => 'Tare impotriva esotericilor +%d%%',
			'TOOLTIP_APPLY_ATTBONUS_MONSTER' => 'Tare impotriva monstrilor +%d%%',
			'TOOLTIP_APPLY_ATTBONUS_ORC' => 'Tare impotriva orcilor +%d%%',
			'TOOLTIP_APPLY_ATTBONUS_SHAMAN' => 'Tare impotriva samanilor +%d%%',
			'TOOLTIP_APPLY_ATTBONUS_SURA' => 'Tare impotriva sura +%d%%',
			'TOOLTIP_APPLY_ATTBONUS_UNDEAD' => 'Puternic impotriva vampirilor +%d%%',
			'TOOLTIP_APPLY_ATTBONUS_WARRIOR' => 'Tare impotriva razboinicilor +%d%%',
			'TOOLTIP_APPLY_BLOCK' => 'Sansa de a bloca un atac corporal +%d%%',
			'TOOLTIP_APPLY_CAST_SPEED' => 'Viteza farmecului +%d%%',
			'TOOLTIP_APPLY_CON' => 'Vitalitate +%d',
			'TOOLTIP_APPLY_COSTUME_ATTR_BONUS' => 'Bonus costum %d%%',
			'TOOLTIP_APPLY_CRITICAL_PCT' => 'Sansa de lovitura critica +%d%%',
			'TOOLTIP_APPLY_DAMAGE_SP_RECOVER' => 'Sansa %d%% de a primi inapoi PM la lovituri reusite',
			'TOOLTIP_APPLY_DEX' => 'Dexteritate +%d',
			'TOOLTIP_APPLY_DODGE' => 'Sansa %d%% de a evita atacul cu sageti',
			'TOOLTIP_APPLY_ENERGY' => 'Energie %d',
			'TOOLTIP_APPLY_EXP_DOUBLE_BONUS' => 'Sansa %d%% la bonus EXP',
			'TOOLTIP_APPLY_GOLD_DOUBLE_BONUS' => 'Sansa %d%% de a arunca de doua ori mai mult Yang',
			'TOOLTIP_APPLY_HP_REGEN' => 'Regenerarea PV +%d%%',
			'TOOLTIP_APPLY_IMMUNE_FALL' => 'Imun la cazatura',
			'TOOLTIP_APPLY_IMMUNE_SLOW' => 'Aparare impotriva incetinirii',
			'TOOLTIP_APPLY_IMMUNE_STUN' => 'Aparare impotriva necunostintei',
			'TOOLTIP_APPLY_INT' => 'Inteligenta +%d',
			'TOOLTIP_APPLY_ITEM_DROP_BONUS' => 'Sansa %d%% de a arunca de 2 ori mai multe obiecte',
			'TOOLTIP_APPLY_KILL_HP_RECOVER' => 'Sansa %d%% de a reface PV',
			'TOOLTIP_APPLY_KILL_SP_RECOVER' => 'Sansa %d%% de a reface PM',
			'TOOLTIP_APPLY_MANA_BURN_PCT' => '%d%% sansa de a jefui PM',
			'TOOLTIP_APPLY_MAGIC_ATTBONUS_PER' => 'Atac magic +%d%%',
			'TOOLTIP_APPLY_MAGIC_ATT_GRADE' => 'Valoarea atacului magic +%d',
			'TOOLTIP_APPLY_MAGIC_DEF_GRADE' => 'Aparare magica +%d',
			'TOOLTIP_APPLY_MALL_ATTBONUS' => 'Valoarea atacului +%d%%',
			'TOOLTIP_APPLY_MALL_DEFBONUS' => 'Aparare +%d%%',
			'TOOLTIP_APPLY_MALL_EXPBONUS' => 'EXP +%d%%',
			'TOOLTIP_APPLY_MALL_GOLDBONUS' => 'Sansa de drop Yang %d%%',
			'TOOLTIP_APPLY_MALL_ITEMBONUS' => 'Sansa de drop obiecte %d%%',
			'TOOLTIP_APPLY_MAX_HP' => 'Max. PV +%d',
			'TOOLTIP_APPLY_MAX_HP_PCT' => 'Max. PV +%d%%',
			'TOOLTIP_APPLY_MAX_SP' => 'Max. PM +%d',
			'TOOLTIP_APPLY_MAX_SP_PCT' => 'Max. PM +%d%%',
			'TOOLTIP_APPLY_MAX_STAMINA' => 'Rezistenta max. +%d',
			'TOOLTIP_APPLY_MOV_SPEED' => 'Viteza de miscare %d%%',
			'TOOLTIP_APPLY_NORMAL_HIT_DAMAGE_BONUS' => 'Paguba medie %d%%',
			'TOOLTIP_APPLY_NORMAL_HIT_DEFEND_BONUS' => 'Rezistenta medie la paguba %d%%',
			'TOOLTIP_APPLY_PENETRATE_PCT' => 'Sansa %d%% la lovituri patrunzatoare',
			'TOOLTIP_APPLY_POISON_PCT' => 'Sansa de otravire %d%%',
			'TOOLTIP_APPLY_POISON_REDUCE' => 'Rezistenta la otrava %d%%',
			'TOOLTIP_APPLY_POTION_BONUS' => '%d%% crestere efect al licorii',
			'TOOLTIP_APPLY_REFLECT_CURSE' => 'Sansa %d%% de a reflecta un blestem',
			'TOOLTIP_APPLY_REFLECT_MELEE' => '%d%% sansa de a reflecta loviturile atacului corporal',
			'TOOLTIP_APPLY_RESIST_ASSASSIN' => 'Sansa de aparare impotriva atacului ninja %d%%',
			'TOOLTIP_APPLY_RESIST_BELL' => 'Aparare clopot %d%%',
			'TOOLTIP_APPLY_RESIST_BOW' => 'Rezistenta la sageti %d%%',
			'TOOLTIP_APPLY_RESIST_DAGGER' => 'Aparare pumnal %d%%',
			'TOOLTIP_APPLY_RESIST_ELEC' => 'Rezistenta la fulger %d%%',
			'TOOLTIP_APPLY_RESIST_FAN' => 'Aparare evantai %d%%',
			'TOOLTIP_APPLY_RESIST_FIRE' => 'Rezistenta la foc %d%%',
			'TOOLTIP_APPLY_RESIST_MAGIC' => 'Rezistenta la magie %d%%',
			'TOOLTIP_APPLY_RESIST_SHAMAN' => 'Sansa de aparare impotriva atacului samanilor %d%%',
			'TOOLTIP_APPLY_RESIST_SLOW' => 'Aparare impotriva incetinirii',
			'TOOLTIP_APPLY_RESIST_SURA' => 'Sansa de aparare impotriva atacului sura %d%%',
			'TOOLTIP_APPLY_RESIST_SWORD' => 'Aparare sabie %d%%',
			'TOOLTIP_APPLY_RESIST_TWOHAND' => 'Aparare doua maini %d%%',
			'TOOLTIP_APPLY_RESIST_WARRIOR' => 'Sansa de aparare impotriva atacului razboinicilor %d%%',
			'TOOLTIP_APPLY_RESIST_WIND' => 'Rezistenta la vant %d%%',
			'TOOLTIP_APPLY_SKILL_DAMAGE_BONUS' => 'Paguba competentei %d%%',
			'TOOLTIP_APPLY_SKILL_DEFEND_BONUS' => 'Rezistenta la paguba competentei %d%%',
			'TOOLTIP_APPLY_SLOW_PCT' => 'Sansa de incetinire %d%%',
			'TOOLTIP_APPLY_SP_REGEN' => 'Regenerare PM +%d%%',
			'TOOLTIP_APPLY_STEAL_HP' => '%d%% daune vor fi absorbite de PV',
			'TOOLTIP_APPLY_STEAL_SP' => '%d%% daune vor fi absorbite de PM',
			'TOOLTIP_APPLY_STR' => 'Putere +%d',
			'TOOLTIP_APPLY_STUN_PCT' => 'Sansa de blocare %d%%',
			'TOOLTIP_APPLY_ATT_SPEED' => 'Viteza de atac +%d%%',
			'TOOLTIP_APPLY_ATT_GRADE_BONUS' => 'Valoarea atacului +%d',
			'TOOLTIP_APPLY_DEF_GRADE_BONUS' => 'Aparare +%d',
			'TOOLTIP_APPLY_DEF_GRADE' => 'Aparare +%d',
		);

		return $this->tooltipTextMap;
	}

    private function formatTooltipText($template, $value)
    {
        $text = str_replace('%d%%', $value . '%', $template);
        $text = str_replace('%d', (string) $value, $text);

        return $text;
    }

    private function normalizeText($text)
    {
        $text = trim((string) $text);

        if ($text === '') {
            return $text;
        }

        if (function_exists('mb_convert_encoding')) {
            $converted = @mb_convert_encoding($text, 'UTF-8', 'UTF-8, ISO-8859-1, ISO-8859-2, Windows-1250, Windows-1252');
            if (is_string($converted) && $converted !== '') {
                return $converted;
            }
        }

        if (function_exists('iconv')) {
            $converted = @iconv('Windows-1250', 'UTF-8//IGNORE', $text);
            if (is_string($converted) && $converted !== '') {
                return $converted;
            }
        }

        return $text;
    }
}