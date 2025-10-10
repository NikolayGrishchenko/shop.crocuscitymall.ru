$(function() {
	$(document).on('click', function(e) {
		if ($(e.target).closest('.catalog-section').length == 0 && $(e.target).closest('.catalog-section-popup').length == 0) {
			$('[data-category-id].active').removeClass('active');
			$('[data-subcategory-id].show').removeClass('show');
		}
	});

	$('[data-category-id]').on('click', function() {
		let $this = $(this);
		let id = $this.data('category-id');
		if (id) {
			let $subcategory = $('[data-subcategory-id=' + id + ']');
			if ($subcategory.length > 0) {
				if ($this.hasClass('active')) {
					$this.removeClass('active');
					$subcategory.removeClass('show');
				} else {
					$('[data-category-id].active').removeClass('active');

					$this.addClass('active');
					$subcategory.addClass('show');
				}
				return false;
			}
		}
	});
});
