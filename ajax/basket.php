<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

use Bitrix\Sale;
use Bitrix\Main;

$data = [];
try {
    if (!check_bitrix_sessid()) {
        throw new Exception("Не удалось подтвердить сессию");
    }

    switch ($_REQUEST['action']) {
        case 'get_quantity':
            Main\Loader::includeModule('sale');
            
            $basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Main\Context::getCurrent()->getSite());
            $basketItems = $basket->getBasketItems();

            $totalQuantity = 0;
            foreach ($basketItems as $basketItem) {
                $totalQuantity += $basketItem->getQuantity();
            }
            $data['quantity'] = $totalQuantity;
            break;
        default: 
            throw new Exception("Неизвестное действие");
    }
} catch (Exception $e) {
    $data['error'] = $e->getMessage();
}

echo json_encode($data);
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php');