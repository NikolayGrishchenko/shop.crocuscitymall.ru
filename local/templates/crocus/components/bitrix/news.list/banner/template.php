<?if (!empty($arResult['ITEMS'])):?>
	<div id="carouselBanner" class="carousel slide" data-ride="carousel">
		<ol class="carousel-indicators">
			<?foreach ($arResult['ITEMS'] as $key => $item):?>
				<li data-target="#carouselBanner" data-slide-to="<?=$key?>" data-bs-target <?if ($key == 0):?> class="active"<?endif;?>></li>
			<?endforeach;?>
		</ol>
		<div class="carousel-inner">
			<?foreach ($arResult['ITEMS'] as $key => $item):?>
				<div class="carousel-item<?if ($key == 0):?> active<?endif;?>">
					<a href="<?=$item['PROPERTIES']['URL']['VALUE']?>">
						<img class="d-block w-100" src="<?=$item['DETAIL_PICTURE']['SRC']?>" alt="<?=$item['NAME']?>" />
					</a>
				</div>
			<?endforeach;?>
		</div>
		<a class="carousel-control-prev" href="#carouselBanner" role="button" data-slide="prev">
			<span class="carousel-control-prev-icon" aria-hidden="true"></span>
			<span class="sr-only"></span>
		</a>
		<a class="carousel-control-next" href="#carouselBanner" role="button" data-slide="next">
			<span class="carousel-control-next-icon" aria-hidden="true"></span>
			<span class="sr-only"></span>
		</a>
	</div>
<?endif;?>
