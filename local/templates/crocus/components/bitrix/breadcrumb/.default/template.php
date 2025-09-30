<?
ob_start();
?>
<nav class="breadcrumbs" itemprop="breadcrumb">
	<a href="/">CROCUSCITYMALL</a> <span class="rarr">/</span>
	<?foreach ($arResult as $item):?>
		<?if (!empty($item['LINK'])):?>
			<a href="<?=$item['LINK']?>"><?=$item['TITLE']?></a>
			<span class="rarr">/</span>
		<?endif;?>
	<?endforeach;?>
</nav>
<?
return ob_get_clean();