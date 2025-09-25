$(function() {
	$('[data-filter-item]').on('click', function() {
		$(this).toggleClass('active');
	});

	$('[data-filter-item]').each(function(i, item) {
		var $filterItem = $(item);
		var $selected = $filterItem.find('[data-catalog-filter-selected-value]');
		var $selectedValues = $filterItem.find('input:checked');
		
		if ($selectedValues.length > 1) {
			$selected.text($selectedValues.length + ' выбрано');
		} else if ($selectedValues.length == 1) {
			$selected.text($selectedValues.eq(0).closest('[data-catalog-filter-value]').find('[data-catalog-filter-value-name]').text());
		} else {
			$selected.text('Выбор');
		}
	});

	$('[data-catalog-filter-value] input').on('change', function() {
		var $input = $(this);
		var $filterItem = $input.closest('[data-filter-item]');
		var $selected = $filterItem.find('[data-catalog-filter-selected-value]');
		var $selectedValues = $filterItem.find('input:checked');
		
		if ($selectedValues.length > 1) {
			$selected.text($selectedValues.length + ' выбрано');
		} else if ($selectedValues.length == 1) {
			$selected.text($selectedValues.eq(0).closest('[data-catalog-filter-value]').find('[data-catalog-filter-value-name]').text());
		} else {
			$selected.text('Выбор');
		}
	});
});