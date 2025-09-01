<?
if (array_key_exists('MAX_LEVEL', $arParams)) {
	$arResult = array_filter($arResult, function($item) use ($arParams) {
		return $item['DEPTH_LEVEL'] <= $arParams['MAX_LEVEL'];
	});
}