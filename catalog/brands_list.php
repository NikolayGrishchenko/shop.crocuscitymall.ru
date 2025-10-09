<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

global $APPLICATION;

$APPLICATION->SetTitle("Бренды");

use Bitrix\Iblock\ElementTable;
use Bitrix\Main\Loader;

Loader::includeModule('iblock');

global $brandFilter;
$brandFilter = [];

if ($sectionCode && $sectionCode !== 'all')
{
	$brandIds = [];
	$sectionRes = CIBlockSection::GetList([], ['IBLOCK_ID' => CATALOG_IBLOCK_ID, 'CODE' => $sectionCode], false, ['ID']);
	if ($section = $sectionRes->Fetch())
	{
		$sectionId = $section['ID'];
		$productRes = CIBlockElement::GetList([], [
			'IBLOCK_ID' => CATALOG_IBLOCK_ID,
			'SECTION_ID' => $sectionId,
			'ACTIVE' => 'Y'
		], ['PROPERTY_BRAND']);

		while ($product = $productRes->Fetch())
		{
			$brandIds[] = $product['PROPERTY_BRAND_VALUE'];
		}

		$brandFilter = ['ID' => array_unique($brandIds)];
	}
}
?><?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"brands",
	Array(
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"AJAX_MODE" => "Y",
		"IBLOCK_TYPE" => "references",
		"IBLOCK_ID" => BRAND_IBLOCK_ID,
		"NEWS_COUNT" => "200",
		"SORT_BY1" => "SORT",
		"SORT_ORDER1" => "ASC",
		"SORT_BY2" => "SORT",
		"SORT_ORDER2" => "ASC",
		"FILTER_NAME" => "",
		"FIELD_CODE" => Array("DETAIL_PICTURE"),
		"PROPERTY_CODE" => Array("HAS_PRODUCTS_FOR_KIDS"),
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
		"PAGER_PARAMS_NAME" => "brandFilter",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"PRODUCT_SECTION_CODE" => $sectionCode,
	)
);?>