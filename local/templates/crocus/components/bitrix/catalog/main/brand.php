<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * @global CMain 
 * @var CBitrixComponent 
 */

use Bitrix\Main\Loader;
use Bitrix\Iblock\Iblock;

if (!Loader::includeModule('iblock'))
{
	ShowError('Module iblock not installed');
	return;
}

// --------------------------------------------------
// Get SEF variables
// --------------------------------------------------
$sectionCode = $arResult['VARIABLES']['SECTION_CODE'] ?? '';
$brandCode   = $arResult['VARIABLES']['BRAND_CODE'] ?? '';

if (!$brandCode)
{
	ShowError('Brand code is missing in URL');
	return;
}

// --------------------------------------------------
// Specific brand page CSS
// --------------------------------------------------
$cssFilename = $component->__template->GetFolder()."/brand.css";
if (
	$arParams["SEF_URL_TEMPLATES"]["brand"]
	&& strpos($APPLICATION->GetCurPage(), '/brands/') !== false
	&& file_exists($_SERVER['DOCUMENT_ROOT'].$cssFilename)
) {
	$APPLICATION->SetAdditionalCSS($cssFilename);
}

// --------------------------------------------------
// Get section info (category) by CODE
// --------------------------------------------------
$section = null;
if ($sectionCode)
{
	$section = CIBlockSection::GetList(
		[],
		['IBLOCK_ID' => $arParams['IBLOCK_ID'], '=CODE' => $sectionCode],
		false,
		['ID', 'NAME', 'SECTION_PAGE_URL']
	)->Fetch();
}

$sectionId   = $section['ID'] ?? 0;
$sectionName = $section['NAME'] ?? '';

// --------------------------------------------------
// Get brand element by CODE
// --------------------------------------------------
$brand = Iblock::wakeUp(BRAND_IBLOCK_ID)->getEntityDataClass()::getList([
	'filter' => ['=CODE' => $brandCode],
	'select' => [
		'ID',
		'NAME',
		'DETAIL_TEXT',
		'PREVIEW_TEXT',
		'DETAIL_PICTURE',
		'CONTACT_PHONE_VALUE' => 'CONTACT_PHONE.VALUE',
		'CONTACT_TELEGRAM_VALUE' => 'CONTACT_TELEGRAM.VALUE',
		'CONTACT_WHATSAPP_VALUE' => 'CONTACT_WHATSAPP.VALUE',
	]
])->fetch();

if (!$brand)
{
	@define("ERROR_404", "Y");
	return;
}

// --------------------------------------------------
// SEO: Set page title, meta, breadcrumbs
// --------------------------------------------------
$brandName = $brand['NAME'];
$seoTitle = $brandName . " – Хиты продаж";
$seoDescription = strip_tags($brand['PREVIEW_TEXT'] ?: $brand['DETAIL_TEXT']);

$APPLICATION->SetTitle($seoTitle);
$APPLICATION->SetPageProperty("title", $seoTitle);
$APPLICATION->SetPageProperty("description", mb_substr($seoDescription, 0, 180));

$curPage = $APPLICATION->GetCurPage(false);
$pathParts = explode('/', trim($curPage, '/'));

array_pop($pathParts);

$parentUrl = '/' . implode('/', $pathParts) . '/';

$APPLICATION->AddChainItem('Бренды', $parentUrl);
$APPLICATION->AddChainItem($brandName);

$APPLICATION->IncludeComponent(
	"bitrix:breadcrumb",
	"",
	Array(
		"START_FROM" => "2", 
		"PATH" => "", 
		"SITE_ID" => "s1" 
	),
	$component
);

// --------------------------------------------------
// Display brand information
// --------------------------------------------------
if (!function_exists('__normalizePhone'))
{
	function __normalizePhone($phone)
	{
		$digits = preg_replace('/\D+/', '', $phone);

		if (strpos($digits, '8') === 0)
		{
			$digits = '7' . substr($digits, 1);
		}
		elseif (strpos($digits, '7') !== 0)
		{
			return '+' . $digits;
		}

		return '+' . $digits;
	}
}
$sPhone = __normalizePhone($brand['CONTACT_PHONE_VALUE']);
?>
<div class="brand-data">
	<div class="brand-description">
		<h1><?= htmlspecialcharsbx($brandName) ?></h1>

		<? if ($brand['DETAIL_PICTURE']): ?>
			<img class="brand-image"
				 src="<?= CFile::GetPath($brand['DETAIL_PICTURE']) ?>"
				 alt="<?= htmlspecialcharsbx($brandName) ?>">
		<? endif; ?>

		<? if ($brand['DETAIL_TEXT']): ?>
			<?= $brand['DETAIL_TEXT'] ?>
		<? elseif ($brand['PREVIEW_TEXT']): ?>
			<?= $brand['PREVIEW_TEXT'] ?>
		<? endif; ?>

		<p class="brand-contacts">
			<? if ($brand['CONTACT_PHONE_VALUE']): ?>
				<br>
				<a class="brand-contact-phone" href="tel:<?= $sPhone ?>">тел.: <?= $brand['CONTACT_PHONE_VALUE'] ?></a>
			<? endif; ?>
			<? if ($brand['CONTACT_WHATSAPP_VALUE']): ?>
				<br>
				<a href="<?= $brand['CONTACT_WHATSAPP_VALUE'] ?>" target="_blank">
					<img src="<?=SITE_TEMPLATE_PATH?>/assets/img/wa.png" style="width: 25px; height: 25px;" width="100" height="100">
				</a>
			<? endif; ?>
			<? if ($brand['CONTACT_TELEGRAM_VALUE']): ?>
				<br>
				<a href="<?= $brand['CONTACT_TELEGRAM_VALUE'] ?>" target="_blank">
					<img src="<?=SITE_TEMPLATE_PATH?>/assets/img/tg.png" style="width: 20px; height: 20px;" width="100" height="100">
				</a>
			<? endif; ?>
		</p>
	</div>
</div>

<hr>

<?
// --------------------------------------------------
// Sort catalog products
// --------------------------------------------------
$APPLICATION->IncludeComponent(
	"grishchenko:sort",
	"",
	array(
		'SORT_NAME' => 'arSort',
	)
);

// --------------------------------------------------
// Filter catalog products by this brand
// --------------------------------------------------
global $arrBrandFilter;
$arrBrandFilter = ['=PROPERTY_BRAND' => $brand['ID']];

// --------------------------------------------------
// Include catalog.section for filtered products
// --------------------------------------------------
$arParams = [
	"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
	"IBLOCK_ID" => $arParams["IBLOCK_ID"],
	"ELEMENT_SORT_FIELD" => $arSort['FIELD'],
	"ELEMENT_SORT_ORDER" => $arSort['ORDER'],
	"ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD"],
	"ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER"],
	"PROPERTY_CODE" => (isset($arParams["LIST_PROPERTY_CODE"]) ? $arParams["LIST_PROPERTY_CODE"] : []),
	"PROPERTY_CODE_MOBILE" => $arParams["LIST_PROPERTY_CODE_MOBILE"],
	"META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
	"META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
	"BROWSER_TITLE" => $arParams["LIST_BROWSER_TITLE"],
	"SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
	"INCLUDE_SUBSECTIONS" => $arParams["INCLUDE_SUBSECTIONS"],
	"BASKET_URL" => $arParams["BASKET_URL"],
	"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
	"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
	"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
	"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
	"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
	"FILTER_NAME" => "arrBrandFilter",
	"CACHE_TYPE" => $arParams["CACHE_TYPE"],
	"CACHE_TIME" => $arParams["CACHE_TIME"],
	"CACHE_FILTER" => $arParams["CACHE_FILTER"],
	"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
	"SET_TITLE" => $arParams["SET_TITLE"],
	"MESSAGE_404" => $arParams["~MESSAGE_404"],
	"SET_STATUS_404" => $arParams["SET_STATUS_404"],
	"SHOW_404" => $arParams["SHOW_404"],
	"FILE_404" => $arParams["FILE_404"],
	"DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
	"PAGE_ELEMENT_COUNT" => $arParams["PAGE_ELEMENT_COUNT"],
	"LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
	"PRICE_CODE" => $arParams["~PRICE_CODE"],
	"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
	"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],

	"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
	"USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
	"ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
	"PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
	"PRODUCT_PROPERTIES" => (isset($arParams["PRODUCT_PROPERTIES"]) ? $arParams["PRODUCT_PROPERTIES"] : []),

	"DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
	"DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
	"PAGER_TITLE" => $arParams["PAGER_TITLE"],
	"PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
	"PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
	"PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
	"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
	"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
	"PAGER_BASE_LINK_ENABLE" => $arParams["PAGER_BASE_LINK_ENABLE"],
	"PAGER_BASE_LINK" => $arParams["PAGER_BASE_LINK"],
	"PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
	"LAZY_LOAD" => $arParams["LAZY_LOAD"],
	"MESS_BTN_LAZY_LOAD" => $arParams["~MESS_BTN_LAZY_LOAD"],
	"LOAD_ON_SCROLL" => $arParams["LOAD_ON_SCROLL"],

	"OFFERS_CART_PROPERTIES" => (isset($arParams["OFFERS_CART_PROPERTIES"]) ? $arParams["OFFERS_CART_PROPERTIES"] : []),
	"OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
	"OFFERS_PROPERTY_CODE" => (isset($arParams["LIST_OFFERS_PROPERTY_CODE"]) ? $arParams["LIST_OFFERS_PROPERTY_CODE"] : []),
	"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
	"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
	"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
	"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
		"OFFERS_LIMIT" => (isset($arParams["LIST_OFFERS_LIMIT"]) ? $arParams["LIST_OFFERS_LIMIT"] : 0),

	"SECTION_ID" => $sectionId,
	"SECTION_CODE" => $sectionCode,
	"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
	"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
	"USE_MAIN_ELEMENT_SECTION" => $arParams["USE_MAIN_ELEMENT_SECTION"],
	'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
	'CURRENCY_ID' => $arParams['CURRENCY_ID'],
	'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
	'HIDE_NOT_AVAILABLE_OFFERS' => $arParams["HIDE_NOT_AVAILABLE_OFFERS"],

	'LABEL_PROP' => $arParams['LABEL_PROP'],
	'LABEL_PROP_MOBILE' => $arParams['LABEL_PROP_MOBILE'],
	'LABEL_PROP_POSITION' => $arParams['LABEL_PROP_POSITION'],
	'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'] ?? '',
	'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
	'PRODUCT_BLOCKS_ORDER' => $arParams['LIST_PRODUCT_BLOCKS_ORDER'],
	'PRODUCT_ROW_VARIANTS' => $arParams['LIST_PRODUCT_ROW_VARIANTS'],
	'ENLARGE_PRODUCT' => $arParams['LIST_ENLARGE_PRODUCT'],
	'ENLARGE_PROP' => isset($arParams['LIST_ENLARGE_PROP']) ? $arParams['LIST_ENLARGE_PROP'] : '',
	'SHOW_SLIDER' => $arParams['LIST_SHOW_SLIDER'],
	'SLIDER_INTERVAL' => isset($arParams['LIST_SLIDER_INTERVAL']) ? $arParams['LIST_SLIDER_INTERVAL'] : '',
	'SLIDER_PROGRESS' => isset($arParams['LIST_SLIDER_PROGRESS']) ? $arParams['LIST_SLIDER_PROGRESS'] : '',

	'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
	'OFFER_TREE_PROPS' => (isset($arParams['OFFER_TREE_PROPS']) ? $arParams['OFFER_TREE_PROPS'] : []),
	'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
	'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
	'DISCOUNT_PERCENT_POSITION' => $arParams['DISCOUNT_PERCENT_POSITION'],
	'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
	'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
	'MESS_SHOW_MAX_QUANTITY' => (isset($arParams['~MESS_SHOW_MAX_QUANTITY']) ? $arParams['~MESS_SHOW_MAX_QUANTITY'] : ''),
	'RELATIVE_QUANTITY_FACTOR' => (isset($arParams['RELATIVE_QUANTITY_FACTOR']) ? $arParams['RELATIVE_QUANTITY_FACTOR'] : ''),
	'MESS_RELATIVE_QUANTITY_MANY' => (isset($arParams['~MESS_RELATIVE_QUANTITY_MANY']) ? $arParams['~MESS_RELATIVE_QUANTITY_MANY'] : ''),
	'MESS_RELATIVE_QUANTITY_FEW' => (isset($arParams['~MESS_RELATIVE_QUANTITY_FEW']) ? $arParams['~MESS_RELATIVE_QUANTITY_FEW'] : ''),
	'MESS_BTN_BUY' => (isset($arParams['~MESS_BTN_BUY']) ? $arParams['~MESS_BTN_BUY'] : ''),
	'MESS_BTN_ADD_TO_BASKET' => (isset($arParams['~MESS_BTN_ADD_TO_BASKET']) ? $arParams['~MESS_BTN_ADD_TO_BASKET'] : ''),
	'MESS_BTN_SUBSCRIBE' => (isset($arParams['~MESS_BTN_SUBSCRIBE']) ? $arParams['~MESS_BTN_SUBSCRIBE'] : ''),
	'MESS_BTN_DETAIL' => (isset($arParams['~MESS_BTN_DETAIL']) ? $arParams['~MESS_BTN_DETAIL'] : ''),
		'MESS_NOT_AVAILABLE' => $arParams['~MESS_NOT_AVAILABLE'] ?? '',
		'MESS_NOT_AVAILABLE_SERVICE' => $arParams['~MESS_NOT_AVAILABLE_SERVICE'] ?? '',
	'MESS_BTN_COMPARE' => (isset($arParams['~MESS_BTN_COMPARE']) ? $arParams['~MESS_BTN_COMPARE'] : ''),

	'USE_ENHANCED_ECOMMERCE' => (isset($arParams['USE_ENHANCED_ECOMMERCE']) ? $arParams['USE_ENHANCED_ECOMMERCE'] : ''),
	'DATA_LAYER_NAME' => (isset($arParams['DATA_LAYER_NAME']) ? $arParams['DATA_LAYER_NAME'] : ''),
	'BRAND_PROPERTY' => (isset($arParams['BRAND_PROPERTY']) ? $arParams['BRAND_PROPERTY'] : ''),

	'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
	"ADD_SECTIONS_CHAIN" => "N",
	'ADD_TO_BASKET_ACTION' => $basketAction,
	'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
	'COMPARE_PATH' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['compare'],
	'COMPARE_NAME' => $arParams['COMPARE_NAME'],
	'USE_COMPARE_LIST' => 'Y',
	'BACKGROUND_IMAGE' => (isset($arParams['SECTION_BACKGROUND_IMAGE']) ? $arParams['SECTION_BACKGROUND_IMAGE'] : ''),
	'COMPATIBLE_MODE' => (isset($arParams['COMPATIBLE_MODE']) ? $arParams['COMPATIBLE_MODE'] : ''),
	'DISABLE_INIT_JS_IN_COMPONENT' => (isset($arParams['DISABLE_INIT_JS_IN_COMPONENT']) ? $arParams['DISABLE_INIT_JS_IN_COMPONENT'] : ''),
];

if ($sectionCode === 'all')
{
	$arParams['SECTION_ID'] = 0;
	$arParams['SECTION_CODE'] = '';
}

$APPLICATION->IncludeComponent(
	"bitrix:catalog.section",
	"main",
	$arParams,
	$component,
	['HIDE_ICONS' => 'Y']
);
