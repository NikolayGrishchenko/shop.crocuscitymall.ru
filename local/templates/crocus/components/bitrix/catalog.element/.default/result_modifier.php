<?
$arResult['BRAND'] = null;
if (!empty($arResult['PROPERTIES']['BRAND']['VALUE'])) {
	$res = CIBlockElement::getById($arResult['PROPERTIES']['BRAND']['VALUE']);
	if ($brand = $res->getNext()) {
		$arResult['BRAND'] = $brand;
	}
}