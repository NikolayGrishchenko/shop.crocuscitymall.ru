<?if (!empty($arResult)):?>
	<div class="departments">
		<ul class="menu-v">
			<?foreach ($arResult as $item):
				$hasChild = array_key_exists('CHILD', $item) && !empty($item['CHILD']);
				?>
				<li<?if ($hasChild):?> class="collapsible"<?endif;?>>
					<a href="<?=$item['LINK']?>" title="<?=$item['TEXT']?>"><?=$item['TEXT']?></a>
					<?if ($hasChild):?>
						<ul class="menu-v">
							<?foreach ($item['CHILD'] as $child):?>
								<li>
									<a href="<?=$child['LINK']?>" title="<?=$child['TEXT']?>"><?=$child['TEXT']?></a>
								</li>
							<?endforeach;?>
						</ul>
					<?endif;?>
				</li>
			<?endforeach;?>
		</ul>
	</div>
<?endif;?>