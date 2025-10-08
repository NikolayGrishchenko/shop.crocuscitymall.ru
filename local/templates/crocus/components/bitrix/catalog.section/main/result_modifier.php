<?

use Bitrix\Iblock\SectionTable;

if ($arParams['USE_SECTION_PATH_FIX'] === 'Y') {
	foreach ($arResult['ITEMS'] as &$item) {
		if (!empty($item['IBLOCK_SECTION_ID'])) {
			$section = SectionTable::getList([
				'select' => ['CODE', 'IBLOCK_SECTION_ID'],
				'filter' => ['=ID' => $item['IBLOCK_SECTION_ID']],
				'limit'  => 1,
			])->fetch();

			if ($section) {
				$item['DETAIL_PAGE_URL'] = str_replace(
					['#SECTION_CODE_PATH#', '#ELEMENT_CODE#'],
					[$section['CODE'], $item['CODE']],
					$arParams['DETAIL_URL'],
				);
			}
		}
	}

	unset($item);
}

foreach ($arResult['ITEMS'] as &$item) {
	if (!empty($item['ITEM_PRICES'])) {
		$item['PRICE'] = $item['ITEM_PRICES'][0]['PRICE'];
	} elseif (!empty($item['OFFERS'])) {
		foreach ($item['OFFERS'] as $offer) {
			if (!empty($offer['ITEM_PRICES'])) {
				$item['PRICE'] = $offer['ITEM_PRICES'][0]['PRICE'];
			}
		}
	}
}
unset($item);
