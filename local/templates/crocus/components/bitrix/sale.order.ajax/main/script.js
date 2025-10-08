$(function() {
	$('[data-order-form]').on('submit', function(e) {
		let $this = $(this);

		$.ajax({
			type: $this.attr('method'),
			url: $this.attr('action'),
			data: $this.serialize(),
			success: function(data) {
				if (data.order && data.order.REDIRECT_URL) {
					location.href = data.order.REDIRECT_URL;
				} else if (data.order && data.order.ERROR) {
					alert(data.order.ERROR[Object.keys(data.order.ERROR)[0]].join(', '));
				}
			}
		});
		return false;
	});
});