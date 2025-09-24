<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;

class GrishchenkoFilterComponent extends CBitrixComponent
{
    public function executeComponent()
    {
        if (!Loader::includeModule('iblock')) {
            ShowError('Module "iblock" is not installed.');
            return;
        }

        $this->arResult['FILTER'] = [];
		if (!empty($this->arParams['SECTION_ID'])) {
			$products = [];
			$res = CIBlockElement::getList([], [
				'IBLOCK_ID' => $this->arParams['IBLOCK_ID'],
				'ACTIVE' => 'Y',
				'SECTION_ID' => $this->arParams['SECTION_ID'],
				'INCLUDE_SUBSECTIONS' => 'Y',
			]);
			while ($ob = $res->getnextElement()) {
				$item = $ob->getFields();
				$item['PROPERTIES'] = $ob->getProperties();

				$products[] = $item;
			}

			$offers = [];
			if (!empty($products)) {
				$res = CIBlockElement::getList([], [
					'IBLOCK_CODE' => 'catalog_offers',
					'ACTIVE' => 'Y',
					'PROPERTY_CML2_LINK' => array_column($products, 'ID'),
				]);
				while ($ob = $res->getnextElement()) {
					$item = $ob->getFields();
					$item['PROPERTIES'] = $ob->getProperties();

					$offers[] = $item;
				}
			}

			$brandIds = [];
			foreach ($products as $item) {
				$brandIds[] = $item['PROPERTIES']['BRAND']['VALUE'];
			}
			$brandIds = array_unique(array_filter($brandIds));

			$brands = [];
			if (!empty($brandIds)) {
				$res = CIBlockElement::getList(
					[
						'SORT' => 'ASC',
					],
					[
						'ID' => $brandIds,
						'ACTIVE' => 'Y',
					]
				);
				while ($item = $res->fetch()) {
					$brands[] = [
						'ID' => $item['ID'],
						'NAME' => $item['NAME'],
					];
				}
			}

			if (!empty($brands)) {
				$this->arResult['FILTER']['BRAND'] = $brands;
			}

			if (!empty($offers)) {
				$sizes = [];
				$colorIds = [];
				foreach ($offers as $item) {
					$sizes[] = $item['PROPERTIES']['SIZE']['VALUE'];
					$colorIds[] = $item['PROPERTIES']['COLOR']['VALUE'];
				}

				$sizes = array_filter($sizes);
				if (!empty($sizes)) {
					$sizes = array_map(function($item) {
						return [
							'ID' => $item,
							'NAME' => $item,
						];
					}, $sizes);
					$this->arResult['FILTER']['SIZE'] = $sizes;
				}

				if (!empty($colorIds)) {
					$colors = [];
					$res = CIBlockElement::getList([], [
						'IBLOCK_CODE' => 'colors',
						'ACTIVE' => 'Y',
						'ID' => $colorIds,
					]);
					while ($ob = $res->getnextElement()) {
						$item = $ob->getFields();
						$item['PROPERTIES'] = $ob->getProperties();

						$colors[] = [
							'ID' => $item['ID'],
							'NAME' => $item['NAME'],
							'COLOR' => $item['PROPERTIES']['CODE']['VALUE'],
						];
					}

					if (!empty($colors)) {
						$this->arResult['FILTER']['COLOR'] = $colors;
					}
				}
			}
		}

        $this->includeComponentTemplate();
    }
}
?>