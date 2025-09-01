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

		$res = CIBlockElement::getById($_GET['id']);
		if ($ob = $res->getNextElement()) {
			$element = $ob->getFields();
			$element['PROPERTIES'] = $ob->getProperties();

			$updateProperties = [];

			if (array_key_exists($data['brand'], $mapBrand)) {
				$brand = $mapBrand[$data['brand']];
				if ($brand != $element['PROPERTIES']['BRAND']['VALUE']) {
					$updateProperties['BRAND'] = $brand;
				}
			}
			if (array_key_exists($data['sex'], $mapSex)) {
				$sex = $mapSex[$data['sex']];
				if ($sex != $element['PROPERTIES']['SEX']['VALUE_ENUM_ID']) {
					$updateProperties['SEX'] = $sex;
				}
			}

			if (!empty($updateProperties)) {
				CIBlockElement::SetPropertyValuesEx($element['ID'], $element['IBLOCK_ID'], $updateProperties);
			}

			$res = CIBlockElement::getList([], [
				'IBLOCK_ID' => $mapIblock['catalog_offers'],
				'NAME' => $data['sku'],
				'PROPERTY_CML2_LINK' => $element['ID'],
			]);
			if ($ob = $res->getNextElement()) {
				$offer = $ob->getFields();
				$offer['PROPERTIES'] = $ob->getProperties();

				$updateProperties = [];

				if (array_key_exists('color', $data) && $data['color'] != $offer['PROPERTIES']['COLOR']['VALUE']) {
					$updateProperties['COLOR'] = $data['color'];
				}
				if (array_key_exists('size', $data) && $data['size'] != $offer['PROPERTIES']['SIZE']['VALUE']) {
					$updateProperties['SIZE'] = $data['size'];
				}

				if (!empty($updateProperties)) {
					CIBlockElement::SetPropertyValuesEx($offer['ID'], $offer['IBLOCK_ID'], $updateProperties);
				}

				$product = CCatalogProduct::getById($offer['ID']);
				if ($product) {
					$updateFields = [];

					if (array_key_exists('count', $data) && $data['count'] != $product['QUANTITY']) {
						$updateFields['QUANTITY'] = $data['count'];
					}
					if (array_key_exists('price', $data) && $data['price'] != $product['PURCHASING_PRICE']) {
						$updateFields['PURCHASING_PRICE'] = $data['price'];
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
	} else {
		echo 'Инфоблок Каталог не найден';
	}
} else {
	echo "Неверный токен авторизации"; 
}