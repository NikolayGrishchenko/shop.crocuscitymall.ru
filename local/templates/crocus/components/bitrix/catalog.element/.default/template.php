<?
$price = null;
foreach ($arResult['ITEM_PRICES'] as $item)
{
	if (!empty($item['PRICE']))
	{
		$price = $item['PRICE'];
		break;
	}
}
if (empty($price)) {
	foreach ($arResult['OFFERS'] as $offer)
	{
		foreach ($offer['ITEM_PRICES'] as $item)
		{
			if (!empty($item['PRICE']))
			{
				$price = $item['PRICE'];
				break 2;
			}
		}
	}
}
?>
<div class="row catalog-element">
	<div class="col-12">
		<?$APPLICATION->IncludeComponent(
			"bitrix:breadcrumb",
			"",
			Array(
				"START_FROM" => "2", 
				"PATH" => "", 
				"SITE_ID" => "s1" 
			),
			$component
		);?>
	</div>
	<div class="col-12">
		<h1><?=$arResult['NAME']?></h1>
	</div>
	<div class="col-8">
		<?
		$images = [];
		if (!empty($arResult['PROPERTIES']['IMAGE']['VALUE'])) {
			$images = $arResult['PROPERTIES']['IMAGE']['VALUE'];
		} elseif (!empty($arResult['DETAIL_PICTURE'])) {
			$images[] = $arResult['DETAIL_PICTURE']['ID'];
		}
		
		if (!empty($images)):?>
			<div class="image-wrapper">
				<div class="image-preview-wrapper">
					<?foreach ($images as $key => $image):?>
						<div class="image-preview<?if ($key == 0):?> active<?endif;?>" data-image-preview="<?=$image?>">
							<img src="<?=CFile::getPath($image)?>" class="img-fluid" alt="<?=$arResult['NAME']?>" />
						</div>
					<?endforeach;?>
				</div>
				<div class="image-show-wrapper">
					<?foreach ($images as $key => $image):?>
						<div class="image-show<?if ($key == 0):?> active<?endif;?>" data-image-show="<?=$image?>">
							<img src="<?=CFile::getPath($image)?>" class="img-fluid" alt="<?=$arResult['NAME']?>" />
						</div>
					<?endforeach;?>
				</div>
				<div class="image-full-modal" data-image-modal>
					<div class="image-close" data-image-modal-close>&times;</div>
					<div class="image-full-wrapper">
						<?foreach ($images as $key => $image):?>
							<div class="image-full" data-image-full="<?=$image?>">
								<img src="<?=CFile::getPath($image)?>" class="img-fluid" alt="<?=$arResult['NAME']?>" />
							</div>
						<?endforeach;?>
					</div>
				</div>
			</div>
		<?endif;?>
	</div>
	<div class="col-4">
		<div class="row">
			<div class="col-12">
				<?if (!empty($arResult['PROPERTIES']['OLD_PRICE']['VALUE'])):?>
					<span class="compare-at-price nowrap"> <?=CurrencyFormat($arResult['PROPERTIES']['OLD_PRICE']['VALUE'], 'RUB')?></span><br>
				<?endif;?>
				<?if (!empty($price)):?>
					<span data-selected-price="<?=$price?>" class="price nowrap"><?=CurrencyFormat($price, 'RUB')?></span><hr>
				<?endif;?>
				<?if (!empty($arResult['BRAND'])):?>
					<div class="sku-selector-wrapper singleline">
						<div class="sku-selector-title">Бренд</div>
						<div class="sku-selector-value">
							<? if (!empty($arResult['BRAND']['DETAIL_PAGE_URL'])): ?>
								<a href="<?=$arResult['BRAND']['DETAIL_PAGE_URL']?>"><?=$arResult['BRAND']['NAME']?></a>
							<? else: ?>
								<?=$arResult['BRAND']['NAME']?>
							<? endif; ?>
						</div>
					</div>
				<?endif;?>
				<?if (!empty($arResult['PROPERTIES']['VENDOR_CODE']['VALUE'])):?>
					<div class="sku-selector-wrapper singleline">
						<div class="sku-selector-title">Артикул</div>
						<div class="sku-selector-value"><?=$arResult['PROPERTIES']['VENDOR_CODE']['VALUE']?></div>
					</div>
				<?endif;?>
				<?if (!empty($arResult['PROPERTIES']['COUNTRY']['VALUE'])):?>
					<div class="sku-selector-wrapper singleline">
						<div class="sku-selector-title">Страна</div>
						<div class="sku-selector-value"><?=$arResult['PROPERTIES']['COUNTRY']['VALUE']?></div>
					</div>
				<?endif;?>
				<?if (!empty($arResult['PROPERTIES']['COMPOSITION']['VALUE'])):?>
					<div class="sku-selector-wrapper singleline">
						<div class="sku-selector-title">Состав</div>
						<div class="sku-selector-value"><?=$arResult['PROPERTIES']['COMPOSITION']['VALUE']?></div>
					</div>
				<?endif;?>
				<?if (!empty($arResult['OFFERS'])):?>
					<div class="sku-selector-wrapper">
						<div class="sku-selector-title">Размер</div>
						<input type="hidden" name="offer_id" value="<?=$arResult['OFFERS'][0]['ID']?>">
						<div class="sku-selector-items">
							<?foreach ($arResult['OFFERS'] as $key => $offer):
								$isActive = $key == 0;
								$price = $offer['ITEM_PRICES'][0]['PRICE'];
								?>
								<div class="sku-selector<?if ($isActive):?> selected<?endif;?>" data-offer="<?=$offer['ID']?>" data-price="<?=CurrencyFormat($price, 'RUB')?>">
									<div class="sku-selector-text"><?=$offer['PROPERTIES']['SIZE']['VALUE']?></div>
									<meta itemprop="price" content="<?=$price?>">
									<meta itemprop="priceCurrency" content="RUB">
									<link itemprop="availability" href="http://schema.org/InStock">
								</div>
							<?endforeach;?>
						</div>
					</div>
				<?endif;?>
				<div class="cart">
					<div class="cart-quantity">
						<div class="cart-value-button" data-decrease>-</div>
						<input class="cart-quantity-value" type="text" name="quantity" value="1">
						<div class="cart-value-button" data-increase>+</div>
					</div>
            		<button type="button" class="cart-button" data-basket-button>В корзину</button>
            	</div>
				</div>
			</div>
		</div>
	</div>
	<? if (!empty($arResult['DETAIL_TEXT'])): ?>
		<div class="col-12">
			<h2>Описание</h2>
			<hr/>
			<div class="description"><?=$arResult['~DETAIL_TEXT']?></div>
		</div>
	<? endif; ?>
</div>