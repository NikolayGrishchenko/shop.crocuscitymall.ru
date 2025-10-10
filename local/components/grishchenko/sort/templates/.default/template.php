<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

global $APPLICATION;
?>
<ul class="sorting">
    <li>Сортировать:</li>
    <?foreach ($arResult['SORT'] as $item):?>
        <li<?if ($item['ACTIVE']):?> class="selected"<?endif;?>>
            <a href="<?=$item['HREF']?>"><?=$item['NAME']?><?if ($item['ACTIVE']):?> <i class="sort-<?=strtolower($item['DIRECTION'] == 'ASC' ? 'DESC' : 'ASC')?>"></i><?endif;?></a>
        </li>
    <?endforeach;?>
</ul>