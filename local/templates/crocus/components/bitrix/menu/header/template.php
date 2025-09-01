<?if (!empty($arResult)):?>
	<ul class="maincategory">
		<?foreach ($arResult as $item):?>
			<li>
				<a class="effectline" href="<?=$item['LINK']?>"><?=$item['TEXT']?></a>
			</li>
		<?endforeach;?>
	</ul>
<?endif;?>