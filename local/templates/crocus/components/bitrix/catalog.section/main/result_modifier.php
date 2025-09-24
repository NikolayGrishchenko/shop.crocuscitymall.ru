<?
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