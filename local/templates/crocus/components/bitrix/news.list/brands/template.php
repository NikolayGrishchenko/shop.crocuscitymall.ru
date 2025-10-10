<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

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

$this->setFrameMode(true);
?>
<div class="row catalog-brand">
	<div class="col-12">
		<h1 class="catalog-section-name"><?=$arResult['NAME']?></h1>
	</div>
	<div class="col-12 brand-wrapper">
		<div class="brand-words">
			<? foreach ($arResult['ITEM_GROUPS'] as $letter => $brands): ?>
				<div class="brand-word" onclick="document.getElementById('brands_beginning_with_<?=htmlspecialchars($letter)?>').scrollIntoView({behavior: 'smooth'});">
					<span><?=htmlspecialchars($letter)?></span>
				</div>
			<? endforeach; ?>
		</div>
		<div class="brand-brands">
			<? foreach ($arResult['ITEM_GROUPS'] as $letter => $brands): ?>
				<div id="brands_beginning_with_<?=htmlspecialchars($letter)?>" class="brand-group">
					<div class="brand-group-word"><?=htmlspecialchars($letter)?></div>
					<div class="brand-group-items">
						<? foreach ($brands as $brand): ?>
							<?
							$hasKids = $brand["PROPERTIES"]["HAS_PRODUCTS_FOR_KIDS"]["VALUE_XML_ID"] === "Y" 
								 || $brand["PROPERTIES"]["HAS_PRODUCTS_FOR_KIDS"]["VALUE"] === "Y";
							?>
							<? if (!empty($brand["CODE"])): ?>
								<a href="<?=htmlspecialchars($brand["DETAIL_PAGE_URL"])?>" class="brand-group-item">
									<span><?=htmlspecialchars($brand["NAME"])?></span>
									<? if ($hasKids): ?>
										<img src="<?=SITE_TEMPLATE_PATH?>/assets/img/for-kids-icon.jpg" alt="Kids" class="brand-kids">
									<? endif; ?>
								</a>
							<? else: ?>
								<span class="brand-group-item" style="opacity: 0.5;">
									<span><?=htmlspecialchars($brand["NAME"])?></span>
									<? if ($hasKids): ?>
										<img src="<?=SITE_TEMPLATE_PATH?>/assets/img/for-kids-icon.jpg" alt="Kids" class="brand-kids">
									<? endif; ?>
								</span>
							<? endif; ?>
								
						<? endforeach; ?>
					</div>
				</div>
			<? endforeach; ?>
		</div>
	</div>
</div>