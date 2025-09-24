<div class="col-3 card-wrapper">
	<?if (!empty($arResult['PREVIEW_PICTURE']) || !empty($arResult['DETAIL_PICTURE'])):?>
		<div class="row">
			<a href="<?=$arResult['DETAIL_PAGE_URL']?>" class="col-12 d-flex justify-content-center align-items-center">
				<div class="card-image">
					<?if (!empty($arResult['PREVIEW_PICTURE'])):?>
						<img src="<?=$arResult['PREVIEW_PICTURE']['SRC']?>" class="img-fluid" alt="<?=$arResult['NAME']?>">
					<?elseif (!empty($arResult['DETAIL_PICTURE'])):?>
						<img src="<?=$arResult['DETAIL_PICTURE']['SRC']?>" class="img-fluid" alt="<?=$arResult['NAME']?>">
					<?endif;?>
				</div>
			</a>
		</div>
	<?endif;?>
	<div class="row justify-content-between align-items-center">
		<div class="col-auto">
			<a href="<?=$arResult['DETAIL_PAGE_URL']?>" class="card-name"><?=$arResult['NAME']?></a>
			<?if (!empty($arResult['DISPLAY_PROPERTIES']['BRAND'])):?>
				<a href="<?=$arResult['DETAIL_PAGE_URL']?>" class="card-brand"><?=$arResult['DISPLAY_PROPERTIES']['BRAND']['LINK_ELEMENT_VALUE'][$arResult['DISPLAY_PROPERTIES']['BRAND']['VALUE']]['NAME']?></a>
			<?endif;?>
			<div class="card-prices">
				<?if (!empty($arResult['PROPERTIES']['OLD_PRICE']['VALUE'])):?>
					<span class="card-price-old"><?=CurrencyFormat($arResult['PROPERTIES']['OLD_PRICE']['VALUE'], 'RUB')?></span>
				<?endif;?>
				<?if (!empty($arResult['PRICE'])):?>
					<span class="card-price"><?=CurrencyFormat($arResult['PRICE'], 'RUB')?></span>
				<?endif;?>
			</div>
		</div>
		<div class="col-auto">
			<div class="card-basket" title="Добавить в корзину">
				<img src="<?=$templateFolder?>/images/basket.svg">
			</div>
		</div>
	</div>
</div>