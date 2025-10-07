<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Context;
use Bitrix\Main\Web\Uri;

class GrishchenkoSortComponent extends CBitrixComponent
{
    public function executeComponent()
    {
        if (!Loader::includeModule('iblock')) {
            ShowError('Module "iblock" is not installed.');
            return;
        }

        $sort = [
            'NAME' => [
                'NAME' => 'Название',
                'CODE' => 'NAME',
                'FIELD' => 'NAME',
                'DIRECTION' => 'DSC',
                'ACTIVE' => false,
            ],
            'PRICE' => [
                'NAME' => 'Цена',
                'CODE' => 'PRICE',
                'FIELD' => 'CATALOG_PRICE_1',
                'DIRECTION' => 'DSC',
                'ACTIVE' => false,
            ],
            'POPULARITY' => [
                'NAME' => 'Хиты продаж',
                'CODE' => 'POPULARITY',
                'FIELD' => 'PROPERTY_POPULARITY',
                'DIRECTION' => 'ASC',
                'ACTIVE' => false,
            ],
            'DATE' => [
                'NAME' => 'Дата добавления',
                'CODE' => 'DATE',
                'FIELD' => 'CREATED',
                'DIRECTION' => 'DESC',
                'ACTIVE' => false,
            ],
        ];

        if (array_key_exists('sort', $_REQUEST) && array_key_exists($_REQUEST['sort'], $sort)) {
            $sort[$_REQUEST['sort']]['ACTIVE'] = true;

            if (array_key_exists('order', $_REQUEST) && in_array($_REQUEST['order'], ['ASC', 'DESC'])) {
                $sort[$_REQUEST['sort']]['DIRECTION'] = $_REQUEST['order'] == 'ASC' ? 'ASC' : 'DESC';
            }
        } else {
            $sort['DATE']['ACTIVE'] = true;
        }

        $request = Context::getCurrent()->getRequest();
        $currentUrl = $request->getRequestUri();
        $baseUri = new Uri($currentUrl);
        foreach ($sort as $sIndex => $item)
        {
            $itemUri = clone $baseUri;
            $itemUri->addParams([
                'sort' => $item['CODE'],
                'order' => $item['DIRECTION'] == 'ASC' ? 'DESC' : 'ASC',
            ]);

            $sort[$sIndex]['HREF'] = $itemUri->getUri();
        }

        $this->arResult['SORT'] = $sort;

        $activeSort = array_filter($sort, function ($item) {
            return $item['ACTIVE'];
        });
        if (!empty($activeSort)) {
            $activeSort = reset($activeSort);
        } else {
            $activeSort = $sort['DATE'];
        }

        global ${$this->arParams['SORT_NAME']};
        ${$this->arParams['SORT_NAME']}['FIELD'] = $activeSort['FIELD'];
        ${$this->arParams['SORT_NAME']}['ORDER'] = $activeSort['DIRECTION'];


        $this->includeComponentTemplate();
    }
}
