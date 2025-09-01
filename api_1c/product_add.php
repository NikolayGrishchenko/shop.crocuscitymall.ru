<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::includeModule('iblock');
CModule::includeModule('catalog');

if (isset($_GET['access_token']) && ($_GET['access_token'] == "e4c1b6801a18d6cf770e09d64d6db275'" || $_GET['access_token'] == "e4c1b6801a18dd6cf770e09d64d6db275'")) {
	$json = file_get_contents('php://input');
	$data = json_decode($json, true);

	$mapIblock = [];
	$res = CIBlock::getList([], []);
	while ($item = $res->fetch()) {
		$mapIblock[$item['CODE']] = $item['ID'];
	}

	if (array_key_exists('catalog', $mapIblock)) {
		$mapBrand = [];
		$res = CIBlockElement::getList([], [
			'IBLOCK_CODE' => 'brand',
		]);
		while ($item = $res->fetch()) {
			$mapBrand[$item['NAME']] = $item['ID'];
		}

		$mapSex = [];
		$res = CIBlockPropertyEnum::GetList([], [
			'IBLOCK_ID' => $mapIblock['catalog'],
			'CODE' => 'SEX',
		]);
		while ($item = $res->fetch()) {
			$mapSex[$item['XML_ID']] = $item['ID'];
		}

		foreach ($data as $product) {
			$fields = [
				'IBLOCK_ID' => $mapIblock['catalog'],
			];
			$properties = [];

			if (array_key_exists('name', $product)) {
				$fields['NAME'] = $product['name'];
			}

			if (array_key_exists('features', $product)) {
				if (array_key_exists('brand', $product['features'])) {
					if (array_key_exists($product['features']['brand'], $mapBrand)) {
						$properties['BRAND'] = $mapBrand[$product['features']['brand']];
					}
				}
				if (array_key_exists('sex', $product['features'])) {
					if (array_key_exists($product['features']['sex'], $mapSex)) {
						$properties['SEX'] = $mapSex[$product['features']['sex']];
					}
				}
			}

			$fields['PROPERTY_VALUES'] = $properties;
			$el = new CIBlockElement;
			$id = $el->add($fields);
			if ($id) {
				if (array_key_exists('skus', $product)) {
					foreach ($product['skus'] as $sku) {
						$fields = [
							'IBLOCK_ID' => $mapIblock['catalog_offers'],
						];
						$properties = [
							'CML2_LINK' => $id,
						];

						if (array_key_exists('name', $sku)) {
							$fields['NAME'] = $sku['name'];
						} elseif (array_key_exists('sku', $sku)) {
							$fields['NAME'] = $sku['sku'];
						}

						if (array_key_exists('color', $sku)) {
							$properties['COLOR'] = $sku['color'];
						}
						if (array_key_exists('size', $sku)) {
							$properties['SIZE'] = $sku['size'];
						}

						$fields['PROPERTY_VALUES'] = $properties;
						$id = $el->add($fields);

						if ($id) {
							$result = CCatalogProduct::Add([
								'ID' => $id,
								'PURCHASING_PRICE' => array_key_exists('price', $sku) ? intval($sku['price']) : 0,
								'PURCHASING_CURRENCY' => 'RUB',
								'QUANTITY' => array_key_exists('count', $sku) ? $sku['count'] : 0,
							]);
							if (!$result) {
								echo 'Не удалось добавить товар';
							}
						} else {
							echo 'Не удалось добавить торговое предложение: ' . $el->LAST_ERROR;
						}
					}
				}
			} else {
				echo 'Ошибка при добавлении элемента: ' . $el->LAST_ERROR;
			}
		}
	} else {
		echo 'Инфоблок Каталог не найден';
	}
} else {
	echo "Неверный токен авторизации"; 
}