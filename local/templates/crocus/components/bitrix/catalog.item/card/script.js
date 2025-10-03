$(function() {
	$('[data-basket-url]').on('click', function() {
		$.ajax({
			url: $(this).data('basket-url'),
			data: {
				ajax_basket: 'Y',
				quantity: 1,
			},
			method: 'POST',
			dataType: 'json',
			success: function(res) {
				if (res.STATUS == 'OK') {
					refreshBasketCount();
				} else {
					alert(res.MESSAGE);
				}
			},
			error: function(a) {
				alert('Ошибка: ' + a.status + ' ' + a.statusText);
			}
		});
		return false;
	});
});