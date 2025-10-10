<form class="catalog-filter" method="GET" action="">
	<?if (!empty($_REQUEST['sort'])):?>
		<input type="hidden" name="sort" value="<?=$_REQUEST['sort']?>">
	<?endif;?>
	<?if (!empty($_REQUEST['order'])):?>
		<input type="hidden" name="order" value="<?=$_REQUEST['order']?>">
	<?endif;?>
	<div class="catalog-filter-title">Фильтр</div>
	<div class="catalog-filter-items">
		<?foreach ($arResult['FILTER'] as $filter):?>
			<div class="catalog-filter-item" data-filter-item="<?=$filter['CODE']?>">
				<div class="catalog-filter-header">
					<div class="catalog-filter-name"><?=$filter['NAME']?></div>
					<div class="catalog-filter-selected-value" data-catalog-filter-selected-value>Выбор</div>
				</div>
				<?if (!empty($filter['VALUES'])):?>
					<div class="catalog-filter-values">
						<?foreach ($filter['VALUES'] as $value):?>
							<label class="catalog-filter-value" data-catalog-filter-value>
								<input
									type="checkbox"
									name="FILTER[<?=$filter['CODE']?>][]"
									value="<?=$value['ID']?>"
									<?if (!empty($_REQUEST['FILTER']) && array_key_exists($filter['CODE'], $_REQUEST['FILTER']) && in_array($value['ID'], $_REQUEST['FILTER'][$filter['CODE']])):?>checked<?endif;?>
									/>
								<?if (array_key_exists('COLOR', $value)):?>
									<span class="catalog-filter-color" style="background: <?=$value['COLOR']?>;"></span>
								<?endif;?>
								<span data-catalog-filter-value-name><?=$value['NAME']?></span>
							</label>
						<?endforeach;?>
					</div>
				<?endif;?>
			</div>
		<?endforeach;?>
	</div>
	<button class="catalog-filter-button" type="submit">Показать</button>
</form>