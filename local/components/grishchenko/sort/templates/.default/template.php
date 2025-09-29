<ul class="sorting">
    <li>Сортировать:</li>
    <?foreach ($arResult['SORT'] as $item):?>
        <li<?if ($item['ACTIVE']):?> class="selected"<?endif;?>>
            <a href="?sort=<?=$item['CODE']?>&order=<?=$item['DIRECTION'] == 'ASC' ? 'DESC' : 'ASC'?>"><?=$item['NAME']?><?if ($item['ACTIVE']):?> <i class="sort-<?=strtolower($item['DIRECTION'] == 'ASC' ? 'DESC' : 'ASC')?>"></i><?endif;?></a>
        </li>
    <?endforeach;?>
</ul>