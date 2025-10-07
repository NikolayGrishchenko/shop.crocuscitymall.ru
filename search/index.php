<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Context;
use Bitrix\Iblock\Iblock;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

if (!Loader::includeModule('iblock') || !Loader::includeModule('catalog'))
{
	ShowError("Required modules are not installed.");
	return;
}

global $arrFilter;
$arrFilter = [];

$request = Context::getCurrent()->getRequest();

$searchQueryString = trim($request->get('query'));
if ($searchQueryString !== '') {
	$productQuery = Iblock::wakeUp(CATALOG_IBLOCK_ID)->getEntityDataClass()::query();
	$productQuery->setFilter([
		'%SEARCHABLE_CONTENT' => str_replace('%', '\%', $searchQueryString),
		'ACTIVE' => 'Y',
	]);
	$productQuery->setSelect(['ID']);
	$arProductIds = $productQuery->fetchAll();

	$arrFilter['ID'] = array_column($arProductIds, 'ID');

	$APPLICATION->SetTitle($searchQueryString." — CrocusCityMall");
} else {
	$APPLICATION->SetTitle("Поиск — CrocusCityMall");

	$arProductIds = [];
}

?><h1 class="search-heading"><?= $searchQueryString ?></h1><?

if ($searchQueryString !== '' && count($arProductIds) < 1):
	?><p>Не найдено ни одного товара.</p><?
else:
	global $arSort;

	if ($searchQueryString === ''):
		$APPLICATION->IncludeComponent(
			"grishchenko:sort",
			"",
			array(
				'SORT_NAME' => 'arSort',
			)
		);
	endif;

	?><?$APPLICATION->IncludeComponent(
		"bitrix:catalog.section",
		"main",
		array(
			"IBLOCK_TYPE"           => "catalog",
			"IBLOCK_ID"             => "2",
			"SECTION_USER_FIELDS"   => array("UF_*"),
			"ELEMENT_SORT_FIELD"    => $arSort['FIELD'],
			"ELEMENT_SORT_ORDER"    => $arSort['ORDER'],
			"FILTER_NAME"           => "arrFilter",
			"INCLUDE_SUBSECTIONS"   => "Y",
			"SHOW_ALL_WO_SECTION"   => "N",
			"PAGE_ELEMENT_COUNT"    => "20",
			"LINE_ELEMENT_COUNT"    => "4",
			"PROPERTY_CODE"         => array("PRICE", "COLOR", "SIZE"),
			"OFFERS_LIMIT"          => "5",
			"CACHE_TYPE"            => "A",
			"CACHE_TIME"            => "36000000",
			"CACHE_FILTER"          => "N",
			"CACHE_GROUPS"          => "Y",
			"SET_TITLE"             => "Y",
			"ADD_SECTIONS_CHAIN"    => "Y",
			"HIDE_NOT_AVAILABLE"    => "N",
			"PRICE_CODE"            => array("BASE"),
			"USE_PRICE_COUNT"       => "N",
			"SHOW_PRICE_COUNT"      => "1",
			"BASKET_URL"            => "/personal/cart/",
			"SECTION_URL"           => "/catalog/#SECTION_CODE_PATH#/",
			"DETAIL_URL"            => "/catalog/#SECTION_CODE_PATH#/#ELEMENT_CODE#/",
			"VIEW_MODE"             => "TILE",
			
			"USE_SECTION_PATH_FIX"  => "Y",
		)
	);?><?
endif;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
