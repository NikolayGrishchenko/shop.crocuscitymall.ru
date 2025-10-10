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
		"bitrix:news.list",
		"catalog",
		Array(
			"DISPLAY_DATE" => "Y",
			"DISPLAY_NAME" => "Y",
			"DISPLAY_PICTURE" => "Y",
			"DISPLAY_PREVIEW_TEXT" => "Y",
			"AJAX_MODE" => "Y",
			"IBLOCK_TYPE" => "catalog",
			"IBLOCK_ID" => "2",
			"NEWS_COUNT" => "48",
			"SORT_BY1" => "DATE_CREATE",
			"SORT_ORDER1" => "DESC",
			"SORT_BY2" => "SORT",
			"SORT_ORDER2" => "ASC",
			"FILTER_NAME" => "arrFilter",
			"FIELD_CODE" => Array("DETAIL_PICTURE"),
			"PROPERTY_CODE" => Array("BRAND"),
			"CHECK_DATES" => "Y",
			"DETAIL_URL" => "",
			"PREVIEW_TRUNCATE_LEN" => "",
			"ACTIVE_DATE_FORMAT" => "d.m.Y",
			"SET_TITLE" => "N",
			"SET_BROWSER_TITLE" => "N",
			"SET_META_KEYWORDS" => "N",
			"SET_META_DESCRIPTION" => "N",
			"SET_LAST_MODIFIED" => "N",
			"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
			"ADD_SECTIONS_CHAIN" => "N",
			"HIDE_LINK_WHEN_NO_DETAIL" => "Y",
			"PARENT_SECTION" => "",
			"PARENT_SECTION_CODE" => "",
			"INCLUDE_SUBSECTIONS" => "Y",
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "3600",
			"CACHE_FILTER" => "Y",
			"CACHE_GROUPS" => "Y",
			"DISPLAY_TOP_PAGER" => "Y",
			"DISPLAY_BOTTOM_PAGER" => "Y",
			"PAGER_TITLE" => "Новости",
			"PAGER_SHOW_ALWAYS" => "Y",
			"PAGER_TEMPLATE" => "",
			"PAGER_DESC_NUMBERING" => "Y",
			"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
			"PAGER_SHOW_ALL" => "Y",
			"PAGER_BASE_LINK_ENABLE" => "Y",
			"SET_STATUS_404" => "Y",
			"SHOW_404" => "Y",
			"MESSAGE_404" => "",
			"PAGER_BASE_LINK" => "",
			"PAGER_PARAMS_NAME" => "arrPager",
			"AJAX_OPTION_JUMP" => "N",
			"AJAX_OPTION_STYLE" => "Y",
			"AJAX_OPTION_HISTORY" => "N",
			"AJAX_OPTION_ADDITIONAL" => "",
			'TITLE' => "Поиск \"$searchQueryString\"",
		)
	);?><?
endif;
?><style>
	.catalog-title {
		line-height: 1.2em;
		font-weight: 500;
		font-size: 2.6em;
		text-transform: none;
	}
</style><?

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
