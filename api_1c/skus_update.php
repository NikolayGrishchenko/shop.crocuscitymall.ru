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

		$mapElement = [];
		$res = CIBlockElement::getList([], [
			'IBLOCK_ID' => $mapIblock['catalog'],
			'ID' => array_column($data, 'id'),
		]);
		if ($ob = $res->getNextElement()) {
			$element = $ob->getFields();
			$element['PROPERTIES'] = $ob->getProperties();

			$mapElement[$item['ID']] = $element;
		}

		foreach ($data as $item) {
			if (array_key_exists($item['id'], $mapElement)) {
				$element = $mapElement[$item['id']];

				$updateProperties = [];

				if (array_key_exists($item['brand'], $mapBrand)) {
					$brand = $mapBrand[$item['brand']];
					if ($brand != $element['PROPERTIES']['BRAND']['VALUE']) {
						$updateProperties['BRAND'] = $brand;
					}
				}
				if (array_key_exists($item['sex'], $mapSex)) {
					$sex = $mapSex[$item['sex']];
					if ($sex != $element['PROPERTIES']['SEX']['VALUE_ENUM_ID']) {
						$updateProperties['SEX'] = $sex;
					}
				}

				if (!empty($updateProperties)) {
					CIBlockElement::SetPropertyValuesEx($element['ID'], $element['IBLOCK_ID'], $updateProperties);
				}

				$res = CIBlockElement::getList([], [
					'IBLOCK_ID' => $mapIblock['catalog_offers'],
					'NAME' => $item['sku'],
					'PROPERTY_CML2_LINK' => $element['ID'],
				]);
				if ($ob = $res->getNextElement()) {
					$offer = $ob->getFields();
					$offer['PROPERTIES'] = $ob->getProperties();

					$updateProperties = [];

					if (array_key_exists('color', $item) && $item['color'] != $offer['PROPERTIES']['COLOR']['VALUE']) {
						$updateProperties['COLOR'] = $item['color'];
					}
					if (array_key_exists('size', $item) && $item['size'] != $offer['PROPERTIES']['SIZE']['VALUE']) {
						$updateProperties['SIZE'] = $item['size'];
					}

					if (!empty($updateProperties)) {
						CIBlockElement::SetPropertyValuesEx($offer['ID'], $offer['IBLOCK_ID'], $updateProperties);
					}

					$product = CCatalogProduct::getById($offer['ID']);
					if ($product) {
						$updateFields = [];

						if (array_key_exists('count', $item) && $item['count'] != $product['QUANTITY']) {
							$updateFields['QUANTITY'] = $item['count'];
						}
						if (array_key_exists('price', $item) && $item['price'] != $product['PURCHASING_PRICE']) {
							$updateFields['PURCHASING_PRICE'] = $item['price'];
						}

						if (!empty($updateFields)) {
							CCatalogProduct::update($product['ID'], $updateFields);
						}
					} else {
						echo 'Товар каталога не найден';
					}
				} else {
					echo 'Торговое предложение не найдено';
				}
			} else {
				echo 'Элемент ИБ не найден';
			}
		}
	} else {
		echo 'Инфоблок Каталог не найден';
	}
} else {
	echo "Неверный токен авторизации"; 
}