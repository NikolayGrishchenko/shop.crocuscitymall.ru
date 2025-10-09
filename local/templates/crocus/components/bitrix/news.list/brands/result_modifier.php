<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * @global CMain 
 * @var CBitrixComponent 
 */

if (empty($arResult["ITEMS"])) return;

if (!empty($arParams['PRODUCT_SECTION_CODE']))
{
	foreach ($arResult["ITEMS"] as $itemIndex => $item)
	{
		$arResult["ITEMS"][$itemIndex]['DETAIL_PAGE_URL'] = str_replace('#PRODUCT_SECTION_CODE#', $arParams['PRODUCT_SECTION_CODE'], $arResult["ITEMS"][$itemIndex]['DETAIL_PAGE_URL']);
	}
}

$groupedBrands = [];
foreach ($arResult["ITEMS"] as $item)
{
	$name = trim($item["NAME"]);
	if (empty($name))
	{
		continue;
	}

	$letter = mb_strtoupper(mb_substr($name, 0, 1));

	if (!isset($groupedBrands[$letter]))
	{
		$groupedBrands[$letter] = [];
	}
	$groupedBrands[$letter][] = $item;
}

ksort($groupedBrands, SORT_STRING | SORT_FLAG_CASE);

$arResult['ITEM_GROUPS'] = $groupedBrands;

unset($groupedBrands);
