<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Context;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

if (!Loader::includeModule('iblock') || !Loader::includeModule('catalog'))
{
    ShowError("Required modules are not installed.");
    return;
}

global $arrFilter;
$arrFilter = ["ACTIVE" => "Y"];

$request = Context::getCurrent()->getRequest();

$query = trim($request->get('query'));
if ($query !== '') {
    $safeQuery = str_replace('%', '\%', $query);

    $arrFilter['%NAME'] = $safeQuery;

	$APPLICATION->SetTitle("— CrocusCityMall");
} else {
	$APPLICATION->SetTitle("Поиск — CrocusCityMall");
}

?><?$APPLICATION->IncludeComponent(
    "bitrix:catalog.section",
    "main",
    array(
		"IBLOCK_TYPE"           => "catalog",
		"IBLOCK_ID"             => "2",
		"SECTION_USER_FIELDS"   => array("UF_*"),
		"ELEMENT_SORT_FIELD"    => "sort",
		"ELEMENT_SORT_ORDER"    => "asc",
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
		"SECTION_URL"           => "/catalog/#SECTION_CODE#/",
		"DETAIL_URL"            => "/catalog/#SECTION_CODE#/#ELEMENT_CODE#/",
		"VIEW_MODE"             => "TILE",
	)
);?><?

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
