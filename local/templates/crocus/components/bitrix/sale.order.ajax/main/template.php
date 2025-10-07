<?
use Bitrix\Main;

$context = Main\Application::getInstance()->getContext();
$request = $context->getRequest();
if (strlen($request->get('ORDER_ID')) > 0):
	include(Main\Application::getDocumentRoot() . $templateFolder . '/confirm.php');
else:?>
	<div class="row">
		<div class="col-12 col-md-6">
			<div class="order-title">
				<div class="order-title-text">Корзина</div>
				<a href="<?=$arParams['PATH_TO_BASKET']?>" class="order-title-link">Изменить</a>
			</div>
			<table class="order-basket-table">
				<tbody>
					<?foreach ($arResult['BASKET_ITEMS'] as $basket):?>
						<tr>
							<td>
								<?if (!empty($basket['DETAIL_PICTURE_SRC'])):?>
									<img src="<?=$basket['DETAIL_PICTURE_SRC']?>" class="order-basket-image" alt="<?=$basket['NAME']?>">
								<?endif;?>
							</td>
							<td>
								<a href="<?=$basket['DETAIL_PAGE_URL']?>" class="order-basket-name"><?=$basket['NAME']?></a>
							</td>
							<td>
								<div class="order-basket-measure">шт.</div>
								<div class="order-basket-quantity"><?=$basket['QUANTITY']?></div>
								<div class="order-basket-price"><?=$basket['PRICE_FORMATED']?>/шт</div>
							</td>
							<td>
								<div class="order-basket-sum"><?=$basket['SUM_BASE_FORMATED']?></div>
							</td>
						</tr>
					<?endforeach;?>
				</tbody>
			</table>
		</div>
		<div class="col-12 col-md-6">
			<div class="order-title">
				<div class="order-title-text">Резервирование в бутике</div>
			</div>
			<form class="row order-form" method="POST" action="<?=$arParams['CURRENT_PAGE']?>" enctype="multipart/form-data" data-order-form>
				<input type="hidden" name="soa-action" value="saveOrderAjax">
				<input type="hidden" name="DELIVERY_ID" value="<?=$arResult['ORDER_DATA']['DELIVERY_ID']?>">
				<input type="hidden" name="PAY_SYSTEM_ID" value="<?=$arResult['ORDER_DATA']['PAY_SYSTEM_ID']?>">
				<div class="col-12">
					<div class="order-subtitle">Покупатель</div>
					<div class="row order-properties">
						<?foreach ($arResult['ORDER_PROP']['USER_PROPS_Y'] as $property):?>
							<div class="col-12 col-md-6 order-property">
								<div class="order-property-name"><?=$property['NAME']?><?if ($property['REQUIRED'] == 'Y'):?>*<?endif;?></div>
								<div class="order-property-field">
									<input type="text" name="ORDER_PROP_<?=$property['ID']?>" class="order-property-input" value="<?=$property['VALUE']?>" <?if ($property['REQUIRED'] == 'Y'):?> required<?endif;?> />
								</div>
							</div>
						<?endforeach;?>
					</div>
					<div class="row">
						<div class="col-12 order-policy">
							<label>
								<input type="checkbox" name="policy" value="Y" checked required>
								<span>Я принимаю условия <a href="/upload/Policy.pdf" target="_blank">политики обработки персональных данных</a></span>
							</label>
						</div>
					</div>
					<div class="row order-total">
						<div class="col-12">
							<div class="row">
								<div class="col-4">
									<div class="order-total-product-title">Стоимость товаров</div>
								</div>
								<div class="col-4">
									<div class="order-total-product-sum"><?=$arResult['ORDER_TOTAL_PRICE_FORMATED']?></div>
								</div>
							</div>
							<div class="row align-items-center">
								<div class="col-3">
									<div class="order-total-title">Итого</div>
								</div>
								<div class="col-3">
									<div class="order-total-sum"><?=$arResult['ORDER_TOTAL_PRICE_FORMATED']?></div>
								</div>
								<div class="col-6">
									<button type="submit" class="order-total-button">Резервировать в бутике</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
<?endif;?>