<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

/**
 * @global CMain 
 * @var CBitrixComponent 
 */

use Bitrix\Iblock\Elements\ElementBrandsTable;

$sectionCode = $arVariables['SECTION_CODE'];

// Load all brands that have products in this section (simplified example)
$brands = ElementBrandsTable::getList([
    'filter' => ['=IBLOCK_ID' => 4],
    'select' => ['NAME', 'CODE']
]);

echo "<h1>Brands in this category</h1>";
foreach ($brands as $brand) {
    echo '<a href="/catalog/'.$sectionCode.'/brands/'.$brand['CODE'].'/">'.$brand['NAME'].'</a><br>';
}
