<?
$mapSection = [];
foreach ($arResult['SECTIONS'] as $section) {
	$mapSection[$section['IBLOCK_SECTION_ID']][] = $section;
}

$mainSections = $mapSection[$arResult['SECTION']['ID']];

foreach ($mainSections as &$section) {
	$section['SECTIONS'] = $mapSection[$section['ID']];
}
unset($section);

$arResult['SECTIONS'] = $mainSections;
