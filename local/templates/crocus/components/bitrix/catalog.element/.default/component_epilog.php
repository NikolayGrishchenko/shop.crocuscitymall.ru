<?
use \Bitrix\Catalog\CatalogViewedProductTable as CatalogViewedProductTable;
CatalogViewedProductTable::refresh($arResult['ID'], CSaleBasket::GetBasketUserID());