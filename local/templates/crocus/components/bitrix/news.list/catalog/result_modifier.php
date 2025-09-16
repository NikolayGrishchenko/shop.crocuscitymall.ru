<?
use \Bitrix\Catalog\PriceTable;
use \Bitrix\Main\Loader;

Loader::includeModule("catalog");

$ids = array_column($arResult['ITEMS'], 'ID');
if (!empty($ids)) {
	$mapPrice = [];
	$res = PriceTable::getList([
		'filter' => [
			'PRODUCT_ID' => $ids,
		]
	]);
	while ($item = $res->fetch()) {
		$mapPrice[$item['PRODUCT_ID']] = $item['PRICE'];
	}

	foreach ($arResult['ITEMS'] as &$item) {
		$item['PRICE'] = $mapPrice[$item['ID']];
	}
	unset($item);
}