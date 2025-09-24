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