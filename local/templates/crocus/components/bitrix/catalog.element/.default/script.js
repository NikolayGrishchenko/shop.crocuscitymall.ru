$(function() {
	$('[data-offer]').on('click', function() {
		var $this = $(this);
		var id = $this.data('offer');

		$('[data-offer].selected').removeClass('selected');
		$this.addClass('selected');

		$('[data-selected-price]').text($this.data('price'));

		$('[name=offer_id]').val(id);
	});

	$('[data-decrease]').on('click', function() {
		var $quantity = $('[name=quantity]');
		var value = parseInt($quantity.val());
		if (value > 1) {
			$quantity.val(value - 1);
		}
	});

	$('[data-increase]').on('click', function() {
		var $quantity = $('[name=quantity]');
		var value = parseInt($quantity.val());
		$quantity.val(value + 1);
	});

	$('[data-image-preview]').on('click', function() {
		var $this = $(this);
		var id = $this.data('image-preview');

		$('[data-image-preview].active').removeClass('active');
		$this.addClass('active');

		$('[data-image-show].active').removeClass('active');
		$('[data-image-show=' + id + ']').addClass('active');
	});

	$('[data-image-show]').on('click', function() {
		var $this = $(this);
		var id = $this.data('image-show');

		$('[data-image-modal]').addClass('active');

		$('[data-image-full].active').removeClass('active');
		$('[data-image-full=' + id + ']').addClass('active');
	});

	$('[data-image-modal-close]').on('click', function() {
		$('[data-image-modal]').removeClass('active');
	});

	$(window).scroll(function() {
		if ($('[data-image-modal]').hasClass('active')) {
			$('[data-image-modal]').removeClass('active');
		}
	});

	$('[data-basket-button]').on('click', function() {
		$.ajax({
			url: '?action=BUY&id=' + $('[name=offer_id]').val(),
			data: {
				ajax_basket: 'Y',
				quantity: $('[name=quantity]').val(),
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