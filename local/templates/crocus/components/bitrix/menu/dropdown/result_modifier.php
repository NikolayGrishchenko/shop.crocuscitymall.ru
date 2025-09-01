<?
$arResult[] = [
	'TEXT' => 'Бренды',
	'LINK' => '/brands/',
	'DEPTH_LEVEL' => 1,
	'IS_PARENT' => false,
];

if (array_key_exists('MAX_LEVEL', $arParams)) {
	$arResult = array_filter($arResult, function($item) use ($arParams) {
		return $item['DEPTH_LEVEL'] <= $arParams['MAX_LEVEL'];
	});
}

$arNewMenu = [];
foreach ($arResult as $key => $arItem) {
    if ($arItem["DEPTH_LEVEL"] == 1) {
        $arNewMenu[] = $arItem;
    } elseif ($arItem["DEPTH_LEVEL"] == 2) {
        $arNewMenu[count($arNewMenu) - 1]["CHILD"][] = $arItem;
    } elseif ($arItem["DEPTH_LEVEL"] == 3) {
        $child2_count = count($arNewMenu[count($arNewMenu) - 1]["CHILD"]);
        $arNewMenu[count($arNewMenu) - 1]["CHILD"][$child2_count-1]['CHILD'][] = $arItem;
    } elseif ($arItem["DEPTH_LEVEL"] == 4) {
        $child2_count = count($arNewMenu[count($arNewMenu) - 1]["CHILD"]);
        $child3_count = count($arNewMenu[count($arNewMenu) - 1]["CHILD"][$child2_count-1]['CHILD']);
        $arNewMenu[count($arNewMenu) - 1]["CHILD"][$child2_count-1]['CHILD'][$child3_count-1]['CHILD'][] = $arItem;
    } elseif ($arItem["DEPTH_LEVEL"] == 5) {
        $child2_count = count($arNewMenu[count($arNewMenu) - 1]["CHILD"]);
        $child3_count = count($arNewMenu[count($arNewMenu) - 1]["CHILD"][$child2_count-1]['CHILD']);
        $child4_count = count($arNewMenu[count($arNewMenu) - 1]["CHILD"][$child2_count-1]['CHILD'][$child3_count-1]['CHILD']);
        $arNewMenu[count($arNewMenu) - 1]["CHILD"][$child2_count-1]['CHILD'][$child3_count-1]['CHILD'][$child4_count-1]['CHILD'][] = $arItem;
    }
}
$arResult = $arNewMenu;