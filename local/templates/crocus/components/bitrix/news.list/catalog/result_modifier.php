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

	$mapOffer = [];
	$res = CIBlockElement::getList([], [
		'IBLOCK_CODE' => 'catalog_offers',
		'PROPERTY_CML2_LINK' => $ids,
		'ACTIVE' => 'Y',
	]);
	while ($ob = $res->getNextElement()) {
		$item = $ob->getFields();
		$item['PROPERTIES'] = $ob->getProperties();

		$mapOffer[$item['PROPERTIES']['CML2_LINK']['VALUE']][] = $item;
	}

	foreach ($arResult['ITEMS'] as &$item) {
		$item['PRICE'] = $mapPrice[$item['ID']];
		$item['OFFERS'] = $mapOffer[$item['ID']];
	}
	unset($item);
}