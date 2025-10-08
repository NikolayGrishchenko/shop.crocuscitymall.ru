<?
$arResult['BRAND'] = null;
if (!empty($arResult['PROPERTIES']['BRAND']['VALUE']))
{
	$res = CIBlockElement::getById($arResult['PROPERTIES']['BRAND']['VALUE']);
	if ($brand = $res->getNext())
	{
		if (!empty($brand['DETAIL_PAGE_URL']))
		{
			if (!empty($brand['CODE']))
			{
				$brand['DETAIL_PAGE_URL'] = str_replace(
					'#PRODUCT_SECTION_CODE#', 
					'all', 
					$brand['DETAIL_PAGE_URL']
				);
			}
			else
			{
				$brand['DETAIL_PAGE_URL'] = '';
			}
		}

		$arResult['BRAND'] = $brand;
	}
}
