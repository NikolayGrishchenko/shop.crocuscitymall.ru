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
		images
	</div>
	<div class="col-4">
		<div class="row">
			<div class="col-12">
				<span class="compare-at-price nowrap"> 302 400 <span class="ruble">₽</span></span><br>
				<span data-price="211700" class="price nowrap">211 700 <span class="ruble">₽</span></span><hr>
				<div class="sku-selector-wrapper singleline">
					<div class="sku-selector-title">Бренд</div>
					<div class="sku-selector-value">
						<a href="/category/muzhchinam/aksessuary/sumki-i-ryukzaki/brendy/Artioli/">Artioli</a>
					</div>
				</div>
				<div class="sku-selector-wrapper singleline">
					<div class="sku-selector-title">Артикул</div>
					<div class="sku-selector-value">MERCURIO/BIS</div>
				</div>
				<div class="sku-selector-wrapper singleline">
					<div class="sku-selector-title">Страна</div>
					<div class="sku-selector-value">ИТАЛИЯ</div>
				</div>
				<div class="sku-selector-wrapper singleline">
					<div class="sku-selector-title">Состав</div>
					<div class="sku-selector-value">100% кожа теленка</div>
				</div>
				<div itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
					<span class="hint">б/р</span>
					<meta itemprop="name" content="б/р">
					<meta itemprop="price" content="211700">
					<meta itemprop="priceCurrency" content="RUB">
					<link itemprop="availability" href="http://schema.org/InStock">
				</div>
			</div>
		</div>
	</div>
</div>
<pre><?print_r($arResult)?></pre>