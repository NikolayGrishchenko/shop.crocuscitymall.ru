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
				while ($ob = $res->getNextElement()) {
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
				$this->arResult['FILTER'][] = [
					'CODE' => 'BRAND',
					'NAME' => 'Бренд',
					'VALUES' => $brands,
				];
			}

			if (!empty($offers)) {
				$sizes = [];
				$colorIds = [];
				foreach ($offers as $item) {
					$sizes[] = $item['PROPERTIES']['SIZE']['VALUE'];
					$colorIds[] = $item['PROPERTIES']['COLOR']['VALUE'];
				}

				$sizes = array_unique(array_filter($sizes));
				if (!empty($sizes)) {
					$sizes = array_map(function($item) {
						return [
							'ID' => $item,
							'NAME' => $item,
						];
					}, $sizes);
					$this->arResult['FILTER'][] = [
						'CODE' => 'SIZE',
						'NAME' => 'Размер',
						'VALUES' => $sizes,
					];
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
						$this->arResult['FILTER'][] = [
							'CODE' => 'COLOR',
							'NAME' => 'Цвет',
							'VALUES' => $colors,
						];
					}
				}
			}
		}

		if (!empty($_REQUEST['FILTER'])) {
			global ${$this->arParams['FILTER_NAME']};

			$productIds = null;

			if (array_key_exists('BRAND', $_REQUEST['FILTER']) && !empty($_REQUEST['FILTER']['BRAND'])) {
				${$this->arParams['FILTER_NAME']}['PROPERTY_BRAND'] = $_REQUEST['FILTER']['BRAND'];
			}

			if (array_key_exists('SIZE', $_REQUEST['FILTER']) && !empty($_REQUEST['FILTER']['SIZE'])) {
				$productSize = [];
				$res = CIBlockElement::getList([], [
					'IBLOCK_CODE' => 'catalog_offers',
					'ACTIVE' => 'Y',
					'PROPERTY_SIZE' => $_REQUEST['FILTER']['SIZE'],
				]);
				while ($ob = $res->getnextElement()) {
					$item = $ob->getFields();
					$item['PROPERTIES'] = $ob->getProperties();

					$productSize[] = $item['PROPERTIES']['CML2_LINK']['VALUE'];
				}
				if (!is_null($productIds)) {
					$productIds = array_intersect($productIds, $productSize);
				} else {
					$productIds = $productSize;
				}
			}

			if (array_key_exists('COLOR', $_REQUEST['FILTER']) && !empty($_REQUEST['FILTER']['COLOR'])) {
				$productColor = [];
				$res = CIBlockElement::getList([], [
					'IBLOCK_CODE' => 'catalog_offers',
					'ACTIVE' => 'Y',
					'PROPERTY_COLOR' => $_REQUEST['FILTER']['COLOR'],
				]);
				while ($ob = $res->getnextElement()) {
					$item = $ob->getFields();
					$item['PROPERTIES'] = $ob->getProperties();

					$productColor[] = $item['PROPERTIES']['CML2_LINK']['VALUE'];
				}

				if (!is_null($productIds)) {
					$productIds = array_intersect($productIds, $productColor);
				} else {
					$productIds = $productColor;
				}
			}

			if (!is_null($productIds)) {
				${$this->arParams['FILTER_NAME']}['ID'] = !empty($productIds) ? $productIds : false;
			}
		}

        $this->includeComponentTemplate();
    }
}
?>