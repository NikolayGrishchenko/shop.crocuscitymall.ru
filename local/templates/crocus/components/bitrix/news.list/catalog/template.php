<?if (!empty($arParams['TITLE'])):?>
	<h5 class="catalog-title"><?=$arParams['TITLE']?></h5>
<?endif;?>
<div class="row m-0">
	<?foreach ($arResult['ITEMS'] as $item):?>
		<?$APPLICATION->IncludeComponent(
			'bitrix:catalog.item',
			'card',
			[
				'PARANS' => [],
				'RESULT' => $item,
			]
		);?>
	<?endforeach;?>
</div>