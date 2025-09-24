<?if (!empty($arResult['SECTIONS'])):?>
	<ul class="catalog-sections">
		<?foreach ($arResult['SECTIONS'] as $section):?>
			<li>
				<a href="<?=$section['SECTION_PAGE_URL']?>" data-category-id="<?=$section['ID']?>"><?=$section['NAME']?></a>
			</li>
		<?endforeach;?>
	</ul>
	<?foreach ($arResult['SECTIONS'] as $section):
		if (!empty($section['SECTIONS'])):?>
			<div class="catalog-section-popup" data-subcategory-id="<?=$section['ID']?>">
				<div class="catalog-section-popup-wrapper">
					<div class="catalog-section-column">
						<a href="<?=$section['SECTION_PAGE_URL']?>" class="catalog-section-popup-item">Все категории</a>
						<?foreach ($section['SECTIONS'] as $item):?>
							<a href="<?=$item['SECTION_PAGE_URL']?>" class="catalog-section-popup-item"><?=$item['NAME']?></a>
						<?endforeach;?>
					</div>
				</div>
			</div>
		<?endif;
	endforeach;?>
<?endif;?>

<h1 class="catalog-section-name"><?=$arResult['SECTION']['NAME']?></h1>