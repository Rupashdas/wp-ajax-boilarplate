(function ($) {
	'use strict';

	$(document).ready(function () {
		const wrapper = $('#blog-posts');
		function load_posts(page_number = 1, search_value = "", categories = []) {
			const post_data = {
				action: 'load_global_posts',
				nonce: dublin_data.nonce,
				from_page: page_number,
			}
			if (search_value != "") {
				post_data.search_value = search_value;
			}
			if (Array.isArray(categories) && categories.length > 0) {
				post_data.categories = categories;
			}

			$.ajax({
				url: dublin_data.ajax_url,
				type: 'POST',
				data: post_data,
				beforeSend: function () {
					$("#loader-wrapper").show();
				},
				success: function (response) {
					console.log(response);
					if (response.success) {
						$("#loader-wrapper").hide();
						const data = response.data;
						wrapper.html(data.html);

					}
				}
			});
		}
		load_posts(1);

		let searchTimer;
		$("#blog-search").on("keyup", function () {
			const selectedCats = $("#blog-category").val();
			clearTimeout(searchTimer);
			searchTimer = setTimeout(function () {
				let searchValue = $("#blog-search").val();
				load_posts(1, searchValue, selectedCats);
			}, 500);
		});

		$("#blog-category").on("change", function () {
			let selectedCats = $(this).val();
			let searchValue = $("#blog-search").val();
			load_posts(1, searchValue, selectedCats);
		});




	});

})(jQuery);
