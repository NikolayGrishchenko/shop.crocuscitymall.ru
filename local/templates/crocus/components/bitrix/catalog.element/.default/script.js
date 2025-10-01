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
});