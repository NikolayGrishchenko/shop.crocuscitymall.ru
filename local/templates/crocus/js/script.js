function refreshBasketCount() {
	$.ajax({
		url: '/ajax/basket.php',
		data: {
			sessid: BX.bitrix_sessid(),
			action: 'get_quantity',
		},
		dataType: 'json',
		success: function(res) {
			if (res.error) {
				$('[data-basket-quantity]').text('');
			} else {
				$('[data-basket-quantity]').text(res.quantity);
			}
		}
	});
}

refreshBasketCount();